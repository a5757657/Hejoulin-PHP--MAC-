<?php require __DIR__ . './../parts/__connect_db.php';

header('Content-Type: application/json');

$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];

$container_img = $_POST['container_img'] ?? '';
$container_shadow = $_POST['container_shadow'] ?? '';
$container_name = $_POST['container_name'] ?? '';

if (empty($container_name)) {
    $output['code'] = 405;
    $output['error'] = '請輸入正確的酒器名稱';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$upload_folder = __DIR__ . '\..\img\container';

$exts = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/gif' => '.gif'
];

if (!empty($_FILES['container_img'])) {
    $ext01 = $exts[$_FILES['container_img']['type']];
    if (!empty($_FILES['container_shadow'])) {
        $ext02 = $exts[$_FILES['container_shadow']['type']];
        if (!empty($ext01)) {
            $filename01 = $_FILES['container_img']['name'] . $ext01;
            $target01 = $upload_folder . "\\" . $filename01;
            if (!empty($ext02)) {
                $filename02 = $_FILES['container_shadow']['name'] . $ext02;
                $target02 = $upload_folder . "\\" . $filename02;
                if (move_uploaded_file($_FILES['container_img']['tmp_name'], $target01)) {
                    if (move_uploaded_file($_FILES['container_shadow']['tmp_name'], $target02)) {
            
                        $sql = "INSERT INTO `product_container`(
                            `container_name`,
                            `container_img`,
                           `container_shadow`
                           ) VALUES (?,?,?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                           $container_name,
                           $filename01,
                           $filename02
                        ]);
                        $output['success'] = $stmt->rowCount() == 1;
                        $output['rowCount'] = $stmt->rowCount();
                        
                        $output['success'] = true;
                        $output['filename01'] = $filename01;
                        $output['filename02'] = $filename02;

                    } else {
                        $output['error'] = '無法移動檔案02';
                    }
                } else {
                    $output['error'] = '無法移動檔案01';
                }
            } else {
                $output['error'] = '不合法的檔案類型02';
            }
            
        } else {
            $output['error'] = '不合法的檔案類型01';
        }
        
    } else {
        $output['error'] = '沒有上傳檔案02';
    }
    
} else {
    $output['error'] = '沒有上傳檔案01';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);