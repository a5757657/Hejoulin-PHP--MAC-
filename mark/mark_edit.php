<?php
require __DIR__ . '/../parts/__connect_db.php';

//if (! isset($_SESSION['user'])){
//    header('Location:user_list.php');
//    exit;

$title = '修改酒標資料';

if (!isset($_GET['mark_id'])) {
    header('Location: mark_list.php');exit;
}
$markID = intval($_GET['mark_id']);
$row = $pdo->query("SELECT * FROM `mark` WHERE mark_id=$markID")->fetch();

if (empty($row)) {
    header('Location: mark_list.php');exit;
}
?>
<?php include __DIR__ . '/../parts/__head.php' ?>
<?php include __DIR__ . '/../parts/__navbar.php' ?>
<?php include __DIR__ . '/../parts/__sidebar.html' ?>

<?php include __DIR__ . '/../parts/__main_start.html' ?>


    <div class="container">
        <div class="row mt-5">
            <div class="col-md">
                <div class="card">
                    <h5 class="card-header py-3">修改酒標資料</h5>
                    <div class="card-body">
                        <form name="form1" onsubmit="sendData(); return false;">
                            <input type="hidden" name="mark_id"  value="<?=$row['mark_id']?>">

                            <div class="mb-3">
                                <label for="member_id" class="form-label">會員ID</label>
                                <select name="member" id="member" class="form-select">
                                    <option value="" selected>選擇會員</option>
                                    <?php
                                    $sql = sprintf("SELECT * FROM `member`");
                                    $memberRows = $pdo->query($sql)->fetchAll();
                                    foreach ($memberRows as $r) :
                                        ?>
                                        <option value="<?= $r['member_id'] ?>"><?= $r['member_id'] ?>&nbsp &nbsp<?= $r['member_name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="pics" class="form-label">pics</label>
                                <input type="text" class="form-control" id="pics" name="pics" value="<?=$row['pics']?>">
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

<?php include __DIR__ . '/../parts/__main_end.html' ?>

<?php include __DIR__ . '/../parts/__script.html' ?>
    <script>

        const mID = document.querySelector('#member');
        const pics = document.querySelector('#pics');

        const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
        const modalBody = document.querySelector('.modal-body');

        function sendData() {
            mID.nextElementSibling.innerHTML = '';
            pics.nextElementSibling.innerHTML = '';

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

                fetch('mark-edit-api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                    .then(obj => {
                        console.log(obj);
                        if (obj.success) {
                            modalBody.innerHTML = `修改成功`;
                            document.querySelector('.modal-footer').innerHTML = `<a href="mark_list.php" class="btn btn-secondary">完成</a>`;
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
<?php include __DIR__ . '/../parts/__foot.html' ?>