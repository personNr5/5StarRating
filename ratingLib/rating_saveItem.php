<?php
  /**
  * include settings File
  **/

  require_once('settings.php');

  require_once('rating_refSec.php');

  if ($acoh && isset($pdo)) {

    $returnMSG = 2;
    $ratingSystem = new ratingSystem(5, $pdo);

    // Connected successfully
    if(!empty($_POST['formID']) && !empty($_POST['itemName']) && !empty($_POST['confPW'])){

      $formID = filter_input(INPUT_POST, "formID", FILTER_VALIDATE_INT);
      $itemName = filter_input(INPUT_POST, "itemName", FILTER_SANITIZE_STRING);
      $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);
      $confPW = filter_input(INPUT_POST, "confPW", FILTER_SANITIZE_STRING);

      if ($itemsManipulationOn && ($confPW === $itemsManipulationPassword)) {
        $returnMSG = $ratingSystem->setCategory($formID, $itemName, $description);
      }

    } else {
      // do nothing
    }

  }

  // refer back to show page
  header("Location: rating_show.php?returnMSG=".$returnMSG);

?>