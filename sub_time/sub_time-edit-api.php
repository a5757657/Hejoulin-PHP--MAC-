<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}
$output = [
    'success' => false,
    'code' => 0,
    'error' => ''
];
$subtime_id = isset($_POST['subtime_id']) ? intval($_POST['subtime_id']) : 0;
if (empty($subtime_id)) {
    $output['code'] = 400;
    $output['error'] = "這筆資料沒有subtime_id，subtime_id = $subtime_id ";
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sub_time = $_POST["sub_time"] ?? '';
$sub_time_month = $_POST["sub_time_month"] ?? '';
$sub_discount = $_POST["sub_discount"] ?? '';

if (empty($sub_time)) {
    $output['code'] = 401;
    $output['error'] = '請輸入完整的週期名稱';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($sub_time_month)) {
    $output['code'] = 403;
    $output['error'] = '請輸入完整的週期月數';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($sub_discount)) {
    $output['code'] = 405;
    $output['error'] = '請輸入完整的週期折扣';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "UPDATE `sub_time` SET `sub_time`=?,`sub_time_month`=?,`sub_discount`=?,`modified_at`= NOW() WHERE subtime_id =?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $sub_time,
    $sub_time_month,
    $sub_discount,
    $subtime_id
]);
if ($stmt->rowCount() == 0) {
    $output['error'] = "資料沒有修改";
} else {
    $output['success'] = true;
}


echo json_encode($output);