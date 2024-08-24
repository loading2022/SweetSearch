<?php
// Ensure no output is sent before these calls
ini_set('session.save_handler', 'redis');

// Construct the Redis URL
$redis_url = "tcp://{$_ENV['REDIS_HOST']}:{$_ENV['REDIS_PORT']}";

// If you have a password, include it in the save path
if (!empty($_ENV['REDIS_PASSWORD'])) {
    $redis_url = "tcp://:{$_ENV['REDIS_PASSWORD']}@{$_ENV['REDIS_HOST']}:{$_ENV['REDIS_PORT']}";
}

ini_set('session.save_path', $redis_url);

session_start();

// Example session usage
$_SESSION['username'] = 'John Doe';
echo $_SESSION['username'];
?>
