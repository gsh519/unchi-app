<?php

$dbName = $_SERVER['DB_NAME'];
$host = $_SERVER['DB_HOST'];
$user = $_SERVER['DB_USER'];
$pass = $_SERVER['DB_PASS'];
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
  $pdo = new PDO('mysql:charset=UTF8;dbname=' . $dbName . ';host=' . $host, $user, $pass, $option);
} catch (PDOException $e) {
  $errors[] = $e->getMessage();
}

if (empty($errors)) {
  $pdo->beginTransaction();

  try {
    $stmt = $pdo->prepare("DELETE FROM diary WHERE date = :date");

    $stmt->bindValue(":date", $date, PDO::PARAM_STR);

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
