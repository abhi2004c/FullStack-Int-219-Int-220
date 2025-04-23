<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - EventHub</title>
    <style>
        :root {
            --primary: #5048E5;
            --primary-light: #6d67ea;
            --secondary: #4FD1C5;
            --background: #F9FAFC;
            --white: #FFFFFF;
            --dark: #1F2937;
            --gray: #6B7280;
            --light-gray: #E5E7EB;
            --danger: #EF4444;
            --success: #10B981;
            --font-heading: 'Poppins', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--background);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: var(--primary);
            font-weight: 500;
            transition: color 0.3s;
        }

        a:hover {
            color: var(--primary-light);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        header {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 0;
        }

        .logo {
            font-family: var(--font-heading);
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }

        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
        }

        .auth-card {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 2.5rem;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-title {
            font-family: var(--font-heading);
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            color: var(--gray);
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
            width: 100%;
        }

        .form-row .form-group {
            flex: 1;
            min-width: 0;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
        }

        .form-input {
            padding: 0.8rem 1rem;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            border-color: var(--primary);
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .checkbox-group input {
            margin-top: 5px;
        }

        .terms-text {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 1rem;
            background-color: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: var(--primary-light);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: var(--gray);
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background-color: var(--light-gray);
        }

        .divider span {
            padding: 0 1rem;
        }

        .social-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem;
            background-color: var(--background);
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .social-btn:hover {
            background-color: var(--light-gray);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray);
        }

        .error-message {
            color: var(--danger);
            background-color: rgba(239, 68, 68, 0.1);
            padding: 0.8rem;
            border-radius: 8px;
            font-size: 0.9rem;
            text-align: center;
            display: none;
        }

        footer {
            background-color: var(--dark);
            color: var(--white);
            padding: 1.5rem 0;
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-link {
            color: var(--light-gray);
            transition: color 0.3s;
        }

        .footer-link:hover {
            color: var(--white);
        }

        @media (max-width: 768px) {
            .auth-card {
                padding: 2rem 1.5rem;
            }

            .form-row {
                flex-direction: column;
                gap: 1.5rem;
            }

            .footer-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo">EventHub</a>
            </nav>
        </div>
    </header>

    <main class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Create an Account</h1>
                <p class="auth-subtitle">Join thousands of event-goers today</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message" style="display: block;">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php else: ?>
                <div class="error-message" id="errorMessage">
                    Please fill all required fields correctly.
                </div>
            <?php endif; ?>

            <form class="auth-form" id="signupForm" action="signup_process.php" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" id="firstName" name="firstName" class="form-input" placeholder="Enter first name" required>
                    </div>

                    <div class="form-group">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" id="lastName" name="lastName" class="form-input" placeholder="Enter last name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" minlength="8" maxlength="20" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" title="Must contain at least 8 characters, including UPPER/lowercase, number and special character" title="" placeholder="Create a password" required>
                </div>

                <div class="form-group">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="form-input" placeholder="Confirm your password" required>
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms" class="terms-text">
                            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn">Create Account</button>
            </form>

            <div class="divider">
                <span>OR</span>
            </div>

            <div class="social-buttons">
                <button class="social-btn">
                    <i class="fab fa-google"></i>
                    <span>Google</span>
                </button>
                <button class="social-btn">
                    <i class="fab fa-facebook-f"></i>
                    <span>Facebook</span>
                </button>
            </div>

            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <p>&copy; 2025 EventHub. All rights reserved.</p>
                <div class="footer-links">
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Help Center</a>
                </div>
            </div>
        </div>
    </footer>


</body>

</html>