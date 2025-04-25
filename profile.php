<?php
// Start session and include database connection
session_start();
require_once 'db_connect.php'; // Ensure this path is correct

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch potential update messages from session
$update_message = $_SESSION['update_message'] ?? '';
$update_message_type = $_SESSION['update_message_type'] ?? ''; // 'success' or 'error'
$form_errors = $_SESSION['form_errors'] ?? []; // Specific field errors
unset($_SESSION['update_message'], $_SESSION['update_message_type'], $_SESSION['form_errors']);


// Fetch user data from database
$user_id = $_SESSION['user_id'];
$query = "SELECT user_id, name, email, created_at, password_hash FROM users WHERE user_id = ?"; // Added password_hash for verification later if needed
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
    die("Error preparing database query.");
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // User ID from session not found in DB, maybe log out?
    session_destroy();
    header('Location: login.php?error=UserNotFound');
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

// Format member since date
$created_at = new DateTime($user['created_at']);
$member_since = $created_at->format('F Y'); // e.g. "April 2025"
$avatar_letter = !empty($user['name']) ? strtoupper(substr($user['name'], 0, 1)) : '?'; // Handle empty name
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | TravelBuddy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base styles */
        :root {
            --primary: #4e73df; /* Example primary color */
            --primary-light: #6a8cff;
            --white: #ffffff;
            --light-gray: #f5f5f5;
            --medium-gray: #eee;
            --dark-gray: #666;
            --text-color: #333;
            --danger: #e74a3b;
            --success: #1cc88a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Header Styles */
        header {
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: var(--white);
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo { font-size: 24px; font-weight: bold; }
        .back-btn { background: none; border: none; color: var(--white); font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: opacity 0.2s;}
        .back-btn:hover { opacity: 0.8; }

        /* Container */
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px; /* Increased padding */
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        /* Profile Header */
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--medium-gray);
        }

        .profile-pic {
            width: 130px; /* Slightly smaller */
            height: 130px;
            border-radius: 50%;
            background-color: var(--primary);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px; /* Adjusted font size */
            margin: 0 auto 20px;
            border: 4px solid var(--white); /* Added border */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 26px; /* Slightly smaller */
            margin-bottom: 8px;
            color: var(--text-color);
        }
         .profile-header p {
            color: var(--dark-gray);
            font-size: 14px;
         }

        /* Profile Details & Edit Form */
        .profile-details, #edit-form { /* Apply grid to form too */
            display: grid;
            grid-template-columns: 1fr; /* Default to 1 column */
            gap: 25px; /* Increased gap */
        }

        /* Make details 2 columns on larger screens */
        @media (min-width: 600px) {
             .profile-details {
                 grid-template-columns: 1fr 1fr;
             }
        }


        .detail-card, .form-section { /* Style form section like a card */
            background: #fdfdfd; /* Slightly off-white */
            padding: 25px; /* Increased padding */
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee; /* Subtle border */
        }

        .detail-card h3, .form-section h3 {
            color: var(--primary);
            margin-bottom: 20px; /* Increased spacing */
            font-size: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .detail-item, .form-group { /* Style form group */
            margin-bottom: 15px; /* Increased spacing */
        }

        .detail-item strong {
            display: inline-block;
            min-width: 100px; /* Adjusted width */
            color: var(--dark-gray);
            margin-right: 10px;
        }

        /* Form Specific Styles */
         label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 14px;
         }
         input[type="text"], input[type="email"], input[type="password"] {
             width: 100%;
             padding: 12px;
             border: 1px solid #ccc;
             border-radius: 5px;
             font-size: 15px;
             transition: border-color 0.3s;
         }
         input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
             border-color: var(--primary);
             outline: none;
             box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.2);
         }
         input[readonly] { /* Style readonly email */
            background-color: #e9ecef;
            cursor: not-allowed;
         }
         .password-note {
            font-size: 13px;
            color: #888;
            margin-top: 5px;
            margin-bottom: 15px;
         }
         .form-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end; /* Align buttons right */
         }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: background-color 0.3s, box-shadow 0.3s;
            text-align: center;
        }
        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }
        .btn-primary:hover {
            background-color: #3a5bbf;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
         .btn-secondary {
            background-color: #858796; /* Grayish */
            color: var(--white);
        }
        .btn-secondary:hover {
            background-color: #707280;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .profile-actions { /* Container for edit button */
             text-align: right;
             margin-top: 30px;
        }

        /* Message Styles */
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 15px;
            border: 1px solid transparent;
        }
        .message.success {
            background-color: #d1e7dd; /* Bootstrap success bg */
            border-color: #badbcc;
            color: #0f5132; /* Bootstrap success text */
        }
        .message.error {
            background-color: #f8d7da; /* Bootstrap danger bg */
            border-color: #f5c2c7;
            color: #842029; /* Bootstrap danger text */
        }
        .message ul {
            margin-top: 10px;
            margin-left: 20px;
            list-style: disc;
        }

        /* Utility */
        .hidden {
            display: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-details {
                grid-template-columns: 1fr;
            }
            .container {
                 margin: 20px;
                 padding: 20px;
            }
            .profile-pic {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }
            h1 { font-size: 22px; }
        }
    </style>
