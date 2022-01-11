<?php require __DIR__ . '\..\parts\__connect_db.php' ?>
<?php
$title = '購物車-清酒&酒標';
$pageName = 'cart_sake, mark';

$t_sql = "SELECT COUNT(1) FROM cart_sake";
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

$sql = sprintf("SELECT cs.*, cm.`cart_mark_id`, cm.`mark_id`, m.`member_name`, ps.`pro_name`, pf.`pro_mark` FROM `cart_sake` cs LEFT JOIN `cart_mark` cm ON cs.`cart_sake_id`= cm.`cart_sake_id` LEFT JOIN `member` m ON cs.`member_id` = m.`member_id` LEFT JOIN `product_sake` ps ON cs.`pro_id` = ps.`pro_id` LEFT JOIN `product_format` pf ON ps.`format_id` = pf.`format_id` ORDER BY cs.`cart_sake_id` ASC LIMIT %s, %s; ", ($currentPage - 1) * $perPage, $perPage);
$rows = $pdo->query($sql)->fetchAll();


//如果未登入管理員帳號，會直接跳轉至別的頁面
if (!$_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

?>
<?php include __DIR__ . '\..\parts\__head.php' ?>
<?php include __DIR__ . '\..\parts\__navbar.php' ?>
<?php include __DIR__ . '\..\parts\__sidebar.html' ?>

<?php include __DIR__ . '\..\parts\__main_start.html' ?>
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->
<!-- table -->
<div class="d-flex justify-content-between mt-5">
    <div>
        <button type="button" class="btn btn-secondary btn-sm" onclick="javascript: deleteMulti()">刪除選擇項目</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="location.href = 'cart_sake-add.php'">新增資料</button>
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
                <th>購物車清酒編號</th>
                <th>會員編號</th>
                <th>會員姓名</th>
                <th>商品編號</th>
                <th>商品名稱</th>
                <th>數量</th>
                <th>購物車酒標編號</th>
                <th>酒標編號</th>
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
                        <a href="javascript: delete_it('<?= $r['cart_sake_id'] ?>')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                    <td id="cart_sake_id"><?= $r['cart_sake_id'] ?></td>
                    <td><?= $r["member_id"] ?></td>
                    <td><?= $r["member_name"] ?></td>
                    <td><?= $r["pro_id"] ?></td>
                    <td><?= $r["pro_name"] ?></td>
                    <td><?= $r["cart_quantity"] ?></td>
                    <td><?= $r["cart_mark_id"] ?></td>
                    <td><?= $r["mark_id"] ?></td>
                    <td class="text-center">
                        <a href="cart_sake-edit.php?cart_sake_id=<?= $r["cart_sake_id"] ?>"><i class="fas fa-pen"></i></a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>



<?php include __DIR__ . '\..\parts\__main_end.html' ?>

<!-- 如果要 modal 的話留下面的結構 -->
<?php include __DIR__ . '\..\parts\__modal_ash.html' ?>

<?php include __DIR__ . '\..\parts\__script.html' ?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
    const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
    //  modal.show() 讓 modal 跳出

    //刪除功能，連到api
    function delete_it(cart_sake_id) {
        document.querySelector('#exampleModalLabel').innerHTML = '確定要刪除';
        document.querySelector('.modal-body').innerHTML = `確定要刪除 cart_sake_id = ${cart_sake_id}的這筆資料嗎?`;
        document.querySelector('#modal_btn').setAttribute("onclick", "return false;");
        modal.show();
        document.querySelector('#modal_btn').addEventListener('click', function() {
            location.href = `cart_sake-delete-api.php?cart_sake_id=${cart_sake_id}`
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
                id = s.closest('tr').querySelector('#cart_sake_id').innerHTML;
                checkedID.push(id);
            }
        });
        document.querySelector('#exampleModalLabel').innerHTML = `確定要刪除這幾筆資料嗎?`;
        document.querySelector('.modal-body').innerHTML = `確定要刪除 cart_sake_id = ${checkedID}的這筆資料嗎?`;
        document.querySelector('#modal_btn').setAttribute("onclick", "return false;");
        modal.show();
        document.querySelector('#modal_btn').addEventListener('click', function() {
            location.href = `cart_sake-delete-api.php?cart_sake_id=${checkedID}`;
        });
    }
</script>
<?php include __DIR__ . '\..\parts\__foot.html' ?>