<?php require __DIR__ . './../parts/__connect_db.php';

if(isset($_GET['gift_d_id'])){
    $gift_d_id = $_GET['gift_d_id'];
    $pdo -> query("DELETE FROM `product_gift_d` WHERE `gift_d_id` IN ($gift_d_id)");
}

$come_from = $_SERVER['HTTP_REFERER'] ?? 'gift_detail.php';
header("Location: $come_from");