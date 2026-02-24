<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: index.php');

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <nav>
            <div class="logo">💰 SecureWallet Pro</div>
            <div class="user-info">
                <span>Hi, <?php echo $user['username']; ?>!</span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </nav>
        
        <div class="balance-card">
            <h2>Available Balance</h2>
            <div class="balance">$<?php echo number_format($user['balance'], 2); ?></div>
        </div>
        
        <div class="action-buttons">
            <a href="deposit.php" class="btn-large btn-deposit">💳 Deposit</a>
            <a href="withdraw.php" class="btn-large btn-withdraw">💸 Withdraw</a>
            <a href="transactions.php" class="btn-large btn-history">📊 History</a>
        </div>
        
        <div class="stats">
            <div class="stat">
                <h3>Total Deposits</h3>
                <span>$1,250.00</span>
            </div>
            <div class="stat">
                <h3>Total Withdrawals</h3>
                <span>$980.00</span>
            </div>
        </div>
    </div>
</body>
</html>
