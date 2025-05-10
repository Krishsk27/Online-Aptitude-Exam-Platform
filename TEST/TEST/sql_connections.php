<?php

  function getConn() {
    // Authentication credentials
    
    // $db_url   = parse_url("mysql://qbseohgakp1wlh5s:mnawg79qryrp0i70@uzb4o9e2oe257glt.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/nsd94ei8xe57kae2");
    // $db_url   = parse_url("mysql://root:@localhost:3306/mocks?reconnect=true");

    $db_url      = parse_url(getenv("JAWSDB_URL"));
    $DB_HOST     = $db_url["localhost"];
    $DB_USERNAME = $db_url["root"];
    $DB_PASSWORD = $db_url[""];
    $DB_NAME     = substr($db_url["mocks"], 1);
    $DB_CHARSET  = "utf8mb4";

    $dsn = "mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME . ";charset=" . $DB_CHARSET;
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
      $pdo = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD, $options);
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
    return $pdo;
  }

?>
