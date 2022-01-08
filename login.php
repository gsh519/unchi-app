<?php

//初期値
date_default_timezone_set('Asia/Tokyo');
$errors = [];
$login = $_POST['login_btn'];
$user_name = $_POST['user_name'];

if (!empty($login)) {
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
    //日付を取得
    $date = date('Y-m-j');

    // SQL作成
    $stmt = $pdo->prepare("SELECT * FROM user WHERE user_name = :user_name");

    // 値をセット
    $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);

    // SQLクエリの実行
    $stmt->execute();

    // 表示するデータを取得
    $fetchData = $stmt->fetch();

    if (!empty($fetchData)) {
      header("Location: ./index.php");
      exit;
    } else {
      $errors[] = 'おなまえが違います';
    }
  } else {
    foreach ($errors as $error) {
      echo $error . '</br>';
    }
  }
}

$pdo = null;

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

    <div class="wrapper">
      <div class="login-form">
        <p class="login-form__comm">ログインしてね!</p>
        <form action="" method="POST">
          <!-- おなまえ -->
          <div class="wrap_area user_name">
            <label for="name">おなまえ</label>
            <input type="text" class="login-input" id="name" name="user_name">
          </div>

          <?php if (!empty($errors)) : ?>
            <ul class="error">
              <?php foreach ($errors as $error) : ?>
                <li class="error__message">※<?php echo $error; ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <!-- ログイン -->
          <div class="wrap_area login">
            <input type="submit" class="login_btn" id="login_btn" name="login_btn" value="ログイン">
          </div>
        </form>

        <a class="signup" href="./signup.php">アカウントをお持ちでない方はこちら</a>
      </div>
    </div>
  </main>
</body>

</html>
