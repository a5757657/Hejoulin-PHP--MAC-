<?php require __DIR__ . './../parts/__connect_db.php';

if(isset($_GET['q_id'])){
    $q_id = $_GET['q_id'];
    $pdo -> query("DELETE FROM `guide_q` WHERE q_id IN ($q_id)");
}

$come_from = $_SERVER['HTTP_REFERER'] ?? 'guide_question.php';
header("Location: $come_from");