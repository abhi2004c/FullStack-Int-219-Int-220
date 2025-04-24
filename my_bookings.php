<?php
include 'config.php';
include 'functions.php';
session_start();

if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit;
}

$stmt = $pdo->prepare("SELECT b.booking_id, e.title, e.date, e.location, e.price FROM bookings b  JOIN events e ON b.event_id = e.event_id WHERE b.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>My Bookings - EventHub</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="styles.css">
</head>

<body>
	<?php
	$success = $_SESSION['success'] ?? null;
	$error = $_SESSION['error'] ?? null;
	unset($_SESSION['success'], $_SESSION['error']);
	?>

	<header>
		<div class="container">


			<nav class="navbar">
				<a href="index.php" class="logo">EventHub<span class="dot"></span></a>
				<ul class="nav-links">
					<li><a href="index.php">Home</a></li>
					<li><a href="index.php">About</a></li>
					<li><a href="my_bookings.php">My Bookings</a></li>
					<li><a href="logout.php" class="btn btn-primary">Logout</a></li>
				</ul>
				<div class="mobile-menu-btn"><i class="fas fa-bars"></i></div>
			</nav>
	</header>

	<section class="container">
		<h2>My Bookings</h2>

		<?php if (isset($success)): ?>
			<p class="success-message"><?php echo htmlspecialchars($success); ?></p>
		<?php endif; ?>
		<?php if (isset($error)): ?>
			<p class="error-message"><?php echo htmlspecialchars($error); ?></p>
		<?php endif; ?>

		<?php if (empty($bookings)): ?>
			<p>No bookings found.</p>
		<?php else: ?>
			<table class="admin-table">
				<thead>
					<tr>
						<th>Event Title</th>
						<th>Date</th>
						<th>Location</th>
						<th>Price</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($bookings as $booking): ?>
						<tr>
							<td><?php echo htmlspecialchars($booking['title']); ?></td>
							<td><?php echo date('M d, Y', strtotime($booking['date'])); ?></td>
							<td><?php echo htmlspecialchars($booking['location']); ?></td>
							<td>Rs <?php echo number_format($booking['price'], 2); ?> </td>
							<td>
								<form action="cancel_booking.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
									<input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
									<button type="submit" class="btn btn-danger">Cancel</button>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</section>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
			const navLinks = document.querySelector('.nav-links');
			mobileMenuBtn.addEventListener('click', function() {
				navLinks.classList.toggle('active');
				const icon = mobileMenuBtn.querySelector('i');
				if (navLinks.classList.contains('active')) {
					icon.classList.remove('fa-bars');
					icon.classList.add('fa-times');
				} else {
					icon.classList.remove('fa-times');
					icon.classList.add('fa-bars');
				}
			});
			document.addEventListener('click', function(event) {
				if (!event.target.closest('.mobile-menu-btn') &&
					!event.target.closest('.nav-links') &&
					navLinks.classList.contains('active')) {
					navLinks.classList.remove('active');
					const icon = mobileMenuBtn.querySelector('i');
					icon.classList.remove('fa-times');
					icon.classList.add('fa-bars');
				}
			});
		});
	</script>
</body>

</html>