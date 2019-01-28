<?php

  /**
  * Settings File for the ratingSystem Lib
  * Version 0.2 Queserser
  * FS
  **/

  /**
  * - - - MySQL DB Credentials - - -
  **/

    $servername = "localhost";
    $username = "ratingLib";
    $password = "ratingLib";
    $db = "ratingSystem";
    $charset = "UTF-8";

  /**
  * - - - Whitelist for Servers that are allowed to save Ratings and manipulate the Database - - -
  **/

    $legalRatingOrigin = [
      "127.0.0.1",
      "example.com"
    ];

  /**
  * - - - ID Settings - - -
  **/

    $itemsManipulationOn = false;
    $itemsManipulationPassword = "password";

  /**
  * - - - Required Libraryfiles to load - - -
  **/

    // connect to database
    require_once("db_con.php");

    // include rating Class
    require_once("rating_lib.php");

?>