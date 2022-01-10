<?php
require __DIR__ . '\..\parts\__connect_db.php';

if (isset($_GET['discount_id'])){
    $dId = ($_GET['discount_id']);

    $pdo -> query("DELETE FROM `discount` WHERE `discount`.`discount_id` IN ($dId)");
}
$come_from = $_SERVER['HTTP_REFERER'] ?? 'discount_list.php';

header("Location: $come_from");