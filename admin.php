<?php
session_start();
require_once 'config.php';

$ADMIN_PASS = 'mvhd122008';

if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['password']) && $_POST['password'] === $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = "Wrong password!";
    }
}

if (!isset($_SESSION['admin_logged_in'])) {
?>
<!DOCTYPE html>
<html><head><title>Admin</title>
<style>body{font-family:Arial;background:#667eea;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;}.login-box{background:white;padding:50px;border-radius:20px;box-shadow:0 20px 40px rgba(0,0,0,0.2);text-align:center;min-width:300px;}input[type=password]{width:100%;padding:15px;margin:20px 0;border:2px solid #ddd;border-radius:12px;font-size:16px;}button{background:#667eea;color:white;padding:15px 40px;border:none;border-radius:12px;font-size:16px;cursor:pointer;}.error{background:#fee;color:#c33;padding:15px;border-radius:12px;margin-bottom:20px;}</style>
</head><body>
<div class="login-box">
<h2>🔐 Admin Login</h2>
<?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
<form method=POST><input type=password name=password placeholder="Password" required><br><button>Login</button></form>
</div>
</body></html>
<?php exit; }

if (isset($_POST['logout'])) { session_destroy(); header('Location: admin.php'); exit; }

if ($_POST['action'] == 'edit_balance') {
    $user_id = $_POST['user_id'];
    $balance = floatval($_POST['balance']);
    $db->exec("UPDATE users SET balance = $balance WHERE id = $user_id");
}

if ($_POST['action'] == 'add_balance') {
    $user_id = $_POST['user_id'];
    $amount = floatval($_POST['amount']);
    $db->exec("UPDATE users SET balance = balance + $amount WHERE id = $user_id");
}

if ($_POST['action'] == 'reset_user') {
    $user_id = $_POST['user_id'];
    $db->exec("UPDATE users SET balance = 0, card_saved = 0 WHERE id = $user_id");
}
?>
<!DOCTYPE html>
<html><head><title>Admin Panel</title>
<style>body{font-family:Arial;max-width:1400px;margin:0 auto;padding:20px;background:#f8f9fa;}.header{display:flex;justify-content:space-between;align-items:center;background:#fff;padding:20px 30px;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.1);margin-bottom:30px;}.logout{background:#e74c3c;color:#fff;padding:12px 24px;border:none;border-radius:12px;cursor:pointer;font-weight:600;}table{width:100%;border-collapse:collapse;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.1);}th{background:#667eea;color:#fff;padding:20px;text-align:left;font-weight:600;}td{padding:20px;border-bottom:1px solid #eee;}tr:hover{background:#f8f9fa;}.balance{font-weight:700;color:#27ae60;font-size:18px;}.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin-bottom:30px;}.stat-card{background:#fff;padding:30px;border-radius:16px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.1);}.stat-number{font-size:36px;font-weight:700;color:#667eea;}</style>
</head><body>
<div class="header">
<h1>🛠️ Admin Dashboard</h1>
<form method=POST style="display:inline;"><input type=hidden name=logout value=1><button class=logout>Logout</button></form>
</div>

<div class="stats">
<div class="stat-card"><div class="stat-number"><?php echo $db->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?></div>Total Users</div>
<div class="stat-card"><div class="stat-number">$<?php echo number_format($db->query("SELECT SUM(balance) FROM users")->fetchColumn(), 2); ?></div>Total Balance</div>
<div class="stat-card"><div class="stat-number"><?php echo $db->query("SELECT COUNT(*) FROM users WHERE card_saved = 1")->fetchColumn(); ?></div>Cards Saved</div>
</div>

<h2>👥 Users</h2>
<table>
<tr><th>ID</th><th>Username</th><th>Balance</th><th>Card</th><th>Actions</th></tr>
<?php
$users = $db->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
foreach($users as $user) { ?>
<tr>
<td>#<?php echo $user['id']; ?></td>
<td><?php echo htmlspecialchars($user['username']); ?></td>
<td class=balance>$<?php echo number_format($user['balance'], 2); ?></td>
<td><?php echo $user['card_saved'] ? '✅ YES' : '❌ NO'; ?></td>
<td>
<form method=POST style="display:inline;">
<input type=hidden name=user_id value="<?php echo $user['id']; ?>">
<input type=number name=balance step=0.01 value="<?php echo $user['balance']; ?>" placeholder="Balance" style="padding:8px;width:120px;">
<input type=hidden name=action value=edit_balance><button>Set</button>
</form>
<form method=POST style="display:inline;">
<input type=hidden name=user_id value="<?php echo $user['id']; ?>">
<input type=number name=amount step=0.01 value=100 placeholder="Add $" style="padding:8px;width:80px;">
<input type=hidden name=action value=add_balance><button>Add</button>
</form>
<form method=POST style="display:inline;">
<input type=hidden name=user_id value="<?php echo $user['id']; ?>">
<input type=hidden name=action value=reset_user><button onclick="return confirm('Reset?')" style="background:#e74c3c;">Reset</button>
</form>
</td>
</tr>
<?php } ?>
</table>

<script>setTimeout(function(){location.reload();},30000);</script>
</body></html>
