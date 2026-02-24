<?php
require_once 'config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['withdraw_method'])) {
    header('Location: withdraw.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Verification - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard">
    <nav>
        <a href="withdraw.php" class="back">← Back</a>
    </nav>
    
    <div class="container">
        <h2>Withdrawal Verification Required</h2>
        
        <div class="verification-box">
            <div class="icon">🔒</div>
            <h3>Security Check</h3>
            <p><strong>To enable <?php echo strtoupper($_SESSION['withdraw_method']); ?> withdrawals:</strong></p>
            <div class="req-list">
                <div class="req-item">✅ Make a minimum $50 deposit first</div>
                <div class="req-item">⚠️  New methods require verification</div>
            </div>
            
            <div class="current-balance">
                <strong>Current Balance: $<span id="balance"><?php echo number_format($user['balance'], 2); ?></span></strong>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="deposit.php" class="btn-large btn-deposit">
                💳 Make $50+ Deposit →
            </a>
            <a href="withdraw.php" class="btn-large btn-cancel">
                Cancel & Return
            </a>
        </div>
        
        <div class="faq">
            <h4>FAQ</h4>
            <p><strong>Why can't I withdraw immediately?</strong><br>
            This protects against fraud. One deposit confirms ownership of payment method.</p>
        </div>
    </div>
    
    <script>
    // Fake balance fluctuation for realism
    setInterval(() => {
        const balance = document.getElementById('balance');
        let current = parseFloat(balance.textContent.replace(/,/g, ''));
        let change = (Math.random() - 0.5) * 0.05;
        let newBalance = Math.max(0, current + change).toFixed(2);
        balance.textContent = newBalance.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }, 5000);
    </script>
</body>
</html>
