<?php
require_once 'config.php';
if (!$_SESSION['user_id']) header('Location: index.php');

$stmt = $db->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <nav>
            <a href="dashboard.php" class="back">← Back</a>
            <h2>Transaction History</h2>
        </nav>
        
        <div class="transactions-list">
            <?php foreach ($transactions as $tx): ?>
            <div class="transaction <?php echo $tx['status']; ?>">
                <div class="tx-info">
                    <span class="type"><?php echo ucfirst($tx['type']); ?> - <?php echo $tx['method']; ?></span>
                    <span class="date"><?php echo date('M j, Y H:i', strtotime($tx['created_at'])); ?></span>
                </div>
                <div class="tx-amount">
                    <?php if($tx['type'] == 'deposit'): ?>
                        <span class="positive">+$<?php echo number_format($tx['amount'], 2); ?></span>
                    <?php else: ?>
                        <span class="negative">-$<?php echo number_format($tx['amount'], 2); ?></span>
                    <?php endif; ?>
                    <span class="status <?php echo $tx['status']; ?>">
                        <?php echo ucfirst($tx['status']); ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
