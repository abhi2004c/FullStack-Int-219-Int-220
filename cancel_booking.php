<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['booking_id'])) {
	header('Location: my_bookings.php');
	exit;
}

$bookingId = $_POST['booking_id'];
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("DELETE FROM bookings WHERE booking_id = ? AND user_id = ?");
$success = $stmt->execute([$bookingId, $userId]);

if ($success) {
	$_SESSION['success'] = 'Booking cancelled successfully.';
} else {
	$_SESSION['error'] = 'Unable to cancel booking.';
}

header('Location: my_bookings.php');
exit;
