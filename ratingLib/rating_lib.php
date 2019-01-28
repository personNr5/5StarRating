<?php

  /**
  *
  * 5 Star Rating System Class
  * V0.2 Q
  *
  **/

  class ratingSystem
  {

    // local variables
    protected $db;
    protected $stars;


    function __construct($stars=5, PDO $db)
    {
      $this->db = $db;
      $this->stars = $stars;
    }

    function setRating($itemID=0, $rating=0) {

      if ($rating <= 0) {
        $rating = 1;
      } elseif ($rating >= $this->stars) {
        $rating = $this->stars;
      }

      $stmt = $this->db->prepare("INSERT INTO item_rating (itemId, ratingNumber) VALUES (?, ?)");

      if ($stmt->execute([$itemID, $rating])) {
        return "success";
      } else {
        return "inputfail";
      }

    }

    function setCategory($categoryName, $categoryID, $categoryDescription=0) {
      if ($categoryName && $categoryID) {

        $stmt = $this->db->prepare("SELECT count(*) FROM items WHERE items.formID = ?");
        if ($stmt->execute([$categoryID])) {
          $row = $stmt->fetch();
          if ($row >= 1) {
            return 3;
          }
        } else {
          return 3;
        }

        $stmt = $this->db->prepare("INSERT INTO items (formID, itemName, description) VALUES (?, ?, ?)");

        if ($stmt->execute([$categoryName, $categoryID, $categoryDescription])) {
          return true;
        } else {
          return 3;
        }
      }
    }

    function getResults($sortorder=0) {

      //get inserted rating Values
      $sql = "SELECT
                ratingId,
                itemId,
                ratingNumber,
                created
              FROM
                item_rating
              ORDER BY
                itemId ASC, created DESC";

      $ratingData = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

      // get rating categories
      $sql = "SELECT
                formID,
                itemName,
                description
              FROM
                items
              ORDER BY
                itemName ASC";

      $ratingCategories = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

      /**
      * Process the Information
      **/

      for ($j=0; $j < count($ratingCategories); $j++) {
          $itemCounter = 0;
          $totalValue = 0;
          for ($i=0; $i < count($ratingData); $i++) {
              if ($ratingData[$i]["itemId"] === $ratingCategories[$j]["formID"]) {
                  $itemCounter++;
                  $totalValue += $ratingData[$i]["ratingNumber"];
              }
          }
          $ratingCategories[$j]["COUNT"] = $itemCounter;
          $ratingCategories[$j]["AVG"] = round(($totalValue/$itemCounter), 2);
      }

      /**
      * Sort the Information
      **/

      $ArrID  = array_column($ratingCategories, "formID");
      $ArrName = array_column($ratingCategories, "itemName");
      $ArrDesc = array_column($ratingCategories, "description");
      $ArrCounter = array_column($ratingCategories, "COUNT");
      $ArrAv = array_column($ratingCategories, "AVG");

      switch ($sortorder) {
          case 1:
              # order by Max Participation
              array_multisort($ArrCounter, SORT_DESC, $ArrAv, SORT_DESC, $ArrName, SORT_ASC, $ratingCategories);
              break;
          case 2:
              # order by Name
              array_multisort($ArrName, SORT_ASC, $ArrAv, SORT_DESC, $ArrCounter, SORT_DESC, $ratingCategories);
              break;
          case 3:
              # order by Average
              array_multisort($ArrAv, SORT_DESC, $ArrCounter, SORT_DESC, $ArrName, SORT_ASC, $ratingCategories);
              break;
          case 4:
              # order by ID
              array_multisort($ArrID, SORT_ASC, $ArrCounter, SORT_DESC, $ArrName, SORT_ASC, $ratingCategories);
              break;
          default:
              # ordered by name by default
              break;
      }

      /**
      * Output
      **/
      return $ratingCategories;


    }
  }

?>