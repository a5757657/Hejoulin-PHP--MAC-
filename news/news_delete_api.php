<?php require __DIR__. '/../parts/__connect_db.php';
// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

if(isset($_GET['sid'])){
    $sid = $_GET['sid'];
    $pdo->query("DELETE FROM `news` WHERE `news`.`news_id` IN ($sid)");

    
}

$come_from = $_SERVER['HTTP_REFERER'] ?? 'news.php';

header("Location: $come_from");

?>