<?php require __DIR__. '\..\parts\__connect_db.php';
$title = "最新消息";
$pageName = "news_page";

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

// //每頁有幾筆
// $perPage = 2;

// // 算總筆數SQL語法
// $t_sql = "SELECT COUNT(1) FROM address_book";

// // 總筆數
// $totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

// // 總頁數＝總筆數/頁數 ceil() 函數向上捨入為最接近的整數。
// $totalPages = ceil($totalRows/$perPage);


$sql = "SELECT * FROM news";
$rows = $pdo->query($sql)->fetchAll();
?>
<?php include __DIR__ . '\..\parts\__head.php'?>
<?php include __DIR__ . '\..\parts\__navbar.php'?>
<?php include __DIR__ . '\..\parts\__sidebar.html'?>

<?php include __DIR__ . '\..\parts\__main_start.html'?>
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->

<!-- table -->
<div class="d-flex justify-content-between mt-5">
    <div>
    <button type="button" onclick="delete_many()" class="btn btn-secondary btn-sm">刪除所選消息</button>
    <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='news_add.php'">新增消息</button>
    </div>
    <!-- 分頁數字鍵頭箭頭  先隱藏-->
    <nav aria-label="Page navigation example" style="display: none">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="#"><i class="fas fa-angle-double-left"></i></a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#"><i class="fas fa-angle-left"></i></a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#"><i class="fas fa-angle-right"></i></a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#"><i class="fas fa-angle-double-right"></i></a>
            </li>
        </ul>
    </nav>
</div>
<!-- 表格 -->
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>
                    <input class="form-check-input" type="checkbox" value="" id="ckall"/>
                </th>
                <th class="text-center" width="50">刪除</th>
                <th width="50">編號</th>
                <th width="100">標題</th>
                <th >內容</th>
                <th >封面圖</th>
                <!-- <th >圖片</th> -->
                <th width="100">創建日期</th>
                <th width="100">修改日期</th>
                <th class="text-center" width="50" >編輯</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $r): ?>
            <tr>
                <td >
                    <input class="form-check-input ck" type="checkbox" value="<?= $r['news_id'] ?>" />
                </td>
                <td class="text-center">
                    <a href="javascript: delete_it(<?= $r['news_id'] ?>)"><i class="fas fa-trash"></i></a>
                </td>
                <td><?= $r['news_id'] ?></td>
                <td><?= $r['title'] ?></td>
                <td><?= $r['content'] ?></td>
                <td><img src="..\img\pro_img\<?= $r['cover_pic'] ?>" width="100%"></td>
                <!-- <td><img src="..\img\pro_img\<?= $r['pics'] ?>" width="100%"></td> -->
                <td><?= $r['create_at'] ?></td>
                <td><?= $r['modified_at'] ?></td>
                <td class="text-center">
                    <a href="news_edit.php?sid=<?= $r['news_id'] ?>"><i class="fas fa-pen"></i></a>
                </td>
            </tr>
            <?php endforeach;  ?>
        </tbody>
    </table>
</div>
<!-- 如果要 modal 的話留下面的結構 -->
<?php include __DIR__ . '\..\parts\__modal.html'?>
<?php include __DIR__ . '\..\parts\__script.html'?>
<script>

// 如果要 光箱 modal 的話留下面的 script
    const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
    const modalBody = document.querySelector('.modal-body');


// 多選設定
const ckall = document.querySelector("#ckall");
const ck = document.querySelectorAll(".ck");
let ck_b = false;

ckall.addEventListener("click", function(e){
    if (ck_b == false){
        ck.forEach(item=>{
            item.checked = true;
        })
        ck_b = true;
    }else{
        ck.forEach(item=>{
            item.checked = false;
        })
        ck_b = false;
    }
})


// 刪除單筆資料
function delete_it(sid){
        modalBody.innerHTML = `確定要刪除編號為 ${sid} 的資料嗎?`;
        document.querySelector('.modal-footer').innerHTML = `<a href="news_delete_api.php?sid=${sid}" class="btn btn-secondary">刪除</a>`;
        modal.show();
    }


// 刪除多筆資料
function delete_many(){
        let ck_s = [];
        let checked = [];
 
        for (let i = 0; i<ck.length; i++ ) {
            if(ck[i].checked == true) {
                checked.push(ck[i].value)
            }
        }

        let str = '';
        str = checked.join(",");

        // if(confirm(`確定要刪除編號為 ${str} 的資料嗎?`)){
        //     location.href = `news_delete_api.php?sid=${str}`;
        // }

        modalBody.innerHTML = `確定要刪除編號為 ${str} 的資料嗎?`;
        document.querySelector('.modal-footer').innerHTML = `<a href="news_delete_api.php?sid=${str}" class="btn btn-secondary">刪除</a>`;
        modal.show();
}





</script>





<?php include __DIR__ . '\..\parts\__foot.html'?>