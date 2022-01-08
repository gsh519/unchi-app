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
