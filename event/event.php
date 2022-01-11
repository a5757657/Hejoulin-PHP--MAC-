<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php

$title = '活動管理';

$perPage = 8;


$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

//總比數
$t_sql = "SELECT COUNT(1) FROM `event`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$totalPages = ceil($totalRows / $perPage);

//若page大於總頁數一律跳轉到最後一頁
if ($page > $totalPages) {
    header('Location: event.php?page=' . $totalPages);
    exit;
}

//若page小於總頁數一律跳轉到第一頁
if ($page < 1) {
    header('Location: event.php?page=' . '1');
    exit;
}

//判斷排序條件

//設定每一頁出現幾筆資料
$sql = sprintf("SELECT e.* , ec.* FROM `event` e JOIN `event_cat` ec ON e.`event_cat_id` = ec.`event_cat_id` LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

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

<div class="d-flex justify-content-end mt-5">

    <nav aria-label="Page navigation example">
        <ul class="pagination">

            <!-- 設定頁數的顯示 -->
            <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                <a class="page-link  " href="?page=<?= "1" ?>">
                    <i class="fas fa-angle-double-left"></i></a>
            </li>
            <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>"><i class="fas fa-angle-left"></i></a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <?php if ($i > ($page - 3) && $i < ($page + 3)) : ?>

                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endif ?>
            <?php endfor ?>
            <li class="page-item <?= $totalPages == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>"><i class="fas fa-angle-right"></i></a>
            </li>
            <li class="page-item <?= $totalPages == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $totalPages ?>"><i class="fas fa-angle-double-right"></i></a>
            </li>
        </ul>
    </nav>
</div>
<div class="table-responsive" style="overflow-x: scroll; height: 80vh;">
    <table class="table table-striped table-sm">
        <thead>
            <tr class="d-flex">
                <th class="col-1">編號</th>
                <th class="col-1">分類</th>
                <th class="col-2">名稱</th>
                <th class="col-2">開始時間</th>
                <th class="col-2">結束時間</th>
                <th class="col-4">簡述</th>
                <th class="col-2">地點</th>
                <th class="col-6">介紹</th>
                <th class="col-1">價錢</th>
                <th class="col-1">狀態</th>
                <th class="col-2">截止日期</th>
                <th class="col-2">新增時間</th>
                <th class="col-2">更新時間</th>
            </tr>
        </thead>
        <tbody>
            <!-- 讀入資料 -->
            <?php foreach ($rows as $r) : ?>
                <tr class="d-flex">
                    <td class="col-1" id="sid"><?= $r['event_id'] ?></td>
                    <td class="col-1" id="sid"><?= $r['event_cat_name'] ?></td>
                    <td class="col-2"><?= htmlentities($r['event_name']) ?></td>
                    <td class="col-2"><?= $r['event_time_start'] ?></td>
                    <td class="col-2"><?= $r['event_time_end'] ?></td>
                    <td class="col-4"><?= htmlentities($r['event_brief']) ?></td>
                    <td class="col-2"><?= htmlentities($r['event_location']) ?></td>
                    <td class="col-6"><?= htmlentities($r['event_intro']) ?></td>
                    <td class="col-1">$<?= $r['event_cost'] ?></td>
                    <td class="col-1"><?= $r['event_condition'] ?></td>
                    <td class="col-2"><?= $r['event_due'] ?></td>
                    <td class="col-2"><?= $r['event_create_date'] ?></td>
                    <td class="col-2"><?= $r['event_update_date'] ?></td>
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

    /* //刪除單筆、多筆資料
    function delete_it(sid) {

        let alertModal = document.querySelector('#alertModal');
        let confirmDel = document.querySelector('.confirmDel');
        alertModal.innerHTML = `確定要刪除編號為 ${sid} 的資料嗎?`

        if (alertModal.innerHTML) {
            modal.show();

            confirmDel.addEventListener('click', function() {
                location.href = `event-del-api.php?event_id=${sid}`;
            })

        }
    } */


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
    /* let delAll = document.querySelector('#delAll');

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
        console.log(arr);
        
        if (arr) {
            delete_it(arr)
        }

    }) */
</script>
<?php include __DIR__ . '/../parts/__foot.html' ?>