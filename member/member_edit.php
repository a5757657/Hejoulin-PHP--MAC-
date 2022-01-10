<?php
require __DIR__ . '\..\parts\__connect_db.php';


$title = '修改會員資料';

if(! isset($_GET['user_id'])){
    header('Location: member_list.php');exit;
}

$userID = intval($_GET['user_id']);
$row = $pdo->query("SELECT * FROM `member` WHERE user_id=$userID")->fetch();
if(empty($row)){
    header('Location: member_list.php');exit;
}

?>
<?php include __DIR__ . '\..\parts\__head.php'?>
<?php include __DIR__ . '\..\parts\__navbar.php'?>
<?php include __DIR__ . '\..\parts\__sidebar.html'?>
<?php include __DIR__ . '\..\parts\__main_start.html'?>
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->

    <div class="container">
        <div class="row mt-5">
            <div class="col-md">
                <div class="card">
                    <h5 class="card-header py-3">修改會員資料</h5>
                    <div class="card-body">
                        <form name="form5" onsubmit="sendData(); return false;">
                            <input type="hidden" name="ID"  value="<?=$row['user_id']?>">

                            <div class="mb-3">
                                <label for="user_id" class="form-label">user_id</label>
                                <input type="text" class="form-control" id="user_id"
                                       name="user_id" value="<?=$row['user_id']?>" required>
                                <div class="form-text"></div>
                            </div>
<!--                            <div class="mb-3">-->
<!--                                <label for="member_id" class="form-label">member_id</label>-->
<!--                                <input type="text" class="form-control" id="member_id"-->
<!--                                       name="member_id" value="--><?//=$row['member_id']?><!--" required>-->
<!--                                <div class="form-text"></div>-->
<!--                            </div>-->

                            <div class="mb-3">
                                <label for="name" class="form-label">姓名</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="<?=$row['member_name']?>" required>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="birthday" class="form-label">生日</label>
                                <input type="date" class="form-control" id="birthday" name="birthday"
                                       value="<?=$row['member_bir']?>">
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label">手機號碼</label>
                                <input type="text" class="form-control" id="mobile" name="mobile"
                                       data-pattern="09\d{2}-?\d{3}-?\d{3}"
                                       value="<?=$row['member_mob']?>" required>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">居住地址</label>
                                <textarea class="form-label" name="address" id="address"
                                          cols="30"
                                          rows="3"<?=$row['member_addr']?>></textarea>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="level" class="form-label">會員等級</label>
                                <input type="text" class="form-control" id="level" name="level"
                                       value="<?=$row['member_level']?>">
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

<!-- edit -->
<?php include __DIR__ . '\..\parts\__main_end.html'?>
<?php include __DIR__ . '\..\parts\__modal.html'?>
<?php include __DIR__ . '\..\parts\__script.html'?>
<script>


    const uID = document.querySelector('#user_id');
    // const mID = document.querySelector('#member_id');
    const name = document.querySelector('#name');
    const bir = document.querySelector('#birthday');
    const mobile = document.querySelector('#mobile');
    const address = document.querySelector('#address');
    const level = document.querySelector('#level');

        const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));

        // const mobile_re = /^09\d{2}-?\d{3}-?\d{3}$/;

        function sendData() {
            uID.nextElementSibling.innerHTML = '';
            // mID.nextElementSibling.innerHTML = '';
            name.nextElementSibling.innerHTML = '';
            bir.nextElementSibling.innerHTML = '';
            mobile.nextElementSibling.innerHTML = '';
            address.nextElementSibling.innerHTML = '';
            level.nextElementSibling.innerHTML = '';

        let isPass = true;
        //檢查表單資料
        //     if (name.value.length < 2) {
        //         isPass = false;
        //         name.nextElementSibling.innerHTML = '請輸入收件可以使用的姓名';
        //     }
        //
        //     if (name.value && !mobile_re.test(mobile.value)) {
        //         isPass = false;
        //         mobile.nextElementSibling.innerHTML = '請輸入正確的手機號碼';
        //     }

            if (isPass) {
                const fd = new FormData(document.form5);

                fetch('member-edit-api.php', {
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
        //  const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
        // //  modal.show() 讓 modal 跳出
</script>

<?php include __DIR__ . '\..\parts\__foot.html'?>