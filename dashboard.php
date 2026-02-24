
<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - SecureWallet Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard">
    <nav>
        <div class="logo-nav">💰 SecureWallet</div>
        <div class="user-nav">
            <span>Hi, <?php echo htmlspecialchars($user['username']); ?>!</span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="balance-card">
            <h2>Your Balance</h2>
            <div class="balance">$<?php echo number_format($user['balance'], 2); ?></div>
        </div>
        
        <div class="action-grid">
            <a href="deposit.php" class="action-card deposit">
                <div class="icon">💳</div>
                <h3>Deposit</h3>
            </a>
            <a href="withdraw.php" class="action-card withdraw">
                <div class="icon">💸</div>
                <h3>Withdraw</h3>
            </a>
            <a href="transactions.php" class="action-card history">
                <div class="icon">📊</div>
                <h3>History</h3>
            </a>
        </div>
    </div>
</body>
</html>
