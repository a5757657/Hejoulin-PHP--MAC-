<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php
$title = 'Edit Data';
$pageName = 'edit';

//如果未登入管理員帳號，會直接跳轉至別的頁面
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

//確定有get到資料
if (!isset($_GET['subtime_id'])) {
    header("Location: sub_time.php");
    exit;
}
$subtime_id = $_GET['subtime_id'];
$sql = "SELECT * FROM `sub_time` WHERE subtime_id = $subtime_id";
$row = $pdo->query($sql)->fetch();

//確定有這筆sid的資料，沒有的話就跳轉
if (empty($row)) {
    header("Location: sub_time.php");
    exit;
}

?>
<?php include __DIR__ . '/../parts/__head.php' ?>
<?php include __DIR__ . '/../parts/__navbar.php' ?>
<?php include __DIR__ . '/../parts/__sidebar.html' ?>
<?php include __DIR__ . '/../parts/__main_start.html' ?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="card" style="width: 40rem;">
                <div class="card-body">
                    <h5 class="card-title">編輯訂閱週期資料</h5>
                    <form name="formInsert" onsubmit="sendData(); return false">
                        <input type="hidden" name="subtime_id" id="subtime_id" value="<?= $row['subtime_id'] ?>">
                        <div class="mb-3">
                            <!-- 把value先設成抓到的資料 -->
                            <label for="sub_time" class="form-label">週期名稱</label>
                            <input type="text" class="form-control" id="sub_time" name="sub_time"
                                value="<?= htmlentities($row['sub_time']) ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="sub_time_month" class="form-label">週期月數</label>
                            <input type="number" class="form-control" id="sub_time_month" name="sub_time_month"
                                value="<?= $row['sub_time_month'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="sub_discount" class="form-label">方案折扣</label>
                            <input type="number" class="form-control" id="sub_discount" name="sub_discount"
                                value="<?= $row['sub_discount'] ?>" min="0.00" max="1.00" step="0.01">
                            <div class="form-text"></div>
                        </div>
                        <a class="btn btn-primary" href="javascript:history.go(-1)">返回</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../parts/__main_end.html' ?>
<?php include __DIR__ . '/../parts/__modal_ash.html' ?>
<?php include __DIR__ . '/../parts/__script.html' ?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
const subtime_id = document.querySelector("#subtime_id").value;
const sub_time = document.querySelector("#sub_time");
const sub_time_month = document.querySelector("#sub_time_month");
const sub_discount = document.querySelector("#sub_discount");

const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
//  modal.show() 讓 modal 跳出

function sendData() {
    let isPass = true;
    sub_time.nextElementSibling.innerHTML = '';
    sub_time_month.nextElementSibling.innerHTML = '';
    sub_discount.nextElementSibling.innerHTML = '';

    if (sub_time.value.length < 1) {
        isPass = false;
        sub_time.nextElementSibling.innerHTML = '<div class="alert alert-dark mt-2" role="alert">請輸入完整的週期名稱</div>';
    }
    if (sub_time_month.value < 1) {
        isPass = false;
        sub_time_month.nextElementSibling.innerHTML =
            '<div class="alert alert-dark mt-2" role="alert">請輸入完整的週期月數</div>';
    }
    if (sub_discount.value > 1 || sub_discount.value.length < 1) {
        isPass = false;
        sub_discount.nextElementSibling.innerHTML = '<div class="alert alert-dark mt-2" role="alert">請輸入完整的週期折扣</div>';
    }

    if (isPass) {
        const fd = new FormData(document.formInsert);
        fetch('sub_time-edit-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    document.querySelector('#exampleModalLabel').innerHTML = '修改成功';
                    document.querySelector('.modal-body').innerHTML = `編號${subtime_id}的資料被修改了`;
                    document.querySelector('#modal_btn').setAttribute("onclick", "location.href='sub_time.php'");
                    modal.show();
                    // location.href = 'sub_time.php';
                } else {
                    document.querySelector('#exampleModalLabel').innerHTML = '資料修改發生錯誤';
                    document.querySelector('.modal-body').innerHTML = `編號${subtime_id}的資料修改失敗 ${obj.error}`;
                    document.querySelector('#modal_btn').setAttribute("onclick", "location.href='sub_time.php'");
                    modal.show();
                }
            });
    }
}
</script>
<?php include __DIR__ . '/../parts/__foot.html' ?>