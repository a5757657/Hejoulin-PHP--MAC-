<?php require __DIR__. '/../parts/__connect_db.php';

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

$title = "新增合作餐廳";
$pageName = "restaurant_add";
?>
<?php include __DIR__ . '/../parts/__head.php'?>
<style>
.plus-icon i {
    font-size: 2rem;
    transition: .2s
}

.add-card:hover .plus-icon i {
    font-size: 2.5rem
}

.click-image {
    background-color: #aaa;
    color: #ddd;
    border: none;
    border-radius: .25rem .25rem 0 0;
    transition: .5s
}

.click-image:hover {
    background-color: #ccc;
    color: #999;
}

input:focus {
    border: 1px #aaa;
    border-bottom-style: solid;
    border-top-style: none;
    border-left-style: none;
    border-right-style: none;
    outline: unset;
}

.menu-input {
    border: 1px #aaa;
    border-bottom-style: solid;
    border-top-style: none;
    border-left-style: none;
    border-right-style: none;
    width: 80%
}

.sp-menu-card .fas.fa-trash {
    cursor: pointer
}

.sp-menu-card .fas.fa-trash:hover {
    color: #aaa
}
</style>
<?php include __DIR__ . '/../parts/__navbar.php'?>
<?php include __DIR__ . '/../parts/__sidebar.html'?>

<?php include __DIR__ . '/../parts/__main_start.html'?>
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->

