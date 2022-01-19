<?php require __DIR__ . '/../parts/__connect_db.php' ?>

<?php

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

$title = '會員收藏';

//每一頁出現幾筆資料
$perPage = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

//總比數
$t_sql = "SELECT COUNT(1) FROM `member`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$totalPages = ceil($totalRows / $perPage);

//若page大於總頁數一律跳轉到最後一頁
if ($page > $totalPages) {
    header('Location: favorite-member.php?page=' . $totalPages);
    exit;
}

//若page小於總頁數一律跳轉到第一頁
if ($page < 1) {
    header('Location: favorite-member.php?page=' . '1');
    exit;
}

$sql = sprintf("SELECT m.* , u.* FROM `member` m JOIN `user` u ON m.`user_id` = u.`user_id` LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

$rows = $pdo->query($sql)->fetchAll()



?>

<?php include __DIR__ . '/../parts/__head.php' ?>
<?php include __DIR__ . '/../parts/__navbar.php' ?>
<?php include __DIR__ . '/../parts/__sidebar.html' ?>
<?php include __DIR__ . '/../parts/__main_start.html' ?>

<div class="d-flex justify-content-end mt-5">
    <nav aria-label="Page navigation example">
        <ul class="pagination">

            <!-- 設定頁數的顯示 -->
            <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= "1" ?>"><i class="fas fa-angle-double-left"></i></a>
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
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr class='d-flex'>
                <th class='col-2'>會員編號</th>
                <th class='col-3'>會員名稱</th>
                <th class='col-3'>會員帳號</th>
                <th class='col-3'>商品數量</th>
                <th class='col-1'></th>
            </tr>
        </thead>
        <tbody>
            <!-- 計算出此會員收藏商品的數量 -->
            <?php foreach ($rows as $r) :
                $m_id = $r['member_id'];
                $ct = "SELECT COUNT(1) FROM `favorite` f WHERE f.`member_id` = $m_id;";
                $totalCount = $pdo->query($ct)->fetch(PDO::FETCH_NUM)[0];
            ?>

                <tr class='d-flex'>
                    <td class='col-2'><?= $r['member_id'] ?></td>
                    <td class='col-3'><?= $r['member_name'] ?></td>
                    <td class='col-3'><?= $r['user_account'] ?></td>
                    <td class='col-3'><?= $totalCount ?></td>
                    <td class='col-auto'><a href="favorite-list.php?member_id=<?= $r['member_id'] ?>&member_name=<?= $r['member_name'] ?>"><button type="button" class="btn btn-secondary btn-sm">收藏商品</button></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

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
</script>
<?php include __DIR__ . '/../parts/__foot.html' ?>