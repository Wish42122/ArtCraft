<?php
// setup_admin.php - run this ONCE in the browser to create the admin account.
// After that, DELETE this file.
include 'db.php';

$email    = "admin@nsbm.ac.lk";
$password = "admin123";

$found = db_all($conn, "SELECT id FROM users WHERE email = ?", "s", [$email]);

if (count($found) > 0) {
    echo "Admin account already exists. Please delete setup_admin.php now.";
} else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    db_run($conn, "INSERT INTO users (fullname, studentid, email, password, role) VALUES (?, ?, ?, ?, 'admin')",
           "ssss", ["Admin", "ADMIN001", $email, $hash]);
    echo "Admin created!<br>Email: $email<br>Password: $password<br><br>";
    echo "Now delete setup_admin.php and <a href='login.php'>go to login</a>.";
}
