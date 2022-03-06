<?php
require __DIR__ . '/../parts/__connect_db.php';


$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];

$userID = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
if(empty($userID)){
    $output['code'] = 400;
    $output['error'] = '沒有user_id';
    echo json_encode($output, JSON_UNESCAPED_UNICODE); exit;
}

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

$sql = "UPDATE `user` SET
                          `user_account` =?,
                          `user_pass` = ?
                          
        WHERE `user_id`=?";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    $uAccount,
    $uPass,
    $userID
]);

if($stmt->rowCount()==0){
    $output['error'] = '資料沒有修改';
}else{
    $output['success'] = true;
}
echo json_encode($output, JSON_UNESCAPED_UNICODE);