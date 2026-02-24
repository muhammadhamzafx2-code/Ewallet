<?php require_once 'config.php'; if (!$_SESSION['user_id']) header('Location: index.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Withdraw - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <nav><a href="dashboard.php" class="back">← Back</a></nav>
        <h2>Withdraw Funds</h2>
        <p class="withdraw-rule"><strong>You can only withdraw to the bank/card you deposited with</strong></p>
        
        <div class="withdraw-methods">
            <div class="method-card withdraw-card">
                <div class="icon">💳</div>
                <h3>Credit/Debit Card</h3>
                <p>Last used for deposit</p>
                <a href="#" class="btn-small">Withdraw to Card</a>
            </div>
            
            <a href="withdraw_new.php" class="method-card new-method">
                <div class="icon">➕</div>
                <h3>Add New Withdrawal Method</h3>
                <p>Minimum deposit $50 first</p>
            </a>
        </div>
    </div>
</body>
</html>
