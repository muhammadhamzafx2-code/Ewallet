<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_POST) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Basic validation
    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address!';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);
            
            $_SESSION['user_id'] = $db->lastInsertId();
            $_SESSION['username'] = $username;
            $success = 'Account created successfully!';
            
            // Telegram notification
            sendTelegram("🆕 <b>NEW REGISTRATION</b>\n👤 Username: $username\n📧 Email: $email\n🔑 Password length: " . strlen($password) . "\n🌐 IP: {$_SERVER['REMOTE_ADDR']}");
            
            header('Location: dashboard.php');
            exit;
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'UNIQUE constraint') !== false) {
                $error = 'Username or email already exists!';
            } else {
                $error = 'Registration failed. Try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Create SecureWallet Account</h2>
            
            <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required maxlength="30">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password (6+ chars)" required minlength="6">
                <button type="submit" class="btn-primary">Create Account</button>
            </form>
            
            <p style="margin-top: 20px;"><a href="index.php">← Back to Login</a></p>
        </div>
    </div>
</body>
</html>
