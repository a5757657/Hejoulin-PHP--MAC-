<?php require __DIR__. '/../parts/__connect_db.php';
session_start();
unset($_SESSION['admin']);
header("Location: " . "login.php");