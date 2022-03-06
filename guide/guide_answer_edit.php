 <?php require __DIR__ . './../parts/__connect_db.php';
$title = "修改指南答案";
$pageName = "guide_answer_edit";

if(! isset($_GET['a_no'])){
    header("Location: guide_answer.php");
    exit;
}
$a_no = intval($_GET['a_no']);


$sqlall = "SELECT * FROM `guide_q`";
$rowall= $pdo->query($sqlall)->fetchAll();

$sql = "SELECT * FROM `guide_a` WHERE a_no=$a_no";
$row= $pdo->query($sql)->fetchAll();
if(empty($row)){
    header('Location: guide_answer.php');
    exit;
}
?>
 <?php include __DIR__ . './../parts/__head.php' ?>
 <?php include __DIR__ . './../parts/__navbar.php'?>
 <?php include __DIR__ . './../parts/__sidebar.html' ?>

 <?php include __DIR__ . './../parts/__main_start.html' ?>
 <!-- 主要的內容放在 __main_start 與 __main_end 之間 -->
 <div class="mt-5">
     <div class="row justify-content-center">
         <div class="col-8">
             <div class="card">
                 <h5 class="card-header py-3">修改指南答案</h5>
                 <div class="card-body">
                     <form name="form_a" onsubmit="sendData(); return false;" method="POST">
                         <div class="form-group mb-3">
                             <input type="hidden" name="a_no" value="" id="a_no">
                             <label for="q_id" class="mb-2">對應問題</label>
                             <select class="form-control" aria-label="Default select example" id="q_id" name="q_id">
                             </select>
                             <div class="form-text"></div>
                         </div>

                         <div class="form-group mb-3">
                             <label for="a_item" class="mb-2">答案選項</label>
                             <input type="text" class="form-control" id="a_item" name="a_item" value="123" />
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
 <?php include __DIR__ . './../parts/__main_end.html' ?>

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

 <?php include __DIR__ . './../parts/__script.html' ?>
 <!-- 如果要 modal 的話留下面的 script -->
 <script>
const qId = document.querySelector('#q_id');
const aItem = document.querySelector('#a_item');
const a_no = document.querySelector('#a_no');

const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
//  modal.show() 讓 modal 跳出
// let q_id_c = document.querySelector('#q_id').childNodes;
// q_id_c.forEach(el =>{
// if(el.value == ""){
//         el.setAttribute('selected','selected');
//     }
// });

const qallData = <?= json_encode($rowall) ?>;
const qData = <?= json_encode($row) ?>;

qallData.forEach(item => {
    qId.innerHTML += `<option value="${item.q_des}">${item.q_des}</option>`;
})


qallData.forEach(item => {
    if (qData[0].q_id == item.q_id) {
        qId.value = item.q_des
    }
})

a_item.value = qData[0].a_item;

a_no.value = qData[0].a_no;

function sendData() {
    qId.nextElementSibling.innerHTML = '';
    aItem.nextElementSibling.innerHTML = '';
    let isPass = true;

    if (aItem.value.length < 2) {
        isPass = false;
        aItem.nextElementSibling.innerHTML = '請輸入正確的答案選項';
    }

    if (isPass) {
        const fd = new FormData(form_a);

        fetch('guide_anwser_edit_api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    document.querySelector('.modal-body').innerHTML = "資料修改成功";
                    document.querySelector('.modal-footer').innerHTML =
                        `<a href="guide_answer.php" class="btn btn-secondary">完成</a>`;
                    modal.show();
                } else {
                    document.querySelector('.modal-body').innerHTML = obj.error || '資料修改發生錯誤';
                    modal.show();
                }
            })
    }
}
 </script>
 <?php include __DIR__ . './../parts/__foot.html' ?>