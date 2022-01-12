<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

$title = '商品列表 - 修改頁面';

if (!isset($_GET['pro_id'])) {
    header("Location: product.php");
    exit;
}

$sid = intval($_GET['pro_id']);

$format = $pdo->query("SELECT `format_id` FROM `product_sake` WHERE `product_sake`.`pro_id` = $sid ")->fetch(); //此時的$format是陣列
$format = $format['format_id']; ////取陣列裡的值

$pformat = $pdo->query("SELECT * FROM `product_format` WHERE `format_id` = $format")->fetch();
$psake = $pdo->query("SELECT * FROM `product_sake` WHERE `pro_id` = $sid")->fetch();

if (empty($psake)) {
    header('Location: product.php');
    exit;
}

//禮盒樣式
$pro_mark = "SELECT * FROM `product_gift` WHERE 1";
$pro_marks = $pdo->query($pro_mark)->fetchAll();

//酒器樣式
$pro_con = "SELECT * FROM `product_container` WHERE 1";
$pro_cons = $pdo->query($pro_con)->fetchAll();

?>
<?php include __DIR__ . '/../parts/__head.php' ?>
<?php include __DIR__ . '/../parts/__navbar.php' ?>
<?php include __DIR__ . '/../parts/__sidebar.html' ?>
<?php include __DIR__ . '/../parts/__main_start.html' ?>
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->
<!-- edit -->
<style>
    #edit_img,
    #myimg {
        height: 400px;
        text-align: center;
        filter: drop-shadow(0px 5px 6px rgba(50, 50, 50, .5));
    }

    .img_div {
        text-align: center;
    }

    small {
        font-size: .1rem;
        color: #666;
    }

    .fla,
    .tem {
        cursor: pointer;
    }
