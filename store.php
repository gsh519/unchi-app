<?php

require_once("./function.php");

// define('DB_NAME', $_SERVER['DB_NAME']);
// define('DB_HOST', $_SERVER['DB_HOST']);
// define('DB_USER', $_SERVER['DB_USER']);
// define('DB_PASS', $_SERVER['DB_PASS']);

//タイムゾーン設定
session_start();
date_default_timezone_set('Asia/Tokyo');
$dbc = new Dbc();

$submit = $_POST['btn_submit'];
$status = $_POST['status'];
$comment = $_POST['comment'];
$amount_int = (int)$_POST['amount'];


$errors = [];

if ($amount_int === 0) {
  $amount = 'ぶりぶりうんち';
} elseif ($amount_int === 1) {
  $amount = 'ノーマルうんち';
} elseif ($amount_int === 2) {
  $amount = '小さめうんち';
} elseif ($amount_int === 3) {
  $amount = 'ころころうんち';
} elseif ($amount_int === 4) {
  $amount = '下痢うんち';
} elseif ($amount_int === 5) {
  $amount = 'でなかった';
}

if (!empty($submit)) {
  // DB接続
  // $pdo = dbConnect(DB_NAME, DB_HOST, DB_USER, DB_PASS);
  $pdo = $dbc->dbConnect("unchi", "localhost", "root", "root");
}

if (empty($errors)) {
  //日付を取得
  $date = $_POST['date'];

  $pdo->beginTransaction();

  // データをDBに追加
  $sql = "INSERT INTO diary (date, user_id, amount, status, comment) VALUES (:date, :user_id, :amount, :status, :comment)";

  try {
    $stmt = $pdo->prepare($sql);

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
