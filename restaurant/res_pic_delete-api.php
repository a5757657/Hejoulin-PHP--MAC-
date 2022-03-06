<?php
require __DIR__. '/../parts/__connect_db.php';

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    $output['error'] = '請登入管理帳號';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    header("Location: " . "../login/login.php");
    exit;
}

if(isset($_GET['res_pic_id'])) {
    $sid = intval($_GET['res_pic_id']);
    $pdo->query("DELETE FROM `restaurant_pictures` WHERE `res_pic_id` IN ($sid)");
}