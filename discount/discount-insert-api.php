<?php
require __DIR__ . '\..\parts\__connect_db.php';


$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];
$dID = $_POST['discountID'] ?? '';
$dCode = $_POST['discountCode'] ?? '';
$dInfo = $_POST['discountInfo'] ?? '';
$perscent = $_POST['perscent'] ?? '';
$dTs = $_POST['discountTstart'] ?? '';
$dTe = $_POST['discountTend'] ?? '';
$active = $_POST['active'] ?? '';
////TODO:檢查欄位資料
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
$sql = "INSERT INTO `discount`(
                     `discount_id`,
                     `discount_code`,
                     `discount_info`,
                     `perscent`,
                     `discount_time_start`,
                     `discount_time_end`,
                     `active`,
                     `create_at`,
                     `modified_at`
                          ) VALUES (?,?,?,?,?,?,?,now(),now())";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    $dID,
    $dCode,
    $dInfo,
    $perscent,
    $dTs,
    $dTe,
    $active,
]);

$output['success'] = $stmt->rowCount() == 1;
$output['rowCount'] = $stmt->rowCount();

echo json_encode($output, JSON_UNESCAPED_UNICODE);