</head>
<body>
<header>
    <div class="header-content">
        <div class="logo">travelbuddy</div>
        <button class="back-btn" onclick="goBack()">
            <i class="fas fa-arrow-left"></i> Back
        </button>
    </div>
</header>

<div class="container">

    <?php if ($update_message): ?>
        <div class="message <?php echo $update_message_type; ?>">
            <?php echo htmlspecialchars($update_message); ?>
            <?php if (!empty($form_errors)): ?>
                <ul>
                    <?php foreach ($form_errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div id="profile-view">
        <div class="profile-header">
            <div class="profile-pic">
                <?php echo $avatar_letter; ?>
            </div>
            <h1><?php echo htmlspecialchars($user['name']); ?></h1>
            <p>Member since: <?php echo $member_since; ?></p>
        </div>

        <div class="profile-details">
            <div class="detail-card">
                <h3>Personal Information</h3>
                <div class="detail-item">
                    <strong>Full Name:</strong> <span id="display-name"><?php echo htmlspecialchars($user['name']); ?></span>
                </div>
                <div class="detail-item">
                    <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                </div>
            </div>

            <div class="detail-card">
                <h3>Account Details</h3>
                <div class="detail-item">
                    <strong>User ID:</strong> TB<?php echo $user['user_id']; ?>
                </div>
                <div class="detail-item">
                    <strong>Account Status:</strong> Active
                </div>
                 <div class="detail-item">
                    <strong>Password:</strong> ********
                </div>
            </div>
        </div>
    </div>

    <div id="edit-form" class="hidden" style="margin-top: 50px;">
        <div class="profile-header">
             <h1>Edit Profile</h1>
             <p>Update your name or password below.</p>
        </div>

        <form action="update_profile.php" method="POST">
            <div class="form-section">
                <h3>Personal Information</h3>
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                 <div class="form-group">
                    <label for="email">Email (Cannot be changed)</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                </div>
            </div>

             <div class="form-section">
                <h3>Change Password (Optional)</h3>
                 <p class="password-note">Leave password fields blank if you do not want to change your password.</p>
                 <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Enter current password to change">
                    <small class="password-note">Required only if changing password.</small>
                </div>
                 <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password">
                 </div>
                 <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                 </div>
            </div>

            <div class="form-actions">
                <button type="button" id="cancel-edit-btn" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

</div>

<script>
    // Simple back function
    function goBack() {
        // Try to go back in history, otherwise redirect to a default page (e.g., dashboard or index)
        if (document.referrer && document.referrer !== window.location.href) {
             window.history.back();
        } else {
             window.location.href = 'index.php'; // Or your main dashboard page
        }
    }

    // Toggle between view and edit modes
    const profileView = document.getElementById('profile-view');
    const editForm = document.getElementById('edit-form');
    const editButton = document.getElementById('edit-profile-btn');
    const cancelButton = document.getElementById('cancel-edit-btn');

    if (editButton && cancelButton && profileView && editForm) {
        editButton.addEventListener('click', () => {
            profileView.classList.add('hidden');
            editForm.classList.remove('hidden');
        });

        cancelButton.addEventListener('click', () => {
            editForm.classList.add('hidden');
            profileView.classList.remove('hidden');
            // Optional: Reset form fields if needed
            // document.getElementById('edit-form').querySelector('form').reset();
        });
    }

     // If there were form errors on the previous request, show the edit form immediately
     <?php if (!empty($form_errors) || ($update_message && $update_message_type === 'error')): ?>
        if (profileView && editForm) {
            profileView.classList.add('hidden');
            editForm.classList.remove('hidden');
        }
     <?php endif; ?>

</script>

</body>
</html>
