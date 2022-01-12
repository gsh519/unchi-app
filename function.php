<?php

function db_connect($db_name, $db_host, $db_user, $db_pass)
{
  $option = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
  ];
  $pdo = new PDO('mysql:charset=UTF8;dbname=' . $db_name . ';host=' . $db_host, $db_user, $db_pass, $option);

  return $pdo;
}
