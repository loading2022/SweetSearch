<?php
    require_once('../db.php'); // 引入資料庫連線
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="../css/all.css">
</head>
</html>
<body>
    <div class="wrap">
        <div class="navbar">
            <h1 class="logo"><a href="../index.php">搜蒐甜點店</a></h1>
            <ul class="nav">
                <?php
                    if (isset($_SESSION['nowUser'])) {
                        // 使用者已登入，顯示收藏和圖鑑
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
        <?php
            if (isset($_SESSION["nowUser"]) && !empty($_SESSION["nowUser"])) {
                // 確認使用者登入
                $nowUser = $_SESSION["nowUser"];
                $showUser = $nowUser['user_ID'];
                // $originalNickName = $nowUser['user_NickName'];
                // $originalEmail = $nowUser['user_Email'];
                // $originalPassword = $nowUser['user_Password'];

                //從資料庫選取NickName 
                $nickNameQuery = "SELECT user_NickName FROM user WHERE user_ID = '$showUser'";
                $nickNameResult = $conn->query($nickNameQuery);
                $nickName = $nickNameResult->fetch_assoc();
                $nickName = $nickName["user_NickName"];
                
                //從資料庫選取Email
                $emailQuery = "SELECT user_Email FROM user WHERE user_ID = '$showUser'";
                $emailResult = $conn->query($emailQuery);
                $email = $emailResult->fetch_assoc();
                $email = $email["user_Email"];
                
                //從資料庫選取Password
                $passwordQuery = "SELECT user_Password FROM user WHERE user_ID = '$showUser'";
                $passwordResult = $conn->query($passwordQuery);
                $password = $passwordResult->fetch_assoc();
                $password = $password["user_Password"];
                

                
                // 檢查表單是否提交
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // 取得表單提交的資料
                    $newNickName = $_POST["newNickName"];
                    $newEmail = $_POST["newEmail"];
                    $newPassword = $_POST["newPassword"];

                    // 更新資料庫中的使用者資訊
                    $updateSql = "UPDATE user SET user_NickName='$newNickName', user_Email='$newEmail', user_Password='$newPassword' WHERE user_ID='$showUser'";
                    
                    if ($conn->query($updateSql) == TRUE) {
                        header("Location: user_info.php?userid=$showUser");
                        exit();
                        /*
                        echo '
                            <script>
                                Swal.fire({
                                    title: "資料更新成功",
                                    icon: "success",
                                    confirmButtonText: "確認"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "user_info.php";
                                    }
                                });
                                    // 清除表單中的輸入數據
                                    document.getElementById("updateForm").reset();
                                </script>';*/
                    }
                    else 
                    {
                        echo "錯誤：" . $conn->error;
                    }
                }
            }
        ?>
        <div class="adjust-user-main">
            <h2>修改使用者資料</h2>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="newNickName">新的暱稱</label>
                <input type="text" id="newNickName" name="newNickName" value="<?php echo $nickName ;?>" required>
                <label for="newEmail">新的電子郵件</label>
                <input type="email" id="newEmail" name="newEmail" value="<?php echo $email; ?>"  required>
                <label for="newPassword">新的密碼</label>
                <input type="text" id="newPassword" name="newPassword" value="<?php  echo $password; ?>" required>
                <button type="submit" class="user-update">更新資料</button>
            </form>
        </div>
    </div>
    <div class="footer">
        <div class="left-footer"><img src='../image/logo-4.png'></div>
        <div class="right-footer">
            <p>Copyright © 2023 搜蒐甜點店 All Rights Reserved</p>
        </div>
    </div>
<script src="../js/all.js"></script>
</body>
</html>
