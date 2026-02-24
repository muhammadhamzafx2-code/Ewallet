<?php require_once 'config.php'; ?>
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
                <p>Your trusted crypto & fiat wallet</p>
            </div>
            
            <form method="POST" action="login.php">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn-primary">Login</button>
            </form>
            
            <p class="signup-link">Don't have an account? <a href="register.php">Sign up free</a></p>
            
            <div class="features">
                <div>🔒 2FA Protected</div>
                <div>⚡ Instant Transactions</div>
                <div>🌍 Global Support</div>
            </div>
        </div>
    </div>
</body>
</html>
