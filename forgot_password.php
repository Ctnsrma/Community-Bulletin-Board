<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connect.php';
    
    // Sanitize input
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    // Generate token and expiration time (1 hour from now)
    $token = bin2hex(random_bytes(50));
    $token_expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Use case-insensitive check for email existence
    $check = mysqli_query($con, "SELECT * FROM `registration` WHERE LOWER(email) = LOWER('$email')");

    // Handle SQL error
    if (!$check) {
        die("Query Failed: " . mysqli_error($con));
    }

    if (mysqli_num_rows($check) > 0) {
        // Email exists, update the reset token and expiration time
        $update = mysqli_query($con, "UPDATE `registration` SET reset_token='$token', token_expiry='$token_expiry' WHERE LOWER(email) = LOWER('$email')");

        if (!$update) {
            die("Token Update Failed: " . mysqli_error($con));
        }

        // Email content
        $subject = "Password Reset Request";
        $body = "Hi!\nClick the link to reset your password:\nhttp://localhost/reset_password.php?token=$token";
        $headers = "From: yourmail@example.com";

        // Send email
        if (mail($email, $subject, $body, $headers)) {
            echo "<script>alert('Reset link sent to your email.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Failed to send email. Check your email setup.');</script>";
        }
    } else {
        echo "<script>alert('Email not registered. Please sign up first.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-pink-400 to-purple-600 h-screen flex justify-center items-center">
    <form method="post" class="bg-white p-8 rounded-lg shadow-lg w-96 space-y-4">
        <h2 class="text-2xl font-bold text-center">Forgot Password</h2>
        <input type="email" name="email" placeholder="Enter your registered email"
               class="w-full p-2 border rounded" required>
        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
            Send Reset Link
        </button>
        <a href="login.php" class="text-sm text-blue-600 hover:underline block text-center">Back to Login</a>
    </form>
</body>
</html>
