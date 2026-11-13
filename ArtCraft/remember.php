<?php
function remember_cookie_name(): string {
    $folder = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    return 'artcraft_remember_'.substr(hash('sha256', $folder), 0, 12);
}

function set_remember_cookie(string $value, int $expires): void {
    $options = session_get_cookie_params();
    setcookie(remember_cookie_name(), $value, [
        'expires' => $expires,
        'path' => $options['path'],
        'secure' => $options['secure'],
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function forget_login(): void {
    $cookie = $_COOKIE[remember_cookie_name()] ?? '';
    if (is_string($cookie) && preg_match('/^([a-f0-9]{24}):[a-f0-9]{64}$/D', $cookie, $parts)) {
        sql('DELETE FROM remember_tokens WHERE selector=?', [$parts[1]]);
    }
    set_remember_cookie('', time() - 3600);
}

function remember_login(array $account): void {
    forget_login();
    $selector = bin2hex(random_bytes(12));
    $secret = bin2hex(random_bytes(32));
    $expires = time() + 30 * 86400;
    sql('DELETE FROM remember_tokens WHERE expires_at<?', [time()]);
    sql('INSERT INTO remember_tokens (selector,token_hash,user_id,password_stamp,expires_at) VALUES (?,?,?,?,?)', [
        $selector,
        hash('sha256', $secret),
        $account['id'],
        hash('sha256', $account['password_hash']),
        $expires,
    ]);
    set_remember_cookie($selector.':'.$secret, $expires);
}

function restore_login(): void {
    if (isset($_SESSION['uid'])) {
        return;
    }
    $cookie = $_COOKIE[remember_cookie_name()] ?? '';
    if (!is_string($cookie) || $cookie === '') {
        return;
    }
    if (!preg_match('/^([a-f0-9]{24}):([a-f0-9]{64})$/D', $cookie, $parts)) {
        set_remember_cookie('', time() - 3600);
        return;
    }
    $record = sql(
        'SELECT t.token_hash,t.password_stamp,t.expires_at,u.id,u.password_hash,u.status
         FROM remember_tokens t JOIN users u ON u.id=t.user_id WHERE t.selector=?',
        [$parts[1]]
    )->fetch();
    if (!$record || $record['status'] !== 'active' || (int)$record['expires_at'] <= time()
        || !hash_equals($record['token_hash'], hash('sha256', $parts[2]))
        || !hash_equals($record['password_stamp'], hash('sha256', $record['password_hash']))) {
        forget_login();
        return;
    }
    session_regenerate_id(true);
    $_SESSION = [
        'uid' => (int)$record['id'],
        'seen' => time(),
        'auth_stamp' => hash('sha256', $record['password_hash']),
    ];
}

