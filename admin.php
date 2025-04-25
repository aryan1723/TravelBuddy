<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db_connect.php';

if (!defined('ADMIN_EMAIL')) {
    define('ADMIN_EMAIL', 'admin@travelbudy.in');
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

$contact_messages = [];
$result_messages = $mysqli->query("SELECT message_id, name, subject, message, submitted_at FROM contact_messages ORDER BY submitted_at DESC");
if ($result_messages) {
    while ($row = $result_messages->fetch_assoc()) {
        $contact_messages[] = $row;
    }
    $result_messages->free();
} else {
    error_log("Error fetching contact messages: " . $mysqli->error);
}

$users = [];
$admin_email_exclude = ADMIN_EMAIL;
$stmt_users = $mysqli->prepare("SELECT user_id, name, email, created_at FROM users WHERE email != ? ORDER BY created_at DESC");
if ($stmt_users) {
    $stmt_users->bind_param("s", $admin_email_exclude);
    $stmt_users->execute();
    $result_users = $stmt_users->get_result();
    while ($row = $result_users->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt_users->close();
} else {
    error_log("Error fetching users: " . $mysqli->error);
}

$news_items = [];
$result_news = $mysqli->query("SELECT id, title, content, published_at, url FROM news ORDER BY published_at DESC");
if ($result_news) {
    while ($row = $result_news->fetch_assoc()) {
        $news_items[] = $row;
    }
    $result_news->free();
} else {
    error_log("Error fetching news: " . $mysqli->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Travelbuddy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7fafc;
        }
        .travelbuddy-orange { color: #FF5E00; }
        .bg-travelbuddy-orange { background-color: #FF5E00; }
        .border-travelbuddy-orange { border-color: #FF5E00; }
        .hover\:bg-travelbuddy-orange-dark:hover { background-color: #E05500; }
        .welcome-message { font-size: 1.1em; color: #4a5568; }
        .section-title {
            border-bottom: 2px solid #FF5E00;
            padding-bottom: 8px;
            margin-bottom: 24px;
            font-size: 1.75rem;
            font-weight: 600;
            color: #2d3748;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 24px;
            margin-bottom: 24px;
        }
        .table th {
             background-color: #edf2f7;
             padding: 12px 16px;
             text-align: left;
             font-weight: 600;
             color: #4a5568;
             border-bottom: 2px solid #e2e8f0;
        }
        .table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }
        .table tbody tr:hover {
            background-color: #f7fafc;
        }
        .message-block, .news-block {
            border-left: 4px solid #FF5E00;
            padding: 16px;
            margin-bottom: 16px;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .message-block strong, .news-block strong {
             color: #FF5E00;
             font-weight: 600;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4a5568;
        }
        .form-input, .form-textarea {
             width: 100%;
             padding: 10px 12px;
             border: 1px solid #cbd5e0;
             border-radius: 6px;
             box-sizing: border-box;
             transition: border-color 0.3s ease, box-shadow 0.3s ease;
             color: #4a5568;
        }
        .form-input:focus, .form-textarea:focus {
             border-color: #FF5E00;
             outline: none;
             box-shadow: 0 0 0 3px rgba(255, 94, 0, 0.2);
        }
        .form-textarea {
            min-height: 100px;
        }
        .btn {
             padding: 10px 20px;
             border-radius: 6px;
             cursor: pointer;
             transition: background-color 0.3s ease, transform 0.1s ease;
             text-decoration: none;
             display: inline-block;
             font-size: 0.875rem;
             font-weight: 600;
             border: none;
        }
        .btn-primary {
            background-color: #FF5E00;
            color: white;
        }
        .btn-primary:hover {
            background-color: #E05500;
            transform: translateY(-1px);
        }
        .btn-danger {
            background-color: #e53e3e;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c53030;
             transform: translateY(-1px);
        }
        .btn-secondary {
             background-color: #718096;
             color: white;
        }
         .btn-secondary:hover {
             background-color: #4a5568;
             transform: translateY(-1px);
        }
        .alert { padding: 16px; margin-bottom: 24px; border-radius: 6px; border: 1px solid transparent; }
        .alert-success { background-color: #c6f6d5; color: #2f855a; border-color: #9ae6b4; }
        .alert-error { background-color: #fed7d7; color: #c53030; border-color: #feb2b2; }
    </style>
</head>
<body class="bg-gray-100">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
             <div class="text-2xl font-bold">travel<span class="travelbuddy-orange">buddy</span> <span class="text-lg font-normal text-gray-600">Admin</span></div>
             <div class="flex items-center space-x-4">
                <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <a href="logout.php" class="btn btn-secondary text-sm">
                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                </a>
             </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8">

        <h1 class="section-title">Admin Dashboard</h1>

        <?php if (isset($_SESSION['admin_message'])): ?>
            <div class="alert <?php echo strpos($_SESSION['admin_message'], 'Error') !== false ? 'alert-error' : 'alert-success'; ?>">
                <?php echo htmlspecialchars($_SESSION['admin_message']); ?>
            </div>
            <?php unset($_SESSION['admin_message']); ?>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="card">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Contact Messages</h2>
                <div class="max-h-96 overflow-y-auto pr-2">
                    <?php if (empty($contact_messages)): ?>
                        <p class="text-gray-500">No contact messages received yet.</p>
                    <?php else: ?>
                        <?php foreach ($contact_messages as $msg): ?>
                            <div class="message-block">
                                <strong>From:</strong> <?php echo htmlspecialchars($msg['name']); ?><br>
                                <strong>Subject:</strong> <?php echo htmlspecialchars($msg['subject'] ?: 'N/A'); ?><br>
                                <strong class="text-xs">Received:</strong> <span class="text-xs text-gray-500"><?php echo htmlspecialchars(date('M d, Y H:i', strtotime($msg['submitted_at']))); ?></span><br>
                                <strong class="mt-1 inline-block">Message:</strong>
                                <div class="pl-2 mt-1 text-sm text-gray-600 whitespace-pre-wrap break-words"><?php echo htmlspecialchars($msg['message']); ?></div>
                                 <form action="admin_actions.php" method="POST" class="mt-2">
                                     <input type="hidden" name="action" value="remove_message">
                                     <input type="hidden" name="message_id" value="<?php echo $msg['message_id']; ?>">
                                     <button type="submit" class="btn btn-danger btn-sm py-1 px-2 text-xs" onclick="return confirm('Delete this message?');">
                                         <i class="fas fa-trash-alt mr-1"></i>Delete
                                     </button>
                                 </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                 <h2 class="text-xl font-semibold text-gray-700 mb-4">Manage News</h2>

                 <h3 class="text-lg font-semibold text-gray-600 mb-3">Add New Item</h3>
                 <form action="admin_actions.php" method="POST" class="space-y-4">
                     <input type="hidden" name="action" value="add_news">
                     <div>
                         <label for="news-title" class="form-label">Title:</label>
                         <input type="text" id="news-title" name="title" class="form-input" required>
                     </div>
                     <div>
                         <label for="news-content" class="form-label">Content:</label>
                         <textarea id="news-content" name="content" rows="4" class="form-textarea" required></textarea>
                     </div>
                      <div>
                         <label for="news-url" class="form-label">URL (Optional):</label>
                         <input type="url" id="news-url" name="url" class="form-input" placeholder="https://example.com">
                     </div>
                     <button type="submit" class="btn btn-primary">
                         <i class="fas fa-plus mr-1"></i>Add News Item
                     </button>
                 </form>

                 <h3 class="text-lg font-semibold text-gray-600 mt-6 mb-3">Existing News Items</h3>
                 <div class="max-h-80 overflow-y-auto pr-2">
                     <?php if (empty($news_items)): ?>
                         <p class="text-gray-500">No news items found.</p>
                     <?php else: ?>
                         <?php foreach ($news_items as $item): ?>
                             <div class="news-block">
                                 <strong><?php echo htmlspecialchars($item['title']); ?></strong><br>
                                 <small class="text-gray-500">Published: <?php echo htmlspecialchars(date('M d, Y H:i', strtotime($item['published_at']))); ?></small>
                                 <div class="mt-1 text-sm text-gray-600 whitespace-pre-wrap break-words"><?php echo htmlspecialchars($item['content']); ?></div>
                                 <?php if (!empty($item['url']) && $item['url'] !== '#'): ?>
                                     <div class="mt-1 text-xs">
                                         <span class="text-gray-500">URL:</span> <a href="<?php echo htmlspecialchars($item['url']); ?>" target="_blank" class="text-blue-600 hover:underline break-all"><?php echo htmlspecialchars($item['url']); ?></a>
                                     </div>
                                 <?php endif; ?>
                                 <form action="admin_actions.php" method="POST" class="mt-2">
                                     <input type="hidden" name="action" value="remove_news">
                                     <input type="hidden" name="news_id" value="<?php echo $item['id']; ?>">
                                     <button type="submit" class="btn btn-danger btn-sm py-1 px-2 text-xs" onclick="return confirm('Are you sure you want to remove this news item?');">
                                         <i class="fas fa-trash-alt mr-1"></i>Remove
                                     </button>
                                 </form>
                             </div>
                         <?php endforeach; ?>
                     <?php endif; ?>
                 </div>
            </div>

        </div>

        <div class="card mt-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Registered Users</h2>
             <?php if (empty($users)): ?>
                 <p class="text-gray-500">No users registered (excluding admin).</p>
             <?php else: ?>
                 <div class="overflow-x-auto">
                     <table class="min-w-full table">
                         <thead>
                             <tr>
                                 <th>ID</th>
                                 <th>Name</th>
                                 <th>Email</th>
                                 <th>Registered At</th>
                                 <th>Actions</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php foreach ($users as $user): ?>
                                 <tr>
                                     <td>TB<?php echo htmlspecialchars($user['user_id']); ?></td>
                                     <td><?php echo htmlspecialchars($user['name']); ?></td>
                                     <td><?php echo htmlspecialchars($user['email']); ?></td>
                                     <td><?php echo htmlspecialchars(date('M d, Y H:i', strtotime($user['created_at']))); ?></td>
                                     <td>
                                         <form action="admin_actions.php" method="POST" class="inline">
                                              <input type="hidden" name="action" value="remove_user">
                                              <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                              <button type="submit" class="btn btn-danger btn-sm py-1 px-2 text-xs" onclick="return confirm('Delete user <?php echo htmlspecialchars(addslashes($user['name'])); ?>? This is irreversible.');">
                                                  <i class="fas fa-user-times mr-1"></i>Delete User
                                              </button>
                                         </form>
                                     </td>
                                 </tr>
                             <?php endforeach; ?>
                         </tbody>
                     </table>
                 </div>
             <?php endif; ?>
        </div>

    </div>

</body>
</html>
<?php
if (isset($mysqli) && $mysqli instanceof mysqli) {
    $mysqli->close();
}
?>
