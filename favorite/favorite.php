<?php
require_once '../db.php';
//session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搜蒐甜點店</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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
                    echo '<li class="nav-content"><a href="favorite.php?userid=' . $_SESSION['nowUser']['user_ID'] . '">收藏</a></li>';
                    echo '<li class="nav-content"><a href="../gallery/gallery.php?userid=' . $_SESSION['nowUser']['user_ID'] . '">圖鑑</a></li>';
                    echo '<li class="nav-content"><a href="../user/user_info.php?userid=' . $_SESSION['nowUser']['user_ID'] . '"><i class="fa-solid fa-user"></i></a></li>';
                } else {
                    // 使用者未登入
                    echo '<li class="nav-content hide"><a href="#">收藏</a></li>';
                    echo '<li class="nav-content hide"><a href="#">圖鑑</a></li>';
                    echo '<li class="nav-content"><a href="../user/signup.php"><i class="fa-solid fa-user"></i></a></li>';
                }
                ?>

            </ul>
        </div>
        <div class="favorite-page">
            <form action="favorite-type.php" method="POST" class="favorite-type">
                <h2>搜尋</h2>
                <input type="text" placeholder="輸入想查詢的店家名" class="favorite-search-bar" name="favorite-search-bar">
                <input type="submit" value="搜尋" class="favorite-search-button">
            </form>
            <div class="favorite-main">
                <h2>收藏清單</h2>
                <?php
                if (isset($_SESSION['favorite-title'])) {
                    echo $_SESSION['favorite-title'];
                    // 清除 SESSION 中的搜尋結果，以避免重複顯示
                    unset($_SESSION['favorite-title']);
                } else {
                    $userID = $_SESSION['nowUser']['user_ID'];
                    $sql = "SELECT * FROM shop WHERE shop_ID IN(SELECT shop_ID FROM favorite WHERE user_ID='$userID')";
                    $result = $conn->query($sql);
                    $numRows = mysqli_num_rows($result);
                    echo "<p>$numRows 間店</p>";
                }
                ?>
                <ul class="favorite-shop">
                    <?php
                    if (isset($_SESSION["nowUser"]) && !empty($_SESSION["nowUser"])) {
                        $nowUser = $_SESSION["nowUser"];
                        $showUser = $nowUser['user_ID'];

                        if (isset($_SESSION['favorite-result'])) {
                            echo $_SESSION['favorite-result'];
                            // 清除 SESSION 中的搜尋結果，以避免重複顯示
                            unset($_SESSION['favorite-result']);
                        } else {
                            $sql = "SELECT * FROM favorite,shop WHERE favorite.shop_ID=shop.shop_ID AND user_ID='$showUser'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $shopID = $row["shop_ID"];
                                    echo "<li class='favorite-shop-detail'>";
                                    if($row['shop_Photo']!=""){
                                        echo "<img src='".$row['shop_Photo']."' alt='".$row['shop_Name']."'>";
                                        
                                    }
                                    else{
                                        echo "<img src='../image/no-image.png' alt='尚無店家圖片'>";
                                    }
                                
                                echo "<div class='favorite-shop-content'>
                                    <h2><a href='../shop/shop_info.php?shop_id=$shopID'>" . $row["shop_Name"] . "</a></h2>
                                    <p><i class='fas fa-map-marker-alt' style='color: #ea0b43;margin-right:10px;'></i>" . $row["shop_Address"] . "</p>
                                    <p><i class='fa-solid fa-phone' style='color: #21e448;margin-right:10px;'></i>" . $row["shop_Phone"] . "</p>
                                    <a class='heart' onclick='deletionFavorite(\"$shopID\")'>
                                        <i class='fa-solid fa-heart' style='color: #f10937;'></i>&nbsp;已收藏
                                    </a>
                                </div>
                            </li>";
                                }
                            } else {
                                echo "尚無收藏清單";
                            }
                        }
                    } else {
                        echo "目前尚無收藏";
                    }
                    $conn->close();
                    ?>
                </ul>
            </div>
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