<?php
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
	$redirect_url = 'book.php?event_id=' . urlencode($_GET['event_id'] ?? '');
	header('Location: login.php?redirect=' . urlencode($redirect_url));
	exit;
}

// Validate event_id
if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
	header('Location: index.php?booking=error&message=' . urlencode('Invalid event ID'));
	exit;
}

$event_id = (int)$_GET['event_id'];
$user_id = $_SESSION['user_id'];

try {
	// Verify event exists
	$stmt = $pdo->prepare("SELECT event_id FROM events WHERE event_id = ?");
	$stmt->execute([$event_id]);
	if (!$stmt->fetch()) {
		header('Location: index.php?booking=error&message=' . urlencode('Event not found'));
		exit;
	}

	// Check for duplicate booking (optional, to prevent multiple bookings)
	$stmt = $pdo->prepare("SELECT booking_id FROM bookings WHERE user_id = ? AND event_id = ?");
	$stmt->execute([$user_id, $event_id]);
	if ($stmt->fetch()) {
		header('Location: index.php?booking=error&message=' . urlencode('You have already booked this event'));
		exit;
	}

	// Insert booking
	$stmt = $pdo->prepare("INSERT INTO bookings (user_id, event_id, booking_date, status) VALUES (?, ?, NOW(), 'pending')");
	$stmt->execute([$user_id, $event_id]);

	// Redirect with success message
	header('Location: index.php?booking=success');
	exit;
} catch (PDOException $e) {
	// Log error and redirect with error message
	error_log("Booking error: " . $e->getMessage());
	header('Location: index.php?booking=error&message=' . urlencode('Booking failed: Database error'));
	exit;
}
