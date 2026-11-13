<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require __DIR__.'/bootstrap.php';

if ($argc !== 3 || $argv[1] !== '--promote' || !filter_var($argv[2],FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR,"Usage: php create-admin.php --promote your-registered-email@example.com\n");
    exit(1);
}
$email = strtolower($argv[2]);
$account = sql('SELECT id,role,status FROM users WHERE email=?',[$email])->fetch();
if (!$account) {
    fwrite(STDERR,"No account is registered with that email. Open http://localhost/ArtCraft/register.php and register it first.\n");
    exit(1);
}
if ($account['status'] !== 'active') {
    fwrite(STDERR,"That account is inactive. An administrator must activate it first.\n");
    exit(1);
}
if ($account['role'] === 'admin') {
    echo "This account is already an administrator. Open http://localhost/ArtCraft/login.php and sign in.\n";
    exit;
}
$updated = sql("UPDATE users SET role='admin' WHERE id=? AND status='active'",[$account['id']]);
if (!$updated->rowCount()) {
    fwrite(STDERR,"The account changed while this command was running. Check its status and try again.\n");
    exit(1);
}
echo "Administrator access granted. Sign out, then sign in at http://localhost/ArtCraft/login.php.\n";
