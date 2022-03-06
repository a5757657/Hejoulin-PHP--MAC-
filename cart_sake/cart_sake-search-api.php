<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}
if ($_POST['product_id'] == 0) {
    echo json_encode('x');
    exit;
}
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $sql = "SELECT * FROM `product_sake` WHERE `pro_id` =?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $product_id
    ]);
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    $formatID = $row['format_id'];
}
if (isset($formatID)) {
    $sql = "SELECT * FROM `product_format` WHERE `format_id` =?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $formatID
    ]);
    $row = $stmt->fetch();
    $proMark = $row['pro_mark'];
}
if (isset($proMark)) {
    echo json_encode($proMark);
} else {
    echo json_encode('api no value');
}

?>