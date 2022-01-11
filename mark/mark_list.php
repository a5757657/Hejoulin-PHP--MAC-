<?php
require __DIR__ . '\..\parts\__connect_db.php';


// 如果未登入管理帳號就轉向
if (!$_SESSION['admin']) {
    header("Location: " . "./../login/login.php");
    exit;
}

$title = '酒標列表';
$pageName = 'markList';

$perPage = 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sql = "SELECT COUNT(1) FROM `mark`";

//總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$totalPages = ceil($totalRows / $perPage);//幾頁

if ($page > $totalPages) {
    header('Location: mark_list.php?page=' . $totalPages);
    exit;
}

if ($page < 1) {
    header('Location: mark_list.php?page=' . '1');
    exit;
}

$sql = sprintf("SELECT * FROM `mark` LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

$rows = $pdo->query($sql)->fetchAll();
?>
<?php include __DIR__ . '\..\parts\__head.php' ?>
<?php include __DIR__ . '\..\parts\__navbar.php' ?>
<?php include __DIR__ . '\..\parts\__sidebar.html' ?>
<?php include __DIR__ . '\..\parts\__main_start.html' ?>

    <div class="d-flex justify-content-between mt-5">
        <div class="btnbar">
            <button type="button" class="btn btn-secondary btn-sm" id="delAll">刪除選擇項目</button>
            <button type="button" class="btn btn-secondary btn-sm"><a href="mark_insert.php"
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
                    <input class="form-check-input" type="checkbox" value="" id="allCk" onclick="cAll()"/>
                </th>

                <th scope="col">刪除</th>
                <th>酒標ID</th>
                <th>會員ID</th>
                <th>pics</th>
                <th>建立時間</th>
                <th>
                    編輯
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows

                           as $r): ?>
                <tr>
                    <td>
                        <input class="del" type="checkbox" name="check">
                    </td>
                    <td>
                        <a href="javascript: delete_it(<?= $r['mark_id'] ?>)">
                            <i class="fas fa-trash-alt text-center"></i>
                        </a>
                    </td>
                    <td><?= $r['mark_id'] ?></td>
                    <td><?= $r['member_id'] ?></td>
                    <td><?= $r['pics'] ?></td>
                    <td><?= $r['create_at'] ?></td>
                    <td>
                        <a href="mark_edit.php?mark_id=<?= $r['mark_id'] ?>">
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
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">確認</button>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '\..\parts\__main_end.html' ?>
<?php include __DIR__ . '\..\parts\__script.html' ?>
    <!-- 如果要 modal 的話留下面的 script -->
    <script>
        const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
        const modalBody = document.querySelector('.modal-body');

        function delete_it(mark_id) {
            modalBody.innerHTML = `確定要刪除編號為 ${mark_id} 的資料嗎？`;
            document.querySelector('.modal-footer').innerHTML = `<a href="mark-delete.php?mark_id=${mark_id}" class="btn btn-secondary">刪除</a>`;
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
<?php include __DIR__ . '\..\parts\__foot.html' ?>