<?php
include 'config.php';
include 'functions.php';
session_start();

$events = getEvents($pdo, 13);
$categories = getCategories($pdo);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventHub - Book Amazing Events</title>
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
                <a href="#" class="logo">EventHub<span class="dot"></span></a>
                <ul class="nav-links">
                    <li><a href="#" class="nav-link">Home</a></li>
                    <li><a href="#eve" class="nav-link">Events</a></li>
                    <li><a href="#categories" class="nav-link">Categories</a></li>
                    <li><a href="#foot" class="nav-link">About</a></li>
                    <li><a href="#foot" class="nav-link">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="logout.php" class="btn btn-primary">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn btn-primary">Login</a></li>
                        <li><a href="signup.php" class="btn btn-primary">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
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

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Discover and Book Amazing Events Near You</h1>
                <p>Find the perfect events for your interests and hobbies. From concerts and workshops to sports and exhibitions - all in one place.</p>
                <form action="search.php" method="get" class="search-container">
                    <input type="text" name="search" class="search-input" placeholder="Search for events, categories or locations..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>
        </div>
    </section>

    <section id="eve" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Events</h2>
                <p class="section-subtitle">Discover a variety of events tailored to your interests.</p>
            </div>
            <div class="events-grid">
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
            </div>
        </div>
    </section>



    <section id="categories" class="section categories-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Browse by Category</h2>
                <p class="section-subtitle">Find events that match your interests. We have something for everyone!</p>
            </div>
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                    <div class="category-card">
                        <i class="<?php echo htmlspecialchars($category['icon']); ?> category-icon"></i>
                        <h3 class="category-title"><?php echo htmlspecialchars($category['name']); ?></h3>
                        <p class="category-count">
                            <?php echo getEventCountByCategory($pdo, $category['category_id']) . ' Events'; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">Finding and booking events with EventHub is simple and easy.</p>
            </div>
            <div class="steps-container">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Search Events</h3>
                    <p class="step-description">Browse through our extensive collection of events or search for specific ones that match your interests.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Book Tickets</h3>
                    <p class="step-description">Select your event and book tickets securely in just a few clicks. Get instant confirmation.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Enjoy the Event</h3>
                    <p class="step-description">Receive your tickets via email, show them at the venue, and enjoy your event!</p>
                </div>
            </div>
        </div>
    </section>
    <section class="section testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">What People Say</h2>
                <p class="section-subtitle">Don't just take our word for it. See what our users have to say about EventHub.</p>
            </div>


            <div class="testimonials-slider">
                <div class="testimonial">
                    <p class="testimonial-quote">
                        EventHub made finding and booking tickets to local events so easy! I discovered amazing concerts I would have missed otherwise. The interface is intuitive and the booking process was seamless.
                    </p>
                    <div class="testimonial-author">
                        <img src="data:image/jpeg;base64,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" alt="Sarah Johnson" class="author-image">
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <p>Music Enthusiast</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial">
                    <p class="testimonial-quote">
                        I use EventHub every weekend now. From food festivals to indie film nights, it keeps me connected to what’s happening around me. Highly recommend it to all my friends!
                    </p>
                    <div class="testimonial-author">
                        <img src="data:image/jpeg;base64,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" alt="David Kim" class="author-image">
                        <div class="author-info">
                            <h4>David Kim</h4>
                            <p>Weekend Explorer</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial">
                    <p class="testimonial-quote">
                        As someone new to the city, EventHub helped me dive right into the local scene. I’ve met new people, discovered hidden gems, and made unforgettable memories. It's like having a social guide in your pocket.
                    </p>
                    <div class="testimonial-author">
                        <img src="data:image/jpeg;base64,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" alt="Emily Rodriguez" class="author-image">
                        <div class="author-info">
                            <h4>Emily Rodriguez</h4>
                            <p>New-Comer</p>
                        </div>
                    </div>
                </div>

                <div class="slider-controls">
                    <span class="slider-dot active"></span>
                    <span class="slider-dot"></span>
                    <span class="slider-dot"></span>
                </div>
            </div>
        </div>
    </section>

    <section class="section newsletter-section">
        <div class="container">
            <div class="newsletter-container">
                <h2 class="newsletter-title">Stay Updated</h2>
                <p class="newsletter-description">Subscribe to our newsletter and be the first to know about upcoming events, exclusive offers, and discounts.</p>
                <form class="newsletter-form">
                    <input type="email" class="newsletter-input" placeholder="Enter your email address">
                    <button type="submit" class="newsletter-btn">Subscribe</button>
                </form>
                <p class="newsletter-privacy">We respect your privacy. Unsubscribe at any time.</p>
            </div>
        </div>
    </section>

    <footer id="foot">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column footer-about">
                    <h3>About EventHub</h3>
                    <p>EventHub is the leading platform for discovering and booking events across all categories. Our mission is to connect people with experiences they'll love.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Events</a></li>
                        <li><a href="#">Categories</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Refund Policy</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Lovely Professional University, Phagwara, Punjab 144411</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>+91 7903818511</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>prince.12320812@lpu.in</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="copyright">&copy; 2025 EventHub. All rights reserved.</p>
                <div class="footer-nav">
                    <a href="#">Terms</a>
                    <a href="#">Privacy</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

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

            document.addEventListener("DOMContentLoaded", function () {
                const dots = document.querySelectorAll(".slider-dot");
                const testimonials = document.querySelectorAll(".testimonial");

                // Hide all testimonials except the first
                testimonials.forEach((testimonial, index) => {
                    testimonial.style.display = index === 0 ? "block" : "none";
                });

                dots.forEach((dot, index) => {
                    dot.addEventListener("click", () => {
                        // Remove active class from all dots
                        dots.forEach(dot => dot.classList.remove("active"));
                        // Add active class to the clicked dot
                        dot.classList.add("active");

                        // Show the testimonial corresponding to the dot
                        testimonials.forEach((testimonial, i) => {
                            testimonial.style.display = i === index ? "block" : "none";
                    });
                });
            });
        });
    </script>
</body>

</html>