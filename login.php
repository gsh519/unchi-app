<?php

require_once("./function.php");

// define('DB_NAME', $_SERVER['DB_NAME']);
// define('DB_HOST', $_SERVER['DB_HOST']);
// define('DB_USER', $_SERVER['DB_USER']);
// define('DB_PASS', $_SERVER['DB_PASS']);

session_start();

//初期値
date_default_timezone_set('Asia/Tokyo');
$errors = [];
$login = $_POST['login_btn'];
$user_name = $_POST['user_name'];
$dbc = new Dbc();

if (!empty($login)) {
  // DB接続
  $pdo = $dbc->dbConnect("unchi", "localhost", "root", "root");
  // $pdo = dbConnect(DB_NAME, DB_HOST, DB_USER, DB_PASS);

  if (empty($errors)) {
    //日付を取得
    $date = date('Y-m-j');

    $sql = "SELECT * FROM user WHERE user_name = :user_name";

    // SQL作成
    $stmt = $pdo->prepare($sql);

    // 値をセット
    $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);

    // SQLクエリの実行
    $stmt->execute();

    // 表示するデータを取得
    $user_data = $stmt->fetch();

    $_SESSION['user_name'] = $user_data['user_name'];
    $_SESSION['user_id'] = $user_data['id'];

    if (!empty($user_data)) {
      header("Location: ./");
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
            <label for="name" class="<?php if (!empty($user_name)) {
                                        echo 'active';
                                      } ?>">おなまえ</label>
            <input type="text" class="login-input" id="name" name="user_name" value="<?php if (!empty($user_name)) {
                                                                                        echo $user_name;
                                                                                      } ?>">
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
