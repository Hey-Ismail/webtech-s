<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Demo - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="dashboard-shell">
        <section class="dashboard-card">
            <p class="eyebrow">Dashboard</p>
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
            <p>
                This page reads the username from the PHP session, which means the login state is preserved
                even after moving from the login page to the dashboard.
            </p>

            <div class="session-panel">
                <div>
                    <span>Session Username</span>
                    <strong><?php echo htmlspecialchars($username); ?></strong>
                </div>
                <div>
                    <span>Session ID</span>
                    <strong><?php echo htmlspecialchars(session_id()); ?></strong>
                </div>
            </div>

            <a class="logout-link" href="logout.php">Logout</a>
        </section>
    </main>
</body>
</html>
