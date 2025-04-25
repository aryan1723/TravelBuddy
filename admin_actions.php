<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    $_SESSION['login_error'] = 'Unauthorized access.';
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header('Location: admin.php');
    exit;
}

$action = $_POST['action'];

//Add News
if ($action === 'add_news') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $url = trim($_POST['url'] ?? '');

    if (empty($title) || empty($content)) {
        $_SESSION['admin_message'] = 'Error: Title and Content cannot be empty.';
        header('Location: admin.php');
        exit;
    }

    if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
        $url = '#';
    }

    $sql = "INSERT INTO news (title, content, url, published_at) VALUES (?, ?, ?, NOW())";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $title, $content, $url);
        if ($stmt->execute()) {
            $_SESSION['admin_message'] = 'News item added successfully.';
        } else {
            error_log("Error adding news: " . $stmt->error);
            $_SESSION['admin_message'] = 'Error: Could not add news item. ' . $stmt->error;
        }
        $stmt->close();
    } else {
        error_log("Error preparing add news statement: " . $mysqli->error);
        $_SESSION['admin_message'] = 'Error: Database prepare error. ' . $mysqli->error;
    }

//Remove News ---
} elseif ($action === 'remove_news') {
    $news_id = filter_input(INPUT_POST, 'news_id', FILTER_VALIDATE_INT);

    if (!$news_id) {
        $_SESSION['admin_message'] = 'Error: Invalid News ID.';
        header('Location: admin.php');
        exit;
    }

    $sql = "DELETE FROM news WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $news_id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['admin_message'] = 'News item removed successfully.';
            } else {
                $_SESSION['admin_message'] = 'Error: News item not found or already removed.';
            }
        } else {
            error_log("Error removing news: " . $stmt->error);
            $_SESSION['admin_message'] = 'Error: Could not remove news item. ' . $stmt->error;
        }
        $stmt->close();
    } else {
        error_log("Error preparing remove news statement: " . $mysqli->error);
        $_SESSION['admin_message'] = 'Error: Database prepare error. ' . $mysqli->error;
    }

//Remove Contact Message ---
} elseif ($action === 'remove_message') {
    $message_id = filter_input(INPUT_POST, 'message_id', FILTER_VALIDATE_INT);

    if (!$message_id) {
        $_SESSION['admin_message'] = 'Error: Invalid Message ID.';
        header('Location: admin.php');
        exit;
    }

    $sql = "DELETE FROM contact_messages WHERE message_id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $message_id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['admin_message'] = 'Contact message removed successfully.';
            } else {
                $_SESSION['admin_message'] = 'Error: Message not found or already removed.';
            }
        } else {
            error_log("Error removing contact message: " . $stmt->error);
            $_SESSION['admin_message'] = 'Error: Could not remove contact message. ' . $stmt->error;
        }
        $stmt->close();
    } else {
        error_log("Error preparing remove message statement: " . $mysqli->error);
        $_SESSION['admin_message'] = 'Error: Database prepare error. ' . $mysqli->error;
    }

// Remove User ---
} elseif ($action === 'remove_user') {
    $user_id_to_remove = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

    if (!$user_id_to_remove || $user_id_to_remove == $_SESSION['user_id']) {
        $_SESSION['admin_message'] = 'Error: Invalid User ID or cannot remove own admin account.';
        header('Location: admin.php');
        exit;
    }

    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $user_id_to_remove);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['admin_message'] = 'User removed successfully.';
            } else {
                $_SESSION['admin_message'] = 'Error: User not found or already removed.';
            }
        } else {
            error_log("Error removing user: " . $stmt->error);
            $_SESSION['admin_message'] = 'Error: Could not remove user. ' . $stmt->error;
        }
        $stmt->close();
    } else {
        error_log("Error preparing remove user statement: " . $mysqli->error);
        $_SESSION['admin_message'] = 'Error: Database prepare error. ' . $mysqli->error;
    }

} else {
    $_SESSION['admin_message'] = 'Error: Unknown action specified.';
}

if (isset($mysqli) && $mysqli instanceof mysqli && $mysqli->thread_id) {
     $mysqli->close();
}

header('Location: admin.php');
exit;
?>
