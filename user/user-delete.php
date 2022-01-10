<?php
require __DIR__ . '\..\parts\__connect_db.php';

if (isset($_GET['user_id'])){
    $uId = ($_GET['user_id']);

    $pdo -> query("DELETE FROM `user` WHERE user_id IN ($uId)");
}
$come_from = $_SERVER['HTTP_REFERER'] ?? 'user_list.php';

header("Location: $come_from");