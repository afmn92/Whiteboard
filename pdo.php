<?php
$host = '127.0.0.1';
$db   = 'board';
$user = '';
$pass = '';
$charset = 'utf8mb4';

//$dsn = "sqlite:board.db";
$dsn = "sqlite:".$_SERVER['DOCUMENT_ROOT']."/board.db";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);
?>
