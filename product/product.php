<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php

// 如果未登入管理帳號就轉向
if (!$_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

$title = '商品管理';

//商品排序
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'pro_id';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'ASC';

//每一頁出現幾筆資料
$perPage = isset($_GET['limit']) ? $_GET['limit'] : 8;


$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

//總比數
$t_sql = "SELECT COUNT(1) FROM `product_sake`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$totalPages = ceil($totalRows / $perPage);

//若page大於總頁數一律跳轉到最後一頁
if ($page > $totalPages) {
    header('Location: product.php?page=' . $totalPages);
    exit;
}

//若page小於總頁數一律跳轉到第一頁
if ($page < 1) {
    header('Location: product.php?page=' . '1');
    exit;
}

//判斷排序條件

//設定每一頁出現幾筆資料
$sql = sprintf("SELECT * , pf.* FROM `product_sake` ps JOIN `product_format` pf on ps.format_id = pf.format_id ORDER BY $sort_by $order_by LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

$rows = $pdo->query($sql)->fetchAll();
?>


<?php include __DIR__ . '/../parts/__head.php' ?>
<?php include __DIR__ . '/../parts/__navbar.php' ?>
<?php include __DIR__ . '/../parts/__sidebar.html' ?>
<?php include __DIR__ . '/../parts/__main_start.html' ?>

<style>
    /* 清酒圖片的css樣式 */
    .pro_img {
        height: 160px;
        max-width: 160px;
        padding: 10px;
        filter: drop-shadow(0px 5px 6px rgba(50, 50, 50, .5));
        /* 帶透明圖層用的陰影 */
    }
</style>

<div class="d-flex justify-content-between mt-5">
    <div class="col-8 d-flex justify-content-start gap-2">
        <button type="button" class="btn btn-secondary btn-sm" id="delAll">刪除選擇項目</button>
        <a href="product-insert.php"><button type="button" class="btn btn-secondary btn-sm">新增商品</button></a>
        <div class="col-3">
            <select class="form-select sort" name="sort" aria-label="Default select example">
                <option>**選擇排序**</option>
                <option value="1">預設排序</option>
                <option value="2">價格:由高到低</option>
                <option value="3">價格:由低到高</option>
                <option value="4">上架時間:由新到舊</option>
                <option value="5">上架時間:由舊到新</option>
            </select>
        </div>
        <div class="col-3">
            <select class="form-select limit" name="limit" aria-label="Default select example">
                <option>**每頁顯示數量**</option>
                <option value="1">每頁顯示4個</option>
                <option value="2">每頁顯示8個</option>
                <option value="3">每頁顯示12個</option>
                <option value="4">每頁顯示24個</option>
                <option value="5">每頁顯示48個</option>
            </select>
        </div>
    </div>

    <nav aria-label="Page navigation example">
        <ul class="pagination">

            <!-- 設定頁數的顯示 -->
            <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                <a class="page-link  " href="?sort_by=<?= $sort_by ?>&order_by=<?= $order_by ?>&limit=<?= $perPage ?>&page=<?= "1" ?>">
                    <i class="fas fa-angle-double-left"></i></a>
            </li>
            <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?sort_by=<?= $sort_by ?>&order_by=<?= $order_by ?>&limit=<?= $perPage ?>&page=<?= $page - 1 ?>"><i class="fas fa-angle-left"></i></a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <?php if ($i > ($page - 3) && $i < ($page + 3)) : ?>

                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?sort_by=<?= $sort_by ?>&order_by=<?= $order_by ?>&limit=<?= $perPage ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endif ?>
            <?php endfor ?>
            <li class="page-item <?= $totalPages == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?sort_by=<?= $sort_by ?>&order_by=<?= $order_by ?>&limit=<?= $perPage ?>&page=<?= $page + 1 ?>"><i class="fas fa-angle-right"></i></a>
            </li>
            <li class="page-item <?= $totalPages == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?sort_by=<?= $sort_by ?>&order_by=<?= $order_by ?>&limit=<?= $perPage ?>&page=<?= $totalPages ?>"><i class="fas fa-angle-double-right"></i></a>
            </li>
        </ul>
    </nav>
</div>
<div class="table-responsive" style="overflow-x: scroll; height: 80vh;">
    <table class="table table-striped table-sm">
        <thead>
            <tr class="d-flex">
                <th>
                    <input class="form-check-input" type="checkbox" value="" id="checkAll" onclick="check() //選取全部的checkbox " />
                </th>
                <th class="col-1 text-center">刪除</th>
                <th class="col-1 text-center">商品id</th>
                <th class="col-2 text-center">圖片</th>
                <th class="col-2">名稱</th>
                <th class="col-1">庫存</th>
                <th class="col-1">銷售量</th>
                <th class="col-4">介紹</th>
                <th class="col-1">狀態</th>
                <th class="col-1">規格id</th>
                <th class="col-2">上架時間</th>
                <th class="col-2">下架時間</th>
                <th class="col-1">價格</th>
                <th class="col-1">容量</th>
                <th class="col-1">產地</th>
                <th class="col-1">等級</th>
                <th class="col-1">品牌</th>
                <th class="col-1">精米步合</th>
                <th class="col-1">酒精度</th>
                <th class="col-1">酒造</th>
                <th class="col-2">使用米</th>
                <th class="col-2">口味描述</th>
                <th class="col-2">飲用溫度</th>
                <th class="col-1">禮盒</th>
                <th class="col-1">客製化</th>
                <th class="col-1">酒器</th>
                <th class="col-1">修改</th>
            </tr>
        </thead>
        <tbody>
            <!-- 讀入資料 -->
            <?php foreach ($rows as $r) :

                //抓規格表的酒器id
                $cid = $r['container_id'];
                //用酒器id去抓酒器名稱
                $container = "SELECT pc.`container_name` FROM `product_container` pc JOIN `product_format` pf ON pc.`container_id` = $cid";
                $ctr = $pdo->query($container)->fetch();

                //抓規格表的禮盒id
                $bid = $r['pro_gift'];
                //用禮盒id去抓禮盒名稱
                $pgift = "SELECT pg.`gift_name` FROM `product_gift` pg JOIN `product_format` pf ON pg.`pro_gift` = $bid";
                $gift = $pdo->query($pgift)->fetch();

            ?>
                <tr class="d-flex">
                    <td>
                        <input class="form-check-input check" type="checkbox" value="" />
                    </td>
                    <td class="col-1 text-center">
                        <a href="javascript: delete_it(<?= $r['pro_id'] ?>)"><i class="fas fa-trash"></i></a>
                    </td>
                    <td class="col-1 text-center" id="sid"> <?= $r['pro_id'] ?> </td>
                    <td class="col-2 text-center"><img class="pro_img" src="..\img\pro_img\<?= $r['pro_img'] ?>" alt=""></td>
                    <td class="col-2"><?= htmlentities($r['pro_name']) ?></td>
                    <td class="col-1"><?= $r['pro_stock'] ?></td>
                    <td class="col-1"><?= $r['pro_selling'] ?></td>
                    <td class="col-4"><?= htmlentities($r['pro_intro']) ?></td>
                    <td class="col-1"><?= $r['pro_condition'] ?></td>
                    <td class="col-1"><?= $r['format_id'] ?></td>
                    <td class="col-2"><?= $r['pro_creat_time'] ?></td>
                    <td class="col-2"><?= $r['pro_unsell_time'] ?></td>
                    <td class="col-1">NT$<?= $r['pro_price'] ?></td>
                    <td class="col-1"><?= $r['pro_capacity'] ?>ml</td>
                    <td class="col-1"><?= $r['pro_loca'] ?></td>
                    <td class="col-1"><?= htmlentities($r['pro_level']) ?></td>
                    <td class="col-1"><?= htmlentities($r['pro_brand']) ?></td>
                    <td class="col-1"><?= $r['pro_essence'] ?>%</td>
                    <td class="col-1"><?= $r['pro_alco'] ?>%</td>
                    <td class="col-1"><?= htmlentities($r['pro_marker']) ?></td>
                    <td class="col-2"><?= htmlentities($r['rice']) ?></td>
                    <td class="col-2"><?= htmlentities($r['pro_taste']) ?></td>
                    <td class="col-2"><?= htmlentities($r['pro_temp']) ?></td>
                    <td class="col-1"><?= $gift['gift_name'] ?></td>
                    <td class="col-1"><?= $r['pro_mark'] ? '可' : '否'; ?></td>
                    <td class="col-1"><?= $ctr['container_name'] ?></td>
                    <td class="col-1">
                        <a href="product-edit.php?pro_id=<?= $r['pro_id'] ?>"><i class="fas fa-pen"></i></a>
                    </td>
                </tr>
            <?php endforeach;  ?>

        </tbody>
    </table>
</div>

<!-- 光箱的部分 -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">確定要刪除嗎？</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="alertModal"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirmDel" data-bs-dismiss="modal">確認</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../parts/__main_end.html' ?>
<!-- 如果要 modal 的話留下面的結構 -->
<?php include __DIR__ . '/../parts/__modal.html' ?>

<?php include __DIR__ . '/../parts/__script.html' ?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
    const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
    //  modal.show() 讓 modal 跳出

    //刪除單筆、多筆資料
    function delete_it(sid) {

        let alertModal = document.querySelector('#alertModal');
        let confirmDel = document.querySelector('.confirmDel');
        alertModal.innerHTML = `確定要刪除編號為 ${sid} 的資料嗎?`

        if (alertModal.innerHTML) {
            modal.show();

            confirmDel.addEventListener('click', function() {
                location.href = `product-del-api.php?sid=${sid}`;
            })

        }
    }


    //全選功能
    function check() {

        let checkAll = document.querySelector('#checkAll');
        let check = document.querySelectorAll('.check');

        if (checkAll.checked == true) {

            for (let i = 0; i < check.length; i++) {
                check[i].checked = true;
            }
        } else {
            for (let i = 0; i < check.length; i++) {
                check[i].checked = false;
            }
        }
    }

    //取前臺顯示的商品id值
    let delAll = document.querySelector('#delAll');
    delAll.addEventListener('click', function() {
        let check = document.querySelectorAll('.check');
        let arr = [];
        let str;

        check.forEach(function(el) {
            if (el.checked) {
                str = el.parentElement.nextElementSibling; //選取父元素的隔壁
                str = str.nextElementSibling.innerHTML; //的隔壁元素
                arr.push(str);
            }
        })

        arr = arr.join(','); //陣列加入符號隔開轉為字串(sql看得懂的樣子)

        if (arr) {
            delete_it(arr)
        }
    })

    //商品排序
    let sort = document.querySelector('.sort');

    sort.addEventListener('change', function() {
        if (sort.value == 1) {
            //價格:由高到低
            location.href = `product.php?sort_by=pro_id&order_by=ASC`;
        }
        if (sort.value == 2) {
            //價格:由高到低
            location.href = `product.php?sort_by=pro_price&order_by=DESC`;
        }
        if (sort.value == 3) {
            //價格:由低到高
            location.href = `product.php?sort_by=pro_price&order_by=ASC`;
        }
        if (sort.value == 4) {
            //上架時間:由新到舊
            location.href = `product.php?sort_by=pro_creat_time&order_by=DESC`;
        }
        if (sort.value == 5) {
            //上架時間:由舊到新
            location.href = `product.php?sort_by=pro_creat_time&order_by=ASC`;
        }
    })

    //商品顯示數量

    let limit = document.querySelector('.limit');

    limit.addEventListener('change', function() {

        if (limit.value == 1) {
            //4個
            location.href = `product.php?limit=4`;
        }
        if (limit.value == 2) {
            //8個
            location.href = `product.php?limit=8`;
        }
        if (limit.value == 3) {
            //12個
            location.href = `product.php?limit=12`;
        }
        if (limit.value == 4) {
            //24個
            location.href = `product.php?limit=24`;
        }
        if (limit.value == 5) {
            //24個
            location.href = `product.php?limit=48`;
        }
    })
</script>
<?php include __DIR__ . '/../parts/__foot.html' ?>