<div class="mt-5">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card">
                <h5 class="card-header py-3">新增合作餐廳</h5>
                <div class="card-body">
                    <form onsubmit="sendData();return false;" name="form1" runat="server">
                        <div class="form-group mb-3">
                            <label for="res_type" class="mb-2">餐廳類型</label>
                            <select class="form-select" aria-label="Default select example" name="res_type"
                                id="res_type">
                                <option value="Fine Dining">Fine Dining</option>
                                <option value="Sake Bar">Sake Bar</option>
                                <option value="居酒屋">居酒屋</option>
                            </select>
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="res_area" class="mb-2">餐廳地區</label>
                            <select class="form-select" aria-label="Default select example" name="res_area"
                                id="res_area">
                                <option value="北部">北部</option>
                                <option value="中部">中部</option>
                                <option value="南部">南部</option>
                            </select>
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="res_name" class="mb-2">餐廳名稱</label>
                            <input type="text" class="form-control" id="res_name" name="res_name"
                                placeholder="請輸入餐廳名稱" />
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="res_intro" class="form-label">餐廳介紹</label>
                            <textarea type="text" class="form-control" name="res_intro" id="res_intro" cols="10"
                                rows="5" placeholder="請輸入餐廳介紹"></textarea>
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="res_address" class="form-label">餐廳地址</label>
                            <textarea type="text" class="form-control" name="res_address" id="res_address" cols="10"
                                rows="2" placeholder="請輸入餐廳地址"></textarea>
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="res_ser_hours" class="mb-2">營業時間<small>（例如：12:00–15:00 18:00–22:00
                                    或休息）</small></label>
                            <input type="text" class="form-control mb-3" id="ser_sun" name="ser_sun"
                                placeholder="輸入星期日營業時間" />
                            <div class="form-text"></div>
                            <input type="text" class="form-control mb-3" id="ser_mon" name="ser_mon"
                                placeholder="輸入星期一營業時間" />
                            <div class="form-text"></div>
                            <input type="text" class="form-control mb-3" id="ser_tue" name="ser_tue"
                                placeholder="輸入星期二營業時間" />
                            <div class="form-text"></div>
                            <input type="text" class="form-control mb-3" id="ser_wed" name="ser_wed"
                                placeholder="輸入星期三營業時間" />
                            <div class="form-text"></div>
                            <input type="text" class="form-control mb-3" id="ser_thu" name="ser_thu"
                                placeholder="輸入星期四營業時間" />
                            <div class="form-text"></div>
                            <input type="text" class="form-control mb-3" id="ser_fri" name="ser_fri"
                                placeholder="輸入星期五營業時間" />
                            <div class="form-text"></div>
                            <input type="text" class="form-control mb-3" id="ser_sat" name="ser_sat"
                                placeholder="輸入星期六營業時間" />
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="res_t_number" class="mb-2">餐廳電話</label>
                            <input type="text" class="form-control" id="res_t_number" name="res_t_number"
                                placeholder="請輸入餐廳電話" />
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="web_link" class="mb-2">餐廳官網</label>
                            <input type="text" class="form-control" id="web_link" name="web_link"
                                placeholder="請輸入餐廳官網網址，若無可略過" />
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="fb_link" class="mb-2">餐廳FB</label>
                            <input type="text" class="form-control" id="fb_link" name="fb_link"
                                placeholder="請輸入餐廳FB網址，若無可略過" />
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="ig_link" class="mb-2">餐廳IG</label>
                            <input type="text" class="form-control" id="ig_link" name="ig_link"
                                placeholder="請輸入餐廳IG網址，若無可略過" />
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="booking_link" class="mb-2">訂位網址</label>
                            <input type="text" class="form-control" id="booking_link" name="booking_link"
                                placeholder="請輸入餐廳訂位網址，若無可略過" />
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="res_pic" class="form-label">餐廳圖片<small>（最多選6張，僅限JPG、PNG、GIF格式）</small></label>
                            <input class="form-control" type="file" id="res_pic" multiple name="res_pic[]"
                                accept=".jpg,.jpeg,.png,.gif">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="menu_pic" class="form-label">菜單圖片<small>（最多選6張，僅限JPG、PNG、GIF格式）</small></label>
                            <input class="form-control" type="file" id="menu_pic" multiple name="menu_pic[]"
                                accept=".jpg,.jpeg,.png,.gif">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">特別菜單<small>（最多為3道）</small></label>
                            <div id="sp_menu_area" class="mb-2 d-flex flex-wrap">
                                <div class="col-12 col-xl-4" id="cardarea1" style="display:none"></div>
                                <div class="col-12 col-xl-4" id="cardarea2" style="display:none"></div>
                                <div class="col-12 col-xl-4" id="cardarea3" style="display:none"></div>
                                <!-- add card -->
                                <div class="col-12 col-xl-4" style="cursor: pointer">
                                    <div class="card add-card" style="height: 288px">
                                        <div class="card-body d-flex justify-content-center align-items-center plus-icon"
                                            style="height: 288px">
                                            <i class="fas fa-plus-circle" style="color: #999;"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- sp menu -->
                                <!-- <div class="card col-12 col-xl-4" id="sp_menu_card_1">
                                    <div class="text-center" style="height:200px; line-height: 200px;">
                                        <button onclick='sp_menu_pic_name_1.click()' class="w-100 click-image" id="click_image_1">點擊選擇圖片</button>
                                      <input style="display:none" type="file" id="sp_menu_pic_name_1" name="sp_menu_pic_name" accept=".jpg,.jpeg,.png,.gif">
                                    </div>
                                    <div class="card-body d-flex justify-content-center">
                                      <input type="text" id="sp_menu1" name="sp_menu_name_1" class="text-center" placeholder="輸入特別菜單名" style="border:1px #aaa; border-bottom-style: solid;border-top-style: none;border-left-style:none;border-right-style:none; width: 60%">
                                    </div>
                                    <i class="fas fa-trash" style="padding: .5rem" id="delete1"></i>
                                </div> -->
                                <!-- sp menu card -->
                                <!-- <div class="card col-12 col-xl-4">
                                    <img src="https://fakeimg.pl/700x200/" style="width:100%; height:200px; object-fit:cover">
                                    <div class="card-body">
                                      <p class="card-text text-center">test</p>
                                    </div>
                                </div> -->
                            </div>
                            <div class="form-text" id="picalert"></div>
                            <div class="form-text" id="menualert"></div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-secondary w-25">新增</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../parts/__main_end.html'?>
<!-- 如果要 modal 的話留下面的結構 -->
<?php include __DIR__ . '/../parts/__modal.html'?>

<?php include __DIR__ . '/../parts/__script.html'?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));

const res_name = document.querySelector("#res_name");
const res_intro = document.querySelector("#res_intro");
const res_address = document.querySelector("#res_address");

const ser_sun = document.querySelector("#ser_sun");
const ser_mon = document.querySelector("#ser_mon");
const ser_tue = document.querySelector("#ser_tue");
const ser_wed = document.querySelector("#ser_wed");
const ser_thu = document.querySelector("#ser_thu");
const ser_fri = document.querySelector("#ser_fri");
const ser_sat = document.querySelector("#ser_sat");

const res_t_number = document.querySelector("#res_t_number");
const web_link = document.querySelector("#web_link");
const fb_link = document.querySelector("#fb_link");
const ig_link = document.querySelector("#ig_link");
const booking_link = document.querySelector("#booking_link");

