<?php
require __DIR__ . '/../parts/__connect_db.php';

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

$title = '折扣碼列表';
$pageName = 'discountList';

$perPage = 3;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sql = "SELECT COUNT(1) FROM `discount`";

//總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$totalPages = ceil($totalRows / $perPage);//幾頁

if ($page > $totalPages) {
    header('Location: discount_list.php?page=' . $totalPages);
    exit;
}

if ($page < 1) {
    header('Location: discount_list.php?page=' . '1');
    exit;
}

$sql = sprintf("SELECT * FROM `discount` LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

$rows = $pdo->query($sql)->fetchAll();
?>
<?php include __DIR__ . './../parts/__head.php' ?>
<?php include __DIR__ . './../parts/__navbar.php' ?>
<?php include __DIR__ . './../parts/__sidebar.html' ?>
<?php include __DIR__ . './../parts/__main_start.html' ?>

<div class="d-flex justify-content-between mt-5">
    <div class="btnbar">
        <button type="button" class="btn btn-secondary btn-sm" id="delAll">刪除選擇項目</button>
        <button type="button" class="btn btn-secondary btn-sm"><a href="discount_insert.php"
                style="text-decoration: none; color: white;">新增</a>
        </button>
    </div>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="?page=1">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>
            <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
            <?php for ($i = $page - 2; $i <= $page + 2; $i++)
                    if ($i >= 1 && $i <= $totalPages): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>">
                    <?= $i ?>
                </a>
            </li>
            <?php endif; ?>
            <li class="page-item<?= $totalPages == $page ? '' : 'disabled' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>">
                    <i class="fas fa-angle-right" style="color: gray"></i>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $totalPages ?>">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>
                    <input class="form-check-input" type="checkbox" id="allCk" onclick="cAll()" />
                </th>

                <th style="flex: 0 0 auto; text-align: center">
                    刪除
                </th>
                <th>折扣碼ID</th>
                <th style="text-align: center">折扣碼</th>
                <th style="text-align: center">折扣詳情</th>
                <th>折扣數值</th>
                <th style="text-align: center">折扣開始時間</th>
                <th style="text-align: center">折扣到期時間</th>
                <th>折扣是否開啟</th>
                <th style="text-align: center">折扣建立時間</th>
                <th style="text-align: center">折扣修改時間</th>
                <th>
                    編輯
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
            <tr>
                <td>
                    <input class="del" type="checkbox" name="check">
                </td>
                <td style="text-align: center">
                    <a href="javascript: delete_it(<?= $r['discount_id'] ?>)">
                        <i class="fas fa-trash-alt text-center"></i>
                    </a>
                </td>
                <td style="text-align: center"><?= $r['discount_id'] ?></td>
                <td><?= $r['discount_code'] ?></td>
                <td><?= $r['discount_info'] ?></td>
                <td style="text-align: center"><?= $r['perscent'] ?></td>
                <td><?= $r['discount_time_start'] ?></td>
                <td><?= $r['discount_time_end'] ?></td>
                <td style="text-align: center"><?= $r['active'] ?></td>
                <td><?= $r['create_at'] ?></td>
                <td><?= $r['modified_at'] ?></td>
                <td style="text-align: center">
                    <a href="discount_edit.php?discount_id=<?= $r['discount_id'] ?>">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">訊息</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">確認</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . './../parts/__main_end.html' ?>

<?php include __DIR__ . './../parts/__script.html' ?>
<script>
const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
const modalBody = document.querySelector('.modal-body');

function delete_it(discount_id) {
    modalBody.innerHTML = `確定要刪除編號為 ${discount_id} 的資料嗎？`;
    document.querySelector('.modal-footer').innerHTML =
        `<a href="discount-delete.php?discount_id=${discount_id}" class="btn btn-secondary">刪除</a>`;
    modal.show();
}

function cAll() {
    const checkAll = document.getElementById("allCk");
    const cks = document.getElementsByName("check");

    if (checkAll.checked == true) {
        for (let i = 0; i < cks.length; i++) {
            cks[i].checked = true;
        }
    } else {
        for (let i = 0; i < cks.length; i++) {
            cks[i].checked = false;
        }
    }
}

let delAll = document.querySelector('#delAll');
delAll.addEventListener('click', () => {
    let del = document.querySelectorAll('.del');
    let arr = [];
    let str;

    del.forEach((el) => {
        if (el.checked) {
            str = el.parentElement.nextElementSibling;
            str = str.nextElementSibling.innerHTML;
            arr.push(str);
        }
    })
    arr = arr.join(',');
    console.log(arr);
    if (arr) {
        delete_it(arr);
    }
})
</script>
<?php include __DIR__ . './../parts/__foot.html' ?>