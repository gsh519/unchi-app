<?php

require_once("./user.php");

// セッション開始
session_start();

// セッションにおなまえが記録されていなければログインページに遷移
if (empty($_SESSION['user_name'])) {
  header("Location: ./login.php");
}

//初期値
date_default_timezone_set('Asia/Tokyo');
$errors = [];
$user = new User();


// 前月・次月リンクが押された場合は、GETパラメーターから年月を取得
if (isset($_GET['ym'])) {
  $ym = $_GET['ym'];
} else {
  // 今月の年月を表示
  $ym = date('Y-m');
}

// タイムスタンプを作成し、フォーマットをチェックする
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) {
  $ym = date('Y-m');
  $timestamp = strtotime($ym . '-01');
}

// 今日の日付 フォーマット
$today = date('Y-m-j');

// カレンダーのタイトルを作成
$html_title = date('Y年n月', $timestamp);

// 前月・次月の年月を取得
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) - 1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) + 1, 1, date('Y', $timestamp)));

// 該当月の日数を取得
$day_count = date('t', $timestamp);

// １日が何曜日か　0:日 1:月 2:火 ... 6:土
$youbi = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));


// カレンダー作成の準備
$weeks = [];
$week = '';

// 第１週目：空のセルを追加
$week .= str_repeat('<td></td>', $youbi);

for ($day = 1; $day <= $day_count; $day++, $youbi++) {

  $date = $ym . '-' . $day;

  if (empty($errors)) {

    $diary = $user->getDiary($pdo, $date, $_SESSION['user_id']);

    if ($diary['amount'] === 'ぶりぶりうんち') {
      $img = '<img src="./images/big_unchi.png">';
    } elseif ($diary['amount'] === 'ノーマルうんち') {
      $img = '<img src="./images/normal_unchi.png">';
    } elseif ($diary['amount'] === '小さめうんち') {
      $img = '<img src="./images/small_unchi.png">';
    } elseif ($diary['amount'] === 'ころころうんち') {
      $img = '<img src="./images/corocoro_unchi.png">';
    } elseif ($diary['amount'] === '下痢うんち') {
      $img = '<img src="./images/geri_unchi.png">';
    } elseif ($diary['amount'] === 'でなかった') {
      $img = '<img src="./images/batu.png">';
    } elseif ($diary['amount'] === null) {
      $img = '<img>';
    }
  }

  $a_href = '<a class="create-diary" href="./create.php?date=' . $date . '">' . $img . '</a>';

  if ($today == $date) {
    // 今日の日付の場合は、class="today"をつける
    $week .= '<td class="today">' . '<p>' . $day . '</p>' . $a_href;
  } else {
    $week .= '<td>' . '<p>' . $day . '</p>' . $a_href;
  }
  $week .= '</td>';

  // 週終わり、または、月終わりの場合
  if ($youbi % 7 == 6 || $day == $day_count) {

    if ($day == $day_count) {
      // 月の最終日の場合、空セルを追加
      $week .= str_repeat('<td></td>', 6 - $youbi % 7);
    }

    // weeks配列にtrと$weekを追加する
    $weeks[] = '<tr>' . $week . '</tr>';

    // weekをリセット
    $week = '';
  }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>今日のうんち日記</title>
  <link rel="stylesheet" href="./css/reset.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">

  <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script defer src="./script/script.js"></script>
</head>

<body>
  <main>
    <div class="hero">
      <div class="wrapper">
        <h1 class="hero__ttl">
          <img src="./images/logo.png" alt="今日のうんち日記">
        </h1>
      </div>
    </div>
    <div class="calendar">
      <div class="container">
        <h3 class="mb-2"><a class="arrow" href="?ym=<?php echo $prev; ?>">&lt;</a> <?php echo $html_title; ?> <a class="arrow" href="?ym=<?php echo $next; ?>">&gt;</a></h3>
        <table class="table table-bordered">
          <tr>
            <th>日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
          </tr>
          <?php
          foreach ($weeks as $week) {
            echo $week;
          }
          ?>
        </table>
      </div>
    </div>

    <div class="logout-area">
      <form action="./logout.php" method="POST">
        <div class="wrap_area logout">
          <input type="submit" class="login_btn" name="logout_btn" value="ログアウト">
        </div>
      </form>
    </div>
  </main>
</body>

</html>
