<?php
// Ensure no output is sent before these calls
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', "tcp://{$_ENV['REDIS_HOST']}:{$_ENV['REDIS_PORT']}");

session_start();

try {
    // Initialize Redis connection
    $redis = new Redis();
    $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
    
    // Set a session key expiration as an example
    $redis->expire(session_id(), 3600); // 3600 seconds = 1 hour

    // Your application code here
    $_SESSION['username'] = 'John Doe';
    echo $_SESSION['username'];
} catch (Exception $e) {
    // Handle Redis connection errors
    echo "Could not connect to Redis: " . $e->getMessage();
}
?>
