<?php
require __DIR__.'/bootstrap.php';
require __DIR__.'/auth-limit.php';
if ($user = current_account()) go($user['role']==='admin' ? 'admin.php' : 'myartwork.php');
$error = null;
if (post()) {
    check_csrf();
    $identifier = val('email'); $password = val('password', false);
    limit_login($identifier);
    $account = false;
    if ($identifier !== '' && strlen($identifier)<=254 && mb_check_encoding($identifier,'UTF-8') && $password !== '' && strlen($password)<=72 && !str_contains($password,"\0")) {
        $account = str_contains($identifier,'@')
            ? sql('SELECT * FROM users WHERE email=?',[strtolower($identifier)])->fetch()
            : sql('SELECT * FROM users WHERE studentid=?',[strtoupper($identifier)])->fetch();
        $valid = password_verify($password, $account['password_hash'] ?? '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
        if ($valid && $account && $account['status']==='active') {
            if (password_needs_rehash($account['password_hash'], PASSWORD_DEFAULT)) {
                $hash = password_hash($password,PASSWORD_DEFAULT);
                sql('UPDATE users SET password_hash=? WHERE id=? AND password_hash=?',[$hash,$account['id'],$account['password_hash']]);
                $account['password_hash'] = $hash;
            }
            session_regenerate_id(true);
            $_SESSION = ['uid'=>(int)$account['id'],'seen'=>time(),'auth_stamp'=>hash('sha256',$account['password_hash'])];
            if (val('remember') === 'yes') {
                remember_login($account);
            } else {
                forget_login();
            }
            go($account['role']==='admin' ? 'admin.php' : 'myartwork.php');
        }
    }
    http_response_code(422);
    $error = 'Invalid sign-in details or inactive account.';
}
