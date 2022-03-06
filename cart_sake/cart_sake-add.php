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
                    <h5 class="card-title">新增購物車清酒資料</h5>
                    <form name="formInsert" onsubmit="sendData(); return false">
                        <div class="mb-3">
                            <label for="member" class="form-label">會員</label>
                            <select name="member" id="member" class="form-select">
                                <option value="" selected>選擇會員</option>
                                <?php
                                $sql = sprintf("SELECT * FROM `member`");
                                $memberRows = $pdo->query($sql)->fetchAll();
                                foreach ($memberRows as $r) :
                                ?>
                                <option value="<?= $r['member_id'] ?>"><?= $r['member_id'] ?>&nbsp
                                    &nbsp<?= $r['member_name'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="product" class="form-label">商品</label>
                            <select name="product" id="product" class="form-select">
                                <option value="0" selected>選擇商品</option>
                                <?php
                                $sql = sprintf("SELECT ps.`pro_id`, ps.`pro_name`, pf.`pro_mark` FROM `product_sake` ps LEFT JOIN `product_format` pf ON pf.format_id = ps.format_id;");
                                $productRows = $pdo->query($sql)->fetchAll();
                                foreach ($productRows as $r) :
                                ?>
                                <option value="<?= $r['pro_id'] ?>"><?= $r['pro_id'] ?>&nbsp
                                    &nbsp<?= $r['pro_name'] ?>&nbsp &nbsp<?= $r['pro_mark'] == 1 ? '(可客製酒標)' : '' ?>
                                </option>
                                <?php endforeach ?>

                            </select>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">數量</label>
                            <input type="number" class="form-control" id="quantity" name="quantity">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="mark" class="form-label">酒標</label>
                            <select name="mark" id="mark" class="form-select">
                                <option value="0" selected>選擇酒標</option>
                                <?php
                                $sql = sprintf("SELECT * FROM `mark`");
                                $markRows = $pdo->query($sql)->fetchAll();
                                ?>
                                <option value="0"></option>
                            </select>
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
<script>
let member = document.querySelector("#member");
let memberID = member.value;
let product = document.querySelector("#product");
let productID = product.value;
let mark = document.querySelector('#mark');

//用監聽器看是否有選取不同的商品
product.addEventListener('change', (event) => {
    productID = product.value;
    //用pro_id 去查format資料表，看能不能做酒標
    var formData = new FormData();
    formData.append('product_id', productID);
    fetch('cart_sake-search-api.php', {
            method: 'POST',
            body: formData
        }).then(r => r.json())
        .then(obj => {
            memberMark(obj);
        })
});
memberListener();
//依據被選取的會員，產生其擁有的酒標
function memberListener() {
    member.addEventListener('change', (event) => {
        memberID = member.value;
        productID = product.value;
        //用pro_id 去查format資料表，看能不能做酒標
        var formData = new FormData();
        formData.append('product_id', productID);
        fetch('cart_sake-search-api.php', {
                method: 'POST',
                body: formData
            }).then(r => r.json())
            .then(obj => {
                memberMark(obj);
            })
    })
}

function memberMark(obj) {
    //obj
    if (obj == 0) {
        mark.setAttribute('disabled', '');
        mark.innerHTML = '';
        let option = document.createElement('option');
        option.value = 0;
        option.innerHTML = `此酒不提供客製酒標服務`;
        mark.append(option);
    } else if (obj == 1) {
        mark.removeAttribute("disabled");
        memberID = member.value;
        mark.innerHTML = '';
        let markRows = <?= json_encode($markRows) ?>;
        console.log(markRows);
        let i = 0;
        for (const r of markRows) {
            if (r['member_id'] == memberID) {
                console.log(r['member_id'], "=", memberID);
                let option = document.createElement('option');
                option.value = r['mark_id'];
                option.innerHTML = `${r['mark_id']}&nbsp &nbsp${r['pics']}`;
                mark.append(option);
                i += 1;
            }
        }
        if (i == 0) {
            let option = document.createElement('option');
            option.value = 0;
            option.innerHTML = `無已存在的酒標作品`;

            mark.append(option);
        } else {
            let option = document.createElement('option');
            option.value = 0;
            option.innerHTML = `不客製化酒標`;
            mark.append(option);
        }

    }
    // no value
    //判斷value. pro_mark
    else if (obj == 'x') {
        mark.innerHTML = '';
        let markRows = <?= json_encode($markRows) ?>;
        console.log(markRows);
        let i = 0;
        for (const r of markRows) {
            if (r['member_id'] == memberID) {
                console.log(r['member_id'], "=", memberID);
                let option = document.createElement('option');
                option.value = r['mark_id'];
                option.innerHTML = `${r['mark_id']}&nbsp &nbsp${r['pics']}`;
                mark.append(option);
                i++;
            }
        }
        if (i == 0) {
            let option = document.createElement('option');
            option.value = 0;
            option.innerHTML = `無已存在的酒標作品`;

            mark.append(option);
        } else {
            let option = document.createElement('option');
            option.value = 0;
            option.innerHTML = `不客製化酒標`;
            mark.append(option);
        }
    }



}

//將輸入的資料傳送到add-api
member = document.querySelector("#member");
product = document.querySelector("#product");
const quantity = document.querySelector("#quantity");
mark = document.querySelector("#mark");

console.log(member.value);
const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
//  modal.show() 讓 modal 跳出

function sendData() {
    let isPass = true;
    member.nextElementSibling.innerHTML = '';
    product.nextElementSibling.innerHTML = '';
    quantity.nextElementSibling.innerHTML = '';
    mark.nextElementSibling.innerHTML = '';

    if (member.value == '' || member.value == 0) {
        isPass = false;
        member.nextElementSibling.innerHTML = '<div class="alert alert-dark mt-2" role="alert">請選擇會員</div>';
    }
    if (product.value == '') {
        isPass = false;
        product.nextElementSibling.innerHTML = '<div class="alert alert-dark mt-2" role="alert">請選擇商品</div>';
    }
    if (quantity.value == '' || quantity.value < 0) {
        isPass = false;
        quantity.nextElementSibling.innerHTML = '<div class="alert alert-dark mt-2" role="alert">請輸入數量</div>';
    }

    if (isPass) {
        const fd = new FormData(document.formInsert);
        fetch('cart_sake-add-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    if (obj.successM == true) {
                        document.querySelector('#exampleModalLabel').innerHTML = '新增成功';
                        document.querySelector('.modal-body').innerHTML = `新增了一筆購物車清酒、一筆購物車酒標資料`;
                        document.querySelector('#modal_btn').setAttribute("onclick",
                            "location.href='cart_sake.php'");
                        modal.show();
                    } else if (obj.successM == 'x') {
                        document.querySelector('#exampleModalLabel').innerHTML = '新增成功';
                        document.querySelector('.modal-body').innerHTML = `新增了一筆購物車清酒資料`;
                        document.querySelector('#modal_btn').setAttribute("onclick",
                            "location.href='cart_sake.php'");
                        modal.show();
                    } else if (obj.successM == false) {
                        document.querySelector('#exampleModalLabel').innerHTML = '新增失敗';
                        document.querySelector('.modal-body').innerHTML = `購物車酒標資料新增發生錯誤`;
                        document.querySelector('#modal_btn').setAttribute("onclick",
                            "location.href='cart_sake.php'");
                        modal.show();
                    }

                } else {
                    document.querySelector('#exampleModalLabel').innerHTML = '資料新增發生錯誤';
                    document.querySelector('.modal-body').innerHTML = `資料新增失敗錯誤 :  ${obj.error}`;
                    document.querySelector('#modal_btn').setAttribute("onclick", "location.href='cart_sake.php'");
                    modal.show();
                }
            });
    }
}
</script>
<?php include __DIR__ . '/../parts/__foot.html' ?>