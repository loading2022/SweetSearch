<?php
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', "tcp://{$_ENV['REDIS_HOST']}:{$_ENV['REDIS_PORT']}");
?>