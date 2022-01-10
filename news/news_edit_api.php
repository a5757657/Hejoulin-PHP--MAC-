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


$sid = isset($_POST['news_id']) ? intval($_POST['news_id']) : 0;

if(empty($sid)) {
    $output['code'] = 400;
    $output['error'] = '沒有 編號';
    echo json_encode($output, JSON_UNESCAPED_UNICODE); exit;
}

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$cover_pic = $_POST['cover_pic'] ?? '';
$pics = $_POST['pics'] ?? '';


// TODO: 檢查欄位資料
// if(empty($title)) {
//     $output['code'] = 401;
//     $output['error'] = '請輸入標題';
//     echo json_encode($output); exit;
// }
// if(empty($content)) {
//     $output['code'] = 405;
//     $output['error'] = '請輸入內容';
//     echo json_encode($output); exit;
// }
// if(empty($cover_pic)) {
//     $output['code'] = 407;
//     $output['error'] = '請上傳封面圖片';
//     echo json_encode($output); exit;
// }
// if(empty($pics)) {
//     $output['code'] = 409;
//     $output['error'] = '請上傳圖片';
//     echo json_encode($output); exit;
// }







$sql="UPDATE `news` SET
`title` = ?,
`content` = ?,
`cover_pic` = ?,
`pics` = ?,
`modified_at`= NOW()
WHERE `news`.`news_id`=?";



$stmt = $pdo->prepare($sql);
// $timenow = date_create();
// $timenow_d=date_format($timenow, 'Y-m-d H:i:s');

$stmt->execute([
    $title,
    $content,
    $cover_pic,
    $pics,
    $sid
]);

if($stmt->rowCount()>=1){
    $output['error'] = '資料沒有修改';
} else {
    $output['success'] = true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);













?>