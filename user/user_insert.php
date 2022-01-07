<?php
require __DIR__ . '../parts/__connect_db.php';

//if (! isset($_SESSION['admin'])){
//    header('Location:index.php');
//    exit;

$title = '新增使用者資料';
$pageName = 'user_insert';

//if (isset($_GET['user_id'])) {
//    $id = $_GET['user_id'];
//    $st = 'edit';
//} else {
//    $st = 'insert';
//}
//
//$sql = "SELECT * FROM `member`";
//
//$rows = $pdo->query($sql)->fetchAll();
?>
<?php include __DIR__ . '/parts/__head.html' ?>
<?php include __DIR__ . '/parts/__navbar.html' ?>
<?php include __DIR__ . '/parts/__sidebar.html' ?>
<?php include __DIR__ . '/parts/__main_start.html' ?>
    <!-- 主要的內容放在 __main_start 與 __main_end 之間 -->
    <!-- table -->

    <!-- add -->
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-10">
                <div class="card">
                    <h5 class="card-header py-3">新增使用者資料</h5>
                    <div class="card-body">
                        <form name="form1" onsubmit="sendData(); return false;">
                            <div class="mb-3">
                                <label for="account" class="form-label">帳號</label>
                                <input type="email" class="form-control" id="account" name="account" required>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">密碼</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text"></div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">提交</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--toggle開關的意思-->
    <!--<button style="display: none" id="btn" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">-->
    <!--    Launch demo modal-->
    <!--</button>-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">資料錯誤</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/parts/__main_end.html' ?>
<?php include __DIR__ . '/parts/__modal.html' ?>
<?php include __DIR__ . '/parts/__script.html' ?>
    <!-- 如果要 modal 的話留下面的 script -->
    <script>
        const uAccount = document.querySelector('#account');
        const uPass = document.querySelector('#password');

        const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));

        function sendData() {
            uAccount.nextElementSibling.innerHTML = '';
            uPass.nextElementSibling.innerHTML = '';

            let isPass = true;
            //檢查表單資料
            if (uAccount.value.length < 2) {
                isPass = false;
                name.nextElementSibling.innerHTML = '請輸入正確的帳號格式 ex:xxx@xxx.com';
            }
            // if (userID_re.test(userID.value)) {
            //     isPass = false;
            //     userID.nextElementSibling.innerHTML = '請輸入正確的帳號';
            // }

            if (name.value && !uPass.value) {
                isPass = false;
                uPass.nextElementSibling.innerHTML = '請輸入正確的密碼格式';
            }

            if (isPass) {
                const fd = new FormData(document.form1);

                fetch('user-insert-api.php', {
                    method: 'POST',
                    body: fd, //送設定好的資料類型
                }).then(r => r.json())
                    .then(obj => {
                        console.log(obj);
                        if (obj.success) {
                            alert('新增成功');
                            location.href = 'user_list.php';
                        } else {
                            document.querySelector('.modal-body').innerHTML = obj.error || '資料新增發生錯誤';
                            modal.show();
                        }
                    })
            }

        }

        //  const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
        // //  modal.show() 讓 modal 跳出
    </script>

<?php include __DIR__ . '/parts/__foot.html' ?>