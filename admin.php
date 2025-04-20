<?php
include 'config.php';
include 'functions.php';
session_start();

if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit;
}
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user || $user['is_admin'] != 1) {
	header('Location: index.php');
	exit;
}

$events = getEvents($pdo);
$categories = getCategories($pdo);
$users = $pdo->query("SELECT user_id, first_name, last_name, email, is_admin FROM users")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
	$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
	$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
	$date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
	$location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
	$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
	$image_url = filter_input(INPUT_POST, 'image_url', FILTER_SANITIZE_URL);
	$category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);

	if ($title && $description && $date && $location && $price !== false && $image_url && $category_id) {
		try {
			$stmt = $pdo->prepare("
                INSERT INTO events (category_id, title, description, date, location, price, image_url)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
			$stmt->execute([$category_id, $title, $description, $date, $location, $price, $image_url]);
			$success = "Event added successfully!";
		} catch (PDOException $e) {
			$error = "Error adding event: " . $e->getMessage();
		}
	} else {
		$error = "Please fill all fields correctly.";
	}
}

if (isset($_GET['delete_event'])) {
	$event_id = filter_input(INPUT_GET, 'delete_event', FILTER_VALIDATE_INT);
	if ($event_id) {
		try {
			$stmt = $pdo->prepare("DELETE FROM events WHERE event_id = ?");
			$stmt->execute([$event_id]);
			$success = "Event deleted successfully!";
			header('Location: admin.php'); // Refresh to update event list
			exit;
		} catch (PDOException $e) {
			$error = "Error deleting event: " . $e->getMessage();
		}
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
	$name = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_STRING);
	$icon = filter_input(INPUT_POST, 'icon', FILTER_SANITIZE_STRING);
	if ($name && $icon) {
		try {
			$stmt = $pdo->prepare("INSERT INTO categories (name, icon) VALUES (?, ?)");
			$stmt->execute([$name, $icon]);
			$success = "Category added successfully!";
			header('Location: admin.php'); // Refresh
			exit;
		} catch (PDOException $e) {
			$error = "Error adding category: " . $e->getMessage();
		}
	} else {
		$error = "Please fill all category fields.";
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_admin'])) {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $is_admin = filter_input(INPUT_POST, 'is_admin', FILTER_VALIDATE_INT);
    if ($user_id && $is_admin !== null) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET is_admin = ? WHERE user_id = ?");
            $stmt->execute([$is_admin, $user_id]);
            $success = "Admin rights updated successfully!";
            header('Location: admin.php');
            exit;
        } catch (PDOException $e) {
            $error = "Error updating admin rights: " . $e->getMessage();
        }
    } else {
        $error = "Invalid user data.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard - EventHub</title>
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
					<li><a href="admin.php" class="nav-link active">Admin</a></li>
					<li><a href="logout.php" class="btn btn-primary">Logout</a></li>
				</ul>
				<button class="mobile-menu-btn"><i class="fas fa-bars"></i></button>
			</nav>
		</div>
	</header>

	<section class="admin-section">
		<div class="container">
			<h2>Admin Dashboard</h2>

			<?php if (isset($success)): ?>
				<p class="success-message"><?php echo htmlspecialchars($success); ?></p>
			<?php endif; ?>
			<?php if (isset($error)): ?>
				<p class="error-message"><?php echo htmlspecialchars($error); ?></p>
			<?php endif; ?>

			<!-- cha -->
			<div class="admin-tabs">
				<button class="tab-link active" onclick="openTab('events')">Events</button>
				<button class="tab-link" onclick="openTab('categories')">Categories</button>
				<button class="tab-link" onclick="openTab('users')">Users</button>
			</div>
			<div id="events" class="tab-content active">
				<div class="admin-panel">
					<h3>Manage Events</h3>
					<form action="admin.php" method="post" class="admin-form">
						<div class="form-group">
							<label for="title">Event Title</label>
							<input type="text" id="title" name="title" required>
						</div>
						<div class="form-group">
							<label for="description">Description</label>
							<textarea id="description" name="description" required></textarea>
						</div>
						<div class="form-group">
							<label for="date">Date</label>
							<input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
						</div>

						<div class=" form-group">
							<label for="location">Location</label>
							<input type="text" id="location" name="location" required>
						</div>
						<div class="form-group">
							<label for="price">Price (Rs.)</label>
							<input type="number" id="price" name="price" step="0.01" required>
						</div>
						<div class="form-group">
							<label for="image_url">Image URL</label>
							<input type="url" id="image_url" name="image_url" required>
						</div>
						<div class="form-group">
							<label for="category_id">Category</label>
							<select id="category_id" name="category_id" required>
								<?php foreach ($categories as $category): ?>
									<option value="<?php echo $category['category_id']; ?>">
										<?php echo htmlspecialchars($category['name']); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<button type="submit" name="add_event" class="btn btn-primary">Add Event</button>
					</form>

					<h4>Existing Events</h4>
					<table class="admin-table">
						<thead>
							<tr>
								<th>Title</th>
								<th>Category</th>
								<th>Date</th>
								<th>Location</th>
								<th>Price</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($events as $event): ?>
								<?php
								$cat_stmt = $pdo->prepare("SELECT name FROM categories WHERE category_id = ?");
								$cat_stmt->execute([$event['category_id']]);
								$category = $cat_stmt->fetch(PDO::FETCH_ASSOC);
								?>
								<tr>
									<td><?php echo htmlspecialchars($event['title']); ?></td>
									<td><?php echo htmlspecialchars($category['name'] ?? 'N/A'); ?></td>
									<td><?php echo date('M d, Y', strtotime($event['date'])); ?></td>
									<td><?php echo htmlspecialchars($event['location']); ?></td>
									<td>Rs. <?php echo number_format($event['price'], 2); ?></td>
									<td>
										<a href="edit_event.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-secondary">Edit</a>
										<a href="admin.php?delete_event=<?php echo $event['event_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="categories" class="tab-content">
				<div class="admin-panel">
					<h3>Manage Categories</h3>
					<form action="admin.php" method="post" class="admin-form">
						<div class="form-group">
							<label for="category_name">Category Name</label>
							<input type="text" id="category_name" name="category_name" required>
						</div>
						<div class="form-group">
							<label for="icon">Icon Class (Font Awesome)</label>
							<input type="text" id="icon" name="icon" placeholder="e.g., fas fa-music" required>
						</div>
						<button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
					</form>

					<h4>Existing Categories</h4>
					<table class="admin-table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Icon</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($categories as $category): ?>
								<tr>
									<td><?php echo htmlspecialchars($category['name']); ?></td>
									<td><i class="<?php echo htmlspecialchars($category['icon']); ?>"></i> <?php echo htmlspecialchars($category['icon']); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- User ka admin status -->
			<div id="users" class="tab-content">
				<div class="admin-panel">
					<h3>Manage Users</h3>
					<table class="admin-table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Admin Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($users as $user): ?>
								<tr>
									<td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
									<td><?php echo htmlspecialchars($user['email']); ?></td>
									<td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
									<td>
										<form action="admin.php" method="post" style="display:inline;">
											<input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
											<input type="hidden" name="is_admin" value="<?php echo $user['is_admin'] ? 0 : 1; ?>">
											<button type="submit" name="toggle_admin" class="btn btn-secondary">
												<i class="fas fa-<?php echo $user['is_admin'] ? 'times' : 'check'; ?>"></i>
												&nbsp;&nbsp;
												<?php echo $user['is_admin'] ? 'Revoke Admin' : 'Grant Admin'; ?>
											</button>
										</form>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
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

		function openTab(tabName) {
			document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
			document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
			document.getElementById(tabName).classList.add('active');
			document.querySelector(`[onclick="openTab('${tabName}')"]`).classList.add('active');
		}
	</script>
</body>

</html>