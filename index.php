<?php 
require_once 'config.php'; 
// Check if coming from login error
$error = isset($_GET['error']) ? 'Login failed. Check your credentials.' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="logo">
                <h1>💰 SecureWallet Pro</h1>
                <p>Your trusted digital wallet</p>
            </div>
            
            <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="login.php">
                <input type="text" name="email" placeholder="Email or Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn-primary">Login</button>
            </form>
            
            <p class="signup-link">New user? <a href="register.php">Create account</a></p>
            
            <div class="features">
                <div>🔒 2FA Protection</div>
                <div>⚡ Instant Deposits</div>
                <div>🌍 Multi-currency</div>
            </div>
        </div>
    </div>
</body>
</html>
