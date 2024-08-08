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
            <form action="../shop/select.php" method="GET" class="search-section">
                <input type="button" name="zone-choice" id="index-zone-choice" value="選擇地區">
                <ul class="zone-choice-dropdown">
                        <?php 
                            $zoneSql="SELECT DISTINCT(SUBSTRING(shop_Address, LOCATE('桃園市', shop_Address) + CHAR_LENGTH('桃園市'), 3)) AS district FROM shop HAVING district LIKE '%區'";
                            $zone_result = $conn->query($zoneSql);
                            if ($zone_result->num_rows > 0) {
                                while ($zone_row = $zone_result->fetch_assoc()) {
                                    echo "<li class='zone'><a href='../shop/select.php?zone-choice=".$zone_row['district']."'>".$zone_row['district']."</a></li>";
                                }
                            }
                        ?>
                        
                    </ul>
                <input type="button" name="style-choice" id="index-style-choice" value="選擇種類">
                <ul class="style-choice-dropdown">
                    <?php //更改處
                    $sql = "SELECT * FROM desstype";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Shop is visited
                            echo "<li class='style' name='dess-type' id='dess-type'><a href='../shop/select.php?type=" . $row['desstype_Name'] . "' id='dess-type' >" . $row['desstype_Name'] . "</a></li>";
                        }
                    }
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
                        echo '<li class="nav-content"><a href="../dessType/manager_dessType_Index.php">管理甜點種類</a></li>';
                        echo '<li class="nav-content"><a href="../manager_index.php">管理店家</a></li>';
                    }
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
            <div class='shop-list'>
                <?php
                // 列出所有甜點
                $shopID = isset($_GET['shop_id']) ? $_GET['shop_id'] : '';
                $sql = "SELECT shop_Name FROM shop WHERE shop_ID='$shopID'";
                $name_result = $conn->query($sql);
                $name = $name_result->fetch_assoc();
                echo "<h2 class='dessert-title' id='dessert-title'>" . $name["shop_Name"] . "的甜點</h2><hr style='border: 2px dashed #5B2B1E;'><br>";
                $sql = "SELECT d.shop_ID, d.dess_ID, d.dess_Name, d.dess_Price, dt.desstype_Name
            FROM dessert d
            JOIN desstype dt ON d.desstype_ID = dt.desstype_ID
            WHERE d.shop_ID = '$shopID'
            ORDER BY d.dess_ID";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // 顯示資料表格
                    echo "<table id='dessert-table'><tr><th>ID</th><th>名稱</th><th>價格</th><th>類型</th><th></th><th></th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        $changed_shop_ID = $row["shop_ID"];
                        $changed_dess_ID = $row["dess_ID"];
                        echo "<tr id='row_{$changed_shop_ID}_{$changed_dess_ID}'>";
                        echo "<td>" . $row["dess_ID"] . "</td>";
                        echo "<td class='text-element'>" . $row["dess_Name"] . "</td>";
                        echo "<td class='text-element'>" . $row["dess_Price"] . "</td>";
                        echo "<td class='text-element'>" . $row["desstype_Name"] . "</td>";
                        echo "<td><button class='search-button modify-button' onclick=\"modifyRow('$changed_shop_ID', '$changed_dess_ID')\">修改</button></td>";
                        echo "<td><button type='submit' onclick=\"deletionDess('$changed_shop_ID','$changed_dess_ID')\" class='delete-button'>刪除</button></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<button onclick=\"addRow('$shopID')\" class='add-button'>新增</button>";

                } else {
                    echo "<div class='noDess'>0 筆結果</div><br>";
                    echo "<table id='dessert-table'><tr><th>ID</th><th>名稱</th><th>價格</th><th>類型</th><th></th><th></th></tr>";
                    echo "</table>";
                    echo "<button onclick=\"addRow('$shopID')\" class='add-button'>新增</button>";
                }
                echo "<div style='display: flex;justify-content: flex-end;'><a href='../manager_index.php'><button class='add-button' >回到目錄</button></a>";
                echo "<a href='../shop/shop_info.php?shop_id=$shopID'><button class='add-button'>前往店家</button></a></div>";
                $conn->close();
                ?>
            </div>
        </div>
    </div>
    </div>
    <div class="footer" >
        <div class="left-footer"><img src='../image/logo-4.png'></div>
        <div class="right-footer">
            <p>Copyright © 2023 搜蒐甜點店 All Rights Reserved</p>
        </div>
    </div>
</body>

</html>
