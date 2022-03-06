<?php
require __DIR__. '/../parts/__connect_db.php';

header('content-type: application/json');

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    $output['error'] = '請登入管理帳號';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    header("Location: " . "../login/login.php");
    exit;
}

$output = [
    'success' => false,
    'code' => 0,
    'error' => '',
];


$res_type = $_POST['res_type'] ?? '';
$res_area = $_POST['res_area'] ?? '';
$res_name = $_POST['res_name'] ?? '';
$res_intro = $_POST['res_intro'] ?? '';
$res_address = $_POST['res_address'] ?? '';

$ser_sun = $_POST['ser_sun'] ?? '';
$ser_mon = $_POST['ser_mon'] ?? '';
$ser_tue = $_POST['ser_tue'] ?? '';
$ser_wed = $_POST['ser_wed'] ?? '';
$ser_thu = $_POST['ser_thu'] ?? '';
$ser_fri = $_POST['ser_fri'] ?? '';
$ser_sat = $_POST['ser_sat'] ?? '';

$res_ser_hours[] = $ser_sat;
$res_ser_hours[] = $ser_sun;
$res_ser_hours[] = $ser_mon;
$res_ser_hours[] = $ser_tue;
$res_ser_hours[] = $ser_wed;
$res_ser_hours[] = $ser_thu;
$res_ser_hours[] = $ser_fri;

$new_res_ser_hours = json_encode($res_ser_hours);

$res_t_number = $_POST['res_t_number'] ?? '';
$web_link = $_POST['web_link'] ?? '';
$fb_link = $_POST['fb_link'] ?? '';
$ig_link = $_POST['ig_link'] ?? '';
$booking_link = $_POST['booking_link'] ?? '';

$sp_menu_name_1 = $_POST['sp_menu_name_1'] ?? '';
$sp_menu_name_2 = $_POST['sp_menu_name_2'] ?? '';
$sp_menu_name_3 = $_POST['sp_menu_name_3'] ?? '';


