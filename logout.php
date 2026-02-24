<?php
session_start();
sendTelegram("🚪 <b>LOGOUT</b>\n👤 Username: {$_SESSION['username']}\n🌐 IP: {$_SERVER['REMOTE_ADDR']}");
session_destroy();
header('Location: index.php');
exit;
?>
