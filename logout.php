<?php
session_start();
header("X-Robots-Tag: noindex, nofollow", true);

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: index");
exit;
?>
