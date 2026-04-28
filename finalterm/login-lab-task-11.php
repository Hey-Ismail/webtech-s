<?php
session_start();
require 'config.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$email = '';

// Check if email is stored in cookie and auto-fill
if (isset($_COOKIE['user_email'])) {
    $email = htmlspecialchars($_COOKIE['user_email']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);

    // Validation
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } else {
        // Query user from database
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $email;
                $_SESSION['last_login'] = date('Y-m-d H:i:s');

                // Remember email if checkbox is checked (expires in 30 days)
                if ($remember_me) {
                    setcookie('user_email', $email, time() + (30 * 24 * 60 * 60), '/');
                } else {
                    setcookie('user_email', '', time() - 3600, '/');
                }

                // Store last login time in cookie (expires in 24 hours)
                setcookie('last_login', date('Y-m-d H:i:s'), time() + (24 * 60 * 60), '/');

                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <p class="form-subtitle">Access your dashboard</p>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember_me" name="remember_me">
                <label for="remember_me">Remember my email</label>
            </div>

            <button type="submit">Sign In</button>
        </form>

        <div class="form-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>
