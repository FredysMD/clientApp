<?php
    require __DIR__ . "./config/bootstrap.php";
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );

    if ((isset($uri[4]) && $uri[4] != 'user') || !isset($uri[4])) {
        header("HTTP/1.1 404 Not Found"); 
        exit();
    }
    require __DIR__ . "/controllers/UserController.php";

    
    $userController = new UserController();
    $strMethodName = $uri[5];
    
    $userController->{$strMethodName}();
?>