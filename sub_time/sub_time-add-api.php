<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php
if (!$_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}
$output = [
    'success' => false,
    'code' => 0,
    'error' => ''
];

$sub_time = $_POST["sub_time"] ?? '';
$sub_discount = $_POST["sub_discount"] ?? '';
$sub_time_month = $_POST["sub_time_month"] ?? '';

if (empty($sub_time)) {
    $output['code'] = 401;
    $output['error'] = '請輸入完整的週期名稱';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($sub_discount)) {
    $output['code'] = 403;
    $output['error'] = '請輸入完整的週期月數';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($sub_time_month)) {
    $output['code'] = 405;
    $output['error'] = '請輸入完整的週期折扣';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "INSERT INTO `sub_time`(`sub_time`, `sub_discount`, `sub_time_month`) VALUES (?,?,?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $sub_time,
    $sub_discount,
    $sub_time_month,
]);
$output['success'] = $stmt->rowCount() == 1;
$output['rowcount'] = $stmt->rowCount();

echo json_encode($output);
