<?php require __DIR__ . '.\..\parts\__connect_db.php';
$title = '修改指南問題';
$pageName = 'guide_question_edit';

if (!isset($_GET['q_id'])) {
    header("Location: guide_question.php");
    exit;
}

$q_id = intval($_GET['q_id']);
$row = $pdo->query("SELECT * FROM `guide_q` WHERE q_id=$q_id")->fetch();
if (empty($row)) {
    header('Location: guide_question.php');
    exit;
}
?>
<?php include __DIR__ . '.\..\parts\__head.php' ?>
<?php include __DIR__ . '.\..\parts\__navbar.php'?>
<?php include __DIR__ . '.\..\parts\__sidebar.html' ?>

<?php include __DIR__ . '.\..\parts\__main_start.html' ?>

<div class="mt-5">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card">
                <h5 class="card-header py-3">修改指南問題</h5>
                <div class="card-body">
                    <form name="form_q" onsubmit="sendData(); return false;">
                        <input type="hidden" name="q_id" value="<?= $row['q_id'] ?>">
                        <div class="form-group mb-3">
                            <label for="q_cate" class="mb-2">指南種類</label>
                            <input type="text" class="form-control" id="q_cate" name="q_cate" value="<?= $row['q_cate'] ?>" />
                            <div class="form-text"></div>
                            <!-- <div class="alert alert-dark mt-2" role="alert"></div> -->
                        </div>
                        <div class="form-group mb-3">
                            <label for="q_seq" class="mb-2">問題序號</label>
                            <input type="number" class="form-control" id="q_seq" name="q_seq" value="<?= $row['q_seq'] ?>" />
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="q_des" class="mb-2">問題</label>
                            <textarea name="q_des" id="q_des" class="form-control" cols="30" rows="2"><?= $row['q_des'] ?></textarea>
                            <div class="form-text"></div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-secondary w-25">修改</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '.\..\parts\__main_end.html' ?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">資料修改</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">確認</button>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '.\..\parts\__script.html' ?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
    const cate = document.querySelector('#q_cate');
    const seq = document.querySelector('#q_seq');
    const des = document.querySelector('#q_des');

    const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
    //  modal.show() 讓 modal 跳出
    function sendData() {
        cate.nextElementSibling.innerHTML = '';
        seq.nextElementSibling.innerHTML = '';
        des.nextElementSibling.innerHTML = '';
        let isPass = true;

        if (cate.value.length >= 2) {
            isPass = false;
            cate.nextElementSibling.innerHTML = '請輸入正確指南種類';
        }
        if (seq.value < 1) {
            isPass = false;
            seq.nextElementSibling.innerHTML = '請輸入正確問題序號';
        }
        if (des.value.length < 5) {
            isPass = false;
            des.nextElementSibling.innerHTML = '請輸入正確的指南問題';
        }

        if (isPass) {
            const fd = new FormData(form_q);

            fetch('guide_question_edit_api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        document.querySelector('.modal-body').innerHTML = "資料修改成功";
                        document.querySelector('.modal-footer').innerHTML = `<a href="guide_question.php" class="btn btn-secondary">完成</a>`;
                        modal.show();
                    } else {
                        document.querySelector('.modal-body').innerHTML = obj.error || '資料修改發生錯誤';
                        modal.show();
                    }
                })
        }
    }
</script>
<?php include __DIR__ . '.\..\parts\__foot.html' ?>