<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar">
    <a href="index.php">Home</a>
    <a href="gallery.php">Gallery</a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Logged-in user -->
        <a href="upload.php">Upload</a>
        <a href="profile.php">Profile</a>
        <a href="account.php">Account</a>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <!-- Visitor -->
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>