const tele_re = /\d{2,4}-?\d{3,4}-?\d{3,4}#?(\d+)?/;
const url_re = /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/i;

const res_pic = document.querySelector("#res_pic");
const menu_pic = document.querySelector("#menu_pic");

const sp_menu_area = document.querySelector("#sp_menu_area");
const addCard = document.querySelector(".add-card");

const picalert = document.querySelector("#picalert");
const menualert = document.querySelector("#menualert");

function menu1() {
    document.querySelector("#cardarea1").style.display = "block";
    let menu_card = document.createElement('div');
    menu_card.setAttribute("id", "sp_menu_card_1");
    menu_card.classList.add("card", "sp-menu-card", "sp_menu_card_1");
    let top = document.createElement('div');
    top.classList.add("text-center")
    top.style.height = "200px";
    top.style.lineHeight = "200px";
    let imgButton = document.createElement('button');
    imgButton.onclick = function() {
        document.getElementById("sp_menu_pic_name_1").click()
    };;
    imgButton.classList.add("w-100", "click-image");
    imgButton.setAttribute("id", "click_image_1");
    imgButton.setAttribute("type", "button");
    imgButton.innerHTML = "點擊選擇圖片";
    let imgInput = document.createElement('input');
    imgInput.style.display = "none";
    imgInput.setAttribute("type", "file");
    imgInput.setAttribute("id", "sp_menu_pic_name_1");
    imgInput.setAttribute("name", "sp_menu_pic_name1");
    imgInput.setAttribute("accept", ".jpg,.jpeg,.png,.gif");
    top.appendChild(imgButton);
    top.appendChild(imgInput);


    let bottom = document.createElement('div');
    bottom.classList.add("card-body", "d-flex", "justify-content-center");
    let textInput = document.createElement('input');
    textInput.classList.add("text-center", "menu-input");
    textInput.setAttribute("type", "text");
    textInput.setAttribute("id", "sp_menu1");
    textInput.setAttribute("name", "sp_menu_name_1");
    textInput.setAttribute("placeholder", "輸入特別菜單名");
    bottom.appendChild(textInput);

    let delDiv = document.createElement('div');
    delDiv.style.marginLeft = "auto";
    let del = document.createElement('i');
    del.classList.add("fas", "fa-trash");
    del.style.padding = ".5rem";
    del.setAttribute("id", "delete1");
    delDiv.appendChild(del);

    menu_card.appendChild(top);
    menu_card.appendChild(bottom);
    menu_card.appendChild(delDiv);

    document.querySelector("#cardarea1").appendChild(menu_card);

}

function menu2() {
    document.querySelector("#cardarea2").style.display = "block";
    let menu_card = document.createElement('div');
    menu_card.setAttribute("id", "sp_menu_card_2");
    menu_card.classList.add("card", "sp-menu-card", "sp_menu_card_2");
    let top = document.createElement('div');
    top.classList.add("text-center")
    top.style.height = "200px";
    top.style.lineHeight = "200px";
    let imgButton = document.createElement('button');
    imgButton.onclick = function() {
        document.getElementById("sp_menu_pic_name_2").click()
    };;
    imgButton.classList.add("w-100", "click-image");
    imgButton.setAttribute("id", "click_image_2");
    imgButton.setAttribute("type", "button");
    imgButton.innerHTML = "點擊選擇圖片";
    let imgInput = document.createElement('input');
    imgInput.style.display = "none";
    imgInput.setAttribute("type", "file");
    imgInput.setAttribute("id", "sp_menu_pic_name_2");
    imgInput.setAttribute("name", "sp_menu_pic_name2");
    imgInput.setAttribute("accept", ".jpg,.jpeg,.png,.gif");
    top.appendChild(imgButton);
    top.appendChild(imgInput);

    let bottom = document.createElement('div');
    bottom.classList.add("card-body", "d-flex", "justify-content-center");
    let textInput = document.createElement('input');
    textInput.classList.add("text-center", "menu-input");
    textInput.setAttribute("type", "text");
    textInput.setAttribute("id", "sp_menu2");
    textInput.setAttribute("name", "sp_menu_name_2");
    textInput.setAttribute("placeholder", "輸入特別菜單名");
    bottom.appendChild(textInput);

    let delDiv = document.createElement('div');
    delDiv.style.marginLeft = "auto";
    let del = document.createElement('i');
    del.classList.add("fas", "fa-trash");
    del.style.padding = ".5rem";
    del.setAttribute("id", "delete2");
    delDiv.appendChild(del);

    menu_card.appendChild(top);
    menu_card.appendChild(bottom);
    menu_card.appendChild(delDiv);
    document.querySelector("#cardarea2").appendChild(menu_card);
}

