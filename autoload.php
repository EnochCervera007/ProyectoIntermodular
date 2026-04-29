<?php
spl_autoload_register(function ($class) {
    $base = __DIR__ . '/PHPMailer-master/src/';
    $prefix = 'PHPMailer\\PHPMailer\\';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative = substr($class, $len);
    $file = $base . str_replace('\\', '/', $relative) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});
?>