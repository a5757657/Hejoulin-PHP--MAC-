<?php require __DIR__. '/../parts/__connect_db.php';

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

require __DIR__ . '\..\vendor\autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$title = "合作餐廳列表";
$pageName = "restaurant_list";

$perPage = 5;   // 每頁 5 筆

$page = isset($_GET['page']) ? intval($_GET['page']) : 1 ;  // 這邊使用 $_GET 接收query string，若沒有值則為1（第一頁）

$param = $_GET['param'] ?? '';


$sql_t = "SELECT COUNT(1) FROM restaurant WHERE `res_name` LIKE '%" . $param . "%'";
$sql = "SELECT * FROM restaurant WHERE `res_name` LIKE '%" . $param . "%'";


$total = $pdo->query($sql_t)->fetch(PDO::FETCH_NUM)[0];


$totalPages = ceil($total/$perPage);   // 算總頁數



if ($page < 1) {
    if( isset($_GET['res_type']) && !isset($_GET['res_area']) ) {
        header('Location: restaurant.php?res_type=' . $res_type);
        exit;
    } else if( isset($_GET['res_area']) && !isset($_GET['res_type']) ) {
        header('Location: restaurant.php?res_area=' . $res_area);
        exit;
    } else {
        header('Location: restaurant.php');
        exit;
    }

}
  
if ($page > $totalPages) {
    if( isset($_GET['res_type']) && !isset($_GET['res_area']) ) {
        header('Location: restaurant.php?res_type=' . $res_type . '&page=' . $totalPages);
        exit;
    } else if( isset($_GET['res_area']) && !isset($_GET['res_type']) ) {
        header('Location: restaurant.php?res_area=' . $res_area . '&page=' . $totalPages);
        exit;
    }  else {
        header('Location: restaurant.php' . '?page=' . $totalPages);
        exit;
    }
}

$rows = $pdo->query($sql)->fetchAll();

$sql_all = "SELECT * FROM restaurant";
$all = $pdo->query($sql_all)->fetchAll();

$sql_res_pic = "SELECT * FROM restaurant_pictures";
$all_res_pic = $pdo->query($sql_res_pic)->fetchAll();

$sql_menu_pic = "SELECT * FROM menu_pictures";
$all_menu_pic = $pdo->query($sql_menu_pic)->fetchAll();

$sql_sp_menu = "SELECT * FROM special_menu";
$all_sp_menu = $pdo->query($sql_sp_menu)->fetchAll();

if(isset($_POST['export'])) {
    header('Content-Type: application/x-www-form-urlencoded');
    header('Content-Transfer-Encoding: Binary');    
    header('Content-disposition: attachment; filename="' . date('Y-m-d') . '_restaurant_list.xlsx"');
    $file = new Spreadsheet();
    $sheet = $file->getActiveSheet();
    $sheet->setCellValue('A1', '編號');
    $sheet->setCellValue('B1', '餐廳類型');
    $sheet->setCellValue('C1', '餐廳地區');
    $sheet->setCellValue('D1', '餐廳名稱');
    $sheet->setCellValue('E1', '餐廳介紹');
    $sheet->setCellValue('F1', '餐廳地址');
    $sheet->setCellValue('G1', '餐廳電話');
    $sheet->setCellValue('H1', '餐廳官網');
    $sheet->setCellValue('I1', '餐廳FB');
    $sheet->setCellValue('J1', '餐廳IG');
    $sheet->setCellValue('K1', '訂位網址');
    $sheet->setCellValue('L1', '建立時間');
    $sheet->setCellValue('M1', '最後修改時間');

    $count = 2;

    foreach($all as $r) {
        $sheet->setCellValue('A' . $count, $r['res_id']);
        $sheet->setCellValue('B' . $count, $r['res_type']);
        $sheet->setCellValue('C' . $count, $r['res_area']);
        $sheet->setCellValue('D' . $count, $r['res_name']);
        $sheet->setCellValue('E' . $count, $r['res_intro']);
        $sheet->setCellValue('F' . $count, $r['res_address']);
        $sheet->setCellValue('G' . $count, $r['res_t_number']);
        $sheet->setCellValue('H' . $count, $r['web_link']);
        $sheet->setCellValue('I' . $count, $r['fb_link']);
        $sheet->setCellValue('J' . $count, $r['ig_link']);
        $sheet->setCellValue('K' . $count, $r['booking_link']);
        $sheet->setCellValue('L' . $count, $r['res_create_date']);
        $sheet->setCellValue('M' . $count, $r['res_update_date']);

        $count = $count + 1;
    }

    $writer = new Xlsx($file);
    $writer->save("php://output");

    exit;

}


