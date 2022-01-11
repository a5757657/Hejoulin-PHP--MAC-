<?php require __DIR__ . '.\..\parts\__connect_db.php';

if(isset($_GET['a_no'])){
    $a_no = $_GET['a_no'];
    $pdo -> query("DELETE FROM `guide_a` WHERE `a_no` IN ($a_no)");
}

$come_from = $_SERVER['HTTP_REFERER'] ?? 'guide_answer.php';
header("Location: $come_from");