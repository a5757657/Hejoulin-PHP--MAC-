<?php require __DIR__ . '.\..\parts\__connect_db.php';

$output = ['success' => false,
    'code' => 0,
    'error' => '',];

$cate = $_POST['q_cate']??'';
$seq = $_POST['q_seq']??'';
$des = $_POST['q_des']??'';

if(empty($cate)){
    $output['code'] = 401;
    $output['error'] = '請輸入正確的指南種類';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if(empty($seq)){
    $output['code'] = 403;
    $output['error'] = '請輸入正確的問題序號';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if(empty($des)){
    $output['code'] = 407;
    $output['error'] = '請輸入正確的選酒指南問題';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "INSERT INTO `guide_q`(`q_cate`, `q_seq`, `q_des`) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $cate,
    $seq,
    $des,
]);

$output['success'] = $stmt->rowCount()==1;
$output['rowCount'] = $stmt->rowCount();

echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>