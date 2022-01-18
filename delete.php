<?php

require_once("./user.php");

// 初期設定
session_start();
$date = $_GET['date'];
date_default_timezone_set('Asia/Tokyo');
$error = '';
$user = new User();

// 投稿削除処理実行
$res = $user->deleteDiary($date, $_SESSION['user_id']);

// 削除処理にエラーがあればエラー表示
if (!$res) {
  $error = "削除に失敗しました";
  echo $error;
}

header("Location: ./index.php");
exit;
