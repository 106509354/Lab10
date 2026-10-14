
<?php
session_start();

if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['Username'];
$message = $_GET['message'] ?? '';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=user", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT Username, Email FROM user WHERE Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($user['Username']); ?>!</h2>

    <?php if (!empty($message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h3>Your Details</h3>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['Username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>

    <h3>Edit Profile</h3>
    <form method="POST" action="process.php">
        <label>New Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required><br><br>
        <button type="submit">Update Email</button>
    </form>

    <br>
    <p><a href="login.php">Logout</a></p>
</body>
</html>