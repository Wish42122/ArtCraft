<?php
require __DIR__.'/bootstrap.php';
if ($user = current_account()) go($user['role']==='admin' ? 'admin.php' : 'myartwork.php');
$error = null;
if (post()) {
    check_csrf();
    try {
        $fields = account_fields();
        $password = val('password',false);
        if ($password !== val('confirm',false)) throw new DomainException('The passwords must match.');
        if (val('terms') !== 'yes') throw new DomainException('Accept the terms to register.');
        $fields[] = new_hash($password);
        sql('INSERT INTO users (fullname,studentid,email,password_hash) VALUES (?,?,?,?)',$fields);
        flash('Account created. Log in to continue.');
        go('login.php');
    } catch (DomainException | PDOException $exception) {
        http_response_code(422);
        $error = form_error($exception);
    }
}
