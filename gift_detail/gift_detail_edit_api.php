<?php require __DIR__ . './../parts/__connect_db.php';

header('Content-Type: application/json');

$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];

$gift_d_id = isset($_POST['gift_d_id']) ? intval($_POST['gift_d_id']) : 0;
$gift_id = $_POST['gift_id'] ?? '';
$gift_img = $_POST['gift_img'] ?? '';
$box_color = $_POST['box_color'] ?? '';
$gift_pro = $_POST['gift_pro'] ?? '';

if (empty($gift_pro)) {
    $output['code'] = 407;
    $output['error'] = '請輸入正確的禮盒商品';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$upload_folder = __DIR__ . '\..\img\gift';

$exts = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/gif' => '.gif'
];

if (!empty($_FILES['gift_img'])) {

    $ext = $exts[$_FILES['gift_img']['type']]; //拿到對應的副檔名
    
    if (!empty($ext)) {
       
        $filename = $_FILES['gift_img']['name'] . $ext;
        $output['ext'] = $ext;
        $target = $upload_folder . "\\" . $filename;
        
        if (move_uploaded_file($_FILES['gift_img']['tmp_name'], $target)) {
            
            $sql_img = "UPDATE `product_gift_d` SET `gift_img`=? WHERE `gift_d_id`=?";
            
            $stmt_img = $pdo->prepare($sql_img);
           
            $stmt_img->execute([
                $filename,
                $gift_d_id               
            ]);
            
            if ($stmt_img->rowCount() == 0) {
                $output['error'] = '資料沒有修改';
            } else {
                $output['success'] = true;
            }
        } else {
            $output['error'] = '無法移動檔案';
        }
    } else {
        $output['error'] = '不合法的檔案類型';
    }
} else {
    $output['error'] = '沒有上傳檔案';
}

$sql = "UPDATE `product_gift_d` SET
            `gift_id`=?,
            `box_color`=?,
            `gift_pro`=?
            WHERE `gift_d_id`=?";
 $stmt = $pdo->prepare($sql);
 $stmt->execute([
     $gift_id,
     $box_color,
     $gift_pro,
     $gift_d_id
 ]);
 if($stmt->rowCount() == 0){
    $output['error'] = '資料沒有修改';
 }else{
    $output['success'] = 'true';
 }

echo json_encode($output, JSON_UNESCAPED_UNICODE);