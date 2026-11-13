<?php
require __DIR__.'/bootstrap.php';
if (post()) {
    check_csrf();
    forget_login();
    $_SESSION=[];
    $cookie=session_get_cookie_params();
    setcookie(session_name(),'',[
        'expires'=>time()-3600, 'path'=>$cookie['path'], 'domain'=>$cookie['domain'],
        'secure'=>$cookie['secure'],'httponly'=>$cookie['httponly'],'samesite'=>'Lax'
    ]);
    session_destroy();
    go('logout.php');
}
$user = current_account();
