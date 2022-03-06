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
$cart_sake_id = $_POST['cart_sake_id'];

// $cart_sake_id = isset($_POST['cart_sake_id']) ? intval($_POST['cart_sake_id']) : 0;
if (empty($cart_sake_id)) {
    $output['code'] = 400;
    $output['error'] = "這筆資料沒有cart_sake_id，cart_sake_id = $cart_sake_id ";
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}


$member = $_POST["member"] ?? '';
$product = $_POST["product"] ?? '';
$quantity = $_POST["quantity"] ?? '';
$mark = $_POST["mark"] ?? 0;


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

$sql = "UPDATE `cart_sake` SET `member_id`=?,`pro_id`=?,`cart_quantity`=? WHERE `cart_sake_id`=?;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $member,
    $product,
    $quantity,
    $cart_sake_id
]);
// $output['success'] = $stmt->rowCount() == 1;
$output['success'] = true;
$output['rowcount'] = $stmt->rowCount();


$output['success'] = true;
//查詢原本有無酒標
$sql = "SELECT * FROM `cart_mark` WHERE `cart_sake_id`=?;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $cart_sake_id
]);
$cmExisted = $stmt->rowCount();

//判斷原本有無酒標
if ($cmExisted) {
    //原本有酒標，要改成別的酒標，使用update修改購物車酒標資料表的資料
    if ($mark != 0) {
        $markEID = $stmt->fetch();
        if ($mark != $markEID['mark_id']) {
            $sqlM = "UPDATE `cart_mark` SET `mark_id`=? WHERE `cart_sake_id`=?;";
            $stmtM = $pdo->prepare($sqlM);
            $stmtM->execute([
                $mark,
                $cart_sake_id
            ]);
            $output['successM'] = $stmtM->rowCount() == 1;
            $output['rowcountM'] = '原本有酒標，改成別的';
        } else {
            $output['successM'] = true;
            $output['rowcountM'] = '原本有酒標，酒標不變';
        }
        // $output['successM'] = $stmtM->rowCount() == 1;

        //原本有酒標，要改成不客製酒標，使用delete刪除購物車酒標資料表的資料
    } else if ($mark == 0) {
        $sqlM = "DELETE FROM `cart_mark` WHERE `cart_sake_id`=?;";
        $stmtM = $pdo->prepare($sqlM);
        $stmtM->execute([
            $cart_sake_id
        ]);
        $output['successM'] = $stmtM->rowCount() == 1;
        $output['rowcountM'] = '原本有酒標，改成沒有';
    }
    //原本無酒標
} else {
    //原本無酒標，要改成有酒標就要用insert新增資料進購物車酒標資料表
    if ($mark != 0) {
        $sqlM = "INSERT INTO `cart_mark`(`mark_id`, `cart_sake_id`) VALUES (?,?)";
        $stmtM = $pdo->prepare($sqlM);
        $stmtM->execute([
            $mark,
            $cart_sake_id
        ]);
        $output['successM'] = $stmtM->rowCount() == 1;
        $output['rowcountM'] = "原本沒酒標，改成有酒標";
    } else {
        $output['successM'] = "x";
        $output['rowcountM'] = "原本沒酒標，現在也沒有";
    }
}




echo json_encode($output);