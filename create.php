<?php
$date = $_GET['date'];
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
  <script defer src="./script/active.js"></script>
</head>

<body>
  <main>
    <h1 class="create-title">日記記録ページ</h1>

    <div class="wrapper">
      <div class="unchi-form">
        <h2 class="unchi-form__ttl">うんち日記記録フォーム</h2>
        <form class="wrap" method="post" action="./store.php">
          <div class="wrap__area amount">
            <ul class="amount__list">
              <li class="item">
                <p>ぶりぶりうんち</p>
                <img src="./images/big_unchi.png" alt="ぶりぶりうんち">
              </li>
              <li class="item">
                <p>ノーマルうんち</p>
                <img src="./images/normal_unchi.png" alt="ノーマルうんち">
              </li>
              <li class="item">
                <p>小さめうんち</p>
                <img src="./images/small_unchi.png" alt="小さめうんち">
              </li>
              <li class="item">
                <p>ころころうんち</p>
                <img src="./images/corocoro_unchi.png" alt="ころころうんち">
              </li>
              <li class="item">
                <p>下痢うんち</p>
                <img src="./images/geri_unchi.png" alt="下痢うんち">
              </li>
              <li class="item">
                <p>でなかった</p>
                <img src="./images/batu.png" alt="でなかった">
              </li>
            </ul>
            <input required class="amount-input" type="hidden" name="amount">
          </div>
          <div class="wrap__area">
            <select required class="status-select" name="status" id="status">
              <option value="お腹の状態">お腹の状態</option>
              <option value="スッキリ">スッキリ🥳</option>
              <option value="スッキリしない">スッキリしない😖</option>
              <option value="お腹がはってる">お腹がはってる😩</option>
              <option value="お尻が痛い">お尻が痛い😣</option>
            </select>
          </div>
          <div class="wrap__area comm">
            <label for="comment">自分メモ<i class="fas fa-pen"></i></label>
            <textarea class="comment-form" name="comment" id="comment"></textarea>
          </div>
          <div class="wrap__area submit">
            <a class="to-home" href="./">Home</a>
            <input type="submit" name="btn_submit" value="記録する">
          </div>
          <input type="hidden" name="date" value="<?php echo $date; ?>">
        </form>
      </div>
    </div>
  </main>


</body>

</html>
