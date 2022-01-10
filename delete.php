<?php

// define('DB_NAME', $_SERVER['DB_NAME']);
// define('DB_HOST', $_SERVER['DB_HOST']);
// define('DB_USER', $_SERVER['DB_USER']);
// define('DB_PASS', $_SERVER['DB_PASS']);
session_start();
$date = $_GET['date'];

// タイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');
$errors = [];

// データーベースに接続
try {
  $option = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
  ];
  // $pdo = new PDO('mysql:charset=UTF8;dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASS, $option);
  $pdo = new PDO('mysql:charset=UTF8;dbname=unchi;host=localhost', 'root', 'root', $option);
} catch (PDOException $e) {
  $errors[] = $e->getMessage();
}

if (empty($errors)) {
  $pdo->beginTransaction();

  try {
    $stmt = $pdo->prepare("DELETE FROM diary WHERE date = :date AND user_id = :user_id");

    $stmt->bindValue(":date", $date, PDO::PARAM_STR);
    $stmt->bindValue(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);

    $stmt->execute();

    $res = $pdo->commit();
  } catch (Exception $e) {
    $pdo->rollBack();
  }

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
