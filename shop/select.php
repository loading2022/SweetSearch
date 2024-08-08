<?php
// 載入db.php來連結資料庫
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/all.css">
    
</head>
<body>
    <div class="wrap">
        <div class="navbar">
            <h1 class="logo"><a href="../index.php">搜蒐甜點店</a></h1>
            <ul class="nav">
            <?php
                    if (isset($_SESSION['nowUser'])) {
                        if ($_SESSION['nowUser']['user_Role'] == "manager") {
                            echo '<li class="nav-content"><a href="../dessType/manager_dessType_Index.php">管理甜點種類</a></li>';
                            echo '<li class="nav-content"><a href="../manager_index.php">管理店家</a></li>';
                        }
                        // 使用者已登入，顯示收藏和圖鑑
                        echo '<li class="nav-content"><a href="../favorite/favorite.php?userid=' . $_SESSION['nowUser']['user_ID'] . '">收藏</a></li>';
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
        <div class="main">
        <form action="select.php" method="GET" autocomplete="off">
            <div class="select-search-section">
                <input type="text" placeholder="請輸入店名或甜點關鍵字" name="keyword" class="select-search-bar">
                <select class="zone-choice" name="zone-choice">
                    <option value="all">全部</option>
                    <?php
                        $zoneSql="SELECT DISTINCT(SUBSTRING(shop_Address, LOCATE('桃園市', shop_Address) + CHAR_LENGTH('桃園市'), 3)) AS district FROM shop HAVING district LIKE '%區'";
                        $zone_result = $conn->query($zoneSql);
                        if ($zone_result->num_rows > 0) {
                            while ($zone_row = $zone_result->fetch_assoc()) {
                                echo "<option value='".$zone_row['district']."'>".$zone_row['district']."</option>";
                            }
                        }
                    ?>
                </select>
                <select class="style-choice" name="style-choice">
                    <option value='all'>全部</option>
                <?php //更改處
                    $sql = "
                    SELECT *
                    FROM desstype";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()){
                            echo "<option value='".$row['desstype_Name']."'>".$row['desstype_Name']."</option>";
                        }}
                ?>
                </select>
                <?php
                if (isset($_SESSION['nowUser'])) {
                    echo "<input type='checkbox' name='no-visited' id='no-visited'>
                    <label for='no-visited'>不看去過的店家</label>";
                }
                ?>
                <div>
                    <input type="checkbox" name="four-star" id="four-star">
                    <label for="four-star">4 星以上</label>
                </div>
                <input type="submit" value="搜尋" class="search-button">
            </div>
        </form>
        <div id="search-result">
        <?php
                $searchTerm=array();
                $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
                $zone = isset($_GET['zone-choice']) ? $_GET['zone-choice'] :'all';
                $style= isset($_GET['style-choice']) ? $_GET['style-choice'] :'all';
                if (isset($_SESSION['nowUser']['user_ID'])) {
                    $userID = $_SESSION['nowUser']['user_ID'];
                }
                $noVisited = isset($_GET['no-visited']) ? $_GET['no-visited'] : false;
                $fourStar = isset($_GET['four-star']) ? $_GET['four-star'] : false;
            
                $sql = "SELECT DISTINCT shop.shop_ID, shop_Name, shop_Address,shop_Phone, shop_Photo,AVG(com_Rating) AS Rating_avg
                FROM shop 
                LEFT JOIN dessert ON shop.shop_ID = dessert.shop_ID
                LEFT JOIN desstype ON dessert.desstype_ID=desstype.desstype_ID
                LEFT JOIN comment ON shop.shop_ID=comment.shop_ID";
                $sql .= " WHERE 1=1";  // To always have a valid condition to append
                
                if ($keyword !== null) {
                    $sql .= " AND (shop_Name LIKE '%$keyword%' OR dess_Name LIKE '%$keyword%')";
                }
                if ($zone !== 'all') {
                    $sql .= " AND shop_Address LIKE '%$zone%'";
                }
                if ($style !== "all") {
                    $sql .= " AND desstype_Name='$style'";
                }
                if ($noVisited) {
                    $sql .= " AND shop.shop_ID NOT IN (SELECT shop_ID FROM visited WHERE user_ID='$userID')";
                    $noVisited=true;
                }
                if (!$noVisited) {
                    // 如果 $_GET['no-visited'] 不存在，將 $noVisited 設置為 false
                    $noVisited = false;
                }
                if ($fourStar) {
                    $sql .= " AND shop.shop_ID IN (SELECT shop_ID FROM comment GROUP BY shop_ID HAVING AVG(com_Rating) >= 4)";
                }
                $sql.="GROUP BY shop.shop_ID ORDER BY Rating_avg DESC";
                $result = $conn->query($sql);
                
                // 顯示查詢結果
                echo "<ul class='shop-list'>";
                if ($result->num_rows > 0) {
                    $data_nums = mysqli_num_rows($result); //統計總比數
                    $per = 5; //每頁顯示項目數量
                    $pages = ceil($data_nums/$per); //取得不小於值的下一個整數
                    if (!isset($_GET["page"])){ //假如$_GET["page"]未設置
                        $page=1; //則在此設定起始頁數
                    } else {
                        $page = intval($_GET["page"]); //確認頁數只能夠是數值資料
                    }
                    $start = ($page-1)*$per; //每一頁開始的資料序號
                    $result = $conn->query($sql.' LIMIT '.$start.', '.$per) or die("Error: " . $conn->error);
                while ($row = mysqli_fetch_array ($result)) {
                    $shopID=$row["shop_ID"];
                    $rating_sql="SELECT shop_ID,AVG(com_Rating) AS Rating_avg FROM comment WHERE shop_ID='$shopID' GROUP BY shop_ID";
                    $result_rating=$conn->query($rating_sql);
                    
                    $shopName= $row["shop_Name"];
                    $shopPhoto=$row["shop_Photo"];
                    echo"
                        <li>
                        <div>";
                        if($shopPhoto!=""){
                            echo "<img src='$shopPhoto' alt=$shopName>";
                        }
                        else{
                            echo "<img src='../image/no-image.png' alt=$shopName>";
                        }
                        echo "
                        <div class='shop-list-content'>
                                <h2>$shopName</h2>";
                            if($row['shop_Phone']!=''){
                                echo "<p><i class='fa-solid fa-phone' style='color: #199b08; margin-right:5px;'></i>".$row['shop_Phone']."</p>";
                            }
                            else{
                                echo "<p><i class='fa-solid fa-phone' style='color: #199b08; margin-right:5px;'></i>尚無電話資訊</p>";
                            }
                    
                            echo "<p><i class='fas fa-map-marker-alt' style='color: #fb1313;margin-right:5px;'></i>".$row['shop_Address']."</p>
                            <p>";
                            $tagSql="SELECT COUNT(desstype_Name) AS SumofType,desstype_Name FROM dessert,desstype,shop
                            WHERE dessert.desstype_ID=desstype.desstype_ID  AND shop.shop_ID=dessert.shop_ID AND shop.shop_ID='$shopID'
                            GROUP BY desstype_Name HAVING desstype_Name != '其他' ORDER BY SumofType DESC LIMIT 3";
                            $result_tag=$conn->query($tagSql);
                            if($result_tag->num_rows>0){
                                while($row_tag = $result_tag->fetch_assoc())
                                {
                                    echo  "<i class='fa-solid fa-tags' style='color: #FF9B8F;margin:5px;'></i>".$row_tag['desstype_Name']."";
                                }
                            }    
                            echo "</p>
                            <a href='shop_info.php?shop_id=" . $row["shop_ID"] . "'><input type='submit' value='查看詳細資訊' name='shop-detail-button' class='shop-detail-button'></a>
                        </div></div>
                        <p>";
                        if($result_rating->num_rows > 0) {
                            $row_rating = $result_rating->fetch_assoc();
                            echo round($row_rating['Rating_avg'],1). "<i class='fa-solid fa-star' style='color:#ffd250;'></i>";
                        };
                        echo "</p></li>";
                    }
                } else {
                        echo "<script>alert('没有找到匹配的结果'); window.location.href='select.php';</script>";
                        exit();
                }
                echo "</ul>";
                
                // Pagination
                echo '<div class="pagination">';
                echo "<a href='?keyword=$keyword&zone-choice=$zone&style-choice=$style&page=1&no-visited=$noVisited&four-star=$fourStar'><</a>";
                for ($i = 1; $i <= $pages; $i++) {
                    if ($page - 3 < $i && $i < $page + 3) {
                        if ($i == $page) {
                            echo "<a class='active' href='?keyword=$keyword&zone-choice=$zone&style-choice=$style&page=".$i."&no-visited=$noVisited&four-star=$fourStar'>".$i."</a> ";
                        } else {
                            echo "<a href='?keyword=$keyword&zone-choice=$zone&style-choice=$style&page=".$i."&no-visited=$noVisited&four-star=$fourStar'>".$i."</a> ";
                        }
                    }
                }
                echo "<a href='?keyword=$keyword&zone-choice=$zone&style-choice=$style&page=".$pages."&no-visited=$noVisited&four-star=$fourStar'>></a><br /><br />";
                echo '</div>';

            // 關閉資料庫連接
            $conn->close();
            ?>
        </div>
        </div>
    </div>
    <div class="footer">
        <div class="left-footer"><img src='../image/logo-4.png'></div>
        <div class="right-footer">
            <p>Copyright © 2023 搜蒐甜點店 All Rights Reserved</p>
        </div>
    </div>
</body>
</html>