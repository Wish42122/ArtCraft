<?php
// db.php - connects to the database and has small helper functions.
// Every other page does:  include 'db.php';

session_start();   // needed so we can remember who is logged in

// ---- database settings (default XAMPP values) ----
$host     = "localhost";
$username = "root";
$password = "";          // XAMPP root has an empty password
$database = "artcraft";

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");


// ---- helper functions ----

// e() makes text safe to print inside HTML (stops people injecting <script>)
function e($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

// db_run() runs one SQL query (INSERT / UPDATE / DELETE / SELECT).
// We use "?" placeholders so user input can never break the SQL (prevents SQL injection).
//   $types  = one letter for each ?   (s = string, i = whole number)
//   $params = the values for the ?s
function db_run($conn, $sql, $types = "", $params = []) {
    $stmt = mysqli_prepare($conn, $sql);
    if ($types != "") {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return $stmt;
}

// db_all() runs a SELECT and gives back all rows as an array
function db_all($conn, $sql, $types = "", $params = []) {
    $stmt   = db_run($conn, $sql, $types, $params);
    $result = mysqli_stmt_get_result($stmt);
    $rows   = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// pages that need a logged in user call this at the top
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?loginfirst=1");
        exit;
    }
}

// pages that only the admin may open call this at the top
function require_admin() {
    require_login();
    if ($_SESSION['role'] != 'admin') {
        header("Location: myartwork.php");
        exit;
    }
}
