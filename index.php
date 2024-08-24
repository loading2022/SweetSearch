<?php
require_once 'db.php';
//session_start();
require_once 'redis_db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搜蒐甜點店</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./css/all.css">
    <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script>
        window.onload = function() {
            var noVisitedCheckbox = document.getElementById('no-visited-input');
            var fourStarCheckbox = document.getElementById('four-star-input');
            noVisitedCheckbox.checked = false;
            fourStarCheckbox.checked = false; 
        };
    </script>

</head>

<body>
    <div class="wrap">
        <div class="banner">
            <div class="navbar-index">
                <?php
                if (isset($_SESSION['nowUser']) && $_SESSION['nowUser']['user_Role'] == "manager") {
                    echo "<h1 class='logo'><a href='manager_index.php'>搜蒐甜點店</a></h1>";
                } else {
                    echo "<h1 class='logo'><a href='index.php'>搜蒐甜點店</a></h1>";
                }
                ?>
                <form action="./shop/select.php" method="GET" class="search-section">
                    <input type="button" name="zone-choice" id="index-zone-choice" value="選擇地區">
                    <ul class="zone-choice-dropdown">
                        <?php 
                            $zoneSql="SELECT DISTINCT(SUBSTRING(shop_Address, LOCATE('桃園市', shop_Address) + CHAR_LENGTH('桃園市'), 3)) AS district FROM shop HAVING district LIKE '%區'";
                            $zone_result = $conn->query($zoneSql);
                            if ($zone_result->num_rows > 0) {
                                while ($zone_row = $zone_result->fetch_assoc()) {
                                    echo "<li class='zone'><a href='./shop/select.php?zone-choice=".$zone_row['district']."'>".$zone_row['district']."</a></li>";
                                }
                            }
                        ?>
                        
                    </ul>
                    <input type="button" name="style-choice" id="index-style-choice" value="選擇種類">
                    <ul class="style-choice-dropdown">
                        <?php //更改處
                        $sql = "SELECT COUNT(desstype.desstype_ID) AS COUNT, desstype_Name,desstype.desstype_ID FROM dessert,desstype
                        WHERE desstype.desstype_ID=dessert.desstype_ID GROUP BY desstype_Name,desstype.desstype_ID HAVING desstype_Name!='其他' ORDER BY COUNT DESC LIMIT 9";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Shop is visited
                                echo "<li class='style' name='dess-type' id='dess-type'><a href='./shop/select.php?style-choice=" . $row['desstype_Name'] . "' id='dess-type' >" . $row['desstype_Name'] . "</a></li>";
                            }
                        }
                        echo "<li class='style' name='dess-type' id='dess-type'><a href='./shop/select.php?style-choice=其他' id='dess-type' >其他</a></li>";
                        ?>
                    </ul>
                    <input type="text" placeholder="請輸入店名或甜點名稱關鍵字" name="keyword" class="search-bar">
                    <div class="search-choice">
                        <?php
                        if (isset($_SESSION['nowUser'])) {
                            echo "<input type='checkbox' name='no-visited' id='no-visited-input' >
                        <label for='no-visited-input' id='no-visited-label'>不看去過的店家</label>";
                        }
                        ?>
                        <input type='checkbox' name='four-star' id='four-star-input'>
                        <label for='four-star-input' id='four-star-label'>四星以上</label>
                        <input type="submit" value="搜尋" class="search-button">
                    </div>
                </form>
                <ul class="nav">
                    <?php
                    if (isset($_SESSION['nowUser'])) {
                        // 使用者已登入，顯示收藏和圖鑑
                        if ($_SESSION['nowUser']['user_Role'] == "manager") {
                            echo '<li class="nav-content"><a href="./dessType/manager_dessType_Index.php">管理甜點種類</a></li>';
                            echo '<li class="nav-content"><a href="manager_index.php">管理店家</a></li>';
                        }
                        echo '<li class="nav-content-index"><a href="./favorite/favorite.php?userid=' . $_SESSION['nowUser']['user_ID'] . '">收藏</a></li>';
                        echo '<li class="nav-content-index"><a href="./gallery/gallery.php?userid=' . $_SESSION['nowUser']['user_ID'] . '">圖鑑</a></li>';
                        echo '<li class="nav-content-index"><a href="./user/user_info.php?userid=' . $_SESSION['nowUser']['user_ID'] . '"><i class="fa-solid fa-user"></i></a></li>';
                    } else {
                        // 使用者未登入
                        echo '<li class="nav-content-index hide"><a href="#">收藏</a></li>';
                        echo '<li class="nav-content-index hide"><a href="#">圖鑑</a></li>';
                        echo '<li class="nav-content-index"><a href="./user/signup.php"><i class="fa-solid fa-user"></i></a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="main">
            <h2 class="title">最多人收藏的店</h2>
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php
                    $sql = "SELECT shop.*,COUNT(favorite.user_ID) FROM shop JOIN favorite ON shop.shop_ID = favorite.shop_ID GROUP BY shop.shop_ID ORDER BY COUNT(favorite.user_ID) DESC LIMIT 6";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $image_path=str_replace("..", ".", $row["shop_Photo"]);
                        if($row['shop_Photo']!=""){
                            echo "<div class='swiper-slide'><a href='./shop/shop_info.php?shop_id=" . $row["shop_ID"] . "'><img src='$image_path'><p>" . $row['shop_Name'] . "</p>";
                        }
                        else{
                            echo "<div class='swiper-slide'><a href='./shop/shop_info.php?shop_id=" . $row["shop_ID"] . "'><img src='./image/no-image.png'><p>" . $row['shop_Name'] . "</p>";
                        }
                        echo "<p><i class='fas fa-map-marker-alt' style='color: #f91a25;'></i>" . $row['shop_Address'] . "</p></a></div>";
                    }
                    ?>
                </div>
                <div class="swiper-button-prev"><i class="fa-solid fa-circle-chevron-left"></i></div>
                <div class="swiper-button-next"><i class="fa-solid fa-circle-chevron-right"></i></div>
            </div>

            <h2 class="title">熱門甜點店</h2>
            <div class="swiper">
                <div class="swiper-wrapper">
                    
                    <?php
                    $sql = "SELECT shop.*,COUNT(visited.user_ID) FROM shop JOIN visited ON shop.shop_ID = visited.shop_ID GROUP BY shop.shop_ID ORDER BY COUNT(visited.user_ID) DESC LIMIT 6";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $image_path=str_replace("..", ".", $row["shop_Photo"]);
                        if($row['shop_Photo']!=""){
                            echo "<div class='swiper-slide'><a href='./shop/shop_info.php?shop_id=" . $row["shop_ID"] . "'><img src='$image_path'><p>" . $row['shop_Name'] . "</p>";
                        }
                       else{
                        echo "<div class='swiper-slide'><a href='./shop/shop_info.php?shop_id=" . $row["shop_ID"] . "'><img src='./image/no-image.png'><p>" . $row['shop_Name'] . "</p>";
                       }
                        echo "<p><i class='fas fa-map-marker-alt' style='color: #f91a25;'></i>" . $row['shop_Address'] . "</p></a></div>";
                    }
                    ?>
                </div>
                <div class="swiper-button-prev"><i class="fa-solid fa-circle-chevron-left"></i></div>
                <div class="swiper-button-next"><i class="fa-solid fa-circle-chevron-right"></i></div>
            </div>
        </div>

    </div>
    <div class="footer">
        <div class="left-footer"><img src='./image/logo-4.png'></div>
        <div class="right-footer">
            <p>Copyright © 2023 搜蒐甜點店 All Rights Reserved</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="./js/swiper.js"></script>
    <script src="./js/all.js"></script>
</body>

</html>