<?php
require_once 'config.php';

if ($_POST) {
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        sendTelegram("🔐 <b>Login Detected</b>\n👤 Username: {$user['username']}\n📧 Email: {$_POST['email']}");
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}

header('Location: index.php?error=1');
exit;
?>