// 檢查欄位資料
if(empty($res_name)) {
    $output['code'] = 401;
    $output['error'] = '請輸入餐廳名稱';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if(empty($res_intro)) {
    $output['code'] = 402;
    $output['error'] = '請輸入餐廳介紹';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if(empty($res_address)) {
    $output['code'] = 403;
    $output['error'] = '請輸入餐廳地址';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if(empty($ser_sat) or empty($ser_sun) or empty($ser_mon) or empty($ser_tue) or empty($ser_wed) or empty($ser_thu) or empty($ser_fri)) {
    $output['code'] = 404;
    $output['error'] = '請輸入營業時間';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if(empty($res_t_number) or !preg_match("/\d{2,4}-?\d{3,4}-?\d{3,4}#?(\d+)?/", $res_t_number)) {
    $output['code'] = 405;
    $output['error'] = '請輸入正確餐廳電話';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if((!empty($web_link) && !filter_var($web_link, FILTER_VALIDATE_URL)) or (!empty($fb_link) && !filter_var($fb_link, FILTER_VALIDATE_URL)) or (!empty($ig_link) && !filter_var($ig_link, FILTER_VALIDATE_URL)) or (!empty($booking_link) && !filter_var($booking_link, FILTER_VALIDATE_URL))) {
    $output['code'] = 406;
    $output['error'] = '請輸入正確網址';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
$resfile =  $_FILES['res_pic']['name'];
$menufile =  $_FILES['menu_pic']['name'];
if( ( count($resfile) > 6 ) or ( count($menufile) > 6 ) ) {
    $output['code'] = 407;
    $output['error'] = '上傳檔案數量超過';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}



$sql = "INSERT INTO `restaurant`(
                           `res_type`, `res_area`, `res_name`, `res_intro`, `res_address`, `res_ser_hours`, `res_t_number`, `web_link`, `fb_link`, `ig_link`, `booking_link`, `res_create_date`, `res_update_date`
                           ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW() )";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $res_type,
    $res_area,
    $res_name,
    $res_intro,
    $res_address,
    $new_res_ser_hours,
    $res_t_number,
    $web_link,
    $fb_link,
    $ig_link,
    $booking_link,
]);

$output['success'] = $stmt->rowCount()==1;  // rowCount() 為1 == 1 ，因此返回true
$output['rowCount'] = $stmt->rowCount();   // rowCount() 返回受最後一條 SQL 語句影響的行數

$res_id = $pdo->lastInsertId();



// 檔案上傳

$output['res_id'] = $res_id ;   // 抓取 res_id

$res_pic_folder = __DIR__. '\..\img\res_pic';
$menu_pic_folder = __DIR__. '\..\img\menu_pic';
$sp_menu_folder = __DIR__. '\..\img\sp_menu';


$exts = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/gif' => '.gif'
];

// 餐廳圖片上傳
if (! empty($_FILES['res_pic']) and !empty($_FILES['res_pic']['name'])) {
    foreach($_FILES['res_pic']['name'] as $i=>$name) {
        $ext = $exts[$_FILES['res_pic']['type'][$i]]?? '';  // 拿到對應的副檔名

        if (!empty($ext)) {
            $filename = sha1($name . rand()) . $ext;    
            $target = $res_pic_folder . '/' . $filename;
            if( move_uploaded_file($_FILES['res_pic']['tmp_name'][$i], $target)) {
                // $output['success'] ++;
                // $output['files'][] = $filename;
                $res_pic_sql = "INSERT INTO `restaurant_pictures`(`res_pic_name`, `res_id`) VALUES (?, ?)";
                $res_pic_stmt = $pdo->prepare($res_pic_sql);
                $res_pic_stmt->execute([
                $filename,
                $res_id
                ]);
            } else {
                // $output['error'] = '無法移動檔案';
            }
    
        } else {
            // $output['error'] = '不合法的檔案類型';
        }

    }
}
 else {
        // $output['error'] = '沒有上傳檔案';
}

// 菜單圖片上傳
if (! empty($_FILES['menu_pic']) and !empty($_FILES['menu_pic']['name'])) {
    foreach($_FILES['menu_pic']['name'] as $i=>$name) {
        $ext = $exts[$_FILES['menu_pic']['type'][$i]]?? '';  // 拿到對應的副檔名

        if (!empty($ext)) {
            $filename = sha1($name . rand()) . $ext;    
            $target = $menu_pic_folder . '/' . $filename;
            if( move_uploaded_file($_FILES['menu_pic']['tmp_name'][$i], $target)) {
                // $output['success'] ++;
                // $output['files'][] = $filename;
                $menu_pic_sql = "INSERT INTO `menu_pictures`(`menu_pic_name`, `res_id`) VALUES (?, ?)";
                $menu_pic_stmt = $pdo->prepare($menu_pic_sql);
                $menu_pic_stmt->execute([
                $filename,
                $res_id
                ]);
            } else {
                // $output['error'] = '無法移動檔案';
            }
    
        } else {
            // $output['error'] = '不合法的檔案類型';
        }

    }
}
 else {
        // $output['error'] = '沒有上傳檔案';
}

//  特別菜單上傳
//  1號
if( !empty($_FILES['sp_menu_pic_name1'])) {
    $ext = $exts[$_FILES['sp_menu_pic_name1']['type']] ?? '';  // 拿到對應的副檔名

    if (!empty($ext)) {
        $filename = sha1($_FILES['sp_menu_pic_name1']['name'] . rand()) . $ext;    
        $target = $sp_menu_folder . '/' . $filename;
        if( move_uploaded_file($_FILES['sp_menu_pic_name1']['tmp_name'], $target)) {
            // $output['success'] = true;
            // $output['filename'] = $filename;
            $sp_menu_sql = "INSERT INTO `special_menu`(`sp_menu_pic_name`, `sp_menu_name`, `res_id`) VALUES (?, ?, ?)";
            $sp_menu_stmt = $pdo->prepare($sp_menu_sql);
            $sp_menu_stmt->execute([
            $filename,
            $sp_menu_name_1,
            $res_id
            ]);
        } else {
            // $output['error'] = '無法移動檔案';   // 若有權限問題將無法移動檔案
        }

    } else {
        // $output['error'] = '不合法的檔案類型';
    }
} else {
        // $output['error'] = '沒有上傳檔案';
}
//  2號
if( !empty($_FILES['sp_menu_pic_name2'])) {
    $ext = $exts[$_FILES['sp_menu_pic_name2']['type']] ?? '';  // 拿到對應的副檔名

    if (!empty($ext)) {
        $filename = sha1($_FILES['sp_menu_pic_name2']['name'] . rand()) . $ext;    
        $target = $sp_menu_folder . '/' . $filename;
        if( move_uploaded_file($_FILES['sp_menu_pic_name2']['tmp_name'], $target)) {
            // $output['success'] = true;
            // $output['filename'] = $filename;
            $sp_menu_sql = "INSERT INTO `special_menu`(`sp_menu_pic_name`, `sp_menu_name`, `res_id`) VALUES (?, ?, ?)";
            $sp_menu_stmt = $pdo->prepare($sp_menu_sql);
            $sp_menu_stmt->execute([
            $filename,
            $sp_menu_name_2,
            $res_id
            ]);
        } else {
            // $output['error'] = '無法移動檔案';   // 若有權限問題將無法移動檔案
        }

    } else {
        // $output['error'] = '不合法的檔案類型';
    }
} else {
        // $output['error'] = '沒有上傳檔案';
}
//  3號
if( !empty($_FILES['sp_menu_pic_name3'])) {
    $ext = $exts[$_FILES['sp_menu_pic_name3']['type']] ?? '';  // 拿到對應的副檔名

    if (!empty($ext)) {
        $filename = sha1($_FILES['sp_menu_pic_name3']['name'] . rand()) . $ext;    
        $target = $sp_menu_folder . '/' . $filename;
        if( move_uploaded_file($_FILES['sp_menu_pic_name3']['tmp_name'], $target)) {
            // $output['success'] = true;
            // $output['filename'] = $filename;
            $sp_menu_sql = "INSERT INTO `special_menu`(`sp_menu_pic_name`, `sp_menu_name`, `res_id`) VALUES (?, ?, ?)";
            $sp_menu_stmt = $pdo->prepare($sp_menu_sql);
            $sp_menu_stmt->execute([
            $filename,
            $sp_menu_name_3,
            $res_id
            ]);
        } else {
            // $output['error'] = '無法移動檔案';   // 若有權限問題將無法移動檔案
        }

    } else {
        // $output['error'] = '不合法的檔案類型';
    }
} else {
        // $output['error'] = '沒有上傳檔案';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);