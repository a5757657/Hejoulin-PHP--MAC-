<?php require __DIR__. '\..\parts\__connect_db.php';
$title = "修改最新消息";

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

// 如果沒有資料就反回
if(! isset($_GET['sid'])) {
    header('Location: news.php');
    exit;
}

$sid = intval($_GET['sid']);
$row = $pdo->query("SELECT * FROM `news` WHERE `news`.`news_id`=$sid")->fetch();
if(empty($row)){
    header('Location: news.php');
    exit;
}




// if(isset($_GET['sid'])){
//     $sid = intval($_GET['sid']);
//     $pdo->query("DELETE FROM `news` WHERE `news`.`news_id`=$sid");

    
// }

// UPDATE `news` SET `title` = '測試40' WHERE `news`.`news_id` = 17;





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
    <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='news.php'">返回</button>
    <div></div>    
</div>


<!-- 表單 -->
<!-- `news_id`, `title`, `content`, `cover_pic`, `pics`, `create_at`, `modified_at` -->
<div class="row justify-content-center mt-3">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header py-3 ">修改</h5>
                <div class="card-body d-flex justify-content-center">
                    <!-- form在這 -->
                    <form class="col-6 " name="add_form" onsubmit="sendData(); return false;" > 
                        <input type="hidden" name="news_id" value="<?= $row['news_id'] ?>">
                        <div class="form-group mb-3 ">
                            <label for="title" class="mb-2">標題</label>
                            <textarea
                                type="text"
                                class="form-control"
                                id="title"
                                name="title"
                                placeholder="請輸入最新消息標題"
                            ><?= htmlentities($row['title']) ?></textarea>
                            <div class="text-area"></div>
                        </div>
                        <div class="form-group mb-3 ">
                            <label for="content" class="mb-2">內容</label>
                            <textarea
                                type="text"
                                class="form-control"
                                id="content"
                                name="content"
                            ><?= htmlentities($row['content']) ?></textarea>
                            <div class="text-area"></div>
                        </div>                        
                        <div class="form-group mb-3 " style="display: none" >
                            <label for="cover_pic" class="mb-2">封面圖片</label>
                            <input
                                type="text"
                                class="form-control"
                                id="cover_pic"
                                name="cover_pic"
                                placeholder="請上傳封面圖片"
                                value="<?= htmlentities($row['cover_pic']) ?>"
                            />
                            <div class="text-area"></div>
                        </div>
                        <div class="form-group mb-3 " style="display: none" >
                            <label for="pics" class="mb-2">圖片</label>
                            <input
                                type="text"
                                class="form-control"
                                id="pics"
                                name="pics"
                                placeholder="請上傳圖片"
                                value="<?= htmlentities($row['pics']) ?>"
                            />
                            <div class="text-area"></div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-secondary w-20">修改</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- 如果要 modal 的話留下面的結構 -->
<?php include __DIR__ . '\..\parts\__modal.html'?>
<?php include __DIR__ . '\..\parts\__script.html'?>
<script>

// 如果要 光箱 modal 的話留下面的 script
    const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
    const modalBody = document.querySelector('.modal-body');

    const title = document.querySelector('#title');
    const content = document.querySelector('#content');
    const cover_pic = document.querySelector('#cover_pic');
    const pics = document.querySelector('#pics');

    function sendData(){
        // =======以下確認可以傳值_只是老師示範的===========
        // const fd = new FormData(document.add_form);
        // fetch('news_add_api.php',{
        //     method: 'POST',
        //     body: fd,
        // }).then(r=>r.json())
        // .then(obj=>{
        //     console.log(obj);
        // })

        

        let isPass = true;
        // =========檢查表單資料========

        if(title.value.length == 0){
            isPass = false;
            title.nextElementSibling.innerHTML = `
            <div class="alert alert-dark mt-2" role="alert">請輸入內容</div>
            `;
        }

        if(content.value.length == 0){
            isPass = false;
            content.nextElementSibling.innerHTML = `
            <div class="alert alert-dark mt-2" role="alert">請輸入內容</div>
            `;
        }

        if(cover_pic.value.length == 0){
            isPass = false;
            cover_pic.nextElementSibling.innerHTML = `
            <div class="alert alert-dark mt-2" role="alert">請上傳封面圖片</div>
            `;
        }

        if(pics.value.length == 0){
            isPass = false;
            pics.nextElementSibling.innerHTML = `
            <div class="alert alert-dark mt-2" role="alert">請上傳圖片</div>
            `;
        }

        console.log(isPass);


        if(isPass) {
            const fd = new FormData(document.add_form);

            fetch('news_edit_api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
                .then(obj => {
                    console.log(obj);
                     if(obj.success) {
			    	document.querySelector('.modal-body').innerHTML = "資料修改成功";
                    document.querySelector('.modal-footer').innerHTML = `<a href="news.php" class="btn btn-secondary">完成</a>`;
                    modal.show();
                } else {
                    document.querySelector('.modal-body').innerHTML = obj.error || "資料未修改";
                    modal.show();
                }
                })
               
                

        }






    }
</script>
<?php include __DIR__ . '\..\parts\__foot.html'?>