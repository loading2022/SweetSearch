<?php
require_once 'redis_db.php';
?>
<?php
    $servername = $_ENV['MYSQL_HOST'];
    $username = $_ENV['MYSQL_USERNAME'];
    $password = $_ENV['MYSQL_PASSWORD'];
    $dbname = $_ENV['MYSQL_DATABASE'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
?>
<?php
session_start();

// Redis will automatically expire the session with the TTL
if (!isset($_SESSION['initialized'])) {
    $_SESSION['initialized'] = true;
    // Set a TTL for the session (1800 seconds = 30 minutes
    $redis->expire('PHPREDIS_SESSION:' . session_id(), 1800);
}
?>