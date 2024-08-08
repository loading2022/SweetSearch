<?php
    require_once('../db.php'); // 引入資料庫連線
    session_start();
?>
<?php
    if (isset($_SESSION["nowUser"]) && !empty($_SESSION["nowUser"])) {
        $nowUser = $_SESSION["nowUser"];
        $showUser = $nowUser['user_ID'];
            
            $deleteSql = "DELETE FROM user WHERE user_ID='$showUser'";
            
            if ($conn->query($deleteSql) == TRUE) {
                session_unset();
                session_destroy();
                header("Location: ../index.php");
                exit();
            }
            else 
            {
                echo "錯誤：" . $conn->error;
            }
    }
?>
