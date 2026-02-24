<?php
require_once 'config.php';
$error = '';
if ($_POST) {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $stmt = $db->prepare("SELECT * FROM users WHERE email=? OR username=?");
    $stmt->execute([$email, $email]);
    $user = $stmt->fetch();
    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        sendTelegram("✅ LOGIN: {$user['username']} | IP: {$_SERVER['REMOTE_ADDR']}");
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Wrong credentials';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <?php if($error) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="email" placeholder="Email/Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button>Login</button>
        </form>
        <a href="register.php">Create Account</a>
    </div>
</body>
</html>
