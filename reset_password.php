<?php
include 'connect.php';

// Check if the token is passed in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Sanitize the token to prevent SQL injection
    $token = mysqli_real_escape_string($con, $token);

    // Query to check if the token exists in the database
    $query = "SELECT * FROM `registration` WHERE reset_token='$token'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Token exists, show the reset password form
        // You can add your reset form here
        echo "Token valid. You can reset your password here.";
        // Reset password logic goes here (you will need to handle form submission)
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-pink-400 to-purple-600 h-screen flex justify-center items-center">
    <form method="post" class="bg-white p-8 rounded-lg shadow-lg w-96 space-y-4">
        <h2 class="text-2xl font-bold text-center">Reset Password</h2>
        <input type="password" name="new_password" placeholder="Enter new password" class="w-full p-2 border rounded" required>
        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
            Reset Password
        </button>
    </form>
</body>
</html>
