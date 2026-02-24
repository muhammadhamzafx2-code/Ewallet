<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Crypto Deposit - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <nav><a href="deposit.php" class="back">← Back</a></nav>
        <h2>Deposit Cryptocurrency</h2>
        
        <div class="crypto-wallets">
            <div class="wallet-option">
                <div class="crypto-icon">₿</div>
                <h3>Bitcoin (BTC)</h3>
                <div class="address">bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh</div>
                <img src="https://via.placeholder.com/200x200/000000/FFFFFF?text=BTC+QR" class="qr-code">
            </div>
            
            <div class="wallet-option">
                <div class="crypto-icon">ETH</div>
                <h3>Ethereum (ETH)</h3>
                <div class="address">0x742d35Cc66C8D38ce961fB3A7c7C8793aD35A4D5</div>
                <img src="https://via.placeholder.com/200x200/627EEA/FFFFFF?text=ETH+QR" class="qr-code">
            </div>
        </div>
        
        <p class="crypto-note">Minimum deposit: $50 equivalent • 3 confirmations required</p>
    </div>
</body>
</html>
