<?php
require __DIR__ . '\..\parts\__connect_db.php';


$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];
$dID = isset($_POST['discountID']) ? intval($_POST['discountID']) : 0;
if(empty($dID)){
    $output['code'] = 400;
    $output['error'] = '沒有discount_id';
    echo json_encode($output, JSON_UNESCAPED_UNICODE); exit;
}
$dCode = $_POST['discountCode'] ?? '';
$perscent = $_POST['perscent'] ?? '';
$dInfo = $_POST['discountInfo'] ?? '';
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
$sql = "UPDATE `discount` SET
                     `discount_code`=?,
                     `perscent`=?,
                     `discount_info`=?,
                     `discount_time_start`=?,
                     `discount_time_end`=?,
                     `active`=?
                          WHERE discount_id=?";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    $dCode,
    $perscent,
    $dInfo,
    $dTs,
    $dTe,
    $active,
    $dID,
]);

if($stmt->rowCount()==0){
    $output['error'] = '資料沒有修改';
}else{
    $output['success'] = true;
}
echo json_encode($output, JSON_UNESCAPED_UNICODE);