<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    
    if ($stmt->execute()) {
        echo "<div class='container'><p class='success'>✅ User registered successfully!</p><a href='login.php'>🔐 Login here</a></div>";
    } else {
        echo "<div class='container'><p class='error'>❌ Username may already exist.</p></div>";
    }
    $stmt->close();
}
?>

<link rel="stylesheet" href="styles1.css">
<div class="container">
    <h2>Create Your Account</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <a href="login.php">Already have an account? Login</a>
</div>
