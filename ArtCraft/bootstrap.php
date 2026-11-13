<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Colombo');
function db(): PDO {
    static $pdo;
    if (!$pdo) {
        $config = is_file(__DIR__.'/config.local.php') ? require __DIR__.'/config.local.php' : [];
        $config += ['dsn'=>'mysql:host=127.0.0.1;dbname=artcraft;charset=utf8mb4','user'=>'root','password'=>''];
        $pdo = new PDO($config['dsn'], $config['user'], $config['password'], [
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES=>false
        ]);
    }
    return $pdo;
}
set_exception_handler(function (Throwable $error): void {
    error_log('ArtCraft: '.$error->getMessage());
    http_response_code(500);
    echo 'ArtCraft could not complete this request. Check the server log and database setup.';
});
function sql(string $query, array $params = []): PDOStatement {
    $stmt = db()->prepare($query);
    $stmt->execute($params);
    return $stmt;
}
if (PHP_SAPI !== 'cli') {
    require __DIR__.'/remember.php';
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_name('artcraft_session');
    session_set_cookie_params([
        'lifetime'=>0, 'path'=>str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])) ?: '/',
        'httponly'=>true, 'samesite'=>'Lax', 'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
    ]);
    session_start();
    header('X-Content-Type-Options: nosniff');
    if (isset($_SESSION['uid']) && time() - ($_SESSION['seen'] ?? 0) > 1800) {
        $_SESSION = [];
        session_regenerate_id(true);
    }
    if (isset($_SESSION['uid'])) $_SESSION['seen'] = time();
}
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function go(string $path): void { header('Location: '.$path, true, 303); exit; }
function post(): bool { return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'; }
function val(string $key, bool $trim = true): string {
    $v = $_POST[$key] ?? '';
    return is_string($v) ? ($trim ? trim($v) : $v) : '';
}
function id($value): int {
    return is_scalar($value) ? (int)filter_var($value, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1,'max_range'=>4294967295]]) : 0;
}
function current_account(): ?array {
    static $loaded = false, $account = null;
    if (!$loaded && isset($_SESSION['uid'])) {
        $account = sql("SELECT id, fullname, studentid, email, role, password_hash FROM users WHERE id=? AND status='active'", [$_SESSION['uid']])->fetch() ?: null;
        if (!$account || !hash_equals(hash('sha256',$account['password_hash']), $_SESSION['auth_stamp'] ?? '')) {
            $account = null;
            $_SESSION = [];
            session_regenerate_id(true);
        } else {
            unset($account['password_hash']);
        }
    }
    $loaded = true;
    return $account;
}
function require_user(bool $admin = false): array {
    $user = current_account();
    if (!$user) go('login.php');
    header('Cache-Control: no-store');
    if ($admin && $user['role'] !== 'admin') {
        http_response_code(403);
        exit('Access denied. <a href="myartwork.php">Return to My Artworks</a>.');
    }
    return $user;
}
function token(): string {
    return '<input type="hidden" name="csrf" value="'.e($_SESSION['csrf'] ??= bin2hex(random_bytes(32))).'">';
}
function check_csrf(): void {
    if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], val('csrf'))) {
        http_response_code(403); exit('This form has expired. Go back and reload it before trying again.');
    }
}
function flash(string $message): void { $_SESSION['flash'] = $message; }
function notice(?string $error = null): void {
    $message = $error ?? ($_SESSION['flash'] ?? null);
    unset($_SESSION['flash']);
    if ($message) echo '<p class="app-notice'.($error ? ' app-error' : '').'" role="'.($error ? 'alert' : 'status').'">'.e($message).'</p>';
}
function valid_text(string $v, int $max, bool $required = true): bool {
    return (!$required || $v !== '') && mb_check_encoding($v,'UTF-8') && mb_strlen($v) <= $max && !str_contains($v, "\0");
}
function account_fields(): array {
    $name = val('fullname'); $sid = strtoupper(val('studentid')); $email = strtolower(val('email'));
    if (!valid_text($name,120)) throw new DomainException('Enter a full name of up to 120 characters.');
    if (!preg_match('/^[A-Z0-9][A-Z0-9_-]{2,31}$/D', $sid)) throw new DomainException('Enter a student ID of 3–32 letters, digits, hyphens or underscores.');
    if (strlen($email)>254 || !filter_var($email,FILTER_VALIDATE_EMAIL)) throw new DomainException('Enter a valid email address.');
    return [$name,$sid,$email];
}
function new_hash(string $password): string {
    if (!mb_check_encoding($password,'UTF-8') || mb_strlen($password)<8 || strlen($password)>72 || str_contains($password,"\0")) {
        throw new DomainException('Use at least 8 characters and no more than 72 bytes for your password.');
    }
    return password_hash($password, PASSWORD_DEFAULT);
}
function form_error(Throwable $error): string {
    if ($error instanceof DomainException) return $error->getMessage();
    if ($error instanceof PDOException && ($error->errorInfo[1] ?? 0) == 1062) return 'That email, student ID or category name is already in use.';
    if ($error instanceof PDOException && ($error->errorInfo[1] ?? 0) == 1451) return 'This record is still used by an artwork. Reassign or remove those artworks first.';
    if ($error instanceof PDOException && ($error->errorInfo[1] ?? 0) == 1452) return 'A selected record no longer exists. Reload and try again.';
    throw $error;
}
function session_nav(string $loginClass = 'login-btn', string $registerClass = 'register-btn'): void {
    $user = current_account();
    if (!$user) {
        echo '<a href="login.php" class="'.e($loginClass).'">Login</a> <a href="register.php" class="'.e($registerClass).'">Register</a>';
        return;
    }
    echo $user['role'] === 'admin' ? '<a class="'.e($loginClass).'" href="admin.php">Dashboard</a>' : '<a class="'.e($loginClass).'" href="myartwork.php">My Artworks</a>';
    echo ' <a class="'.e($registerClass).'" href="logout.php">Logout</a>';
}
function category_options(int $selected = 0): void {
    foreach (sql('SELECT id,name FROM categories ORDER BY name') as $category) {
        echo '<option value="'.e($category['id']).'"'.((int)$category['id']===$selected ? ' selected' : '').'>'.e($category['name']).'</option>';
    }
}
const ART_QUERY = 'SELECT a.id,a.user_id,a.category_id,a.title,a.description,a.materials,a.status,a.featured,a.created_at,u.fullname,c.name AS category FROM artworks a JOIN users u ON u.id=a.user_id JOIN categories c ON c.id=a.category_id';


if (PHP_SAPI !== 'cli') {
    restore_login();
}
