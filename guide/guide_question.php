<?php require __DIR__ . '.\..\parts\__connect_db.php';
$title = "選酒指南問題";
$pageName = "guide_question_list";

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}
?>

<?php

$perPage = 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: guide_question.php');
    exit;
}
$t_sql = "SELECT COUNT(1) FROM guide_q";
// 總比數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$totalPages = ceil($totalRows / $perPage);
if ($page > $totalPages) {
    header('Location: guide_question.php?page=' . $totalPages);
    exit;
}

$sql = sprintf("SELECT * FROM guide_q ORDER BY q_id LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
$rows = $pdo->query($sql)->fetchAll()

?>

<?php include __DIR__ . '.\..\parts\__head.php' ?>
<?php include __DIR__ . '.\..\parts\__navbar.php'?>
<?php include __DIR__ . '.\..\parts\__sidebar.html' ?>

<?php include __DIR__ . '.\..\parts\__main_start.html' ?>
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->

<div class="d-flex justify-content-between mt-5">
    <div>
        <button type="button" class="btn btn-secondary btn-sm" id="deleteAll" onclick="deleteAll()">刪除選擇項目</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='./guide_question_insert.php'">新增指南問題</button>
    </div>
    <!--這邊是頁數的 Btn  -->
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <!-- 跳到最前面的 Btn -->
            <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page == 1 ?>"><i class="fas fa-angle-double-left"></i></a>
            </li>
            <!-- 往前一頁的 Btn -->
            <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>"><i class="fas fa-angle-left"></i></a>
            </li>
            <?php for ($i = $page - 2; $i <= $page + 2; $i++)
                if ($i >= 1 && $i <= $totalPages) : ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
            <?php endif; ?>
            <!-- 往後一頁的 Btn -->
            <li class="page-item <?= $totalPages == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>""><i class=" fas fa-angle-right"></i></a>
            </li>
            <!-- 跳到最後面的 Btn -->
            <li class="page-item <?= $totalPages == $page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page = $totalPages ?>"><i class="fas fa-angle-double-right"></i></a>
            </li>
        </ul>
    </nav>
</div>
<!-- 這邊是 table的內容 -->
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">
                    <input class="form-check-input" type="checkbox" id="itemAll" value="" />
                </th>
                <th class="text-center">刪除</th>
                <th class="text-center">id</th>
                <th class="text-center">指南種類</th>
                <th class="text-center">序號</th>
                <th>問題</th>
                <th class="text-center">修改</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r) : ?>
                <tr>
                    <td class="text-center">
                        <input class="del" type="checkbox" value="<?= $r['q_id']?>" />
                    </td>
                    <td class="text-center">
                        <a href="javascript: delete_it(<?= $r['q_id']?>)"><i class="fas fa-trash"></i></a>
                    </td>
                    <td class="text-center"><?= $r['q_id'] ?></td>
                    <td class="text-center"><?= $r['q_cate'] ?></td>
                    <td class="text-center"><?= $r['q_seq'] ?></td>
                    <td><?= htmlentities($r['q_des']) ?></td>
                    <td class="text-center">
                        <a href="guide_question_edit.php?q_id=<?=$r['q_id'] ?>"><i class="fas fa-pen"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php include __DIR__ . '.\..\parts\__main_end.html' ?>

<!-- 如果要 modal 的話留下面的結構 -->
<?php include __DIR__ . '.\..\parts\__modal.html' ?>

<?php include __DIR__ . '.\..\parts\__script.html' ?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
    const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
    const modalBody = document.querySelector('.modal-body');
    //  modal.show() 讓 modal 跳出
</script>
<script>
       // 全選
       const itemAll = document.querySelector('#itemAll');
    const check = document.querySelectorAll('.del');

    itemAll.addEventListener("click", function() {
        if (itemAll.checked == true) {
            for (let i = 0; i < check.length; i++) {
                check[i].checked = true;
            }
        } else {
            for (let i = 0; i < check.length; i++) {
                check[i].checked = false;
            }
        }
    })

    // 刪除
    function delete_it(q_id) {
        modalBody.innerHTML = `確定要刪除編號為${q_id}的資料嗎?`;
        document.querySelector('.modal-footer').innerHTML = `<a href="guide_question_delete.php?q_id=${q_id}" class="btn btn-secondary">刪除</a>`;
        modal.show();
    }

    // 刪除多筆
    function deleteAll() {
        let checked = [];
        let qId = [];
        let newString = '';
        for (let i = 0; i < check.length; i++) {
            if (check[i].checked == true) {
                checked.push(check[i]);
            }
        }
        for (let i = 0; i < checked.length; i++) {
            qId.push(checked[i].value);
        }
        newString = qId.join(",")
        if (qId.length == 0) {
            modalBody.innerHTML = `目前尚未選取項目。`;
            document.querySelector('.modal-footer').innerHTML = `<button type="button" onclick="modal.hide()" class="btn btn-secondary">確認</button>`;
            modal.show();
        } else {
            delete_it(newString)
        }
    }

</script>
<?php include __DIR__ . '.\..\parts\__foot.html' ?>