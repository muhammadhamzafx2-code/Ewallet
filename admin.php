<?php
session_start();
require_once 'config.php';

$ADMIN_PASS = 'mvhd122008'; // Your password

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_POST['password'] === $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = "❌ Wrong password!";
    }
}

if (!isset($_SESSION['admin_logged_in'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .login-box { background: white; padding: 50px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); text-align: center; min-width: 300px; }
        input[type="password"] { width: 100%; padding: 15px; margin: 20px 0; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; }
        button { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 15px 40px; border: none; border-radius: 12px; font-size: 16px; cursor: pointer; }
        .error { background: #fef2f2; color: #dc2626; padding: 15px; border-radius: 12px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔐 Admin Login</h2>
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="password" name="password" placeholder="Enter password" required>
            <br><button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
<?php
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if ($_POST['action'] === 'edit_balance') {
    $user_id = $_POST['user_id'];
    $balance = floatval($_POST['balance']);
    $db->prepare("UPDATE users SET balance = ? WHERE id = ?")->execute([$balance, $user_id]);
    echo "<script>alert('✅ Balance updated!');</script>";
}

if ($_POST['action'] === 'add_balance') {
    $user_id = $_POST['user_id'];
    $amount = floatval($_POST['amount']);
    $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")->execute([$amount, $user_id]);
    echo "<script>alert('✅ +$" . $amount . " added!');</script>";
}

if ($_POST['action'] === 'reset_user'] {
    $user_id = $_POST['user_id'];
    $db->prepare("UPDATE users SET balance = 0, card_saved = 0 WHERE id = ?")->execute([$user_id]);
    echo "<script>alert('✅ User reset!');</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - SecureWallet Pro</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, sans-serif; max-width: 1400px; margin: 0 auto; padding: 20px; background: #f8fafc; }
        .header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 20px 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 30px; }
        .header h1 { color: #1e293b; margin: 0; }
        .logout { background: #ef4444; color: white; padding: 12px 24px; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.12); }
        th { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; text-align: left; font-weight: 600; }
        td { padding: 20px; border-bottom: 1px solid #f1f5f9; }
        tr:hover { background: #f8fafc; }
        .balance { font-weight: 700; color: #10b981; font-size: 18px; }
        .card-saved { color: #10b981; font-weight: 600; }
        .no-card { color: #ef4444; }
        form { background: #f8fafc; padding: 25px; border-radius: 12px; margin: 15px 0; border: 1px solid #e2e8f0; display: inline-flex; gap: 10px; align-items: center; }
        input[type="number"] { padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; width: 160px; font-size: 16px; }
        button { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s; }
        button:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(16,185,129,0.3); }
        .btn-reset { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
        .stat-number { font-size: 36px; font-weight: 700; color: #667eea; }
    </style>
</head>
<body>
