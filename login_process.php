<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Check if email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $redirect = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'index.php';
        header('Location: ' . $redirect);
        exit;
    } else {
        // Invalid credentials
        $error = "Invalid email or password.";
        header("Location: login.php?error=" . urlencode($error));
        exit;
    }
} else {
    // Invalid request method
    header('Location: login.php');
    exit;
}
?>