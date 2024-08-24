<?php
// 載入db.php來連結資料庫
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="./js/all.js"></script>
    <link rel="stylesheet" href="./css/all.css">
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
        <div class="manager-main">
            <?php
            $message=isset($_GET['message']) ? $_GET['message'] : '';
            if($message!=""){
                echo "<script>alert('$message');</script>";
                echo "<script>
                history.replaceState({}, document.title, window.location.pathname);
                </script>";
            }
            // 列出所有店家
            echo "<p>店家總覽 <a href='./shop/create_shop.php'><button>新增店家</button></a></p>";
            $sql = "SELECT shop_ID, shop_Name FROM shop";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $data_nums = mysqli_num_rows($result); //統計總比數
                $per = 10; //每頁顯示項目數量
                $pages = ceil($data_nums/$per); //取得不小於值的下一個整數
                if (!isset($_GET["page"])){ //假如$_GET["page"]未設置
                    $page=1; //則在此設定起始頁數
                } else {
                    $page = intval($_GET["page"]); //確認頁數只能夠是數值資料
                }
                $start = ($page-1)*$per; //每一頁開始的資料序號
                $result = $conn->query($sql.' LIMIT '.$start.', '.$per) or die("Error: " . $conn->error);
                // 顯示資料表格
                echo "<table><tr><th>店家ID</th><th>店家名稱</th><th> </th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["shop_ID"] . "</td>";
                    echo "<td>" . $row["shop_Name"] . "</td>";
                    echo "<td><a href='./shop/shop_info.php?shop_id=" . $row["shop_ID"] . "'><button>查看</button></a></td>";
                    echo "<td><a href='./shop/manager_shop_adjust.php?shop_id=" . $row["shop_ID"] . "'><button>修改</button></a></td>";
                    $delete_ID = $row["shop_ID"];
                    echo "<script>function deletionShop(shopID){
                        if (confirm('確定要刪除此店家嗎？')) {
                          window.location.href = './shop/delete_shop.php?id=' + shopID;
                        } else {
                          window.location.href = 'manager_index.php';
                        }
                      }</script>";
                    echo "<td><button type='submit' onclick=\"deletionShop('$delete_ID')\">刪除</button></td>";
                    // echo "<td><button type='submit' onclick='deletionShop($delete_ID)'>刪除2</button></td>";
                    echo "<td><a href='./dessert/manager_dessert_index.php?shop_id=" . $row["shop_ID"] . "'><button class='search-button' >查看/管理該店家甜點</button></a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "0 筆結果";
            }
            // Pagination
            echo '<div class="pagination">';
            echo "<a href='?page=1'><</a>";
            for ($i = 1; $i <= $pages; $i++) {
                if ($page - 3 < $i && $i < $page + 3) {
                    if ($i == $page) {
                        echo "<a class='active' href='?page=".$i."'>".$i."</a> ";
                    } else {
                        echo "<a href='?page=".$i."'>".$i."</a> ";
                    }
                }
            }
            echo "<a href='?page=".$pages."'>></a><br /><br />";
            echo '</div>';
            $conn->close();
            ?>
        </div>
    </div>
    <div class="footer">
        <div class="left-footer"><img src='./image/logo-4.png'></div>
        <div class="right-footer">
            <p>Copyright © 2023 搜蒐甜點店 All Rights Reserved</p>
        </div>
    </div>
</body>

</html>
