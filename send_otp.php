<?php
include 'config.php'; // Include your config.php file to get the $pdo connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // Prepare SQL query to check if the email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        // Save OTP & expiry in the database
        $update = $pdo->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?");
        $update->execute([$otp, $expiry, $email]);

        // Save OTP to file (for testing purposes)
        $content = "Email: $email\nOTP: $otp\nExpires At: $expiry\n";
        file_put_contents("OTP_forgot.txt", $content);

        // Redirect to verify page with email in URL
        header("Location: verify_otp.php?email=" . urlencode($email));
        exit();
    } else {
        echo "Email not found.";
    }
}
?>
