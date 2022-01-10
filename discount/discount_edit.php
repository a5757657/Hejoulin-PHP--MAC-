<?php
require __DIR__ . '\..\parts\__connect_db.php';

//if (! isset($_SESSION['admin'])){
//    header('Location:index.php');
//    exit;
$title = '修改折扣碼資料';
$pageName = 'discount_edit';

$disID = intval($_GET['discount_id']);
$row = $pdo->query("SELECT * FROM `discount` WHERE discount_id=$disID")->fetch();
if(empty($row)){
    header('Location: discount_list.php');exit;
}
?>
<?php include __DIR__ . '\..\parts\__head.php' ?>
<?php include __DIR__ . '\..\parts\__navbar.html' ?>
<?php include __DIR__ . '\..\parts\__sidebar.html' ?>
<?php include __DIR__ . '\..\parts\__main_start.html' ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-10">
                <div class="card">
                    <h5 class="card-header py-3">修改折扣碼資料</h5>
                    <div class="card-body">
                        <form name="form1" onsubmit="sendData(); return false;">
                            <input type="hidden" name="discount_id"  value="<?=$row['discount_id']?>">

                            <div class="mb-3">
                                <label for="discount_id" class="form-label">折扣碼ID</label>
                                <input type="text" class="form-control" id="discount_id" name="discountID" value="<?=$row['discount_id']?>" >
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="discount_code" class="form-label">折扣碼</label>
                                <input type="text" class="form-control" id="discount_code" name="discountCode" value="<?=$row['discount_code']?>" required>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="perscent" class="form-label">折扣值</label>
                                <input type="number" min="0.00" max="500.00" step="0.01" class="form-control" id="perscent" name="perscent"  value="<?=$row['perscent']?>" >
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="discount_info" class="form-label">折扣詳情</label>
                                <input type="text" class="form-control" id="discount_info" name="discountInfo" value="<?=$row['discount_info']?>">
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="discount_time_start" class="form-label">折扣開始時間</label>
                                <input type="date" class="form-control" id="discount_tstart" name="discountTstart"
                                       data-pattern="09\d{2}-?\d{3}-?\d{3}"  value="<?=$row['discount_time_start']?>" required>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="discount_time_end" class="form-label">折扣到期時間</label>
                                <input type="date" class="form-control" id="discount_tend" name="discountTend" value="<?=$row['discount_time_end']?>" required>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="active" class="form-label">折扣是否開啟</label>
                                <input type="text" class="form-control" id="active" name="active" value="<?=$row['active']?>" required>

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

<?php include __DIR__ . '\..\parts\__main_end.html' ?>
<?php include __DIR__ . '\..\parts\__modal.html' ?>
<?php include __DIR__ . '\..\parts\__script.html' ?>
    <!-- 如果要 modal 的話留下面的 script -->
    <script>

        const dID = document.querySelector('#discount_id');
        const perscent = document.querySelector('#perscent');
        const dCode = document.querySelector('#discount_code');
        const dInfo = document.querySelector('#discount_info');
        const dTs = document.querySelector('#discount_tstart');
        const dTe = document.querySelector('#discount_tend');
        const active = document.querySelector('#active');

        const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));


        function sendData() {
            dID.nextElementSibling.innerHTML = '';
            perscent.nextElementSibling.innerHTML = '';
            dInfo.nextElementSibling.innerHTML = '';
            dTs.nextElementSibling.innerHTML = '';
            dTe.nextElementSibling.innerHTML = '';
            active.nextElementSibling.innerHTML = '';


            let isPass = true;
            //檢查表單資料
            // if (name.value.length < 2) {
            //     isPass = false;
            //     name.nextElementSibling.innerHTML = '請輸入收件可以使用的姓名';
            // }
            //
            // if (name.value && !mobile_re.test(mobile.value)) {
            //     isPass = false;
            //     mobile.nextElementSibling.innerHTML = '請輸入正確的手機號碼';
            // }

            if (isPass) {
                const fd = new FormData(document.form1);

                fetch('discount-edit-api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                    .then(obj => {
                        console.log(obj);
                        if (obj.success) {
                            alert('修改成功');
                            history.go(-1);
                        } else {
                            document.querySelector('.modal-body').innerHTML = obj.error || '資料修改發生錯誤';
                            modal.show();
                        }
                    })
            }

        }

    </script>

<?php include __DIR__ . '\..\parts\__foot.html' ?>