?>
<?php include __DIR__ . '/../parts/__head.php'?>
<style>
.image-container {
    position: relative;
}

.image-container img {
    width: 100%;
    height: auto;
}

.filter:focus {
    box-shadow: none !important;
}
</style>
<?php include __DIR__ . '/../parts/__navbar.php'?>
<?php include __DIR__ . '/../parts/__sidebar.html'?>

<?php include __DIR__ . '/../parts/__main_start.html'?>
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->
<!-- table -->
<div class="d-flex justify-content-between mt-5 align-items-center">
    <div class="d-flex gap-1">
        <button type="button" class="btn btn-secondary btn-sm" onclick="deleteMany()">刪除選擇項目</button>
        <button type="button" class="btn btn-secondary btn-sm"
            onclick="location.href='restaurant_add.php'">新增合作餐廳</button>
        <div>
            <form method="post">
                <input type="submit" class="btn btn-secondary btn-sm" name="export" value="輸出所有資料"></input>
            </form>
        </div>

        <div class="input-group input-group-sm" style="width: 200px">
            <input id="search" type="text" class="form-control" aria-label="Sizing example input"
                aria-describedby="inputGroup-sizing-sm">
            <button id="send" class="btn btn-secondary"><i class="fas fa-search"></i></button>
        </div>
    </div>
    <nav aria-label="Page navigation example">
        <ul class="pagination" style="margin-bottom: 0px !important">
            <li class="page-item <?= 1==$page ? 'disabled' : '' ?>">
                <a class="page-link" href="?<?php 
                    if( isset($_GET['res_type']) && !isset($_GET['res_area']) ) {
                        echo 'res_type=' . $res_type . '&';
                    } else if( isset($_GET['res_area']) && !isset($_GET['res_type']) ) {
                        echo 'res_area=' . $res_area . '&';
                    } else {
                        echo '';
                    }
                ?>page=<?= $page-5 ?>"><i class="fas fa-angle-double-left"></i></a>
            </li>
            <li class="page-item <?= 1==$page ? 'disabled' : '' ?>">
                <a class="page-link" href="?<?php 
                    if( isset($_GET['res_type']) && !isset($_GET['res_area']) ) {
                        echo 'res_type=' . $res_type . '&';
                    } else if( isset($_GET['res_area']) && !isset($_GET['res_type']) ) {
                        echo 'res_area=' . $res_area . '&';
                    } else {
                        echo '';
                    }
                ?>
                page=<?= $page-1 ?>"><i class="fas fa-angle-left"></i></a>
            </li>
            <?php for($i=1; $i<=$totalPages; $i++): ?>
            <li class="page-item <?= $page==$i? 'active' : ''?>"><a class="page-link" href="?<?php 
                    if( isset($_GET['res_type']) && !isset($_GET['res_area']) ) {
                        echo 'res_type=' . $res_type . '&';
                    } else if( isset($_GET['res_area']) && !isset($_GET['res_type']) ) {
                        echo 'res_area=' . $res_area . '&';
                    } else {
                        echo '';
                    }
                ?>
                page=<?=$i?>"><?= $i ?></a></li>
            <?php endfor ?>
            <li class="page-item <?= $totalPages==$page ? 'disabled' : '' ?>">
                <a class="page-link" href="?<?php 
                    if( isset($_GET['res_type']) && !isset($_GET['res_area']) ) {
                        echo 'res_type=' . $res_type . '&';
                    } else if( isset($_GET['res_area']) && !isset($_GET['res_type']) ) {
                        echo 'res_area=' . $res_area . '&';
                    }  else {
                        echo '';
                    }
                ?>
                page=<?= $page+1 ?>"><i class="fas fa-angle-right"></i></a>
            </li>
            <li class="page-item <?= $totalPages==$page ? 'disabled' : '' ?>">
                <a class="page-link" href="?<?php 
                    if( isset($_GET['res_type']) && !isset($_GET['res_area']) ) {
                        echo 'res_type=' . $res_type . '&';
                    } else if( isset($_GET['res_area']) && !isset($_GET['res_type']) ) {
                        echo 'res_area=' . $res_area . '&';
                    } else {
                        echo '';
                    }
                ?>
                page=<?= $page+5 ?>"><i class="fas fa-angle-double-right"></i></a>
            </li>
        </ul>
    </nav>