function menu3() {
    document.querySelector("#cardarea3").style.display = "block";
    let menu_card = document.createElement('div');
    menu_card.setAttribute("id", "sp_menu_card_3");
    menu_card.classList.add("card", "sp-menu-card", "sp_menu_card_3");
    let top = document.createElement('div');
    top.classList.add("text-center")
    top.style.height = "200px";
    top.style.lineHeight = "200px";
    let imgButton = document.createElement('button');
    imgButton.onclick = function() {
        document.getElementById("sp_menu_pic_name_3").click()
    };;
    imgButton.classList.add("w-100", "click-image");
    imgButton.setAttribute("id", "click_image_3");
    imgButton.setAttribute("type", "button");
    imgButton.innerHTML = "點擊選擇圖片";
    let imgInput = document.createElement('input');
    imgInput.style.display = "none";
    imgInput.setAttribute("type", "file");
    imgInput.setAttribute("id", "sp_menu_pic_name_3");
    imgInput.setAttribute("name", "sp_menu_pic_name3");
    imgInput.setAttribute("accept", ".jpg,.jpeg,.png,.gif");
    top.appendChild(imgButton);
    top.appendChild(imgInput);


    let bottom = document.createElement('div');
    bottom.classList.add("card-body", "d-flex", "justify-content-center");
    let textInput = document.createElement('input');
    textInput.classList.add("text-center", "menu-input");
    textInput.setAttribute("type", "text");
    textInput.setAttribute("id", "sp_menu3");
    textInput.setAttribute("name", "sp_menu_name_3");
    textInput.setAttribute("placeholder", "輸入特別菜單名");
    bottom.appendChild(textInput);

    let delDiv = document.createElement('div');
    delDiv.style.marginLeft = "auto";
    let del = document.createElement('i');
    del.classList.add("fas", "fa-trash");
    del.style.padding = ".5rem";
    del.setAttribute("id", "delete3");
    delDiv.appendChild(del);

    menu_card.appendChild(top);
    menu_card.appendChild(bottom);
    menu_card.appendChild(delDiv);
    document.querySelector("#cardarea3").appendChild(menu_card);

}

addCard.addEventListener("click", function() {
    // 新增第一張圖片
    if (document.getElementsByClassName("sp-menu-card").length == 0) {
        menu1();
    }

    // 新增第二張圖片
    else if (document.getElementsByClassName("sp-menu-card").length == 1) {
        if (!document.getElementById("sp_menu_card_2")) {
            menu2();
        } else {
            !document.getElementById("sp_menu_card_3") ? menu3() : menu1();
        }
    }

    // 新增第三張圖片
    else if (document.getElementsByClassName("sp-menu-card").length == 2) {
        if (!document.getElementById("sp_menu_card_3")) {
            menu3();
        } else {
            !document.getElementById("sp_menu_card_1") ? menu1() : menu2();
        }
    }

})


addCard.addEventListener("click", function() {
    if (!!document.getElementById("sp_menu_card_1")) {
        document.getElementById("sp_menu_pic_name_1").onchange = evt => {
            const [file] = document.getElementById("sp_menu_pic_name_1").files;
            if (file) {
                document.getElementById("click_image_1").style.background =
                    `url("${URL.createObjectURL(file)}") no-repeat center center`;
                document.getElementById("click_image_1").style.backgroundSize = "contain";
            }
        }

        document.getElementById("delete1").addEventListener("click", function() {
            document.querySelectorAll(".sp_menu_card_1").forEach(item => {
                item.remove()
            })
            document.querySelector("#cardarea1").style.display = "none";
        })
    }
    if (!!document.getElementById("sp_menu_card_2")) {
        document.getElementById("sp_menu_pic_name_2").onchange = evt => {
            const [file] = document.getElementById("sp_menu_pic_name_2").files;
            if (file) {
                document.getElementById("click_image_2").style.background =
                    `url("${URL.createObjectURL(file)}") no-repeat center center`;
                document.getElementById("click_image_2").style.backgroundSize = "contain";
            }
        }

        document.getElementById("delete2").addEventListener("click", function() {
            document.querySelectorAll(".sp_menu_card_2").forEach(item => {
                item.remove()
            })
            document.querySelector("#cardarea2").style.display = "none";
        })

    }
    if (!!document.getElementById("sp_menu_card_3")) {
        document.getElementById("sp_menu_pic_name_3").onchange = evt => {
            const [file] = document.getElementById("sp_menu_pic_name_3").files;
            if (file) {
                document.getElementById("click_image_3").style.background =
                    `url("${URL.createObjectURL(file)}") no-repeat center center`;
                document.getElementById("click_image_3").style.backgroundSize = "contain";
            }
        }

        document.getElementById("delete3").addEventListener("click", function() {
            document.querySelectorAll(".sp_menu_card_3").forEach(item => {
                item.remove()
            })
            document.querySelector("#cardarea3").style.display = "none";
        })
    }
})

