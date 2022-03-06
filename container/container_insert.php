<?php require __DIR__ . './../parts/__connect_db.php';

$title = '新增酒器資料';
$pageName = 'container_insert';
?>

<?php include __DIR__ . './../parts/__head.php' ?>
<?php include __DIR__ . './../parts/__navbar.php'?>
<?php include __DIR__ . './../parts/__sidebar.html' ?>

<?php include __DIR__ . './../parts/__main_start.html' ?>

<div class="mt-5">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card">
                <h5 class="card-header py-3">酒器新增</h5>
                <div class="card-body">
                    <form name="form_c" onsubmit="sendData(); return false;" method="POST">
                        <div class="form-group mb-3">
                            <label for="container_name" class="mb-2">酒器名稱</label>
                            <input type="text" class="form-control" id="container_name" name="container_name"
                                placeholder="純錫富士山風情杯" />
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="container_img" class="mb-2">酒器圖片</label>
                            <input type="file" class="form-control" id="container_img" name="container_img"
                                accept=".jpg,.jpeg,.png,.gif" />
                            <div class="form-text"></div>
                        </div>
                        <div class="img-div">
                            <img src="" id="myimg01" style="width: 100%;" />
                        </div>
                        <div class="form-group mb-3">
                            <label for="container_shadow" class="mb-2">酒器禮盒圖片</label>
                            <input type="file" id="container_shadow" class="form-control" name="container_shadow"
                                accept=".jpg,.jpeg,.png,.gif">
                            <div class="form-text"></div>
                        </div>
                        <div class="img-div">
                            <img src="" id="myimg02" style="width: 100%;" />
                        </div>
                        <div class="d-flex justify-content-center m-t-2">
                            <button type="submit" class="btn btn-secondary w-25">新增</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . './../parts/__main_end.html' ?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">資料新增</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">確認</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . './../parts/__script.html' ?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
const containerName = document.querySelector('#container_name');
const myImg01 = document.querySelector('#myimg01');
const containerImg = document.querySelector('#container_img');
const myImg02 = document.querySelector('#myimg02');
const containerShd = document.querySelector('#container_shadow');

const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
//  modal.show() 讓 modal 跳出

//預覽圖片
containerImg.addEventListener('change', doPreview01);

function doPreview01() {
    const [file] = containerImg.files
    if (file) {
        document.querySelector("#myimg01").src = URL.createObjectURL(file)
    }
}

containerShd.addEventListener('change', doPreview02);

function doPreview02() {
    const [file] = containerShd.files
    if (file) {
        document.querySelector("#myimg02").src = URL.createObjectURL(file)
    }
}

//上傳圖片
function doUpload() {
    const fd = new FormData(document.form_c);
    fetch("container_insert_api.php", {
            method: "POST",
            body: fd,
        })
        .then((r) => r.json())
        .then((obj) => {
            if (obj.success) {
                document.querySelector("#myimg01").src = "../img/container/" + obj.filename;
                document.querySelector("#myimg02").src = "../img/container/" + obj.filename;
            } else {
                obj.error;
            }
        });
}

function sendData() {
    containerName.nextElementSibling.innerHTML = '';
    containerImg.nextElementSibling.innerHTML = '';
    containerShd.nextElementSibling.innerHTML = '';
    let isPass = true;

    if (containerName.value < 2) {
        isPass = false;
        containerName.nextElementSibling.innerHTML = '請輸入正確的酒器名稱';
    }

    if (isPass) {
        const fd = new FormData(form_c);

        fetch('container_insert_api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    document.querySelector('.modal-body').innerHTML = "資料新增成功";
                    document.querySelector('.modal-footer').innerHTML =
                        `<a href="container.php" class="btn btn-secondary">完成</a>`;
                    modal.show();
                } else {
                    document.querySelector('.modal-body').innerHTML = obj.error || '資料新增發生錯誤';
                    modal.show();
                }
            })
    }
}
</script>
<?php include __DIR__ . './../parts/__foot.html' ?>