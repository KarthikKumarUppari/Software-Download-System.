<?php
include 'db.php';
session_start();

// Pagination
$results_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Search
$search_query = "";
$search_term = "";
if (isset($_POST['search'])) {
    $search_term = $conn->real_escape_string($_POST['search']);
    $search_query = "WHERE name LIKE '%$search_term%'";
}

// Count total records
$count_sql = "SELECT COUNT(*) FROM software $search_query";
$count_result = $conn->query($count_sql);
$row = $count_result->fetch_row();
$total_results = $row[0];
$total_pages = ceil($total_results / $results_per_page);

// Fetch data
$sql = "SELECT * FROM software $search_query ORDER BY uploaded_at DESC LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Software Download Portal</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">

</head>
<body>
    <!-- Top Navigation Bar -->
<div class="navbar">
    <div class="navbar-container">
        <a href="index.php" class="logo">
        <img src="logo.png" alt="Logo" class="logo-img">  
                  Home</a>
        <div class="navbar-links">
            <?php if (!isset($_SESSION['user_id'])): ?>
              <button><a href="register.php">Register</a></button>
               <button> <a href="login.php">Login</a></button>
            <?php else: ?>
               <button> <a href="logout.php">Logout</a></button>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container">

    <h2>📦 Software Download Portal</h2>

    <!-- Upload Button -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="upload.php" class="fab">➕ Upload New Software</a>
    <?php endif; ?>

    <!-- Search -->
    <form method="POST">
        <input type="text" name="search" value="<?= htmlspecialchars($search_term) ?>" placeholder="🔍 Search Software..." />
        <button type="submit">Search</button>
    </form>

    <!-- Software Table -->
    <table>
        <thead>
            <tr>
                <th>📁 ID</th>
                <th>🧾 Name</th>
                <th>📝 Description</th>
                <th>🔢 Version</th>
                <th>💻 Platform</th>
                <th>⬇ Download</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['version']) ?></td>
                    <td><?= htmlspecialchars($row['platform']) ?></td>
                    <td><a href="download.php?file=<?= urlencode(basename($row['file_path'])) ?>">Download</a></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">No software found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="index.php?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

</div>
<script>
    // JavaScript to add the 'loaded' class to the body after the page loads
window.addEventListener('load', function() {
    document.body.classList.add('loaded');
});

    </script>
</body>
</html>
