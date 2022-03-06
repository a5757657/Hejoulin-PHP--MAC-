<?php require __DIR__. '/../parts/__connect_db.php';
$title = "登入";
$pageName = "login";
?>
<?php include __DIR__ . '/../parts/__head.php'?>
<?php include __DIR__ . '/../parts/__navbar.php'?>

<main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">


    <div class="mt-5">
        <div class="row justify-content-center">
            <div class="col-8 col-lg-6 col-xl-4">
                <div class="card">
                    <h5 class="card-header py-3">管理者登入</h5>
                    <div class="card-body">
                        <form name="form1" onsubmit="doLogin(); return false;">
                            <div class="form-group mb-3">
                                <label for="account" class="mb-2">帳號</label>
                                <input type="text" class="form-control" id="account" name="account"
                                    placeholder="請輸入帳號" />
                                <div class="form-text"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="mb-2">密碼</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="請輸入密碼" />
                                <div class="form-text"></div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-secondary w-50">登入</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../parts/__main_end.html'?>
    <?php include __DIR__ . '/../parts/__modal.html'?>

    <?php include __DIR__ . '/../parts/__script.html'?>
    <script>
    const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));

    function doLogin() {
        const fd = new FormData(document.form1);
        fetch("login-api.php", {
                method: 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.href = "../home/index.php";
                } else {
                    document.querySelector('.modal-body').innerHTML = data.error || "登入失敗";
                    modal.show();
                }
            })
    }
    </script>
    <?php include __DIR__ . '/../parts/__foot.html'?>