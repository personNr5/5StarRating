<?PHP

  /**
  * include settings File
  **/

  require_once('settings.php');

  require_once('rating_refSec.php');


    if ($acoh && isset($pdo)) {

      $ratingSystem = new ratingSystem(5, $pdo);

      // Connected successfully
      if(!empty($_POST['rating']) && !empty($_POST['itemId'])){
        $itemId = filter_input(INPUT_POST, "itemId", FILTER_VALIDATE_INT);
        $itemValue = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);

        echo $ratingSystem->setRating($itemId, $itemValue);

      } else {
        echo "datafail";
      }
    }


?>