addEventListener("click", function() {
    if (document.getElementsByClassName("sp-menu-card").length == 3) {
        addCard.style.display = "none";
    } else {
        addCard.style.display = "block";
    }
})

function sendData() {

    res_name.nextElementSibling.innerHTML = '';
    res_intro.nextElementSibling.innerHTML = '';
    res_address.nextElementSibling.innerHTML = '';
    ser_sun.nextElementSibling.innerHTML = '';
    ser_mon.nextElementSibling.innerHTML = '';
    ser_tue.nextElementSibling.innerHTML = '';
    ser_wed.nextElementSibling.innerHTML = '';
    ser_thu.nextElementSibling.innerHTML = '';
    ser_fri.nextElementSibling.innerHTML = '';
    ser_sat.nextElementSibling.innerHTML = '';
    res_t_number.nextElementSibling.innerHTML = '';
    web_link.nextElementSibling.innerHTML = '';
    fb_link.nextElementSibling.innerHTML = '';
    ig_link.nextElementSibling.innerHTML = '';
    booking_link.nextElementSibling.innerHTML = '';
    picalert.innerHTML = '';
    menualert.innerHTML = '';

    // 檢查資料是否輸入正確
    let isPass = true;
    // 檢查餐廳名稱
    if (res_name.value === "") {
        isPass = false;
        res_name.nextElementSibling.innerHTML = `
            <div class="alert alert-dark mt-2" role="alert">請輸入餐廳名稱</div>
            `;
    }
    if (res_name.value.length > 50) {
        isPass = false;
        res_name.nextElementSibling.innerHTML = `
            <div class="alert alert-dark mt-2" role="alert">餐廳名稱請小於50個字元</div>
            `;
    }
    // 檢查餐廳介紹
    if (res_intro.value === "") {
        isPass = false;
        res_intro.nextElementSibling.innerHTML = `
            <div class="alert alert-dark mt-2" role="alert">請輸入餐廳介紹</div>
            `;
    }
    if (res_intro.value.length > 255) {
        isPass = false;
        res_intro.nextElementSibling.innerHTML = `
            <div class="alert alert-dark mt-2" role="alert">餐廳介紹請小於255個字元</div>
            `;
    }
    // 檢查餐廳地址
    if (res_address.value === "") {
        isPass = false;
        res_address.nextElementSibling.innerHTML = `
            <div class="alert alert-dark mt-2" role="alert">請輸入餐廳地址</div>
            `;
    }
    if (res_address.value.length > 255) {
        isPass = false;
        res_address.nextElementSibling.innerHTML = `
            <div class="alert alert-dark mt-2" role="alert">餐廳地址請小於255個字元</div>
            `;
    }
    // 檢查是否輸入營業時間
    if (ser_sun.value === "") {
        isPass = false;
        ser_sun.nextElementSibling.innerHTML = `
            <div class="alert alert-dark" role="alert">請輸入星期日營業時間</div>
            `;
    }
    if (ser_mon.value === "") {
        isPass = false;
        ser_mon.nextElementSibling.innerHTML = `
            <div class="alert alert-dark" role="alert">請輸入星期一營業時間</div>
            `;
    }
    if (ser_tue.value === "") {
        isPass = false;
        ser_tue.nextElementSibling.innerHTML = `
            <div class="alert alert-dark" role="alert">請輸入星期二營業時間</div>
            `;
    }
    if (ser_wed.value === "") {
        isPass = false;
        ser_wed.nextElementSibling.innerHTML = `
            <div class="alert alert-dark" role="alert">請輸入星期三營業時間</div>
            `;
    }
    if (ser_thu.value === "") {
        isPass = false;
        ser_thu.nextElementSibling.innerHTML = `
            <div class="alert alert-dark" role="alert">請輸入星期四營業時間</div>
            `;
    }
    if (ser_fri.value === "") {
        isPass = false;
        ser_fri.nextElementSibling.innerHTML = `
            <div class="alert alert-dark" role="alert">請輸入星期五營業時間</div>
            `;
    }
    if (ser_sat.value === "") {
        isPass = false;
        ser_sat.nextElementSibling.innerHTML = `
            <div class="alert alert-dark" role="alert">請輸入星期六營業時間</div>
            `;
    }
    // 檢查餐廳電話
    if (res_t_number.value == "") {
        isPass = false;
        res_t_number.nextElementSibling.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             請輸入餐廳電話
            </div>`;
    }
    if (res_t_number.value && !tele_re.test(res_t_number.value)) {
        isPass = false;
        res_t_number.nextElementSibling.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             請輸入正確電話號碼
            </div>`;
    }
    // 檢查網址是否正確
    if (web_link.value && !url_re.test(web_link.value)) {
        isPass = false;
        web_link.nextElementSibling.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             請輸入正確網址
            </div>`;
    }
    if (fb_link.value && !url_re.test(fb_link.value)) {
        isPass = false;
        fb_link.nextElementSibling.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             請輸入正確網址
            </div>`;
    }
    if (ig_link.value && !url_re.test(ig_link.value)) {
        isPass = false;
        ig_link.nextElementSibling.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             請輸入正確網址
            </div>`;
    }
    if (booking_link.value && !url_re.test(booking_link.value)) {
        isPass = false;
        booking_link.nextElementSibling.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             請輸入正確網址
            </div>`;
    }
    // 檢查上傳檔案數量
    if (res_pic.files.length > 6) {
        isPass = false;
        res_pic.nextElementSibling.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             餐廳圖片最多上傳六張圖片
            </div>`;
    }
    if (menu_pic.files.length > 6) {
        isPass = false;
        menu_pic.nextElementSibling.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             菜單圖片最多上傳六張圖片
            </div>`;
    }
    // // TODO:檢查特別菜單欄位有點BUG
    if (!!document.getElementById("sp_menu_card_1")) {
        if ((document.querySelector("#sp_menu_pic_name_1").files.length == 0) && (document.querySelector("#sp_menu1")
                .value.length != 0)) {
            isPass = false;
            picalert.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             未上傳圖片
            </div>`;
        }
        if ((document.querySelector("#sp_menu_pic_name_1").files.length != 0) && (document.querySelector("#sp_menu1")
                .value.length == 0)) {
            isPass = false;
            menualert.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             未輸入特別菜單名
            </div>`;
        }
    }
    if (!!document.getElementById("sp_menu_card_2")) {
        if ((document.querySelector("#sp_menu_pic_name_2").files.length == 0) && (document.querySelector("#sp_menu2")
                .value.length != 0)) {
            isPass = false;
            picalert.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             未上傳圖片
            </div>`;
        }
        if ((document.querySelector("#sp_menu_pic_name_2").files.length != 0) && (document.querySelector("#sp_menu2")
                .value.length == 0)) {
            isPass = false;
            menualert.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             未輸入特別菜單名
            </div>`;
        }
    }
    if (!!document.getElementById("sp_menu_card_3")) {
        if ((document.querySelector("#sp_menu_pic_name_3").files.length == 0) && (document.querySelector("#sp_menu3")
                .value.length != 0)) {
            isPass = false;
            picalert.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             未上傳圖片
            </div>`;
        }
        if ((document.querySelector("#sp_menu_pic_name_3").files.length != 0) && (document.querySelector("#sp_menu3")
                .value.length == 0)) {
            isPass = false;
            menualert.innerHTML = `<div class="alert alert-dark mt-2" role="alert">
             未輸入特別菜單名
            </div>`;
        }
    }






    if (isPass === true) {
        const fd = new FormData(document.form1);
        fetch('restaurant_add-api.php', {
                method: 'POST',
                body: fd,
            })
            .then(res => res.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    document.querySelector('.modal-body').innerHTML = "資料新增成功";
                    document.querySelector('.modal-footer').innerHTML =
                        `<a href="restaurant.php" class="btn btn-secondary">完成</a>`;
                    modal.show();
                } else {
                    document.querySelector('.modal-body').innerHTML = data.error || "資料新增發生錯誤";
                    modal.show();
                }
            })
    }


}
</script>
<?php include __DIR__ . '/../parts/__foot.html'?>