<?php require __DIR__ . './../parts/__connect_db.php';
$title = "新增指南答案";
$pageName = "guide_answer_insert";

$sql_cate = "SELECT * FROM guide_cate ORDER BY guide_c_id";
$rows_cate = $pdo->query($sql_cate)->fetchAll();
$sql_q = "SELECT * FROM `guide_q` ORDER BY q_id";
$rows_q = $pdo->query($sql_q)->fetchAll();


?>
<?php include __DIR__ . './../parts/__head.php' ?>
<?php include __DIR__ . './../parts/__navbar.php' ?>
<?php include __DIR__ . './../parts/__sidebar.html' ?>

<?php include __DIR__ . './../parts/__main_start.html' ?>
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->
<div class="mt-5">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card">
                <h5 class="card-header py-3">新增指南答案選項</h5>
                <div class="card-body">
                    <form name="form_a" onsubmit="sendData(); return false;" method="POST">

                        <div class="form-group mb-3">
                            <label for="q_cate" class="mb-2">指南種類</label>
                            <select class="form-select" aria-label="Default select example" name="q_cate" id="q_cate">
                                <?php foreach ($rows_cate as $r_c) : ?>
                                    <option id="cate" value="<?= $r_c['q_cate'] ?>"><?= $r_c['q_cate_name'] ?></option>
                                <?php
                                // $value = $r_c['q_cate'];
                                endforeach; ?>
                            </select>
                            <div class="form-text"></div>
                        </div>
                        <?php foreach ($rows_q as $r_q) : ?>
                            <input type="hidden" name="q_id" class="value_id <?= $rows_q['q_id'] ?>" id="" value="<?= $rows_q['q_cate'] ?>">
                            <input type="hidden" name="q_des" class="value_des <?= $rows_q['q_id'] ?>" id="" value="<?= $rows_q['q_des'] ?>">
                        <?php endforeach; ?>
                        <div class="form-group mb-3">
                            <label for="q_id" class="mb-2">對應問題id</label>
                            <select class="form-control" aria-label="Default select example" id="q_id" name="q_id">
                                <?php foreach ($rows_q as $r_q) : ?>
                                    <option class="choice" id="" value="<?= $rows_q['q_id'] ?>" style="display: none;"></option>
                                <?php endforeach; ?>
                                <script>
                                    const cate = document.querySelector('#cate');
                                    const value_id = document.querySelector('.value_id');
                                    const value_des = document.querySelector('.value_des');
                                    const choice = document.querySelector('.choice')

                                    console.log(value_id);
                                    // function changeValue() {
                                    //     if (cate.value == value_id.value) {
                                    //         if (value_id.className == value_des.className && value_des.className == choice.id) {
                                    //             for (let i = 1; i <= choice.className; i++) {
                                    //                 choice[i].style.display = 'block';
                                    //                 choice[i].value = choice.id;
                                    //                 choice[i].innerHTML = value_des.value;
                                    //             }
                                    //         }
                                    //     }
                                    // }
                                </script>
                            </select>
                            <div class="form-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="a_item" class="mb-2">答案選項</label>
                            <input type="text" class="form-control" id="a_item" placeholder="清酒" name="a_item" />
                            <div class="form-text"></div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-secondary w-25">新增</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . './../parts/__main_end.html' ?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">資料新增</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">確認</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . './../parts/__script.html' ?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
    const qId = document.querySelector('#q_id');
    const aItem = document.querySelector('#a_item');

    const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
    //  modal.show() 讓 modal 跳出
    function sendData() {
        qId.nextElementSibling.innerHTML = '';
        aItem.nextElementSibling.innerHTML = '';
        let isPass = true;

        if (qId.value < 1) {
            isPass = false;
            qId.nextElementSibling.innerHTML = '請輸入正確的對應問題id';
        }
        if (aItem.value.length < 2) {
            isPass = false;
            aItem.nextElementSibling.innerHTML = '請輸入正確的答案選項';
        }

        if (isPass) {
            const fd = new FormData(form_a);

            fetch('guide_anwser_insert_api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        document.querySelector('.modal-body').innerHTML = "資料新增成功";
                        document.querySelector('.modal-footer').innerHTML = `<a href="guide_answer.php" class="btn btn-secondary">完成</a>`;
                        modal.show();
                    } else {
                        document.querySelector('.modal-body').innerHTML = obj.error || '資料新增發生錯誤';
                        modal.show();
                    }
                })
        }
    }
</script>
<?php include __DIR__ . './../parts/__foot.html' ?>