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
        <div>💰 SecureWallet</div>
        <div>
            Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>!
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <div class="container">
        <div class="balance-card">
            <h2>Balance: $<?php echo number_format($user['balance'], 2); ?></h2>
        </div>
        <div class="actions">
            <a href="deposit.php">💳 Deposit</a>
            <a href="withdraw.php">💸 Withdraw</a>
            <a href="transactions.php">📊 History</a>
        </div>
    </div>
</body>
</html>
