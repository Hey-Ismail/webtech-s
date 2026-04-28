<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_name = $_SESSION['user_name'] ?? 'User';
$user_email = $_SESSION['user_email'] ?? '';
$last_login = isset($_COOKIE['last_login']) ? $_COOKIE['last_login'] : 'First login';

// Fetch user data from database
$stmt = $conn->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

if ($user_data) {
    $user_name = $user_data['name'];
    $user_email = $user_data['email'];
    $created_at = $user_data['created_at'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Dashboard</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </header>

        <div class="welcome-section">
            <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
            <p>You are successfully logged in</p>
        </div>

        <div class="info-section">
            <h3>Account Information</h3>
            <div class="info-card">
                <div class="info-label">Email Address</div>
                <div class="info-value"><?php echo htmlspecialchars($user_email); ?></div>
            </div>

            <div class="info-card">
                <div class="info-label">Full Name</div>
                <div class="info-value"><?php echo htmlspecialchars($user_name); ?></div>
            </div>

            <div class="stats">
                <div class="stat-box">
                    <div class="stat-label">Last Login</div>
                    <div class="stat-value"><?php echo htmlspecialchars($last_login); ?></div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Account Created</div>
                    <div class="stat-value"><?php echo date('M d, Y', strtotime($created_at ?? 'now')); ?></div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3>Session Status</h3>
            <div class="info-card">
                <div class="info-label">Session ID</div>
                <div class="info-value" style="font-family: monospace; font-size: 12px;"><?php echo htmlspecialchars(session_id()); ?></div>
            </div>
            <div class="info-card">
                <div class="info-label">Status</div>
                <div class="info-value" style="color: #27ae60;">✓ Active Session</div>
            </div>
        </div>
    </div>
</body>
</html>
