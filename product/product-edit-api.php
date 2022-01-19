<?php require __DIR__ . '/../parts/__connect_db.php';

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

error_reporting(0);
$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];

$s_id = isset($_POST['pro_id']) ? intval($_POST['pro_id']) : 0;
$f_id = isset($_POST['format_id']) ? intval($_POST['format_id']) : 0;

if (empty($s_id)) {
    $output['code'] = 400;
    $output['error'] = '沒有pro_id';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($f_id)) {
    $output['code'] = 400;
    $output['error'] = '沒有f_id';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$s_name = $_POST['pro_name'] ?? '';
$s_stock = $_POST['pro_stock'] ?? '';
$s_selling = $_POST['pro_selling'] ?? '';
$s_intro = $_POST['pro_intro'] ?? '';
$s_condition = $_POST['pro_condition'] ?? '';


$f_price = $_POST['pro_price'] ?? '';
$f_capacity = $_POST['pro_capacity'] ?? '';
$f_loca = $_POST['pro_loca'] ?? '';
$f_level = $_POST['pro_level'] ?? '';
$f_brand = $_POST['pro_brand'] ?? '';
$f_essence = $_POST['pro_essence'] ?? '';
$f_alco = $_POST['pro_alco'] ?? '';
$f_marker = $_POST['pro_marker'] ?? '';
$f_rice = $_POST['rice'] ?? '';
$f_taste = $_POST['pro_taste'] ?? '';
$f_temp = $_POST['pro_temp'] ?? '';
$f_gift = $_POST['pro_gift'] ?? '';
$f_mark = $_POST['pro_mark'] ?? '';
$f_container_id = $_POST['container_id'] ?? '';

$upload_folder = __DIR__ . '/../img/pro_img';

//後端確認檔案類型
$exts = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/gif' => '.gif'
];

if (!empty($_FILES['pro_img'])) {

    $ext = $exts[$_FILES['pro_img']['type']]; //拿到對應的副檔名

    if (!empty($ext)) {

        //$filename = sha1($_FILES['pro_img']['name'] . rand()) . $ext;
        $filename = $_FILES['pro_img']['name'] . $ext;
        $output['ext'] = $ext;
        $target = $upload_folder . '/' . $filename;

        if (move_uploaded_file($_FILES['pro_img']['tmp_name'], $target)) {

            $sql_4 = "UPDATE `product_sake` SET `pro_img`=? WHERE `pro_id`=?";

            $stmt_4 = $pdo->prepare($sql_4);

            $stmt_4->execute([
                $filename,
                $s_id
            ]);

            if ($stmt_4->rowCount() == 0) {
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

$sql_1 = "UPDATE `product_sake` SET 
                          `pro_name`=?,
                          `pro_stock`=?,
                          `pro_selling`=?,
                          `pro_intro`=?,
                          `pro_condition`=?
            WHERE `pro_id`=?";


$stmt_1 = $pdo->prepare($sql_1);

$stmt_1->execute([
    $s_name,
    $s_stock,
    $s_selling,
    $s_intro,
    $s_condition,
    $s_id
]);

if ($stmt_1->rowCount() == 0) {
    $output['error'] = '資料沒有修改';
} else {
    $output['success'] = true;
}

$sql_2 = "UPDATE `product_format` SET 
                          `pro_price`=?,
                          `pro_capacity`=?,
                          `pro_loca`=?,
                          `pro_level`=?,
                          `pro_brand`=?,
                          `pro_essence`=?,
                          `pro_alco`=?,
                          `pro_marker`=?,
                          `rice`=?,
                          `pro_taste`=?,
                          `pro_temp`=?,
                          `pro_gift`=?,
                          `pro_mark`=?,
                          `container_id`=?
WHERE `format_id`=?";


$stmt_2 = $pdo->prepare($sql_2);

$stmt_2->execute([
    $f_price,
    $f_capacity,
    $f_loca,
    $f_level,
    $f_brand,
    $f_essence,
    $f_alco,
    $f_marker,
    $f_rice,
    $f_taste,
    $f_temp,
    $f_gift,
    $f_mark,
    $f_container_id,
    $f_id
]);

if ($stmt_2->rowCount() == 0) {
    $output['error'] = '資料沒有修改';
} else {
    $output['success'] = true;
}






$condition = $pdo->query("SELECT `pro_condition` FROM `product_sake` WHERE `product_sake`.`pro_id` = $s_id ")->fetch();
$condition = $condition['pro_condition'];

$s_u_time;
$s_c_time;

$date = date_create(); //現在時間

//判定上下架的時間但是有很大的問題
if ($condition !== $s_condition) {

    if ($s_condition == '已上架') {

        $s_c_time = date_format($date, 'Y-m-d H:i:s');
        $s_u_time = $_POST['pro_unsell_time'];
    }

    if ($s_condition == '已下架') {

        $s_c_time = $_POST['pro_creat_time'];
        $s_u_time = date_format($date, 'Y-m-d H:i:s');
    }

    if ($s_condition == '補貨中') {
        $s_u_time = $_POST['pro_unsell_time'];
        $s_c_time = $_POST['pro_creat_time'];
    }
}

$s_u_time = $_POST['pro_unsell_time'];
$s_c_time = $_POST['pro_creat_time'];


$sql_3 = "UPDATE `product_sake` SET 
                          `pro_creat_time`=?,
                          `pro_unsell_time`=?
WHERE `pro_id`=?";


$stmt_3 = $pdo->prepare($sql_3);

$stmt_3->execute([
    $s_c_time,
    $s_u_time,
    $s_id
]);

if ($stmt_3->rowCount() == 0) {
    $output['error'] = '資料沒有修改';
} else {
    $output['success'] = true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
