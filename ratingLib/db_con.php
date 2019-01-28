<?php

  /**
  * Establish PDO - MySQL Connection
  **/

  $dsn = "mysql:host=$servername;dbname=$db;$charset='UTF-8'";
  $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  try {
       $pdo = new PDO($dsn, $username, $password, $options);
  } catch (\PDOException $e) {
    // Internal Error Code 0E:01
    die('Database Connection Error 0E:01');
  }

?>