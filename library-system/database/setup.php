<?php

require_once __DIR__ . '/../config/database.php';

$message = '';
$error = '';

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS);

if (!$connection) {
    $error = 'Database connection failed: ' . mysqli_connect_error();
} else {
    $databaseSql = sprintf(
        'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
        DB_NAME
    );

    if (mysqli_query($connection, $databaseSql)) {
        mysqli_select_db($connection, DB_NAME);

        $tableSql = "CREATE TABLE IF NOT EXISTS books (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(150) NOT NULL,
            author_name VARCHAR(120) NOT NULL,
            category VARCHAR(100) NOT NULL,
            availability_status VARCHAR(20) NOT NULL DEFAULT 'Available',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if (mysqli_query($connection, $tableSql)) {
            $message = 'Database and books table are ready. You can now open the main library dashboard.';
        } else {
            $error = 'Database created, but the table could not be created: ' . mysqli_error($connection);
        }
    } else {
        $error = 'Unable to create the database: ' . mysqli_error($connection);
    }

    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Setup</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: 'Trebuchet MS', sans-serif;
            background: linear-gradient(180deg, #fbf7ef 0%, #f4efe4 100%);
            color: #1f2a2e;
        }

        .card {
            width: min(680px, calc(100% - 32px));
            padding: 28px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.14);
        }

        h1 {
            margin-top: 0;
            font-family: Georgia, 'Times New Roman', serif;
        }

        .success {
            padding: 14px 16px;
            border-radius: 14px;
            background: #e7f8ef;
            color: #14532d;
            border: 1px solid #a7f3d0;
            margin: 18px 0 0;
        }

        .error {
            padding: 14px 16px;
            border-radius: 14px;
            background: #fde8e8;
            color: #991b1b;
            border: 1px solid #fecaca;
            margin: 18px 0 0;
        }

        a {
            display: inline-block;
            margin-top: 18px;
            color: #134e4a;
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Library Database Setup</h1>
        <p>This page creates the database and books table required by the MVC library system.</p>

        <?php if ($message !== ''): ?>
            <div class="success"><?php echo htmlspecialchars($message); ?></div>
        <?php else: ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <a href="../index.php">Open the library dashboard</a>
    </main>
</body>
</html>


//git commit -m "Lab task -12"