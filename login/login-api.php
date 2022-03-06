<?php require __DIR__. '/../parts/__connect_db.php';

$output = [
    'success' => false,
    'code' => 0,
    'error' => '帳號或密碼錯誤',
];

$account = $_POST['account'] ?? '';
$password = $_POST['password'] ?? '';


if(empty($account) or empty($password)) {
    $output['code'] = 401;
    $output['error'] = '尚有欄位未填寫';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}


$sql = sprintf("SELECT * FROM `admin` WHERE `admin_name`=%s", $pdo->quote($account));


$row = $pdo->query($sql)->fetch();

echo empty($row);

if(empty($row)) {
    $output['error'] = '帳號或密碼錯誤'; // 這裡其實是找不到帳號，但為了不要讓輸入錯誤的人知道這個訊息，因此這裡這樣寫
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

if( $password == $row['admin_pass'] ){
    $output['success'] = true;
    $_SESSION['admin'] = [
        'account' => $row['admin_name']
    ];
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);