<?php
// データーベース接続関数
function dbConnect($db_name, $db_host, $db_user, $db_pass)
{
  // データーベースに接続
  try {
    $option = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    ];
    // $pdo = new PDO('mysql:charset=UTF8;dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASS, $option);
    $pdo = new PDO("mysql:charset=UTF8;dbname=$db_name;host=$db_host", $db_user, $db_pass, $option);
    return $pdo;
  } catch (PDOException $e) {
    $errors[] = $e->getMessage();
  }
}

// diaryテーブルの情報を取得してくる関数
function getDiary($pdo, $date, $user_id)
{
  $sql = "SELECT * FROM diary WHERE date = :date AND user_id = :user_id ORDER BY id DESC";

  // SQL作成
  $stmt = $pdo->prepare($sql);

  // 値をセット
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

  // SQLクエリの実行
  $stmt->execute();

  // 表示するデータを取得
  $diary = $stmt->fetch();
  return $diary;
}

//日記削除
function deleteDiary($pdo, $date, $user_id)
{
  $sql = "DELETE FROM diary WHERE date = :date AND user_id = :user_id";

  try {
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(":date", $date, PDO::PARAM_STR);
    $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

    $stmt->execute();

    $res = $pdo->commit();
    return $res;
  } catch (Exception $e) {
    $pdo->rollBack();
  }
}
