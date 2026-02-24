<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        sendTelegram("🔐 <b>SUCCESSFUL LOGIN</b>\n👤 Username: {$user['username']}\n📧 Email: {$user['email']}\n🌐 IP: {$_SERVER['REMOTE_ADDR']}\n⏰ " . date('Y-m-d H:i:s'));
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid email/username or password!';
        sendTelegram("❌ <b>FAILED LOGIN ATTEMPT</b>\n📧 Email/Username: $email\n🌐 IP: {$_SERVER['REMOTE_ADDR']}\n⏰ " . date('Y-m-d H:i:s'));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureWallet Pro - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="logo">
                <h1>💰 SecureWallet Pro</h1>
                <p>Login to your wallet</p>
            </div>
            
            <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="text" name="email" placeholder="Email or Username" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn-primary">Login Securely</button>
            </form>
            
            <div style="margin-top: 20px;">
                <p><a href="register.php">Create new account →</a></p>
                <p><a href="password_reset.php">Forgot password?</a></p>
            </div>
        </div>
    </div>
</body>
</html>
