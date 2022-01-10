<?php require __DIR__. '\..\parts\__connect_db.php';
// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}


$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];

// 上傳圖片

$upload_folder = __DIR__ . '/../img/pro_img';

$exts = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/gif' => '.gif',
];



if(! empty($_FILES['myfile'])) {
    $ext = $exts[$_FILES['myfile']['type']] ?? '';  // 拿到對應的副檔名
    if(! empty( $ext )){

        $filename = sha1($_FILES['myfile']['name']. rand()). $ext;

        $target = $upload_folder. '/'. $filename;
        if( move_uploaded_file($_FILES['myfile']['tmp_name'], $target)){
            $output['success'] = true;
            $output['filename'] = $filename;
            // TODO: 可以將檔案寫入資料表
        } else {
            $output['error'] = '無法移動檔案';
        }

    } else {
        $output['error'] = '不合法的檔案類型';
    }


} else {
    $output['code'] = 410;
    $output['error'] = '沒有上傳檔案';
}



$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$cover_pic = $filename ?? '';
$pics = $filename ?? '';


// TODO: 檢查欄位資料
if(empty($title)) {
    $output['code'] = 401;
    $output['error'] = '請輸入標題';
    echo json_encode($output); exit;
}
if(empty($content)) {
    $output['code'] = 405;
    $output['error'] = '請輸入內容';
    echo json_encode($output); exit;
}
if(empty($cover_pic)) {
    $output['code'] = 407;
    $output['error'] = '請上傳封面圖片';
    echo json_encode($output); exit;
}
if(empty($pics)) {
    $output['code'] = 409;
    $output['error'] = '請上傳圖片';
    echo json_encode($output); exit;
}





$sql = "INSERT INTO `news`(
                            `title`, `content`, `cover_pic`, `pics`, `create_at`, `modified_at`
                            ) VALUES (?, ?, ?, ?, NOW(), NOW())";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $title,
    $content,
    $cover_pic,
    $pics,
]);


$output['success'] = $stmt->rowCount()==1;
$output['rowCount'] = $stmt->rowCount();


// echo json_encode($_POST);
echo json_encode($output);














?>