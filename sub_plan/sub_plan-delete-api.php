<?php require __DIR__ . '/../parts/__connect_db.php' ?>
<?php
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}
echo $_GET['sub_id'];
if (isset($_GET['sub_id'])) {
    $sub_id = $_GET['sub_id'];
    $sql = "DELETE FROM `sub_plan` WHERE sub_id IN ($sub_id) ";
    $stmt = $pdo->query($sql);
}
$come_from = $_SERVER["HTTP_REFERER"] ?? "sub_plan.php";
header("Location: $come_from")

?>