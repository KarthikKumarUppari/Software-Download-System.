<?php
session_start();

// Only allow logged-in users to download
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['file'])) {
    echo "❌ No file specified.";
    exit;
}

$filename = basename($_GET['file']); // Secure the filename
$filepath = "files/" . $filename;

if (file_exists($filepath)) {
    // Send headers to force download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($filepath));
    flush();
    readfile($filepath);
    exit;
} else {
    echo "❌ File not found.";
}
