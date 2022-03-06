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
$sub_id = isset($_POST['sub_id']) ? intval($_POST['sub_id']) : 0;
if (empty($sub_id)) {
    $output['code'] = 400;
    $output['error'] = "這筆資料沒有sub_id，sub_id = $sub_id ";
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sub_plan = $_POST["sub_plan"] ?? '';
$sub_products = $_POST["sub_products"] ?? '';
$sub_price = $_POST["sub_price"] ?? '';

if (empty($sub_plan)) {
    $output['code'] = 401;
    $output['error'] = '請輸入完整的方案名稱';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($sub_products)) {
    $output['code'] = 403;
    $output['error'] = '請輸入完整的產品名稱';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($sub_price)) {
    $output['code'] = 405;
    $output['error'] = '請輸入完整的方案價格';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "UPDATE `sub_plan` SET `sub_plan`=?,`sub_products`=?,`sub_price`=?,`modified_at`= NOW() WHERE sub_id =?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $sub_plan,
    $sub_products,
    $sub_price,
    $sub_id
]);
if ($stmt->rowCount() == 0) {
    $output['error'] = "資料沒有修改";
} else {
    $output['success'] = true;
}


echo json_encode($output);