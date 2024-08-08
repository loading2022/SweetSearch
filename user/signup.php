<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
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
                <li class="nav-content hide"><a href="#">收藏</a></li>
                <li class="nav-content hide"><a href="#">圖鑑</a></li>
                <li class="nav-content"><a href="#"><i class="fa-solid fa-user"></i></a></li>
            </ul>
        </div>
        <form action="signup.php" method="POST" class="signup">
            <h2>註冊</h2>
            <div class="signup-column">
                <label for="nickname">暱稱 </label>
                <input type="text" id="nickname" placeholder="請輸入暱稱" name="nickname">
            </div>
            <div class="signup-column">
                <label for="email">帳號 </label>
                <input type="email" id="email" placeholder="請輸入 Email" name="email">
            </div>
            <div class="signup-column">
                <label for="pwd">密碼 </label>
                <input type="password" id="pwd" placeholder="請輸入密碼" name="pwd">
            </div>
            <input type="submit" value="註冊" class="signup-button">
            <a href="login.php">已經有帳號?</a>
        </form>

    </div>
    <?php
    require_once('../db.php'); // 引入資料庫連線
    
    // Get the maximum user_ID
    $maxUserIdQuery = "SELECT MAX(CAST(SUBSTRING(user_ID, 2) AS UNSIGNED)) AS maxUserId FROM user";
    $result = $conn->query($maxUserIdQuery);

    if ($result) {
        $row = $result->fetch_assoc();
        $maxUserId = $row['maxUserId'];
        // Increment the maximum user_ID to generate a new one
        $newUserId = 'u' . str_pad($maxUserId + 1, strlen($maxUserId), '0', STR_PAD_LEFT);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // 獲取用戶輸入的資訊
            $nickname = $_POST["nickname"];
            $email = $_POST["email"];
            $password = $_POST["pwd"];

            $query = "SELECT * From user Where user_Email = '$email' ";
            $result = $conn->query($query);
            if ($result->num_rows <= 0) {
                $putin = "INSERT INTO user (user_ID,user_NickName,user_Email, user_Password) VALUES ('$newUserId','$nickname','$email', '$password')";
                if ($conn->query($putin) == TRUE) {
                    echo "<script>alert('Sign up successful!');</script>";
                    header("Location: login.php");
                    exit();
                } else {
                    echo "<script>alert('Error: " . $putin . "<br>" . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('帳號已註冊');window.location.href='login.php'</script>";
                exit();
            }
        }

        // 關閉資料庫連接
        $conn->close();
    }

    ?>
    <div class="footer">
        <div class="left-footer"><img src='../image/logo-4.png'></div>
        <div class="right-footer">
            <p>Copyright © 2023 搜蒐甜點店 All Rights Reserved</p>
        </div>
    </div>
    <script src="../js/all.js"></script>
</body>

</html>