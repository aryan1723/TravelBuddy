<?php
// fetch_news.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database Connection
require_once 'db_connect.php';

if (!isset($mysqli) || $mysqli->connect_errno) {
    $error_message = isset($mysqli) ? "({$mysqli->connect_errno}) {$mysqli->connect_error}" : "Connection object not found.";
    error_log("Fetch News Error: Database connection issue - " . $error_message);
    echo '<p class="text-red-500 text-center px-4 py-2">Error: Could not connect to the database.</p>';
    exit;
}

// Fetch News Items
$sql = "SELECT title, content, published_at, url FROM news ORDER BY published_at DESC LIMIT 5";
$result = $mysqli->query($sql);

$output = '';

if ($result && $result->num_rows > 0) {
    $output .= '<ul class="space-y-4 news-list">';
    while ($row = $result->fetch_assoc()) {
        $output .= '<li class="border-b border-gray-200 pb-3">';
        $output .= '<h4 class="font-semibold text-md mb-1">' . htmlspecialchars($row['title']) . '</h4>';
        $formatted_date = date("M d, Y H:i", strtotime($row['published_at']));
        $output .= '<p class="text-xs text-gray-500 mb-2">Published: ' . $formatted_date . '</p>';
        $output .= '<p class="text-sm text-gray-700">' . nl2br(htmlspecialchars($row['content'])) . '</p>';
        if (!empty($row['url']) && $row['url'] !== '#') {
            $output .= '<a href="' . htmlspecialchars($row['url']) . '" target="_blank" class="text-sm text-blue-600 hover:underline mt-1 inline-block">Read more...</a>';
        }
        $output .= '</li>';
    }
    $output .= '</ul>';
} else if ($result) {
    $output .= '<p class="text-center text-gray-500">No recent news available.</p>';
} else {
    error_log("Fetch News Error: SQL query failed - " . $mysqli->error);
    $output .= '<p class="text-red-500 text-center px-4 py-2">Error fetching news. Please check server logs.</p>';
}

// Close Connection
$mysqli->close();

echo $output;
?>
