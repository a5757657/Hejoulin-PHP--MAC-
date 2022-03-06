<?php require __DIR__. './../parts/__connect_db.php';

// 如果未登入管理帳號就轉向
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}


$title = "首頁";
$pageName = "homepage";
?>
<?php include __DIR__ . '/../parts/__head.php'?>

<?php include __DIR__ . '/../parts/__navbar.php'?>
<?php include __DIR__ . '/../parts/__sidebar.html'?>

<?php include __DIR__ . '/../parts/__main_start.html'?>
<!-- 主要的內容放在 __main_start 與 __main_end 之間 -->
<style>
.container {
    position: relative;

}

.logo {
    position: absolute;
    top: 30%;
    left: 30%;
    transform: translate(-50%, -50%);
}

@media screen and (max-width:768px) {
    .logo {
        top: 10%;
        left: 10%;
    }

    .in {
        width: 250px;
    }

    .out1,
    .out2 {
        width: 250px;
    }

}

/* 文字部分 */
.in {
    width: 400px;
    top: 0;
    left: 0;
    position: absolute;
    animation: scale 3s infinite cubic-bezier(0.53, 0.04, 0.56, 0.96);
}

/* 外框 */
.out1,
.out2 {
    width: 400px;
    position: absolute;
    top: 0;
    left: 0;
}

/* 外框 */
.out1 {
    animation: rotate1 4s infinite cubic-bezier(0.53, 1.04, 0.48, -0.02);
}

/* 外框 */
.out2 {
    animation: rotate1 7s infinite ease-in-out;
}

/* 文字縮放 */
@keyframes scale {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.03);
    }

    100% {
        transform: rotate(1);
    }
}

/* 外框旋轉*/
@keyframes rotate1 {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

@keyframes rotate2 {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(-360deg);
    }
}
</style>
<div class="container" style="margin-top: 8rem">
    <div class="logo">
        <img src="temp-logo-word_1.png" alt="" class="in" />
        <img src="temp-logo-2_1.png" alt="" class="out1" />
        <img src="temp-logo-3_1.png" alt="" class="out2" />
    </div>
</div>
<?php include __DIR__ . '/../parts/__main_end.html'?>
<!-- 如果要 modal 的話留下面的結構 -->
<?php include __DIR__ . '/../parts/__modal.html'?>

<?php include __DIR__ . '/../parts/__script.html'?>
<!-- 如果要 modal 的話留下面的 script -->
<script>
</script>
<?php include __DIR__ . '/../parts/__foot.html'?>