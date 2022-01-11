<?php
require __DIR__ . '\..\parts\__connect_db.php';

//if (! isset($_SESSION['user'])){
//    header('Location:user_list.php');
//    exit;

$title = '修改使用者資料';

if (!isset($_GET['user_id'])) {
    header('Location: user_list.php');exit;
}
$userID = intval($_GET['user_id']);
$row = $pdo->query("SELECT * FROM `user` WHERE user_id=$userID")->fetch();

if (empty($row)) {
    header('Location: user_list.php');exit;
}




?>
<?php include __DIR__ . '\..\parts\__head.php' ?>
<?php include __DIR__ . '\..\parts\__navbar.php' ?>
<?php include __DIR__ . '\..\parts\__sidebar.html' ?>

<?php include __DIR__ . '\..\parts\__main_start.html' ?>


    <div class="container">
        <div class="row mt-5">
            <div class="col-md">
                <div class="card">
                    <h5 class="card-header py-3">修改使用者資料</h5>
                    <div class="card-body">
                        <form name="form1" onsubmit="sendData(); return false;">
                            <input type="hidden" name="user_id"  value="<?=$row['user_id']?>">
                            <div class="mb-3">
                                <label for="account" class="form-label">帳號</label>
                                <input type="text" class="form-control" id="account" name="account"
                                       value="<?=$row['user_account']?>">
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">密碼</label>
                                <input type="password" class="form-control" id="password" name="password"
                                       value="<?= $row['user_pass'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">提交</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">修改</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '\..\parts\__main_end.html' ?>

<?php include __DIR__ . '\..\parts\__script.html' ?>
    <script>

        const name = document.querySelector('#account');
        const password = document.querySelector('#password');

        const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
        const modalBody = document.querySelector('.modal-body');

        function sendData() {
            name.nextElementSibling.innerHTML = '';
            password.nextElementSibling.innerHTML = '';

            let isPass = true;
            //檢查表單資料
            //     if (name.value.length < 2) {
            //     isPass = false;
            //         name.nextElementSibling.innerHTML = '請輸入正確的姓名';
            // }
            //     if (name.value && !mobile_re.test(mobile.value)) {
            //     isPass = false;
            //     mobile.nextElementSibling.innerHTML = '請輸入正確的手機號碼';
            // }

            if (isPass) {
                const fd = new FormData(document.form1);

                fetch('user-edit-api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                    .then(obj => {
                        console.log(obj);
                        if (obj.success) {
                            modalBody.innerHTML = `修改成功`;
                            document.querySelector('.modal-footer').innerHTML = `<a href="user_list.php" class="btn btn-secondary">完成</a>`;
                            modal.show();
                        } else {
                            document.querySelector('.modal-body').innerHTML = obj.error || '資料修改發生錯誤';
                            modal.show();
                        }
                    })
            }
        }
        //  const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
        // //  modal.show() 讓 modal 跳出
    </script>
<?php include __DIR__ . '\..\parts\__foot.html' ?>