<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php
$title = '訂閱方案';
$pageName = 'sub_plan';

$t_sql = "SELECT COUNT(1) FROM sub_plan";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

if ($currentPage < 1) {
    $currentPage = 1;
}
$perPage = 5;


$totalPages = ceil($totalRows / $perPage);

if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

$sql = sprintf("SELECT * FROM sub_plan ORDER BY sub_id ASC LIMIT %s, %s ", ($currentPage - 1) * $perPage, $perPage);
$rows = $pdo->query($sql)->fetchAll();


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
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->
<!-- table -->
<div class="d-flex justify-content-between mt-5">
    <div>
        <button type="button" class="btn btn-secondary btn-sm" onclick="javascript: deleteMulti()">刪除選擇項目</button>
        <button type="button" class="btn btn-secondary btn-sm"
            onclick="location.href = 'sub_plan-add.php'">新增資料</button>
    </div>
    <nav>
        <ul class="pagination">
            <li class="page-item <?= 1 == $currentPage ? 'disabled' : '' ?>">
                <a class="page-link  " href="?page=<?= "1" ?>">
                    <i class="fas fa-angle-double-left"></i></a>
            </li>
            <li class="page-item <?= 1 == $currentPage ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
            <?php for ($i = ($currentPage - 2); $i <= ($currentPage + 2); $i++)
                if ($i >= 1 && $i <= $totalPages) : ?>
            <li class="page-item <?= $i == $currentPage ? "active" : "" ?> ">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endif; ?>

            <li class="page-item <?= $currentPage == $totalPages ? "disabled" : "" ?>">
                <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Next">
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
            <li class="page-item <?= $totalPages == $currentPage ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $totalPages ?>"><i class="fas fa-angle-double-right"></i></a>
            </li>
        </ul>
    </nav>
</div>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>
                    <input class="form-check-input" type="checkbox" id="checkAll" />
                </th>
                <th class="text-center">
                    刪除
                </th>
                <th>方案編號</th>
                <th>方案名稱</th>
                <th>月配產品</th>
                <th>方案價格</th>
                <th class="text-center">
                    編輯
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r) : ?>
            <tr>
                <td><input type="checkbox" name="" id="checkSingle"></td>
                <td class="text-center">
                    <a href="javascript: delete_it(<?= $r['sub_id'] ?>)">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
                <td id="sub_id"><?= $r['sub_id'] ?></td>
                <td><?= $r["sub_plan"] ?></td>
                <td><?= $r["sub_products"] ?></td>
                <td><?= $r["sub_price"] ?></td>
                <td class="text-center">
                    <a href="sub_plan-edit.php?sub_id=<?= $r["sub_id"] ?>"><i class="fas fa-pen"></i></a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>


<?php include __DIR__ . '/../parts/__main_end.html' ?>
<!-- 如果要 modal 的話留下面的結構 -->
<?php include __DIR__ . '/../parts/__modal_ash.html' ?>

<?php include __DIR__ . '/../parts/__script.html' ?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
//  modal.show() 讓 modal 跳出

//刪除功能，連到api
function delete_it(sub_id) {
    document.querySelector('#exampleModalLabel').innerHTML = '確定要刪除';
    document.querySelector('.modal-body').innerHTML = `確定要刪除 sub_id = ${sub_id}的這筆資料嗎?`;
    document.querySelector('#modal_btn').setAttribute("onclick", "return false;");
    modal.show();
    document.querySelector('#modal_btn').addEventListener('click', function() {
        location.href = `sub_plan-delete-api.php?sub_id=${sub_id}`
    })
}

//全選checkbox
let checkAll = document.querySelector('#checkAll');
let checkSingle = document.querySelectorAll('#checkSingle');
checkAll.addEventListener('click', function() {
    if (event.target.checked == true) {
        checkSingle.forEach(s => {
            s.checked = true
        });
    } else {
        checkSingle.forEach(s => {
            s.checked = false
        });
    }
});
checkSingle = document.querySelectorAll('#checkSingle');

function deleteMulti() {
    checkedID = [];
    checkSingle.forEach(s => {
        if (s.checked == true) {
            id = s.closest('tr').querySelector('#sub_id').innerHTML;
            checkedID.push(id);
        }
    });
    document.querySelector('#exampleModalLabel').innerHTML = `確定要刪除這幾筆資料嗎?`;
    document.querySelector('.modal-body').innerHTML = `確定要刪除 sub_id = ${checkedID}的這筆資料嗎?`;
    document.querySelector('#modal_btn').setAttribute("onclick", "return false;");
    modal.show();
    document.querySelector('#modal_btn').addEventListener('click', function() {
        location.href = `sub_plan-delete-api.php?sub_id=${checkedID}`;
    });
}
</script>
<?php include __DIR__ . '/../parts/__foot.html' ?>