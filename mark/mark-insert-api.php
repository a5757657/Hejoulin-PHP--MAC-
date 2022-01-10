<?php
require __DIR__ . '\..\parts\__connect_db.php';

$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];
$mID = $_POST['member'] ?? '';
$pic = $_POST['pics'] ?? '';
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


$sql = "INSERT INTO `mark`(
                           `member_id`,`pics`
                          ) VALUES (?,?)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $mID,
    $pic,
]);

$output['success'] = $stmt->rowCount() == 1;
$output['rowCount'] = $stmt->rowCount();

echo json_encode($output, JSON_UNESCAPED_UNICODE);