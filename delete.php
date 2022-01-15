<?php

require_once("./function.php");

// define('DB_NAME', $_SERVER['DB_NAME']);
// define('DB_HOST', $_SERVER['DB_HOST']);
// define('DB_USER', $_SERVER['DB_USER']);
// define('DB_PASS', $_SERVER['DB_PASS']);
session_start();
$date = $_GET['date'];

// タイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');
$errors = [];
$dbc = new Dbc();

// DB接続
// $pdo = dbConnect(DB_NAME, DB_HOST, DB_USER, DB_PASS);
$pdo = $dbc->dbConnect("unchi", "localhost", "root", "root");

if (empty($errors)) {
  $pdo->beginTransaction();

  $res = $dbc->deleteDiary($pdo, $date, $_SESSION['user_id']);

  if (!$res) {
    $errors[] = "削除に失敗しました";
  }

  $stmt = null;

  header("Location: ./index.php");
  exit;
} else {
  foreach ($errors as $error) {
    echo $error . '</br>';
  }
}

$pdo = null;
