<?php
require __DIR__. '/../parts/__connect_db.php';

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    $output['error'] = '請登入管理帳號';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

if(isset($_GET['res_id'])) {
    $sid = $_GET['res_id'];
    $pdo->query("DELETE FROM `restaurant` WHERE `res_id` IN ($sid)");
}

$come_from = $_SERVER['HTTP_REFERER'] ?? 'restaurant.php';

header("Location: $come_from");