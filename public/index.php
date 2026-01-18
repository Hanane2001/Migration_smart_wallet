<?php
session_start();

define('BASE_URL', 'http://localhost/smart-walet_p/public/');

define('DB_HOST', 'localhost');
define('DB_USER', 'postgres');
define('DB_PASS', 'hanane');
define('DB_NAME', 'smart_wallet');
define('DB_PORT', 5432);

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

$app = new App\Core\App();
?>