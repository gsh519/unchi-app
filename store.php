<?php

// define('DB_NAME', $_SERVER['DB_NAME']);
// define('DB_HOST', $_SERVER['DB_HOST']);
// define('DB_USER', $_SERVER['DB_USER']);
// define('DB_PASS', $_SERVER['DB_PASS']);

//タイムゾーン設定
session_start();
// var_dump($_SESSION['user_id']);
date_default_timezone_set('Asia/Tokyo');

$submit = $_POST['btn_submit'];
$status = $_POST['status'];
$comment = $_POST['comment'];


$errors = [];

if ($_POST['amount'] === "0") {
  $amount = 'ぶりぶりうんち';
} elseif ($_POST['amount'] === "1") {
  $amount = 'ノーマルうんち';
} elseif ($_POST['amount'] === "2") {
  $amount = '小さめうんち';
} elseif ($_POST['amount'] === "3") {
  $amount = 'ころころうんち';
} elseif ($_POST['amount'] === "4") {
  $amount = '下痢うんち';
} elseif ($_POST['amount'] === "5") {
  $amount = 'でなかった';
}

if (!empty($submit)) {
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
}

if (empty($errors)) {
  //日付を取得
  $date = $_POST['date'];

  $pdo->beginTransaction();

  // データをDBに追加
  try {
    $stmt = $pdo->prepare("INSERT INTO diary (date, user_id, amount, status, comment) VALUES (:date, :user_id, :amount, :status, :comment)");

    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindValue(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(":amount", $amount, PDO::PARAM_STR);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);

    $stmt->execute();

    $res = $pdo->commit();
  } catch (Exception $e) {
    // エラーが発生した時はロールバック
    $pdo->rollBack();
  }

  if (!$res) {
    $errors[] = '記録に失敗しました';
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
