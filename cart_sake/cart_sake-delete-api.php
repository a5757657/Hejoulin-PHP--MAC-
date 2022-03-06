<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}
if ($_GET['cart_sake_id']) {
    $cart_sake_id = $_GET['cart_sake_id'];
    $sql = "DELETE FROM `cart_sake` WHERE cart_sake_id IN (?);
    DELETE FROM `cart_mark` WHERE cart_sake_id IN (?);
    ";
    $ids = explode(",", $cart_sake_id);
    foreach ($ids as $value) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $value,
            $value
        ]);
    }
}
$come_from = $_SERVER["HTTP_REFERER"] ?? "cart_sake.php";
header("Location: $come_from")

?>