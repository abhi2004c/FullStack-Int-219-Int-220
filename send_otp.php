<?php
include 'config.php'; // Include your config.php file to get the $pdo connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // Check if email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        // Store OTP in DB
        $update = $pdo->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?");
        $update->execute([$otp, $expiry, $email]);

        // Save OTP to file (for testing)
        $content = "Email: $email\nOTP: $otp\nExpires At: $expiry\n";
        file_put_contents("OTP_forgot.txt", $content, FILE_APPEND);

        // Send OTP via email
        $subject = "Your OTP Code";
        $message = "Hello,\n\nYour OTP is: $otp\nIt expires at: $expiry\n\nDo not share this with anyone.";
        $from = "honey2004c@gmail.com";
        $headers = "From: $from";

        if (!mail($email, $subject, $message, $headers)) {
            echo "Failed to send OTP email.";
            exit();
        }

        // Redirect to verify page
        header("Location: verify_otp.php?email=" . urlencode($email));
        exit();
    } else {
        echo "Email not found.";
    }
}
?>
