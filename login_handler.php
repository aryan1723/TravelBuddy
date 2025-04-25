<?php
session_start();
require 'db_connect.php'; // Make sure you have this file

// --- Admin Email Constant ---
define('ADMIN_EMAIL', 'admin@travelbuddy.in');
// --------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    // Set error message in session and pass it back to login.php
    $_SESSION['login_error'] = "Please enter both email and password.";
    header('Location: login.php');
    exit;
}

// Prepare SQL to prevent SQL injection
$sql = "SELECT user_id, name, email, password_hash FROM users WHERE email = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    error_log("Login Prepare Error: " . $mysqli->error);
    $_SESSION['login_error'] = "Database error. Please try again.";
    header('Location: login.php');
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['login_error'] = "Invalid email or password.";
    header('Location: login.php');
    exit;
}

$user = $result->fetch_assoc();

// Verify password using password_verify()
if (password_verify($password, $user['password_hash'])) {
    // Password is correct, login successful

    // Set common session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];

    // --- Check if the logged-in user is the admin ---
    if ($user['email'] === ADMIN_EMAIL) {
        $_SESSION['is_admin'] = true; // Set admin flag
        header('Location: admin.php'); // Redirect admin to admin page
    } else {
        $_SESSION['is_admin'] = false; // Ensure admin flag is false for regular users
        header('Location: dashboard.php'); // Redirect regular users to dashboard
    }
    // ------------------------------------------------

} else {
    // Password incorrect
    $_SESSION['login_error'] = "Invalid email or password.";
    header('Location: login.php');
}

$stmt->close();
$mysqli->close();
exit; // Important to exit after header redirect
?>