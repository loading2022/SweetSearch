<?php
$redis = new Redis();
$redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']); 
$redis->auth($_ENV['REDIS_PASSWORD']); 
?>