</div>
<div class="table-responsive mt-3" style="overflow-x: scroll; height: 80vh;">
    <!-- table style 是為了讓表格的卷軸顯示在比較明顯的位置 -->
    <table class="table table-striped table-sm">
        <thead>
            <tr class="d-flex">
                <th>
                    <input class="form-check-input" type="checkbox" value="" id="isAll" />
                </th>
                <th style="flex: 0 0 auto; width: 3%; text-align: center">
                    刪除
                </th>
                <th>#</th>
                <th class="col-1">
                    <div class="btn-group">
                        <button class="btn btn-secondary btn-sm dropdown-toggle filter" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false" style="
                        background-color: transparent;
                        color: #212529;
                        height: auto;
                        border: none;
                        font-weight: 600;
                        transform: translateY(-4px);">
                            餐廳類型
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="restaurant.php">全部類型</a></li>
                            <li><a class="dropdown-item" href="restaurant.php?res_type=Fine Dining">Fine Dining</a></li>
                            <li><a class="dropdown-item" href="restaurant.php?res_type=Sake Bar">Sake Bar</a></li>
                            <li><a class="dropdown-item" href="restaurant.php?res_type=居酒屋">居酒屋</a></li>
                        </ul>
                    </div>
                </th>
                <th class="col-1">
                    <div class="btn-group">
                        <button class="btn btn-secondary btn-sm dropdown-toggle filter" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false" style="
                        background-color: transparent;
                        color: #212529;
                        height: auto;
                        border: none;
                        font-weight: 600;
                        transform: translateY(-4px);">
                            餐廳地區
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="restaurant.php">全部地區</a></li>
                            <li><a class="dropdown-item" href="restaurant.php?res_area=北部">北部</a></li>
                            <li><a class="dropdown-item" href="restaurant.php?res_area=中部">中部</a></li>
                            <li><a class="dropdown-item" href="restaurant.php?res_area=南部">南部</a></li>
                        </ul>
                    </div>
                </th>
                <th class="col-1">餐廳名稱</th>
                <th class="col-1">餐廳介紹</th>
                <th class="col-1">餐廳地址</th>
                <th style="flex: 0 0 auto; width: 16%;">營業時間</th>
                <th class="col-1">餐廳電話</th>
                <th class="col-1">餐廳官網</th>
                <th class="col-1">餐廳FB</th>
                <th class="col-1">餐廳IG</th>
                <th class="col-1">訂位網址</th>
                <th class="col-2">餐廳圖片</th>
                <th class="col-2">菜單圖片</th>
                <th class="col-2">特別菜單</th>
                <th style="flex: 0 0 auto; width: 3%; text-align: center">
                    編輯
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $r) { ?>
            <tr class="d-flex">
                <td>
                    <input class="form-check-input check" type="checkbox" value="<?= $r['res_id']?>" />
                </td>
                <td style="flex: 0 0 auto; width: 3%; text-align: center">
                    <a href="javascript: delete_it(<?=$r['res_id']?>)"><i class="fas fa-trash"></i></a>
                </td>
                <td><?= htmlentities($r['res_id']) ?></td>
                <td class="col-1"><?= htmlentities($r['res_type']) ?></td>
                <td class="col-1"><?= htmlentities($r['res_area']) ?></td>
                <td class="col-1"><?= htmlentities($r['res_name']) ?></td>
                <td class="col-1"><?= htmlentities($r['res_intro']) ?></td>
                <td class="col-1"><?= htmlentities($r['res_address']) ?></td>
                <td style="flex: 0 0 auto; width: 16%;" data-time="<?= $r['res_id']?>"></td>
                <td class="col-1" style="overflow-wrap: break-word;"><?= htmlentities($r['res_t_number']) ?></td>
                <td class="col-1" style="overflow-wrap: break-word;">
                    <?php if (!empty(htmlentities($r['web_link']))) { ?>
                    <a href="<?= htmlentities($r['web_link']) ?>" target="_blank" style="color: #999"
                        onMouseOver="this.style.color='#ccc'" onMouseOut="this.style.color='#999'">
                        <?= htmlentities($r['web_link']) ?>
                    </a>
                    <?php } ?>
                </td>
                <td class="col-1" style="overflow-wrap: break-word;">
                    <?php if (!empty(htmlentities($r['fb_link']))) { ?>
                    <a href="<?= htmlentities($r['fb_link']) ?>" target="_blank" style="color: #999"
                        onMouseOver="this.style.color='#ccc'" onMouseOut="this.style.color='#999'">
                        <?= htmlentities($r['fb_link']) ?>
                    </a>
                    <?php } ?>
                </td>
                <td class="col-1" style="overflow-wrap: break-word;">
                    <?php if (!empty(htmlentities($r['ig_link']))) { ?>
                    <a href="<?= htmlentities($r['ig_link']) ?>" target="_blank" style="color: #999"
                        onMouseOver="this.style.color='#ccc'" onMouseOut="this.style.color='#999'">
                        <?= htmlentities($r['ig_link']) ?>
                    </a>
                    <?php } ?>
                </td>
                <td class="col-1" style="overflow-wrap: break-word;">
                    <?php if (!empty(htmlentities($r['booking_link']))) { ?>
                    <a href="<?= htmlentities($r['booking_link']) ?>" target="_blank" style="color: #999"
                        onMouseOver="this.style.color='#ccc'" onMouseOut="this.style.color='#999'">
                        <?= htmlentities($r['booking_link']) ?>
                    </a>
                    <?php } ?>
                </td>
                <td class="col-2" data-respic="<?= $r['res_id']?>"></td>
                <td class="col-2" data-menupic="<?= $r['res_id']?>"></td>
                <td class="col-2" data-spmenu="<?= $r['res_id']?>"></td>
                <td style="flex: 0 0 auto; width: 3%; text-align: center">
                    <a href="restaurant_edit.php?res_id=<?= $r['res_id']?>"><i class="fas fa-pen"></i></a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../parts/__main_end.html'?>

<!-- 如果要 modal 的話留下面的結構 -->
<?php include __DIR__ . '/../parts/__modal.html'?>



<!-- imageModal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="image-container">
                    <img class="modal-img" src="">
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../parts/__script.html'?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
const imageModal = new bootstrap.Modal(document.querySelector('#imageModal'));
const modalBody = document.querySelector('.modal-body');
const tds = document.querySelectorAll('td');

const isAll = document.querySelector("#isAll");
const check = document.querySelectorAll(".check");
let v = false;
isAll.addEventListener("click", function() {
    if (v == false) {
        for (let i = 0; i < check.length; i++) {
            check[i].checked = true
        }
        v = true
    } else {
        for (let i = 0; i < check.length; i++) {
            check[i].checked = false
        }
        v = false
    }
})

// 刪除資料
function delete_it(res_id) {
    modalBody.innerHTML = `確定要刪除編號為 ${res_id} 的資料嗎？`;
    document.querySelector('.modal-footer').innerHTML =
        `<a href="delete-many.php?res_id=${res_id}" class="btn btn-secondary">刪除</a>`;
    modal.show();
}

function deleteMany() {
    let checked = [];
    let resId = [];
    let newString = '';
    for (let i = 0; i < check.length; i++) {
        if (check[i].checked == true) {
            checked.push(check[i]);
        }
    }
    for (let i = 0; i < checked.length; i++) {
        resId.push(checked[i].value);
    }
    newString = resId.join(",")
    if (resId.length == 0) {
        modalBody.innerHTML = `目前尚未選取項目。`;
        document.querySelector('.modal-footer').innerHTML =
            `<button type="button" onclick="modal.hide()" class="btn btn-secondary">確認</button>`;
        modal.show();
    } else {
        delete_it(newString)
    }
}

const all = <?= json_encode($all) ?>; // 將資料庫資料送到前端
const allResPic = <?= json_encode($all_res_pic) ?>; // 將餐廳圖片資料庫資料送到前端
const allMenuPic = <?= json_encode($all_menu_pic) ?>; // 將餐廳圖片資料庫資料送到前端
const allSpMenu = <?= json_encode($all_sp_menu) ?>; // 將餐廳圖片資料庫資料送到前端

// render 營業時間
for (let i = 0; i < all.length; i++) {
    tds.forEach(td => {
        if (td.dataset.time == all[i].res_id) {
            let parse = JSON.parse(all[i].res_ser_hours);
            document.querySelector(`[data-time="${td.dataset.time}"]`).innerHTML = `
                <select class="form-select form-select-sm" aria-label="Default select example">
                <option value="1">星期一 ${parse[2]}</option>
                <option value="2">星期二 ${parse[3]}</option>
                <option value="3">星期三 ${parse[4]}</option>
                <option value="4">星期四 ${parse[5]}</option>
                <option value="5">星期五 ${parse[6]}</option>
                <option value="6">星期六 ${parse[0]}</option>
                <option value="7">星期日 ${parse[1]}</option>
                </select>
                `
        }
    })
}
const options = document.querySelectorAll('option');
let today = new Date().getDay();
options.forEach(option => {
    if (option.value == today) {
        option.selected = true
    } // 如果是今天，顯示今天的時間
})

// render 餐廳圖片
for (let i = 0; i < allResPic.length; i++) {
    tds.forEach(td => {
        if (td.dataset.respic == allResPic[i].res_id) {
            document.querySelector(`[data-respic="${td.dataset.respic}"]`).innerHTML += `
                <img onclick="showImg(this)" style="cursor: zoom-in; width:100px; object-fit:cover; margin-bottom:.5rem" src="../img/res_pic/${allResPic[i].res_pic_name}">
                `
        }
    })
}

// render 菜單圖片
for (let i = 0; i < allMenuPic.length; i++) {
    tds.forEach(td => {
        if (td.dataset.menupic == allMenuPic[i].res_id) {
            document.querySelector(`[data-menupic="${td.dataset.menupic}"]`).innerHTML += `
                <img onclick="showImg(this)" style="cursor: zoom-in; width:100px; object-fit:cover; margin-bottom:.5rem" src="../img/menu_pic/${allMenuPic[i].menu_pic_name}">
                `
        }
    })
}

// render 特別菜單
for (let i = 0; i < allSpMenu.length; i++) {
    tds.forEach(td => {
        if (td.dataset.spmenu == allSpMenu[i].res_id) {
            document.querySelector(`[data-spmenu="${td.dataset.spmenu}"]`).innerHTML += `

                <div class="card" style="width: 10rem; margin-bottom:.5rem">
                  <img src="../img/sp_menu/${allSpMenu[i].sp_menu_pic_name}" class="card-img-top" onclick="showImg(this)" style="cursor: zoom-in;">
                  <div class="card-body">
                    <p class="card-text">${allSpMenu[i].sp_menu_name}</p>
                  </div>
                </div>
                `
        }
    })
}


// show img modal

function showImg(e) {
    document.querySelector(".modal-img").setAttribute("src", e.src);
    imageModal.show();
}

// const url = new URL(location.href);
// const param = new URLSearchParams(url.search);
// let page = param.get('page');   // 將 query string "page" 送到前端

// search
const send = document.querySelector("#send");
const search = document.querySelector("#search");
send.addEventListener("click", function() {
    location.href = `restaurant-search.php?param=${search.value}`
})
</script>
<?php include __DIR__ . '/../parts/__foot.html'?>