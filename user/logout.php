<?php
require_once '../redis_db.php';
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['nowUser'])) {
    session_unset();
    session_destroy();
}

// Redirect to the login page
header("Location: ../index.php");
exit();
?>
