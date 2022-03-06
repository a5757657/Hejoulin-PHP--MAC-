<?php
require __DIR__ . '/../parts/__connect_db.php';


$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];
$uAccount = $_POST['account'] ?? '';
$uPass = $_POST['password'] ?? '';

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


$sql = "INSERT INTO `user`(
                           `user_account`,`user_pass`,`user_time`
                          ) VALUES (?,?,now())";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    $uAccount,
    $uPass,
]);

$output['success'] = $stmt->rowCount() == 1;
$output['rowCount'] = $stmt->rowCount();

echo json_encode($output, JSON_UNESCAPED_UNICODE);