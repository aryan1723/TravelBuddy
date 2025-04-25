<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirmPassword = trim($_POST['confirmPassword'] ?? '');

$errors = [];

if (empty($name)) $errors[] = "Name is required.";
if (empty($email)) $errors[] = "Email is required.";
if (empty($password)) $errors[] = "Password is required.";
if ($password !== $confirmPassword) $errors[] = "Passwords do not match.";
if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters.";

if (!empty($errors)) {
    $_SESSION['signup_errors'] = $errors;
    $_SESSION['signup_data'] = ['name' => $name, 'email' => $email];
    header('Location: login.php');
    exit;
}

// Check if email already exists
$check_sql = "SELECT email FROM users WHERE email = ?";
$check_stmt = $mysqli->prepare($check_sql);
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    $_SESSION['signup_errors'] = ["This email is already registered."];
    $_SESSION['signup_data'] = ['name' => $name, 'email' => $email];
    header('Location: login.php');
    exit;
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$insert_sql = "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)";
$insert_stmt = $mysqli->prepare($insert_sql);

if ($insert_stmt === false) {
    error_log("Signup Prepare Error: " . $mysqli->error);
    $_SESSION['signup_errors'] = ["Database error. Please try again."];
    header('Location: login.php');
    exit;
}

$insert_stmt->bind_param("sss", $name, $email, $password_hash);

if ($insert_stmt->execute()) {
    $_SESSION['signup_success'] = "Registration successful! Please login.";
    header('Location: login.php');
} else {
    error_log("Signup Execute Error: " . $insert_stmt->error);
    $_SESSION['signup_errors'] = ["Registration failed. Please try again."];
    header('Location: login.php');
}

$check_stmt->close();
$insert_stmt->close();
$mysqli->close();
exit;
?>