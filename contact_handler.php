<?php
// Enable full error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db_connect.php';

// Verify database connection
if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
    die("Database connection failed");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php#contact');
    exit;
}

// Sanitize inputs
$name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
$subject = trim(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING));
$message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));
$errors = [];

// Validation
if (empty($name)) $errors[] = "Name is required";
if (empty($message)) $errors[] = "Message is required";

if (empty($errors)) {
    try {
        $sql = "INSERT INTO contact_messages (name, subject, message, is_read) VALUES (?, ?, ?, 0)";
        $stmt = $mysqli->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $mysqli->error);
        }
        
        if (!$stmt->bind_param("sss", $name, $subject, $message)) {
            throw new Exception("Bind failed: " . $stmt->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $_SESSION['message'] = "Thank you for your message! We'll get back to you soon.";
        $_SESSION['message_type'] = "success";
        $stmt->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $_SESSION['message'] = "Failed to send message. Please try again later.";
        $_SESSION['message_type'] = "error";
        $_SESSION['contact_data'] = ['name' => $name, 'subject' => $subject, 'message' => $message];
    }
} else {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_data'] = ['name' => $name, 'subject' => $subject, 'message' => $message];
    $_SESSION['message'] = "Please correct the errors below";
    $_SESSION['message_type'] = "error";
}

$mysqli->close();
header('Location: index.php#contact');
exit;
?>