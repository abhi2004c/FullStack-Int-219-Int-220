<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$first_name = filter_var($_POST['firstName'], FILTER_SANITIZE_STRING);
	$last_name = filter_var($_POST['lastName'], FILTER_SANITIZE_STRING);
	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$password = $_POST['password'];
	$confirm_password = $_POST['confirmPassword'];
	$terms = isset($_POST['terms']);

	// Validation
	if (!$first_name || !$last_name || !$email || !$password || !$terms) {
		$error = "Please fill all required fields correctly.";
		header("Location: signup.php?error=" . urlencode($error));
		exit;
	}

	if ($password !== $confirm_password) {
		$error = "Passwords do not match.";
		header("Location: signup.php?error=" . urlencode($error));
		exit;
	}

	// Check for existing email
	$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
	$stmt->execute([$email]);
	if ($stmt->rowCount() > 0) {
		$error = "Email already exists.";
		header("Location: signup.php?error=" . urlencode($error));
		exit;
	}

	// Hash password and insert user
	$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	$stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
	try {
		$stmt->execute([$first_name, $last_name, $email, $hashed_password]);
		header('Location: login.php');
		exit;
	} catch (PDOException $e) {
		$error = "Error creating account: " . $e->getMessage();
		header("Location: signup.php?error=" . urlencode($error));
		exit;
	}
} else {
	header('Location: signup.php');
	exit;
}
