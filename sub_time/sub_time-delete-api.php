<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}
echo $_GET['subtime_id'];
if (isset($_GET['subtime_id'])) {
    $subtime_id = $_GET['subtime_id'];
    $sql = "DELETE FROM `sub_time` WHERE subtime_id IN ($subtime_id) ";
    $stmt = $pdo->query($sql);
}
$come_from = $_SERVER["HTTP_REFERER"] ?? "sub_time.php";
header("Location: $come_from")

?>