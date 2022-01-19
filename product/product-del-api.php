<?php
require __DIR__ . '/../parts/__connect_db.php';

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}

//這邊可以直改為join兩張表一次刪除兩張表的資料
if (isset($_GET['sid'])) {
    $sid = $_GET['sid'];

    $row = $pdo->query("SELECT `format_id` FROM `product_sake` WHERE `product_sake`.`pro_id` IN ($sid) ")->fetchAll(); //篩選出對應pro_id欄位的format_id
    $id = '';
    //$row裡面包裏兩層陣列所以做兩次foreach
    //用商品表id去找出對應的規格表id
    foreach ($row as $k => $v) {
        $row2 = $row[$k];
        foreach ($row2 as $j => $i) {
            $id .= ($row2[$j] . ','); //每一個formau_id後面加上逗號再加入變數裡
            $format_id = $id;
        }
    }

    

    $format_id = substr($id, 0, -1); //刪掉最後一個字元

    echo $sid ;
    
    echo $format_id ;

    $pdo->query("DELETE FROM `product_sake` WHERE `pro_id` IN ($sid)");

    $pdo->query("DELETE FROM `product_format` WHERE `format_id` IN ($format_id)");
};

$come_from = $_SERVER['HTTP_REFERER'] ?? 'product.php';

header("Location: $come_from");
