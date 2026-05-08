<?php
session_start();

$validUsername = 'admin';
$validPassword = '12345';
$errorMessage = '';

if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $errorMessage = 'Please enter both username and password.';
    } elseif ($username === $validUsername && $password === $validPassword) {
        $_SESSION['username'] = $username;
        session_regenerate_id(true);

        header('Location: dashboard.php');
        exit;
    } else {
        $errorMessage = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Demo - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="auth-shell">
        <section class="auth-card">
            <div class="auth-copy">
                <p class="eyebrow">PHP Session Demo</p>
                <h1>Login to see session state across pages.</h1>
                <p>
                    Enter the demo credentials to store the username in a session variable and open the dashboard.
                </p>
            </div>

            <?php if ($errorMessage !== ''): ?>
                <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>

            <form method="post" class="auth-form" autocomplete="off">
                <label>
                    <span>Username</span>
                    <input type="text" name="username" required>
                </label>

                <label>
                    <span>Password</span>
                    <input type="password" name="password" required>
                </label>

                <button type="submit">Login</button>
            </form>

            <div class="hint-box">
                Demo credentials: <strong>admin</strong> / <strong>12345</strong>
            </div>
        </section>
    </main>
</body>
</html>
