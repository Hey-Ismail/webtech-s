<?php
// Database setup script - Run this once to create the database and table

$conn = new mysqli('localhost', 'root', '');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS auth_system";
if ($conn->query($sql) === TRUE) {
    echo "✓ Database created successfully or already exists.<br>";
} else {
    echo "✗ Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db('auth_system');

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "✓ Users table created successfully or already exists.<br>";
} else {
    echo "✗ Error creating table: " . $conn->error . "<br>";
}

$conn->close();
echo "<br><strong>✓ Database setup completed!</strong><br>";
echo "You can now <a href='register.php'>Register</a> or <a href='login.php'>Login</a>";
?>
