<?php

define('DB_NAME', $_SERVER['DB_NAME']);
define('DB_HOST', $_SERVER['DB_HOST']);
define('DB_USER', $_SERVER['DB_USER']);
define('DB_PASS', $_SERVER['DB_PASS']);

class User
{
  // データーベース接続関数
  public function dbConnect($db_name, $db_host, $db_user, $db_pass)
  {
    // データーベースに接続
    try {
      $option = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
      ];
      $pdo = new PDO('mysql:charset=UTF8;dbname=' . $db_name . ';host=' . $db_host, $db_user, $db_pass, $option);
      return $pdo;
    } catch (PDOException $e) {
      $errors[] = $e->getMessage();
    }
  }

  // ログイン
  public function login($pdo, $user_name)
  {
    // DB接続
    $pdo = $this->dbConnect(DB_NAME, DB_HOST, DB_USER, DB_PASS);

    $sql = "SELECT * FROM user WHERE user_name = :user_name";

    // SQL作成
    $stmt = $pdo->prepare($sql);

    // 値をセット
    $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);

    // SQLクエリの実行
    $stmt->execute();

    // 表示するデータを取得
    $user_data = $stmt->fetch();

    $stmt = null;
    $pdo = null;

    return $user_data;
  }

  // サインアップ
  public function signup($pdo, $user_name)
  {
    // DB接続
    $pdo = $this->dbConnect("unchi", "localhost", "root", "root");
    // $pdo = $this->dbConnect(DB_NAME, DB_HOST, DB_USER, DB_PASS);

    //日付を取得
    $date = date('Y-m-j');

    $pdo->beginTransaction();

    try {
      $stmt = $pdo->prepare("INSERT INTO user (user_name, date) VALUES (:user_name, :date)");

      $stmt->bindParam(":user_name", $user_name, PDO::PARAM_STR);
      $stmt->bindValue(":date", $date, PDO::PARAM_STR);

      $stmt->execute();

      // SQL作成
      $stmt = $pdo->prepare("SELECT * FROM user WHERE user_name = :user_name");

      // 値をセット
      $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);

      // SQLクエリの実行
      $stmt->execute();

      // 表示するデータを取得
      $user_data = $stmt->fetch();

      $_SESSION['user_name'] = $user_data['user_name'];
      $_SESSION['user_id'] = $user_data['id'];

      $res = $pdo->commit();

      return $res;
    } catch (Exception $e) {
      // エラーが発生した時はロールバック
      $pdo->rollBack();
    }
  }

  //日記情報取得
  public function getDiary($pdo, $date, $user_id)
  {

    // DB接続
    $pdo = $this->dbConnect("unchi", "localhost", "root", "root");
    // $pdo = $this->dbConnect(DB_NAME, DB_HOST, DB_USER, DB_PASS);

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

  // 日記保存
  function storeDiary($pdo, $amount, $status, $comment)
  {
    // DB接続
    $pdo = $this->dbConnect("unchi", "localhost", "root", "root");
    // $pdo = $this->dbConnect(DB_NAME, DB_HOST, DB_USER, DB_PASS);

    //日付を取得
    $date = $_POST['date'];

    $pdo->beginTransaction();

    // データをDBに追加
    $sql = "INSERT INTO diary (date, user_id, amount, status, comment) VALUES (:date, :user_id, :amount, :status, :comment)";

    try {
      $stmt = $pdo->prepare($sql);

      $stmt->bindParam(":date", $date, PDO::PARAM_STR);
      $stmt->bindValue(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
      $stmt->bindParam(":amount", $amount, PDO::PARAM_STR);
      $stmt->bindParam(":status", $status, PDO::PARAM_STR);
      $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);

      $stmt->execute();

      $res = $pdo->commit();

      return $res;
    } catch (Exception $e) {
      // エラーが発生した時はロールバック
      $pdo->rollBack();
    }
  }

  //日記削除
  public function deleteDiary($pdo, $date, $user_id)
  {

    // DB接続
    $pdo = $this->dbConnect("unchi", "localhost", "root", "root");
    // $pdo = $this->dbConnect(DB_NAME, DB_HOST, DB_USER, DB_PASS);

    $pdo->beginTransaction();

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

    $stmt = null;
    $pdo = null;
  }
}
