<?php

  /**
  * Allow Only POST REQUESTS - No Other Request Allowed
  **/

  if($_SERVER['REQUEST_METHOD'] == "POST") {

    /**
    * Allow Access only from the Servers listed in $legalRatingOrigin / No Cross Site Access allowed
    **/

    $http_origin = $_SERVER['HTTP_HOST'];
    $acoh = 0;

    // CHECK WHITELIST OF ALLOWED SERVERS
    foreach ($legalRatingOrigin as $lRO) {
      if ($http_origin == $lRO) {
        $acoh = 'Access-Control-Allow-Origin: ' . $lRO;
      }
    }

    // Write Headers
    if ($acoh) {

      header ($acoh);
      header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
      header('Access-Control-Allow-Headers: X-PINGARUNER');
      header('Access-Control-Max-Age: 1728000');
      header("Content-Length: 0");
      header("Content-Type: text/plain");
    } else {

      header("HTTP/1.1 403 Access Forbidden");
      header("Content-Type: text/plain");

      die("403 Access Forbidden");
    }
  } else {

    /**
    * Die when Request Method POST is not set
    **/

    die("Request Error");
  }

?>