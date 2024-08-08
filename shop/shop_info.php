<?php
require_once '../db.php';
require_once '../comment/process_comment.php';
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
    <link rel="stylesheet" type="text/css" href="../css/all.css">
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
        <div class="shop-detail-main">
            <?php
            // 獲取 Shop_ID 參數
            $shopID = isset($_GET['shop_id']) ? $_GET['shop_id'] : '';
            $message=isset($_GET['message']) ? $_GET['message'] : '';
            if($message!=""){
                echo "<script>alert('$message');</script>";
                echo "<script>
                var currentUrl = window.location.pathname + '?shop_id=$shopID';
                history.replaceState({}, document.title, currentUrl);
                </script>";
            }
            
            
            if (isset($_SESSION['nowUser']['user_ID'])) {
                $userID = $_SESSION['nowUser']['user_ID'];
            }
            // 查詢資料
            $sql = "SELECT * FROM shop WHERE shop_ID = '$shopID'";
            $result = $conn->query($sql);
            // 顯示查詢結果
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $shopPhoto=$row['shop_Photo'];
                echo "
                <div class='left-content'>
                    <div class='left-top'>
                    <div class='simple-intro'>
                        <div class='shop-image'>";
                            if($shopPhoto!=''){
                                echo "<img src='$shopPhoto' alt='店面照片'>";
                            }
                            else{
                                echo "<img src='../image/no-image.png' alt='尚無圖片'>";
                            }
                            echo "<p class='shop-name'>" . $row['shop_Name'] . "</p>
                        </div> 
                        <div class='introduce'>
                            <h2>店家資訊</h2>
                            <ul class='shop-info'>
                                <li class='address'><i class='fas fa-map-marker-alt' style='color: #fb1313;margin-right:5px;'></i>" . $row['shop_Address'] . "</li>";

                                if($row['shop_Phone']!=""){
                                    echo "<li class='phone'><i class='fa-solid fa-phone' style='color: #199b08; margin-right:5px;'></i>" . $row['shop_Phone'] . "</li>";
                                }
                                else{
                                    echo "<li class='phone'><i class='fa-solid fa-phone' style='color: #199b08; margin-right:5px;'></i>尚無電話資訊</li>";
                                }
                                echo "<li>";
                                if ($row["shop_ForHere"] == 1) {
                                    echo "<li><i class='fa-solid fa-note-sticky' style=' margin-right:5px;'></i>可內用</li>";
                                }
                                echo "</li>
                                                <li><ul class='shop-contact'>";
                                if ($row["shop_Email"] != "") {
                                    echo "<li class='email'><a href='mailto:" . $row['shop_Email'] . "'><i class='fa-solid fa-envelope' style='color: #021211; margin-right:10px;'></i></a></li>";
                                }
                                if ($row["shop_FB"] != "") {
                                    echo "<li><a href='" . $row['shop_FB'] . "'><i class='fab fa-facebook-square' style='color: #3f76d5;margin-right:10px;'></i></a></li>";
                                }
                                if ($row["shop_IG"] != "") {
                                    echo "<li><a href='" . $row['shop_IG'] . "'><i class='fa-brands fa-instagram' style='color: #fd12e1;' margin-right:10px;></i></a></li>";
                                }
                                if ($row["shop_Website"] != "") {
                                    echo "<li><a href='" . $row['shop_Website'] . "'><i class='fa-solid fa-globe' style='margin-right:10px;margin-left:10px;'></i></a></li>";
                                }
                                echo "</li></ul></div>";
                        echo "</div>";
                echo "
                <table class='opening-time'>
                    <tr>
                        <th colspan='2'>營業時間</th>
                    </tr>";
                $mon_sql = "SELECT * FROM opentime WHERE shop_ID = '$shopID' AND shop_OpenWeek='星期一'";
                $tue_sql = "SELECT * FROM opentime WHERE shop_ID = '$shopID' AND shop_OpenWeek='星期二'";
                $wed_sql = "SELECT * FROM opentime WHERE shop_ID = '$shopID' AND shop_OpenWeek='星期三'";
                $thr_sql = "SELECT * FROM opentime WHERE shop_ID = '$shopID' AND shop_OpenWeek='星期四'";
                $fri_sql = "SELECT * FROM opentime WHERE shop_ID = '$shopID' AND shop_OpenWeek='星期五'";
                $sat_sql = "SELECT * FROM opentime WHERE shop_ID = '$shopID' AND shop_OpenWeek='星期六'";
                $sun_sql = "SELECT * FROM opentime WHERE shop_ID = '$shopID' AND shop_OpenWeek='星期日'";
                
                $mon_result = $conn->query($mon_sql);
                $tue_result = $conn->query($tue_sql);
                $wed_result = $conn->query($wed_sql);
                $thr_result = $conn->query($thr_sql);
                $fri_result = $conn->query($fri_sql);
                $sat_result = $conn->query($sat_sql);
                $sun_result = $conn->query($sun_sql);
                if ($mon_result->num_rows > 0) {
                    $mon_row = $mon_result->fetch_assoc();
                    echo "
                        <tr>
                            <td>星期一</td>
                            <td>".$mon_row['shop_OpenTime']."</td>
                        </tr>";
                }
                if ($tue_result->num_rows > 0) {
                    $tue_row = $tue_result->fetch_assoc();
                    echo "
                        <tr>
                            <td>星期二</td>
                            <td>".$tue_row['shop_OpenTime']."</td>
                        </tr>";
                }
                if ($wed_result->num_rows > 0) {
                    $wed_row = $wed_result->fetch_assoc();
                    echo "
                        <tr>
                            <td>星期三</td>
                            <td>".$wed_row['shop_OpenTime']."</td>
                        </tr>";
                }
                if ($thr_result->num_rows > 0) {
                    $thr_row = $thr_result->fetch_assoc();
                    echo "
                        <tr>
                            <td>星期四</td>
                            <td>".$thr_row['shop_OpenTime']."</td>
                        </tr>";
                }
                if ($fri_result->num_rows > 0) {
                    $fri_row = $fri_result->fetch_assoc();
                    echo "
                        <tr>
                            <td>星期五</td>
                            <td>".$fri_row['shop_OpenTime']."</td>
                        </tr>";
                }
                if ($sat_result->num_rows > 0) {
                    $sat_row = $sat_result->fetch_assoc();
                    echo "
                        <tr>
                            <td>星期六</td>
                            <td>".$sat_row['shop_OpenTime']."</td>
                        </tr>";
                }
                if ($sun_result->num_rows > 0) {
                    $sun_row = $sun_result->fetch_assoc();
                    echo "
                        <tr>
                            <td>星期日</td>
                            <td>".$sun_row['shop_OpenTime']."</td>
                        </tr>";
                }
                echo "</table></div>";
                  
                echo "<ul class='add'>";
                if (isset($_SESSION['nowUser']['user_ID'])) {
                    $favorite_sql = "SELECT * FROM favorite WHERE shop_ID='$shopID' AND user_ID='$userID'";
                    $result_favorite = $conn->query($favorite_sql);
                    $row_favorite = $result_favorite->fetch_assoc();
                    $visited_sql = "SELECT * FROM visited WHERE shop_ID='$shopID' AND user_ID='$userID'";
                    $result_visited = $conn->query($visited_sql);
                    $row_visited = $result_visited->fetch_assoc();
                    if ($row_favorite != null) {
                    echo "<li class='favorite-link' data-shopid=$shopID><a href='../favorite/deleteFavorite.php?id=$shopID'><i class='fa-solid fa-heart'></i>收藏</li>";
                    } else if ($row_favorite == null) {
                        echo "<li class='favorite-link' data-shopid=$shopID><a href='../favorite/addToFavorite.php?id=$shopID'><i class='fa-regular fa-heart'></i>收藏</a></li>";
                    }
                    if ($row_visited != null) {
                        echo "<li class='collect-link'><a href='../gallery/addToGallery.php?id=$shopID'><i class='fa-solid fa-check' aria-hidden='true'></i>圖鑑</a></li>";
                    } else if ($row_visited == null) {
                        echo "<li class='collect-link'><a href='../gallery/addToGallery.php?id=$shopID'><i class='fa fa-plus' aria-hidden='true'></i>圖鑑</a></li>";
                    }
                    if (isset($_SESSION['nowUser']) && $_SESSION['nowUser']['user_Role'] == "manager") {
                        echo "<li class='collect-link'><a href='manager_shop_adjust.php?shop_id=" . $row["shop_ID"] . "'><i class='fa fa-pencil' aria-hidden='true'></i>修改店家</a></li>";
                        echo "<li class='collect-link'><a href='../dessert/manager_dessert_index.php?shop_id=" . $row["shop_ID"] . "'><i class='fa fa-pencil' aria-hidden='true'></i>修改甜點</a></li>";
                    }
                } else {
                    echo "<li class='favorite-link' data-shopid=$shopID><a href='../favorite/addToFavorite.php?id=$shopID'><i class='fa-regular fa-heart'></i>收藏</a></li>
                        <li class='collect-link'><a href='../gallery/addToGallery.php?id=$shopID'><i class='fa fa-plus' aria-hidden='true'></i>圖鑑</a></li>";
                }
                echo "
                    </ul>
                    <h2 class='dessert-title' id='dessert-title'>品項</h2> ";
                // 呼叫品項的標籤
                $type_sql = "SELECT DISTINCT dessert.desstype_ID, desstype_Name FROM dessert, desstype WHERE dessert.desstype_ID=desstype.desstype_ID AND shop_ID='$shopID' ORDER BY dessert.desstype_ID DESC";
                $result_type = $conn->query($type_sql);

                echo "<form id='typeForm' method='POST' action='search-type.php'>"; ?>
                <input type='hidden' name='shop_id' value='<?php echo $row['shop_ID']; ?>'>
                <input type='hidden' id='selectedType' name='selectedType' value=''>
                <ul class='dessert_tab'>
                    <?php
                    echo "<li data-type='全部'>全部</li>";

                    if ($result_type->num_rows > 0) {
                        while ($row_type = $result_type->fetch_assoc()) {
                            echo "<li data-type='" . $row_type['desstype_Name'] . "'>" . $row_type['desstype_Name'] . "</li>";
                        }
                    }
                    //顯示所有品項 
                    echo "</ul>
                </form>";
                    if (isset($_SESSION['searchTypeResultHTML'])) {
                        echo $_SESSION['searchTypeResultHTML'];
                        // 清除 SESSION 中的搜尋結果，以避免重複顯示
                        unset($_SESSION['searchTypeResultHTML']);
                    } else {
                        $dessert_sql = "SELECT * FROM dessert,desstype WHERE dessert.desstype_ID=desstype.desstype_ID AND shop_ID = '$shopID'";
                        $dessert_result = $conn->query($dessert_sql);
                        // $all_dessert_sql = "SELECT * FROM dessert WHERE  shop_ID = '$shopID'";
                        // $all_dessert_result = $conn->query($all_dessert_sql);
                        echo "<ul class='dessert-list' id='dessert-list'>";
                        if ($dessert_result->num_rows > 0) {
                            while ($dessert_row = $dessert_result->fetch_assoc()) {
                                echo "<li class='dessert'>
                                    <h3>" . $dessert_row['dess_Name'] . "</h3>
                                    <p>$" . $dessert_row['dess_Price'] . "</p>
                                </li>";
                            }
                        } else {
                            echo "
                            <li class='no-dessert'>
                                <p>暫無任何品項</p>
                            </li>";
                        }
                        echo "</ul>";
                    }
                    echo "</div>
                <div class='right-content'>";
                    if (isset($_SESSION['nowUser'])) {
                        $userID = $_SESSION['nowUser']['user_ID'];
                        $commentornot_sql = "SELECT shop_ID,user_ID,com_Content,com_Rating FROM comment WHERE shop_ID='$shopID' AND user_ID='$userID'";
                        //$editComment_sql = "UPDATE comment SET com_Content = 'TINA',com_Rating= WHERE shop_ID = $shopID AND user_ID=$userID";
                        $result_commentornot = $conn->query($commentornot_sql);
                    }
                    $rating_sql = "SELECT shop_ID,AVG(com_Rating) AS Rating_avg FROM comment WHERE shop_ID='$shopID' GROUP BY shop_ID";
                    $result_rating = $conn->query($rating_sql);

                    if ($result_rating->num_rows > 0) {
                        $row_rating = $result_rating->fetch_assoc();
                        echo "<div class='comment-nav'>
                        <p class='avg'>" . round($row_rating['Rating_avg'], 1) . " 顆星</p>";
                        if (isset($_SESSION['nowUser'])) {
                            if ($result_commentornot->num_rows > 0) {
                                $row_comment_edit = $result_commentornot->fetch_assoc();
                                echo "<div class='write-comment'><i class='fa-solid fa-pen'></i><input type='submit' name='edit-comment-button' class='edit-comment-button' value='修改評論' onclick='editCommentForm()'></div>";
                            } else {
                                echo "<div class='write-comment'><i class='fa-solid fa-pen'></i><input type='submit' name='write-comment-button' class='write-comment-button' value='撰寫評論' onclick='openCommentForm()'></div>";
                            }
                        }
                        echo "
                    </div>";
                    } else {
                        echo "<div class='comment-nav'>
                    <p class='avg'>暫無評論</p>";
                        if (isset($_SESSION['nowUser'])) {
                            if ($result_commentornot->num_rows > 0) {
                                $row_comment_edit = $result_commentornot->fetch_assoc();
                                echo "<div class='write-comment'><i class='fa-solid fa-pen'></i><input type='submit' name='edit-comment-button' class='edit-comment-button' value='修改評論' onclick='editCommentForm()'> </div>";
                            } else {
                                echo "<div class='write-comment'><i class='fa-solid fa-pen'></i><input type='submit' name='write-comment-button' class='write-comment-button' value='撰寫評論' onclick='openCommentForm()'> </div>";
                            }
                        }
                        echo "
                    </div>";
                    }
            }
            ?>
                <!-- 彈出的評論表單模態視窗(新增) -->
                <div id="commentFormOverlay" class="overlay">
                    <div class="comment-form">
                        <p class='comment-user'></p>
                        <form action="../comment/process_comment.php" method="POST">
                            <input type="hidden" name="shop_id" value="<?php echo $row['shop_ID']; ?>">
                            <ul class='comment-rating'>
                                <span>極差</span>
                                <li class='rating-number' onclick="setRating(1)"><i class="fa-solid fa-1"></i></li>
                                <li class='rating-number' onclick="setRating(2)"><i class="fa-solid fa-2"></i></li>
                                <li class='rating-number' onclick="setRating(3)"><i class="fa-solid fa-3"></i></li>
                                <li class='rating-number' onclick="setRating(4)"><i class="fa-solid fa-4"></i></li>
                                <li class='rating-number' onclick="setRating(5)"><i class="fa-solid fa-5"></i></li>
                                <span>非常好</span>
                            </ul>
                            <input type="hidden" id="selectedRating" name="selected_rating" value="" required>
                            <span class="validation-message">請選擇評分</span>
                            <textarea class='textarea-content' placeholder='請輸入你的想法...'
                                name="comment-content"></textarea>
                            <input type="submit" value="提交評論" class='comment-submit'>
                        </form>
                        <button class='comment-form-close' onclick='closeCommentForm()'>&times;</button>
                    </div>
                </div>
                <!-- 彈出的評論表單模態視窗(修改) -->
                <div id="commentEditFormOverlay" class="Editoverlay">
                    <div class="comment-form">
                        <form action="../comment/update_comment.php" method="POST">
                            <input type="hidden" name="shop_id" value="<?php echo $row['shop_ID']; ?>">
                            <?php echo "
                        <ul class='comment-rating'>
                            <span>極差</span>";
                            for ($i = 1; $i <= 5; $i++) {
                                if ($row_comment_edit["com_Rating"] == $i) {
                                    echo "<li class='rating-number active' data-rating=$i onclick='setEditRating($i)'><i class='fa-solid fa-$i'></i></li>";
                                } else {
                                    echo "<li class='rating-number' data-rating=$i onclick='setEditRating($i)'><i class='fa-solid fa-$i'></i></li>";
                                }
                            }
                            echo " <span>非常好</span>
                        </ul>";
                            ?>
                            <input type="hidden" id="selectedRating" name="selected_edit_rating"
                                value="<?php echo $row_comment_edit["com_Rating"]; ?>">
                            <textarea class='textarea-content'
                                name="comment-content"><?php echo $row_comment_edit["com_Content"]; ?></textarea>
                            <div class="revise-button">
                                <input type="submit" value="更新評論" class='comment-submit' name='comment-update'>
                                <button class="comment-delete" name="comment-delete" type="button"
                                    onclick="deletionAlert('<?php echo $row['shop_ID']; ?>')">
                                    <i class="fa-solid fa-trash-can"></i>刪除評論
                                </button>
                            </div>
                        </form>

                        <button class='comment-form-close' onclick='closeEditCommentForm()'>&times;</button>
                    </div>
                </div>

                <div class="comment-search">
                    <form action="../comment/search_comment.php" method="POST">
                        <input type="hidden" name="shop_id" value="<?php echo $row['shop_ID']; ?>">
                        <input type="text" placeholder="請輸入查詢評論關鍵字" name="comment-keyword" class="comment-search-bar">
                        <select class="comment-rating-choice" name="comment-rating-choice">
                            <option value="全部">全部</option>
                            <option value="5">5 星</option>
                            <option value="4">4 星</option>
                            <option value="3">3 星</option>
                            <option value="2">2 星</option>
                            <option value="1">1 星</option>
                        </select>
                        <i class="fa-solid fa-magnifying-glass" onclick="submitForm()"></i>
                    </form>
                </div>
                <?php
                if (isset($_SESSION['searchResultHTML'])) {
                    echo $_SESSION['searchResultHTML'];
                    // 清除 SESSION 中的搜尋結果，以避免重複顯示
                    unset($_SESSION['searchResultHTML']);
                } else {
                    $comment_sql = "SELECT comment.*,user.* FROM comment,user WHERE shop_ID='$shopID' AND comment.user_ID=user.user_ID";
                    $result_comment = $conn->query($comment_sql);
                    echo "<ul class='comment-list'>";
                    if ($result_comment->num_rows > 0) {
                        while ($row_comment = $result_comment->fetch_assoc()) {
                            echo "
                            <li class='comment'>
                                <div class='comment-user'>
                                    <p>" . $row_comment['user_NickName'] . "</p>
                                    <p>" . $row_comment['com_Rating'] . "</p>
                                </div>
                                <p>" . $row_comment['com_Content'] . "</p>
                            </li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<li class='comment'>暫無評論</li>";
                    }
                }
                // 關閉數據庫連接
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
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="../js/swiper.js"></script>
    <script src="../js/all.js"></script>
</body>

</html>