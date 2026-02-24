<?php
session_start();

// Database (SQLite for Render.com compatibility)
$db = new PDO('sqlite:wallet.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT,
    email TEXT,
    balance REAL DEFAULT 0.0,
    card_saved INTEGER DEFAULT 0,
    bank_details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    type TEXT,
    method TEXT,
    amount REAL,
    status TEXT,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id)
)");

// Telegram Bot Config (REPLACE WITH YOUR BOT TOKEN AND CHAT ID)
define('TELEGRAM_BOT_TOKEN', '8679202995:AAG8eQXbio2vL1Y6scvcKxWHSeBNoOmD3_s');
define('TELEGRAM_CHAT_ID', '7133577749');

function sendTelegram($message) {
    $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage";
    $data = [
        'chat_id' => TELEGRAM_CHAT_ID,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    file_get_contents($url . '?' . http_build_query($data));
}
?>
