<?php
require __DIR__ . '\..\parts\__connect_db.php';

if (isset($_GET['mark_id'])){
    $mId = ($_GET['mark_id']);

    $pdo -> query("DELETE FROM `mark` WHERE mark_id=$mId");
}
$come_from = $_SERVER['HTTP_REFERER'] ?? 'mark_list.php';

header("Location: $come_from");