<?php
// logout.php - ends the session and goes back to the login page
session_start();
session_destroy();
header("Location: login.php");
exit;
