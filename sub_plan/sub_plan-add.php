<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php
$title = '新增資料';
$pageName = 'add';

//如果未登入管理員帳號，會直接跳轉至別的頁面
if (!$_SESSION['admin']) {
    header("Location: " . "../login/login.php");
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
                    <h5 class="card-title">新增訂閱方案資料</h5>
                    <form name="formInsert" onsubmit="sendData(); return false">
                        <div class="mb-3">
                            <label for="sub_plan" class="form-label">方案名稱</label>
                            <input type="text" class="form-control" id="sub_plan" name="sub_plan">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="sub_products" class="form-label">月配產品</label>
                            <select name="sub_products" id="sub_products" class="form-select">
                                <option value="0" selected>選擇商品</option>
                                <?php
                                $sql = sprintf("SELECT ps.`pro_id`, ps.`pro_name`, pf.`pro_mark` FROM `product_sake` ps LEFT JOIN `product_format` pf ON pf.format_id = ps.format_id;");
                                $productRows = $pdo->query($sql)->fetchAll();
                                foreach ($productRows as $r) :
                                ?>
                                    <option value="<?= $r['pro_name'] ?>"><?= $r['pro_id'] ?>&nbsp &nbsp<?= $r['pro_name'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="sub_price" class="form-label">方案價格</label>
                            <input type="number" class="form-control" id="sub_price" name="sub_price">
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
    const sub_plan = document.querySelector("#sub_plan");
    const sub_products = document.querySelector("#sub_products");
    const sub_price = document.querySelector("#sub_price");

    const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
    //  modal.show() 讓 modal 跳出

    function sendData() {
        let isPass = true;
        sub_plan.nextElementSibling.innerHTML = '';
        sub_products.nextElementSibling.innerHTML = '';
        sub_price.nextElementSibling.innerHTML = '';

        if (sub_plan.value < 2) {
            isPass = false;
            sub_plan.nextElementSibling.innerHTML = '<div class="alert alert-dark mt-2" role="alert">請輸入完整的方案名稱</div>';
        }
        if (sub_products.value == '') {
            isPass = false;
            sub_products.nextElementSibling.innerHTML = '<div class="alert alert-dark mt-2" role="alert">請輸入完整的產品名稱</div>';
        }
        if (sub_price.value == '' || sub_price.value < 0) {
            isPass = false;
            sub_price.nextElementSibling.innerHTML = '<div class="alert alert-dark mt-2" role="alert">請輸入完整的價格方案</div>';
        }

        if (isPass) {
            const fd = new FormData(document.formInsert);
            fetch('sub_plan-add-api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        document.querySelector('#exampleModalLabel').innerHTML = '新增成功';
                        document.querySelector('.modal-body').innerHTML = `新增了一筆資料`;
                        document.querySelector('#modal_btn').setAttribute("onclick", "location.href='sub_plan.php'");
                        modal.show();
                        // location.href = 'sub_plan.php';
                    } else {
                        document.querySelector('#exampleModalLabel').innerHTML = '資料新增發生錯誤';
                        document.querySelector('.modal-body').innerHTML = `資料新增失敗錯誤 :  ${obj.error}`;
                        document.querySelector('#modal_btn').setAttribute("onclick", "location.href='sub_plan.php'");
                        modal.show();
                    }
                });
        }
    }
</script>
<?php include __DIR__ . '/../parts/__foot.html' ?>