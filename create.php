<?php
// define('DB_NAME', $_SERVER['DB_NAME']);
// define('DB_HOST', $_SERVER['DB_HOST']);
// define('DB_USER', $_SERVER['DB_USER']);
// define('DB_PASS', $_SERVER['DB_PASS']);
$date = $_GET['date'];
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

if (empty($errors)) {
  // SQL作成
  $stmt = $pdo->prepare("SELECT * FROM diary WHERE date = :date ORDER BY id DESC");

  // 値をセット
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);

  // SQLクエリの実行
  $stmt->execute();

  // 表示するデータを取得
  $fetchData = $stmt->fetch();
}

$amount = $fetchData['amount'];
$status = $fetchData['status'];
$comment = $fetchData['comment'];

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>今日のうんち日記</title>
  <link rel="stylesheet" href="./css/reset.css">
  <link rel="stylesheet" href="./css/style.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">

  <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script defer src="./script/script.js"></script>
</head>

<body>
  <main>
    <h1 class="create-title">うんち日記記録ページ</h1>

    <div class="wrapper">
      <div class="unchi-form">
        <h2 class="unchi-form__ttl">うんち日記記録フォーム</h2>
        <form class="wrap" method="post" action="./store.php">
          <div class="wrap__area amount">
            <ul class="amount__list">
              <li class="item <?php if (!empty($amount)) {
                                if ($amount === 'ぶりぶりうんち') {
                                  echo 'select';
                                }
                              } ?>">
                <p>ぶりぶりうんち</p>
                <img src="./images/big_unchi.png" alt="ぶりぶりうんち">
              </li>
              <li class="item <?php if (!empty($amount)) {
                                if ($amount === 'ノーマルうんち') {
                                  echo 'select';
                                }
                              } ?>">
                <p>ノーマルうんち</p>
                <img src="./images/normal_unchi.png" alt="ノーマルうんち">
              </li>
              <li class="item <?php if (!empty($amount)) {
                                if ($amount === '小さめうんち') {
                                  echo 'select';
                                }
                              } ?>">
                <p>小さめうんち</p>
                <img src="./images/small_unchi.png" alt="小さめうんち">
              </li>
              <li class="item <?php if (!empty($amount)) {
                                if ($amount === 'ころころうんち') {
                                  echo 'select';
                                }
                              } ?>">
                <p>ころころうんち</p>
                <img src="./images/corocoro_unchi.png" alt="ころころうんち">
              </li>
              <li class="item <?php if (!empty($amount)) {
                                if ($amount === '下痢うんち') {
                                  echo 'select';
                                }
                              } ?>">
                <p>下痢うんち</p>
                <img src="./images/geri_unchi.png" alt="下痢うんち">
              </li>
              <li class="item <?php if (!empty($amount)) {
                                if ($amount === 'でなかった') {
                                  echo 'select';
                                }
                              } ?>">
                <p>でなかった</p>
                <img src="./images/batu.png" alt="でなかった">
              </li>
            </ul>
            <input required class="amount-input" type="hidden" name="amount">
          </div>
          <div class="wrap__area">
            <select required class="status-select" name="status" id="status">
              <option value="お腹の状態">お腹の状態</option>
              <option value="スッキリ" <?php if (!empty($status)) {
                                      if ($status === "スッキリ") {
                                        echo 'selected';
                                      }
                                    } ?>>スッキリ🥳</option>
              <option value="スッキリしない" <?php if (!empty($status)) {
                                        if ($status === "スッキリしない") {
                                          echo 'selected';
                                        }
                                      } ?>>スッキリしない😖</option>
              <option value="お腹がはってる" <?php if (!empty($status)) {
                                        if ($status === "お腹がはってる") {
                                          echo 'selected';
                                        }
                                      } ?>>お腹がはってる😩</option>
              <option value="お尻が痛い" <?php if (!empty($status)) {
                                      if ($status === "お尻が痛い") {
                                        echo 'selected';
                                      }
                                    } ?>>お尻が痛い😣</option>
            </select>
          </div>
          <div class="wrap__area comm">
            <label for="comment" <?php if (!empty($comment)) {
                                    echo 'class="active"';
                                  } ?>>自分メモ<i class="fas fa-pen"></i></label>
            <textarea class="comment-form" name="comment" id="comment"><?php if (!empty($comment)) {
                                                                          echo $comment;
                                                                        } else {
                                                                          echo '';
                                                                        } ?></textarea>
          </div>
          <div class="wrap__area submit">
            <a class="to-home" href="./">Home</a>
            <?php if (!empty($amount) && !empty($status)) : ?>
              <a href="./delete.php?date=<?php echo $date ?>" class="to-home">削除</a>
            <?php endif; ?>
            <input type="submit" name="btn_submit" value="記録する">
          </div>
          <input type="hidden" name="date" value="<?php echo $date; ?>">
        </form>
      </div>
    </div>
  </main>


</body>

</html>
