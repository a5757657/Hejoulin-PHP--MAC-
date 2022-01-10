<?php
require __DIR__ . '\..\parts\__connect_db.php';


$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];

$mID = $_POST['member'] ?? '';
$pic = $_POST['pics'] ?? '';
$markID = $_POST['mark_id'] ?? '';


//TODO:檢查欄位資料
//if (empty($name)) {
//    $output['code'] = 401;
//    $output['error'] = '請輸入正確的姓名';
//    echo json_encode($output, JSON_UNESCAPED_UNICODE);
//    exit;
//}
//if (empty($userID)) {
//    $output['code'] = 403;
//    $output['error'] = '請輸入正確的帳號';
//    echo json_encode($output, JSON_UNESCAPED_UNICODE);
//    exit;
//}
//if (empty($mobile) or !preg_match("/^09\d{2}-?\d{3}-?\d{3}$/", $mobile)) {
//    $output['code'] = 405;
//    $output['error'] = '請輸入正確的手機號碼';
//    echo json_encode($output, JSON_UNESCAPED_UNICODE);
//    exit;
//}

$sql = "UPDATE `mark` SET
                           `member_id`=?,
                               `pics`=?
                          
        WHERE `mark_id`=?";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    $mID,
    $pic,
    $markID,
]);

if($stmt->rowCount()==0){
    $output['error'] = '資料沒有修改';
}else{
    $output['success'] = true;
}
echo json_encode($output, JSON_UNESCAPED_UNICODE);