<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Bank Transfer - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <nav><a href="deposit.php" class="back">← Back</a></nav>
        <h2>Bank Transfer Details</h2>
        <p>Complete the transfer using the details below</p>
        
        <div class="bank-details">
            <div class="detail">
                <strong>Bank Name:</strong> SecureTrust Bank
            </div>
            <div class="detail">
                <strong>Account Name:</strong> SecureWallet Processing Ltd
            </div>
            <div class="detail">
                <strong>Account Number:</strong> 1234567890
            </div>
            <div class="detail">
                <strong>Routing Number:</strong> 021000021
            </div>
            <div class="detail">
                <strong>Reference:</strong> <span class="ref">SWP-<?php echo $_SESSION['user_id'] . rand(1000,9999); ?></span>
            </div>
        </div>
        
        <div class="instruction">
            <h3>Instructions:</h3>
            <ol>
                <li>Include the Reference number in your transfer</li>
                <li>Processing takes 1-3 business days</li>
                <li>Contact support if not credited within 72 hours</li>
            </ol>
        </div>
    </div>
</body>
</html>
