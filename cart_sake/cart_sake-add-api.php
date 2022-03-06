<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php
$output = [
    'success' => false,
    'code' => 0,
    'error' => ''
];

$member = $_POST["member"] ?? '';
$product = $_POST["product"] ?? '';
$quantity = $_POST["quantity"] ?? '';
$mark = $_POST["mark"] ?? '';


if (empty($member)) {
    $output['code'] = 401;
    $output['error'] = '請選擇會員';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($product)) {
    $output['code'] = 403;
    $output['error'] = '請選擇商品';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($quantity)) {
    $output['code'] = 405;
    $output['error'] = '請輸入數量';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

// 查詢最後一筆資料的ID (PK)，並+1作為新增資料的ID (PK)
$sql = "SELECT * FROM `cart_sake` WHERE `cart_sake_id` = ( SELECT MAX(`cart_sake_id`) FROM `cart_sake`);";

$lastID = $pdo->query($sql)->fetch();
$lastID_sliced = substr($lastID['cart_sake_id'], 1);
$length = strlen(strval($lastID_sliced + 1));
$zero = '';
for ($i = 1; $i <= (10 - $length); $i++) {
    $zero = $zero . '0';
}
// 新增資料的ID (PK)
$cart_sake_id = 'S' . $zero . ($lastID_sliced + 1);


$sql = "INSERT INTO `cart_sake`(`cart_sake_id`, `member_id`, `pro_id`, `cart_quantity`) VALUES (?,?,?,?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $cart_sake_id,
    $member,
    $product,
    $quantity
]);

if (!empty($mark)) {
    $sqlM = "INSERT INTO `cart_mark`(`mark_id`, `cart_sake_id`) VALUES (?,?)";
    $stmtM = $pdo->prepare($sqlM);
    $stmtM->execute([
        $mark,
        $cart_sake_id
    ]);

    $output['successM'] = $stmtM->rowCount() == 1;
    $output['rowcountM'] = $stmtM->rowCount();
} else {
    $output['successM'] = "x";
    $output['rowcountM'] = "x";
}

$output['success'] = $stmt->rowCount() == 1;

$output['rowcount'] = $stmt->rowCount();


echo json_encode($output);
