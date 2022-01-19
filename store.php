<?php

require_once("./user.php");

//初期値
session_start();
date_default_timezone_set('Asia/Tokyo');
$user = new User();
$submit = $_POST['btn_submit'];
$amount_int = $_POST['amount'];
$status = $_POST['status'];
$comment = $_POST['comment'];

if ($amount_int === "0") {
  $amount = 'ぶりぶりうんち';
} elseif ($amount_int === "1") {
  $amount = 'ノーマルうんち';
} elseif ($amount_int === "2") {
  $amount = '小さめうんち';
} elseif ($amount_int === "3") {
  $amount = 'ころころうんち';
} elseif ($amount_int === "4") {
  $amount = '下痢うんち';
} elseif ($amount_int === "5") {
  $amount = 'でなかった';
} elseif ($amount_int === "") {
  $amount = '';
}

if (!empty($submit)) {
  $res = $user->storeDiary($amount, $status, $comment);

  if (!$res) {
    $error = '記録に失敗しました';
    echo $error;
  }

  $stmt = null;
  $pdo = null;

  header("Location: ./index.php");
  exit;
}
