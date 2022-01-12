<?php require __DIR__. '\..\parts\__connect_db.php';

$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];

$a_no = isset($_POST['a_no']) ? intval($_POST['a_no']) : 0;

$q_id = $_POST['q_id'] ?? '';
$a_item = $_POST['a_item'] ?? '';

if(empty($a_item)){
    $output['code'] = 403;
    $output['error'] = '請輸入正確的答案選項';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "UPDATE `guide_a` SET`q_id`=?, `a_item`=? WHERE `a_no`=?";
$stmt= $pdo ->prepare($sql);

$stmt->execute([
    $q_id,
    $a_item,
    $a_no
]);

if($stmt->rowCount()==0){
    $output['error'] = '資料沒有修改';
} else {
    $output['success'] =true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>