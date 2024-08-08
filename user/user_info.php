<?php
require_once('../db.php'); // 引入資料庫連線
//session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搜蒐甜點店</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../js/all.js"></script>
    <link rel="stylesheet" href="../css/all.css">

</head>

<body>
    <div class="wrap">
        <div class="navbar">
            <?php
            if (isset($_SESSION['nowUser']) && $_SESSION['nowUser']['user_Role'] == "manager") {
                echo "<h1 class='logo'><a href='../manager_index.php'>搜蒐甜點店</a></h1>";
            } else {
                echo "<h1 class='logo'><a href='../index.php'>搜蒐甜點店</a></h1>";
            }
            ?>
            <ul class="nav">
                <?php
                if (isset($_SESSION['nowUser'])) {
                    // 使用者已登入，顯示收藏和圖鑑
                    if ($_SESSION['nowUser']['user_Role'] == "manager") {
                        echo '<li class="nav-content"><a href="../dessType/manager_dessType_Index.php">管理甜點種類</a></li>';
                        echo '<li class="nav-content"><a href="../manager_index.php">管理店家</a></li>';
                    }
                    echo '<li class="nav-content"><a href="../favorite/favorite.php?userid=' . $_SESSION['nowUser']['user_ID'] . '">收藏</a></li>';
                    echo '<li class="nav-content"><a href="../gallery/gallery.php?userid=' . $_SESSION['nowUser']['user_ID'] . '">圖鑑</a></li>';
                    echo '<li class="nav-content"><a href="user_info.php?userid=' . $_SESSION['nowUser']['user_ID'] . '"><i class="fa-solid fa-user"></i></a></li>';
                } else {
                    // 使用者未登入
                    echo '<li class="nav-content hide"><a href="#">收藏</a></li>';
                    echo '<li class="nav-content hide"><a href="#">圖鑑</a></li>';
                    echo '<li class="nav-content"><a href="signup.php"><i class="fa-solid fa-user"></i></a></li>';
                }
                ?>

            </ul>
        </div>
    </div>
    <?php
    // 檢查是否有 NowUser 的 session，並確認是否有登入資訊
    if (isset($_SESSION["nowUser"]) && !empty($_SESSION["nowUser"])) {
        // 存在 NowUser 的 session，取出相應的用戶資訊
        $nowUser = $_SESSION["nowUser"];
        $showUser = $nowUser['user_ID'];
        // $nickname = $nowUser['user_NickName'];
        // $email = $nowUser['user_Email'];
        // $pwd=$nowUser['user_Password'];
    
        //從資料庫選取NickName 
        $nickNameQuery = "SELECT user_NickName FROM user WHERE user_ID = '$showUser'";
        $nickNameResult = $conn->query($nickNameQuery);
        $nickName = $nickNameResult->fetch_assoc();
        $nickName = $nickName["user_NickName"];


        $emailQuery = "SELECT user_Email FROM user WHERE user_ID = '$showUser'";
        $emailResult = $conn->query($emailQuery);
        $email = $emailResult->fetch_assoc();
        $email = $email["user_Email"];

        $passwordQuery = "SELECT user_Password FROM user WHERE user_ID = '$showUser'";
        $passwordResult = $conn->query($passwordQuery);
        $password = $passwordResult->fetch_assoc();
        $password = $password["user_Password"];
        $maskedPassword = str_repeat('*', strlen($password));
        // 從資料庫中選擇圖片
        /*
        $sql = "SELECT user_Photo FROM user WHERE user_ID = '$showUser'";
        $result = $conn->query($sql);
        // 檢查查詢是否成功
        if ($result->num_rows > 0) {

            // 將 blob 資料轉換成圖片顯示
            $row = $result->fetch_assoc();
    
            $userPhoto =  $row['user_Photo'];


        }*/
        echo "
            <div class='main-user-info'>
                <div class='setting'>";
        /*<img src='data:image/png;base64," . base64_encode($userPhoto) . "' alt='user_photo' style='width: 350px; height: 400px;' />*/
        echo "
                    <p><a href='adjust_uinfo.php'>修改資料</a></p>
                    <p><button type='submit' onclick=\"deletionUser('$showUser')\">刪除帳號</button></p>
                    <p><a href='logout.php'>登出</a></p>
                </div>
                <div class='info'>
                    <p>名稱(暱稱):" . $nickName . "</p>
                    <p>電子郵件:" . $email . "</p>
                    <p>密碼:" . $maskedPassword . "</p>
                    <div class='link'>";
        echo "
                        <a href='../favorite/favorite.php?userid=$showUser' class='favorite'>
                            收藏
                        </a>";
        echo "
                    <a href='../gallery/gallery.php?userid=$showUser' class='collection'>
                        圖鑑
                    </a>
                    </div>
                </div>
            </div>";
    }
    ?>
    <div class="footer">
        <div class="left-footer"><img src='../image/logo-4.png'></div>
        <div class="right-footer">
            <p>Copyright © 2023 搜蒐甜點店 All Rights Reserved</p>
        </div>
    </div>
</body>

</html>