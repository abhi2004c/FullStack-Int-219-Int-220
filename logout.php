<?php
session_start(); // Start the session to access session variables

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to index.php
header('Location: index.php');
exit;