</style>
<div class="mt-4 mb-4">
    <div class="row justify-content-center">
        <div class="col-11">
            <div class="card">
                <h5 class="card-header py-3">商品資料修改<br><small>商品表id:<?= $psake['pro_id'] ?>&nbsp&nbsp&nbsp&nbsp規格表id:<?= $pformat['format_id'] ?></small></h5>
                <div class="card-body">
                    <form class="row" name="form1" onsubmit="sendData(); return false;">

                        <div class="img_div" id="img_div">
                            <?php if ($psake['pro_img']) : ?>
                                <img id="edit_img" src="..\img\pro_img\<?= $psake['pro_img'] ?>" alt="">
                            <?php endif ?>
                        </div>
                        <div class="img_div d_none">
                            <img src="" id="myimg" />
                        </div>

                        <div class="form-group mb-3">
                            <label for="pro_img" class="mb-2">商品圖片</label>
                            <input type="file" class="form-control" name="pro_img" id="pro_img" />

                        </div>
                        <div class="form-group mb-3 col-4">
                            <label for="pro_name" class="mb-2">名稱</label>
                            <input type="text" class="form-control" name="pro_name" id="pro_name" value="<?= $psake['pro_name'] ?>" />
                        </div>
                        <div class="form-group mb-3 col-4">
                            <label for="pro_stock" class="mb-2">庫存</label>
                            <input type="number" class="form-control" name="pro_stock" id="pro_stock" min="0" value="<?= $psake['pro_stock'] ?>" />
                        </div>



                        <div class="form-group mb-3 col-4">
                            <label for="pro_selling" class="mb-2">銷售量</label>
                            <input type="number" class="form-control" name="pro_selling" id="pro_selling" min="0" value="<?= $psake['pro_selling'] ?>" />
                        </div>


                        <div class="form-group mb-3">
                            <label class="mb-2" for="pro_intro">介紹</label>
                            <textarea class="form-control" name="pro_intro" id="pro_intro" rows="7"><?= $psake['pro_intro'] ?></textarea>
                        </div>

                        <div class="form-group mb-3 col-3">
                            <label class="mb-2" for="pro_condition">商品狀態</label>
                            <select class="form-control" id="pro_condition" name="pro_condition">
                                <option value="">**選擇狀態**</option>
                                <option value="已上架">已上架</option>
                                <option value="已下架">已下架</option>
                                <option value="補貨中">補貨中</option>
                            </select>
                        </div>
                        <div class="form-group mb-3 col-3">
                            <label for="pro_price" class="mb-2">價格</label>
                            <input type="number" class="form-control" name="pro_price" id="pro_price" min="0" value="<?= $pformat['pro_price'] ?>" />
                        </div>
                        <div class="form-group mb-3 col-3">
                            <label for="pro_capacity" class="mb-2">容量</label>
                            <input type="number" class="form-control" name="pro_capacity" id="pro_capacity" min="1" value="<?= $pformat['pro_capacity'] ?>" />
                        </div>
                        <div class="form-group mb-3 col-3">
                            <label class="mb-2" for="pro_loca">產地</label>
                            <select class="form-control" name="pro_loca" id="pro_loca">
                                <option value="">**選擇產地**</option>
                                <option value="東京都">東京都</option>
                                <option value="北海道">北海道</option>
                                <option value="大阪府">大阪府</option>
                                <option value="京都府">京都府</option>
                                <option value="青森縣">青森縣</option>
                                <option value="秋田縣">秋田縣</option>
                                <option value="岩手縣">岩手縣</option>
                                <option value="宮城縣">宮城縣</option>
                                <option value="山形縣">山形縣</option>
                                <option value="福島縣">福島縣</option>
                                <option value="櫪木縣">櫪木縣</option>
                                <option value="茨城縣">茨城縣</option>
                                <option value="千葉縣">千葉縣</option>
                                <option value="埼玉縣">埼玉縣</option>
                                <option value="群馬縣">群馬縣</option>
                                <option value="新潟縣">新潟縣</option>
                                <option value="富山縣">富山縣</option>
                                <option value="石川縣">石川縣</option>
                                <option value="福井縣">福井縣</option>
                                <option value="岐阜縣">岐阜縣</option>
                                <option value="長野縣">長野縣</option>
                                <option value="山梨縣">山梨縣</option>
                                <option value="靜岡縣">靜岡縣</option>
                                <option value="愛知縣">愛知縣</option>
                                <option value="三重縣">三重縣</option>
                                <option value="滋賀縣">滋賀縣</option>
                                <option value="奈良縣">奈良縣</option>
                                <option value="兵庫縣">兵庫縣</option>
                                <option value="鳥取縣">鳥取縣</option>
                                <option value="島根縣">島根縣</option>
                                <option value="山口縣">山口縣</option>
                                <option value="廣島縣">廣島縣</option>
                                <option value="岡山縣">岡山縣</option>
                                <option value="香川縣">香川縣</option>
                                <option value="德島縣">德島縣</option>
                                <option value="高知縣">高知縣</option>
                                <option value="愛媛縣">愛媛縣</option>
                                <option value="大分縣">大分縣</option>
                                <option value="福岡縣">福岡縣</option>
                                <option value="佐賀縣">佐賀縣</option>
                                <option value="長崎縣">長崎縣</option>
                                <option value="熊本縣">熊本縣</option>
                                <option value="宮崎縣">宮崎縣</option>
                                <option value="沖繩縣">沖繩縣</option>
                                <option value="和歌山縣">和歌山縣</option>
                                <option value="鹿兒島縣">鹿兒島縣</option>
                                <option value="神奈川縣">神奈川縣</option>
                            </select>
                        </div>
                        <div class="form-group mb-3 col-3">
                            <label class="mb-2" for="pro_level">等級</label>
                            <select class="form-control" id="pro_level" name="pro_level">
                                <option value="">**選擇等級**</option>
                                <option value="純米大吟釀">純米大吟釀</option>
                                <option value="純米吟釀">純米吟釀</option>
                                <option value="大吟釀">大吟釀</option>
                                <option value="本釀造">本釀造</option>
                                <option value="純米酒">純米酒</option>
                                <option value="吟釀">吟釀</option>
                            </select>
                        </div>
                        <div class="form-group mb-3 col-3">
                            <label for="pro_brand" class="mb-2">品牌</label>
                            <input type="text" class="form-control" name="pro_brand" id="pro_brand" value="<?= $pformat['pro_brand'] ?>" />
                        </div>

                        <div class="form-group mb-3 col-3">
                            <label for="pro_essence" class="mb-2">精米步合<small> 50% -> 50</small></label>
                            <input type="number" class="form-control" name="pro_essence" id="pro_essence" min="1" max="100" value="<?= $pformat['pro_essence'] ?>" />
                        </div>
                        <div class="form-group mb-3 col-3">
                            <label for="pro_alco" class="mb-2">酒精度<small> 50% -> 50</small></label>
                            <input type="number" class="form-control" name="pro_alco" id="pro_alco" min="1" max="100" value="<?= $pformat['pro_alco'] ?>" />
                        </div>
                        <div class="form-group mb-3 col-3">
                            <label for="pro_marker" class="mb-2">酒造</label>
                            <input type="text" class="form-control" name="pro_marker" id="pro_marker" value="<?= $pformat['pro_marker'] ?>" />
                        </div>

                        <div class="form-group mb-3 col-3">
                            <label for="rice" class="mb-2">使用米</label>
                            <input type="text" class="form-control" name="rice" id="rice" value="<?= $pformat['rice'] ?>" />
                        </div>

                        <div class="form-group mb-3 col-3">
                            <label for="pro_taste" class="mb-2">口味<small>
                                    <span class="fla fla1">偏酸</span>
                                    <span class="fla fla2">偏甜</span>
                                    <span class="fla fla3">辛口</span>
                                    <span class="fla fla4">甘口</span>
                                    <span class="fla fla5">輕盈</span>
                                    <span class="fla fla6">適中</span>
                                    <span class="fla fla7">清空</span>
                                </small></label>
                            <input type="text" class="form-control" name="pro_taste" id="pro_taste" value="<?= $pformat['pro_taste'] ?>" aria-label="readonly input example" readonly />
                        </div>
                        <div class="form-group mb-3 col-3">
                            <label for="pro_temp" class="mb-2">溫度<small>
                                    <span class="tem tem1">冷酒</span>
                                    <span class="tem tem2">常溫</span>
                                    <span class="tem tem3">燗酒</span>
                                    <span class="tem tem4">清空</span>
                                </small></label>
                            <input type="text" class="form-control" name="pro_temp" id="pro_temp" value="<?= $pformat['pro_temp'] ?>" aria-label="readonly input example" readonly />
                        </div>
                        <div class="form-group mb-3 col-4">
                            <label class="mb-2" for="pro_gift">禮盒</label>
                            <select class="form-control" id="pro_gift" name="pro_gift">
                                <option value="">**選擇禮盒**</option>
                                <?php foreach ($pro_marks as $pm) : ?>
                                    <option value="<?= $pm['pro_gift'] ?>"><?= $pm['gift_name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group mb-3 col-4">
                            <label class="mb-2" for="pro_mark">酒標客製化</label>
                            <select class="form-control" id="pro_mark" name="pro_mark">
                                <option value="">**選擇是否客製化**</option>
                                <option value="1">可以客製化</option>
                                <option value="0">不可客製化</option>
                            </select>
                        </div>

                        <div class="form-group mb-3 col-4">
                            <label class="mb-2" for="container_id">酒器</label>
                            <select class="form-control" id="container_id" name="container_id">
                                <option value="">**選擇酒器**</option>

                                <?php foreach ($pro_cons as $pc) : ?>
                                    <option value="<?= $pc['container_id'] ?>"><?= $pc['container_name'] ?></option>
                                <?php endforeach ?>

                            </select>
                        </div>
                        <!--警警告文字 -->
                        <div class="form-group mb-3 warning"></div>
                        <div class="d-flex justify-content-around mb-3">
                            <a href="javascript: history.go(-1)" class="btn btn-secondary w-25">返回</a>
                            <button type="submit" class="btn btn-secondary w-25">修改</button>
                        </div>
                        <input type="hidden" name="pro_id" value="<?= $psake['pro_id'] ?>">
                        <input type="hidden" name="format_id" value="<?= $pformat['format_id'] ?>">
                        <input type="hidden" name="pro_creat_time" value="<?= $psake['pro_creat_time'] ?>">
                        <input type="hidden" name="pro_unsell_time" value="<?= $psake['pro_unsell_time'] ?>">

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">商品資料修改</h5>
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

    //預覽圖片

    const pro_img = document.querySelector('#pro_img'); //上傳圖片input
    const img_div = document.querySelector('#img_div');
    const d_none = document.querySelector('.d_none');

    pro_img.addEventListener('change', doPreview);

    function doPreview() {
        img_div.innerHTML = '';
        d_none.style.display = 'block';
        const [file] = pro_img.files
        if (file) {

            document.querySelector("#myimg").src = URL.createObjectURL(file);
        }

    }

    function doUpload() {
        const fd = new FormData(document.form1);
        console.log('33');
        fetch("product-insert-api.php", {
                method: "POST",
                body: fd,
            })
            .then((r) => r.json())
            .then((obj) => {
                if (obj.success) {
                    document.querySelector("#myimg").src = "/Hejoulin/img/pro_img/" + obj.filename;
                } else {
                    obj.error;
                }
            });
    }

    //商品狀態
    let pro_condition_c = document.querySelector('#pro_condition').childNodes; //選取下拉選單的子元素
    pro_condition_c.forEach(el => {
        if (el.value == "<?= $psake['pro_condition'] ?>") {
            el.setAttribute('selected', 'selected');
        }
    });

    //產地
    let pro_loca_c = document.querySelector('#pro_loca').childNodes; //選取下拉選單的子元素
    pro_loca_c.forEach(el => {
        if (el.value == "<?= $pformat['pro_loca'] ?>") {
            el.setAttribute('selected', 'selected');
        }
    });

    //等級
    let pro_level_c = document.querySelector('#pro_level').childNodes; //選取下拉選單的子元素
    pro_level_c.forEach(el => {
        if (el.value == "<?= $pformat['pro_level'] ?>") {
            el.setAttribute('selected', 'selected');
        }
    });


    //禮盒
    let pro_gift_c = document.querySelector('#pro_gift').childNodes; //選取下拉選單的子元素
    pro_gift_c.forEach(el => {
        if (el.value == "<?= $pformat['pro_gift'] ?>") {
            el.setAttribute('selected', 'selected');
        }
    });

    //酒標客製化
    let pro_mark_c = document.querySelector('#pro_mark').childNodes; //選取下拉選單的子元素
    pro_mark_c.forEach(el => {
        if (el.value == "<?= $pformat['pro_mark'] ?>") {
            el.setAttribute('selected', 'selected');
        }
    });

    //酒標客製化
    let container_id_c = document.querySelector('#container_id').childNodes; //選取下拉選單的子元素

    container_id_c.forEach(el => {
        if (el.value == "<?= $pformat['container_id'] ?>") {
            el.setAttribute('selected', 'selected');
        }
    });


    //let pro_img = document.querySelector('#pro_img');
    let pro_name = document.querySelector('#pro_name');
    let pro_stock = document.querySelector('#pro_stock');
    let pro_selling = document.querySelector('#pro_selling');
    let pro_intro = document.querySelector('#pro_intro');
    let pro_price = document.querySelector('#pro_price');
    let pro_capacity = document.querySelector('#pro_capacity');
    let pro_brand = document.querySelector('#pro_brand');
    let pro_essence = document.querySelector('#pro_essence');
    let pro_alco = document.querySelector('#pro_alco');
    let pro_marker = document.querySelector('#pro_marker');
    let rice = document.querySelector('#rice');
    let pro_taste = document.querySelector('#pro_taste');
    let pro_temp = document.querySelector('#pro_temp');
    let warning = document.querySelector('.warning');
    let edit_btn = document.querySelector('#edit_btn');

    let pro_condition = document.querySelector('#pro_condition');
    let pro_loca = document.querySelector('#pro_loca');
    let pro_level = document.querySelector('#pro_level');
    let pro_gift = document.querySelector('#pro_gift');
    let pro_mark = document.querySelector('#pro_mark');
    let container_id = document.querySelector('#container_id');

    //口味描述按鈕
    let fla1 = document.querySelector('.fla1');
    let fla2 = document.querySelector('.fla2');
    let fla3 = document.querySelector('.fla3');
    let fla4 = document.querySelector('.fla4');
    let fla5 = document.querySelector('.fla5');
    let fla6 = document.querySelector('.fla6');
    let fla7 = document.querySelector('.fla7');

    //溫度按鈕
    let tem1 = document.querySelector('.tem1');
    let tem2 = document.querySelector('.tem2');
    let tem3 = document.querySelector('.tem3');
    let tem4 = document.querySelector('.tem4');

    //口味按鈕
    fla1.addEventListener('click', function() {
        if (pro_taste.value.indexOf('偏酸') == '-1' && pro_taste.value.indexOf('偏甜') == '-1') {
            pro_taste.value += '偏酸';
        }
    })

    fla2.addEventListener('click', function() {
        if (pro_taste.value.indexOf('偏甜') == '-1' && pro_taste.value.indexOf('偏酸') == '-1') {
            pro_taste.value += '偏甜';
        }
    })

    fla3.addEventListener('click', function() {
        if (pro_taste.value.indexOf('辛口') == '-1' && pro_taste.value.indexOf('甘口') == '-1') {
            pro_taste.value += '辛口'
        }
    })

    fla4.addEventListener('click', function() {
        if (pro_taste.value.indexOf('辛口') == '-1' && pro_taste.value.indexOf('甘口') == '-1') {
            pro_taste.value += '甘口'
        }
    })

    fla5.addEventListener('click', function() {
        if (pro_taste.value.indexOf('輕盈') == '-1' && pro_taste.value.indexOf('適中') == '-1') {
            pro_taste.value += '輕盈'
        }
    })

    fla6.addEventListener('click', function() {
        if (pro_taste.value.indexOf('輕盈') == '-1' && pro_taste.value.indexOf('適中') == '-1') {
            pro_taste.value += '適中'
        }
    })

    fla7.addEventListener('click', function() {
        pro_taste.value = '';
    })

    //飲用溫度
    tem1.addEventListener('click', function() {
        if (pro_temp.value.indexOf('冷酒') == '-1') {
            pro_temp.value += '冷酒'
        }
    })

    tem2.addEventListener('click', function() {
        if (pro_temp.value.indexOf('常溫') == '-1') {
            pro_temp.value += '常溫'
        }
    })

    tem3.addEventListener('click', function() {
        if (pro_temp.value.indexOf('燗酒') == '-1') {
            pro_temp.value += '燗酒'
        }
    })

    tem4.addEventListener('click', function() {
        pro_temp.value = '';
    })

    pro_gift.addEventListener('change', function() {

        if (pro_gift.value == 3) {
            container_id.removeAttribute("disabled", "")
        } else {
            container_id.value = 5;
            container_id.setAttribute("disabled", "")
        }
    })

    pro_gift.addEventListener('change', function() {

        if (pro_gift.value == 3) {
            container_id.removeAttribute("disabled", "")
        } else {
            container_id.value = 5;
            container_id.setAttribute("disabled", "")
        }
    })

    function sendData() {

        let isPass = true;

        warning.innerHTML = ' ';

        //商品名稱
        if (pro_name.value.length > 50) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">商品名稱過長</div>`;
        }

        if (pro_name.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請輸入名稱</div>`;
        }

        //庫存
        if (isNaN(parseInt(pro_stock.value))) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">庫存欄位請輸入數字</div>`;
        }

        if (pro_stock.value < 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">庫存欄位請勿輸入負數</div>`;
        }

        //銷售量
        if (isNaN(parseInt(pro_selling.value))) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">銷售量欄位請輸入數字</div>`;
        }

        if (pro_selling.value < 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">銷售量欄位請勿輸入負數</div>`;
        }

        //介紹
        if (pro_intro.value.length > 700) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">商品介紹過長</div>`;
        }

        if (pro_intro.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請輸入商品介紹</div>`;
        }

        //價格

        if (isNaN(parseInt(pro_price.value))) {
            isPass = false;
            console.log(parseInt(pro_price.value));
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">價格欄位請輸入數字</div>`;
        }

        if (pro_price.value < 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">價格欄位請勿輸入負數</div>`;
        }


        //容量

        if (isNaN(parseInt(pro_capacity.value))) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">容量欄位請輸入數字</div>`;
        }

        if (pro_capacity.value <= 0 || pro_capacity.value > 10000) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請輸入0~10000內的數字</div>`;
        }

        //品牌
        if (pro_brand.value.length > 20) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">品牌名稱過長</div>`;
        }

        if (pro_brand.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請輸入品牌名稱</div>`;
        }

        //精米步合
        if (isNaN(parseInt(pro_essence.value))) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">精米步合欄位請輸入數字</div>`;
        }

        if (pro_essence.value <= 0 || pro_essence.value >= 100) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請輸入0~100內的數字</div>`;
        }

        //酒精度
        if (isNaN(parseInt(pro_alco.value))) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">酒精度欄位請輸入數字</div>`;
        }

        if (pro_alco.value <= 0 || pro_alco.value >= 100) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請輸入0~100內的數字</div>`;
        }

        //酒造
        if (pro_marker.value.length > 20) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">酒造名稱過長</div>`;
        }

        if (pro_marker.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請輸入酒造名稱</div>`;
        }

        //使用米
        if (rice.value.length > 20) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">使用米名稱過長</div>`;
        }

        if (rice.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請輸入使用米名稱</div>`;
        }

        //口味描述
        if (pro_taste.value.length > 20) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">口味描述過長</div>`;
        }

        if (pro_taste.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請輸入口味描述</div>`;
        }

        if (pro_taste.value.length < 6) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">口味描述不足</div>`;
        }
        if (!isNaN(parseInt(pro_taste.value))) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">口味描述欄位請輸入中文</div>`;
        }

        //飲用溫度
        if (pro_temp.value.length > 6) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">飲用溫度描述過長</div>`;
        }

        if (pro_temp.value.length < 2) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">飲用溫度描述不足</div>`;
        }

        if (pro_temp.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請輸入溫度描述</div>`;
        }
        if (!isNaN(parseInt(pro_temp.value))) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">飲用溫度欄位請輸入中文</div>`;
        }

        //商品狀態
        if (pro_condition.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請選擇商品狀態</div>`;
        }

        //產地
        if (pro_loca.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請選擇產地</div>`;
        }

        //等級
        if (pro_level.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請選擇等級</div>`;
        }

        //禮盒
        if (pro_gift.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請選擇禮盒</div>`;
        }

        //是否客製化
        if (pro_mark.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請選擇是否客製化</div>`;
        }

        //酒器
        if (container_id.value.length <= 0) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請選擇酒器</div>`;
        }

        if (container_id.value == 5 && pro_gift.value == 3) {
            isPass = false;
            warning.innerHTML = `<div class="alert alert-warning mt-2" role="alert">請選擇酒器</div>`;
        }

        if (isPass) {

            const fd = new FormData(document.form1);
            console.log('3');

            fetch('product-edit-api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    if (obj.success) {
                        document.querySelector('#alertModal').innerHTML = '修改成功';
                        modal.show();
                        document.querySelector('#comfirm').addEventListener('click', function() {
                            location.href = `product.php`;
                        })

                    } else {
                        document.querySelector('#alertModal').innerHTML = obj.error || '資料修改發生錯誤';
                        modal.show();
                    }
                })
        }

    }
</script>
<?php include __DIR__ . '/../parts/__foot.html' ?>