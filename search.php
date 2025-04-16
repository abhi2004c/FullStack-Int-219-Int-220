<?php
include 'config.php';
include 'functions.php';
session_start();

$events = [];
$search_term = '';

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
	$search_term = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
	$like_term = "%$search_term%";
	try {
		$stmt = $pdo->prepare("SELECT * FROM events WHERE title LIKE ? OR description LIKE ? OR location LIKE ? ORDER BY date ASC");
		$stmt->execute([$like_term, $like_term, $like_term]);
		$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
		error_log("Search error: " . $e->getMessage());
		$events = [];
	}
} else {
	$events = getEvents($pdo, 6); // Fallback to featured events
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Search Results - EventHub</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="styles.css">
</head>

<body>
	<header>
		<div class="container">
			<nav class="navbar">
				<a href="index.php" class="logo">EventHub<span class="dot"></span></a>
				<ul class="nav-links">
					<li><a href="index.php" class="nav-link">Home</a></li>
					<li><a href="#" class="nav-link">Events</a></li>
					<li><a href="#" class="nav-link">Categories</a></li>
					<li><a href="#" class="nav-link">About</a></li>
					<li><a href="#" class="nav-link">Contact</a></li>
					<?php if (isset($_SESSION['user_id'])): ?>
						<li><a href="logout.php" class="btn btn-primary">Logout</a></li>
					<?php else: ?>
						<li><a href="login.php" class="btn btn-primary">Login</a></li>
						<li><a href="signup.php" class="btn btn-primary">Sign Up</a></li>
					<?php endif; ?>
				</ul>
				<button class="mobile-menu-btn"><i class="fas fa-bars"></i></button>
			</nav>
		</div>
	</header>

	<?php if (isset($_GET['booking'])): ?>
        <div class="container">
            <p class="<?php echo $_GET['booking'] === 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo $_GET['booking'] === 'success' ? 'Booking successful!' : 'Booking failed: ' . htmlspecialchars($_GET['message'] ?? 'Unknown error'); ?>
            </p>
        </div>
    <?php endif; ?>

	<section class="section">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">Search Results</h2>
				<p class="section-subtitle">
					<?php echo $search_term ? 'Results for "' . htmlspecialchars($search_term) . '"' : 'All Featured Events'; ?>
				</p>
			</div>
			<div class="events-grid">
				<?php if (empty($events)): ?>
					<p>No events found matching your search.</p>
				<?php else: ?>
					<?php foreach ($events as $event): ?>
						<div class="event-card">
							<div class="event-image-container">
								<img src="<?php echo htmlspecialchars($event['image_url']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" class="event-image">
								<div class="event-overlay">
									<p class="event-overlay-text"><?php echo htmlspecialchars($event['description']); ?></p>
								</div>
							</div>
							<div class="event-content">
								<span class="event-date"><?php echo date('M d, Y', strtotime($event['date'])); ?></span>
								<h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
								<div class="event-location">
									<i class="fas fa-map-marker-alt"></i>
									<span><?php echo htmlspecialchars($event['location']); ?></span>
								</div>
								<div class="event-details">
									<span class="event-price">$<?php echo number_format($event['price'], 2); ?></span>
									<a href="book.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-primary book-btn">Book Now</a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<!-- Footer (optional, copied from index.php if present) -->
	<footer class="footer">
		<div class="container">
			<div class="footer-content">
				<div class="footer-section">
					<h3 class="footer-title">EventHub</h3>
					<p>Discover and book amazing events near you.</p>
				</div>
				<div class="footer-section">
					<h3 class="footer-title">Quick Links</h3>
					<ul class="footer-links">
						<li><a href="#">Home</a></li>
						<li><a href="#">Events</a></li>
						<li><a href="#">Categories</a></li>
						<li><a href="#">Contact</a></li>
					</ul>
				</div>
				<div class="footer-section">
					<h3 class="footer-title">Contact Us</h3>
					<p>Email: support@eventhub.com</p>
					<p>Phone: +1 234 567 890</p>
				</div>
			</div>
			<div class="footer-bottom">
				<p>&copy; 2025 EventHub. All rights reserved.</p>
			</div>
		</div>
	</footer>

	<!-- Mobile Menu JavaScript -->
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