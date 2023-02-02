<?php
spl_autoload_register(function ($class) {
    require dirname(__DIR__) . "/classes/{$class}.php";
});

require dirname(__DIR__) . '/config.php';