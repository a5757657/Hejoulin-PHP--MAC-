<?php require __DIR__ . '.\..\parts\__connect_db.php';

$output = ['success' => false,
    'code' => 0,
    'error' => '',];

$q_id = isset($_POST['q_id']) ? intval($_POST['q_id']) : 0;
if(empty($q_id)){
    $output['code'] = 400;
    $output['error'] = '沒有 id';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

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

$sql = "UPDATE `guide_q` SET `q_cate`=?,`q_seq`=?,`q_des`=? WHERE `q_id`=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $cate,
    $seq,
    $des,
    $q_id
]);

if($stmt->rowCount()==0){
    $output['error'] = '資料沒有修改';
}else{
    $output['success'] = true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>