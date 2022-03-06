<?php require __DIR__. './../parts/__connect_db.php';

$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];

$q_id = $_POST['q_id'] ?? '';
$a_item = $_POST['a_item'] ?? '';

if(empty($a_item)){
    $output['code'] = 403;
    $output['error'] = '請輸入正確的答案選項';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "INSERT INTO `guide_a`(`q_id`, `a_item`) VALUES (?,?)";
$stmt= $pdo ->prepare($sql);

$stmt->execute([
    $q_id,
    $a_item,
]);

$output['success'] = $stmt->rowCount()==1;
$output['rowCount'] = $stmt->rowCount();

echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>