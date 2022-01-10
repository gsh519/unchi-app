<?php

// define('DB_NAME', $_SERVER['DB_NAME']);
// define('DB_HOST', $_SERVER['DB_HOST']);
// define('DB_USER', $_SERVER['DB_USER']);
// define('DB_PASS', $_SERVER['DB_PASS']);

session_start();

// セッションにおなまえが記録されていなければログインページに遷移
if (empty($_SESSION['user_name'])) {
  header("Location: ./login.php");
}

//初期値
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

// 今日の日付 フォーマット　例）2021-06-3
$today = date('Y-m-j');

// カレンダーのタイトルを作成　例）2021年6月
$html_title = date('Y年n月', $timestamp);

// 前月・次月の年月を取得
// 方法１：mktimeを使う mktime(hour,minute,second,month,day,year)
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) - 1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) + 1, 1, date('Y', $timestamp)));

// 方法２：strtotimeを使う
// $prev = date('Y-m', strtotime('-1 month', $timestamp));
// $next = date('Y-m', strtotime('+1 month', $timestamp));

// 該当月の日数を取得
$day_count = date('t', $timestamp);

// １日が何曜日か　0:日 1:月 2:火 ... 6:土
// 方法１：mktimeを使う
$youbi = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
// 方法２
// $youbi = date('w', $timestamp);


// カレンダー作成の準備
$weeks = [];
$week = '';

// 第１週目：空のセルを追加
// 例）１日が火曜日だった場合、日・月曜日の２つ分の空セルを追加する
$week .= str_repeat('<td></td>', $youbi);

for ($day = 1; $day <= $day_count; $day++, $youbi++) {

  // 2021-06-3
  $date = $ym . '-' . $day;

  if (empty($errors)) {
    // SQL作成
    $stmt = $pdo->prepare("SELECT * FROM diary WHERE date = :date AND user_id = :user_id ORDER BY id DESC");

    // 値をセット
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    // SQLクエリの実行
    $stmt->execute();

    // 表示するデータを取得
    $fetchDate = $stmt->fetch();

    if ($fetchDate['amount'] === 'ぶりぶりうんち') {
      $img = '<img src="./images/big_unchi.png">';
    } elseif ($fetchDate['amount'] === 'ノーマルうんち') {
      $img = '<img src="./images/normal_unchi.png">';
    } elseif ($fetchDate['amount'] === '小さめうんち') {
      $img = '<img src="./images/small_unchi.png">';
    } elseif ($fetchDate['amount'] === 'ころころうんち') {
      $img = '<img src="./images/corocoro_unchi.png">';
    } elseif ($fetchDate['amount'] === '下痢うんち') {
      $img = '<img src="./images/geri_unchi.png">';
    } elseif ($fetchDate['amount'] === 'でなかった') {
      $img = '<img src="./images/batu.png">';
    } elseif ($fetchDate['amount'] === null) {
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
      // 例）最終日が水曜日の場合、木・金・土曜日の空セルを追加
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
      <h1 class="hero__ttl">
        <img src="./images/logo.png" alt="今日のうんち日記">
      </h1>
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
