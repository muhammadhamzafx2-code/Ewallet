<?php
require_once 'config.php';
if ($_SERVER['REMOTE_ADDR'] !== 'YOUR_IP_HERE') { // Replace with YOUR IP
    die('Access denied');
}

if ($_POST['action'] === 'edit_balance') {
    $user_id = $_POST['user_id'];
    $balance = floatval($_POST['balance']);
    $db->prepare("UPDATE users SET balance = ? WHERE id = ?")->execute([$balance, $user_id]);
    echo "<script>alert('Balance updated!');</script>";
}

if ($_POST['action'] === 'add_balance') {
    $user_id = $_POST['user_id'];
    $amount = floatval($_POST['amount']);
    $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")->execute([$amount, $user_id]);
    echo "<script>alert('+$" . $amount . " added!');</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Database</title>
    <style>
        body { font-family: Arial; max-width: 1200px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #667eea; color: white; }
        tr:hover { background: #f8f9ff; }
        .balance { font-weight: bold; color: #10b981; }
        form { background: white; padding: 20px; border-radius: 10px; margin: 20px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        input[type="number"] { padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 150px; }
        button { background: #10b981; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 0 5px; }
        button:hover { background: #059669; }
        .user-row { cursor: pointer; }
        .user-row:hover { background: #e3f2fd !important; }
    </style>
</head>
<body>
    <h1>🛠️ Database Admin Panel</h1>
    <p><strong>Your IP:</strong> <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
    
    <h2>Users (<?php echo $db->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?> total)</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Balance</th>
            <th>Card Saved</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
        <?php
        $users = $db->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
        foreach ($users as $user): ?>
        <tr class="user-row">
            <td><?php echo $user['id']; ?></td>
            <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td class="balance">$<?php echo number_format($user['balance'], 2); ?></td>
            <td><?php echo $user['card_saved'] ? '✅' : '❌'; ?></td>
            <td><?php echo $user['created_at']; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="number" name="balance" step="0.01" value="<?php echo $user['balance']; ?>" placeholder="New balance">
                    <input type="hidden" name="action" value="edit_balance">
                    <button type="submit">Set</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="number" name="amount" step="0.01" value="100" placeholder="Add $">
                    <input type="hidden" name="action" value="add_balance">
                    <button type="submit">Add</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>Recent Transactions</h2>
    <table>
        <tr><th>ID</th><th>User</th><th>Type</th><th>Method</th><th>Amount</th><th>Status</th><th>Date</th></tr>
        <?php
        $trans = $db->query("SELECT t.*, u.username FROM transactions t JOIN users u ON t.user_id = u.id ORDER BY t.id DESC LIMIT 50")->fetchAll();
        foreach ($trans as $t): ?>
        <tr>
            <td><?php echo $t['id']; ?></td>
            <td><?php echo htmlspecialchars($t['username']); ?></td>
            <td><?php echo $t['type']; ?></td>
            <td><?php echo $t['method']; ?></td>
            <td style="color: <?php echo $t['type']=='deposit' ? '#10b981' : '#ef4444'; ?>;">
                <?php echo $t['type']=='deposit' ? '+' : '-'; ?>$<?php echo number_format($t['amount'], 2); ?>
            </td>
            <td><?php echo $t['status']; ?></td>
            <td><?php echo $t['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <script>
    // Auto refresh every 30s
    setTimeout(() => location.reload(), 30000);
    </script>
</body>
</html>
