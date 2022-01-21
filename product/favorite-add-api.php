<?php require __DIR__ . '/../parts/__connect_db.php';

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];

$pro_id = $_POST['pro_id'];
$member_id = $_POST['member_id'];

//拿會員id跟跟商品id去資料表裡面搜尋 若有找到東西就是重複的收藏
$check = $pdo->query("SELECT * FROM `favorite` WHERE `member_id` = $member_id AND `pro_id` = $pro_id;")->fetchAll();

if ($check) { //確認是否重複
    $output['error'] = '重複的商品';
} else {

    $insert = "INSERT INTO `favorite`(
        `member_id`, 
        `pro_id`
    ) VALUES (?, ?)";

    $stmt = $pdo->prepare($insert);

    $stmt->execute([
        $member_id,
        $pro_id
    ]);

    $output['success'] = '收藏成功';
}



echo json_encode($output, JSON_UNESCAPED_UNICODE);
