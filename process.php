<?php
session_start();

if (!isset($_SESSION['Username']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['Username'];
$newEmail = trim($_POST['email'] ?? '');

if (!empty($newEmail) && filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=user", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update database query
        $stmt = $pdo->prepare("UPDATE user SET Email = ? WHERE Username = ?");
        $stmt->execute([$newEmail, $username]);

        header("Location: profile.php?message=Profile updated successfully!");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    header("Location: profile.php?message=Invalid email format.");
    exit();
}