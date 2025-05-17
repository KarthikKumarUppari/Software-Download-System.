<?php
include 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $version = $_POST['version'];
    $platform = $_POST['platform'];
    
    $file = $_FILES['file'];
    $filename = basename($file['name']);
    $tmp_name = $file['tmp_name'];
    $file_type = $file['type'];

    // Check allowed file types
    $allowed_file_types = [
         'application/zip',
         'application/x-rar-compressed',
         'application/x-tar',
         'application/x-zip-compressed',
         'application/x-7z-compressed',
         'application/x-gzip',
         'application/vnd.microsoft.portable-executable',
         'application/x-msdownload',
         'application/octet-stream',
         'application/x-msinstaller',
         'application/x-debian-package',
         'application/x-redhat-package-manager',
         'application/x-compressed',
         'application/x-bzip2',
         'text/plain', // .txt files
         'image/jpeg', // .jpg or .jpeg files
         'image/png',
         'application/pdf',
        ];
        

    if (!in_array($file_type, $allowed_file_types)) {
        die("❌ Invalid file type. Only ZIP, RAR, and TAR ...etc.files are allowed.");
    }

    // Create the files directory if it doesn't exist
    if (!file_exists('files')) {
        mkdir('files', 0777, true);
    }

    $destination = "files/" . $filename;
    if (move_uploaded_file($tmp_name, $destination)) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO software (name, description, file_path, version, platform) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $desc, $destination, $version, $platform);
        $stmt->execute();
        $stmt->close();

        echo "✅ Software uploaded successfully! <a href='index.php'>Go back</a>";
    } else {
        echo "❌ Failed to upload file.";
    }
}
?>

<link rel="stylesheet" href="styles1.css">

<div class="container">


<h2>Upload New Software</h2>
<form method="POST" enctype="multipart/form-data">
    Name: <input name="name" required><br><br>
    Description: <textarea name="description" required></textarea><br><br>
    Version: <input name="version" required><br><br>
    Platform: <input name="platform" required><br><br>
    File: <input type="file" name="file" required><br><br>
    <button type="submit">Upload</button>
</form>

</div>
