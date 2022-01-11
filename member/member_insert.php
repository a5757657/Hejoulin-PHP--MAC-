<?php
require __DIR__ . '\..\parts\__connect_db.php';


$title = '新增會員資料';
$pageName = 'member_insert';


?>
<?php include __DIR__ . '\..\parts\__head.php' ?>
<?php include __DIR__ . '\..\parts\__navbar.php' ?>
<?php include __DIR__ . '\..\parts\__sidebar.html' ?>
<?php include __DIR__ . '\..\parts\__main_start.html' ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-10">
                <div class="card">
                    <h5 class="card-header py-3">新增會員資料</h5>
                    <div class="card-body">
                        <form name="form1" onsubmit="sendData(); return false;">

                            <div class="mb-3">
                                <label for="user_id" class="form-label">user_id</label>
                                <input type="text" class="form-control" id="user_id" name="user_id" required>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">姓名</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="birthday" class="form-label">生日</label>
                                <input type="date" class="form-control" id="birthday" name="birthday">
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label">手機號碼</label>
                                <input type="text" class="form-control" id="mobile" name="mobile"
                                       data-pattern="09\d{2}-?\d{3}-?\d{3}" required>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="level" class="form-label">會員等級</label>
                                <input type="text" class="form-control" id="level" name="level" required>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">居住地址</label>
                                <textarea class="form-label" name="address" id="address" cols="30"
                                          rows="3" required></textarea>
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
                    <h5 class="modal-title" id="exampleModalLabel">新增</h5>
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

        const uID = document.querySelector('#user_id');
        const name = document.querySelector('#name');
        const bir = document.querySelector('#birthday');
        const mobile = document.querySelector('#mobile');
        const level = document.querySelector('#level');
        const address = document.querySelector('#address');

        const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
        const modalBody = document.querySelector('.modal-body');

        // const userID_re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

        const mobile_re = /^09\d{2}-?\d{3}-?\d{3}$/;

        function sendData() {
            uID.nextElementSibling.innerHTML = '';
            name.nextElementSibling.innerHTML = '';
            bir.nextElementSibling.innerHTML = '';
            mobile.nextElementSibling.innerHTML = '';
            level.nextElementSibling.innerHTML = '';
            address.nextElementSibling.innerHTML = '';

            let isPass = true;
            //檢查表單資料
            if (name.value.length < 2) {
                isPass = false;
                name.nextElementSibling.innerHTML = '請輸入收件可以使用的姓名';
            }

            if (name.value && !mobile_re.test(mobile.value)) {
                isPass = false;
                mobile.nextElementSibling.innerHTML = '請輸入正確的手機號碼';
            }

            if (isPass) {
                const fd = new FormData(document.form1);

                fetch('member-insert-api.php', {
                    method: 'POST',
                    body: fd, //送設定好的資料類型
                }).then(r => r.json())
                    .then(obj => {
                        console.log(obj);
                        if (obj.success) {
                            modalBody.innerHTML = `新增成功`;
                            document.querySelector('.modal-footer').innerHTML =
                                `<a href="member_list.php" class="btn btn-secondary">完成</a>`;
                            modal.show();
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

<?php include __DIR__ . '\..\parts\__foot.html' ?>