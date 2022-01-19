<?php

require_once("./user.php");

session_start();

//初期値
date_default_timezone_set('Asia/Tokyo');
$error = '';
$signup = $_POST['signup_btn'];
$user_name = $_POST['user_name'];
$user = new User();

if (!empty($signup)) {

  if (empty($user_name)) {
    $error = 'おなまえを入力してください';
  } elseif (!empty($user_name)) {
    $res = $user->signup($user_name);

    if ($res) {
      header("Location: ./");
      exit;
    } else {
      $error = 'おなまえが他の人とかぶってます!!';
    }

    $stmt = null;
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
        <p class="login-form__comm">アカウントを作る!</p>
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
          <p class="login-form__announce">※おなまえは他の人と違うものにしてください</p>
          <?php if (!empty($error)) : ?>
            <ul class="error">
              <li class="error__message"><?php echo $error; ?></li>
            </ul>
          <?php endif; ?>

          <!-- ログイン -->
          <div class="wrap_area login">
            <input type="submit" class="login_btn" name="signup_btn" value="作成">
          </div>
        </form>

        <a class="signup" href="./login.php">アカウントをお持ちの方はこちら</a>
      </div>
    </div>
  </main>
</body>

</html>
