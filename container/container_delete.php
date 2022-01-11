<?php require __DIR__ . '.\..\parts\__connect_db.php';

if(isset($_GET['container_id'])){
    $container_id = $_GET['container_id'];
    $pdo -> query("DELETE FROM `product_container` WHERE `container_id` IN ($container_id)");
}

$come_from = $_SERVER['HTTP_REFERER'] ?? 'container.php';
header("Location: $come_from");