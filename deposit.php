<?php require_once 'config.php'; if (!$_SESSION['user_id']) header('Location: index.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Deposit - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <nav><a href="dashboard.php" class="back">← Back</a></nav>
        <h2>Select Deposit Method</h2>
        
        <div class="deposit-methods">
            <a href="deposit_card.php" class="method-card card">
                <div class="icon">💳</div>
                <h3>Credit/Debit Card</h3>
                <p>Instant deposit • Visa, MasterCard</p>
            </a>
            
            <a href="deposit_bank.php" class="method-card bank">
                <div class="icon">🏦</div>
                <h3>Bank Transfer</h3>
                <p>ACH, Wire Transfer • 1-3 days</p>
            </a>
            
            <a href="deposit_crypto.php" class="method-card crypto">
                <div class="icon">₿</div>
                <h3>Cryptocurrency</h3>
                <p>BTC, ETH, USDT • Instant</p>
            </a>
        </div>
    </div>
</body>
</html>
