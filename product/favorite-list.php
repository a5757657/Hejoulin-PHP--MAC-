<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

$title = '收藏列表';

if (!isset($_GET['member_id']) || !isset($_GET['member_name'])) {
    header('Location: favorite-member.php?');
    exit;
}

$member_id = $_GET['member_id'];
$member_name = $_GET['member_name'];

//用收藏的表去抓商品表跟規格表
$sql = "SELECT f.* , ps.* , pf.* FROM `favorite` f JOIN `product_sake` ps ON f.`pro_id` = ps.`pro_id` JOIN `product_format` pf ON ps.`format_id` = pf.`format_id` WHERE f.`member_id` = $member_id ;";
$rows = $pdo->query($sql)->fetchAll();

//商品名稱
$product = "SELECT * FROM `product_sake` ORDER BY `pro_name`;";
$pro = $pdo->query($product)->fetchAll();

?>
<?php include __DIR__ . '/../parts/__head.php' ?>
<?php include __DIR__ . '/../parts/__navbar.php' ?>
<?php include __DIR__ . '/../parts/__sidebar.html' ?>
<?php include __DIR__ . '/../parts/__main_start.html' ?>
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->

<style>
    img {
        height: 160px;
    }

    .fa-plus-square {
        font-size: 2rem;
        color: rgba(0, 0, 0, .2);
    }

    .add {
        height: 362px;
    }

    .pro_img {
        height: 160px;
        max-width: 160px;
        padding: 10px;
        filter: drop-shadow(0px 5px 6px rgba(50, 50, 50, .5));
        /* 帶透明圖層用的陰影 */
    }
</style>

<div class="container">
    <div class="row pt-5">
        <div class="d-flex mb-3 justify-content-between border-bottom">
            <div>
                <h4>會員編號：<?= $member_id ?>&emsp;&emsp;會員名稱：<?= $member_name ?></h4>
            </div>
            <div class="">
                <button type="button" class="btn btn-secondary btn-sm del">刪除收藏商品</button>
                <a href="favorite-member.php"><button type="button" class="btn btn-secondary btn-sm">返回</button></a>
            </div>
        </div>

        <div class="row justify-content-start">


            <?php foreach ($rows as $r) : ?>
                <div class="card d-flex align-items-center m-1 card_count" style="width: 18rem;">
                    <img src="..\img\pro_img\<?= $r['pro_img'] ?>" class="pt-2 pro_img">
                    <div class="card-body p-0">
                        <h5 class="card-title"><?= $r['pro_name'] ?></h5>
                    </div>

                    <div class="d-flex flex-row">
                        <div class="mx-2">產地:<?= $r['pro_loca'] ?></div>
                        <div class="mx-2">品牌:<?= $r['pro_brand'] ?></div>
                    </div>
                    <div class='d-flex'>
                        <div class="mx-2">等級:<?= $r['pro_level'] ?></div>
                        <div class="mx-2">價格:NT$<?= $r['pro_price'] ?></div>
                    </div>
                    <input type="hidden" value="<?= $r['pro_id'] ?>">
                    <div class='d-flex py-3'>
                        <input class="form-check-input check" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" >&emsp;選取收藏商品</label>
                        <p style="display:none"><?= $r['pro_id'] ?></p>
                    </div>
                    <input type="hidden" value="<?= $r['pro_name'] ?>">
                </div>
                
                
                <?php endforeach; ?>
                
                <!-- 新增商品 -->
                <div class="card d-flex align-items-center justify-content-center m-1 add" style="width: 18rem;">
                    <a data-bs-target="#add_pro" data-bs-toggle="modal" href="#" class="text-decoration-none d-flex justify-content-center align-items-center" style="width: 100%; height: 100%;">
                        <i class="far fa-plus-square "></i>
                    </a>
                </div>
            </div>
            
            
        </div>
    </div>
 
<!-- Modal 這個是自定義的光箱 觸發點綁在新增商品的按鈕 -->
<!-- 新增收藏商品 -->
<div class="modal fade" id="add_pro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">新增收藏商品</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form name="form1" onsubmit="sendData(); return false;">
                    <div class="form-group mb-3 col-12">
                        <select class="form-control" name="pro_id" id="select">
                            <option value="">**選擇商品**</option>

                        <!-- 計算出此會員收藏商品的數量 -->
                            <?php foreach ($pro as $p) : ?>
                                <option value="<?= $p['pro_id'] ?>"><?= $p['pro_name'] ?></option>
                            <?php endforeach; ?>


                        </select>
                        <div class="warning"></div>
                    </div>
                    <input type="hidden" name="member_id" value="<?= $member_id ?>">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary w-25" id="upload">新增</button>
                        <button type="button" class="btn btn-secondary w-25" data-bs-dismiss="modal">取消</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Modal 這是modal.show的光箱 -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title " id="alertTitle">新增收藏商品</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="alertModal"></div>
            <div class="modal-footer">
                <button id="comfirm" type="button" class="btn btn-secondary" data-bs-dismiss="modal">確認</button>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../parts/__main_end.html' ?>
<?php include __DIR__ . '/../parts/__script.html' ?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
    const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
    //  modal.show() 讓 modal 跳出
    let card_count = document.querySelectorAll('.card_count');
    let add = document.querySelector('.add');

    if (card_count.length >= 10) {
        add.style = "display:none !important";
    }

    function delete_it(sid) {
        
        let alertModal = document.querySelector('#alertModal');
        let alertTitle = document.querySelector('#alertTitle');
        let comfirm = document.querySelector('#comfirm');
        alertTitle.innerHTML = `刪除收藏商品`;
        alertModal.innerHTML = `確定要刪除收藏商品嗎?`;

        if (alertModal.innerHTML) {
            modal.show();

            comfirm.addEventListener('click', function() {
                console.log('4');
                location.href = `favorite-del-api.php?member_id=<?= $member_id ?>&pro_id=${sid}`;
            })

        }
    }

    let del = document.querySelector('.del');


    //多筆、單筆刪除
    del.addEventListener('click', function() {

        let check = document.querySelectorAll('.check')
        let arr = [];
        let str;
        /*  check = check.nextElementSibling
         console.log(check.nextElementSibling.innerHTML); */

        check.forEach(function(el) {

            if (el.checked) {
                str = el.nextElementSibling;
                str = str.nextElementSibling.innerHTML;
                arr.push(str);
            }
        })
        arr = arr.join(',');

        if(arr) {
            delete_it(arr);
        }
    })



    let warning = document.querySelector('.warning');
    let select = document.querySelector('#select');
    let comfirm = document.querySelector('#comfirm');

    function sendData() {

        let isPass = true;
        warning.innerHTML = "";

        if (select.value == "") {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">選擇商品</div>`;
        }
        if (isPass) {
            const fd = new FormData(document.form1);

            fetch('favorite-add-api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    if (obj.success) {
                        document.querySelector('#alertTitle').innerHTML = '新增收藏商品';
                        document.querySelector('#alertModal').innerHTML = '新增成功';
                        modal.show();
                        comfirm.addEventListener('click', function() {
                            history.go(0);
                        })

                    } else {
                        document.querySelector('#alertModal').innerHTML = obj.error || '新增發生錯誤';
                        modal.show();
                    }
                })
        }
    }
</script>
<?php include __DIR__ . '/../parts/__foot.html' ?>