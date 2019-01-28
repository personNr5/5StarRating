<?PHP

  /**
  * include settings File
  **/

  require_once('settings.php');

  /**
  * get variables
  **/

  $sortorder = filter_input(INPUT_GET, "sort", FILTER_VALIDATE_INT);

  if (!empty($_GET['returnMSG'])) {
    $returnMSG = filter_input(INPUT_GET, "returnMSG", FILTER_VALIDATE_INT);
  } else {
    $returnMSG = "99";
  }

  if (isset($pdo)) {

    /**
    * Workflow
    **/

    $ratingSystem = new ratingSystem(5, $pdo);

    $results = $ratingSystem->getResults($sortorder);
  }

  /**
  * HTML Template to present rating Data
  **/

?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="robots" content="noindex" />
    <title>Voting Results</title>
    <style type="text/css">
    body {
    background-color: darkseagreen;
    margin: 0 auto;
    font-family: sans-serif;
    }
    div.fullheight {
    position: absolute;
    left: 0px;
    top: 0px;
    height: 100%;
    width: 100%;
    padding-top: 50px;
    }
    @media only screen and (min-width: 769px) {
    div.fullheight {
    padding-top: 0;
    display: flex;
    align-items: center;
    }
    }
    div.voting.results {
    width: 800px;
    max-width: 80%;
    position: relative;
    display: block;
    margin: 0 auto;
    text-align: left;
    background-color: rgba(255,255,255,0.8);
    padding: 20px;
    }
    div.voting.results table {
    width: 100%;
    }
    div.voting.results td,
    div.voting.results th {
    margin:10px 0;
    border-bottom: 1px solid lightgrey;
    }
    div.voting.results td {
    transition: background-color 0.3s;
    }
    div.voting.results tr {
    transition: background-color 0.3s;
    }
    div.voting.results tbody tr:hover {
    background-color: aliceblue;
    }

    a, a:link, a:active {
    text-decoration:none;
    color: black;
    }
    </style>
  </head>
<body>
  <div class="fullheight">
    <div class="voting results">
      <table>
        <thead>
          <tr>
            <th><a href="?sort=4" title="order by name">ID</a></th>
            <th><a href="?sort=2" title="order by name">Item</a></th>
            <th><a href="?sort=1" title="order by votes"># Votings</a></th>
            <th><a href="?sort=3" title="order by average vote">Average Result</a></th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <?PHP
            foreach ($results as $item) {
                echo "<tr> <td>".$item["formID"]."</td> <td>".$item["itemName"]."</td> <td>".$item["COUNT"]."</td> <td>".$item["AVG"]." / 5 </td> <!--<td> ".$item["description"]."</td>--> </tr>";
            }
          ?>
        </tbody>
      </table>

      <?PHP
        if ($itemsManipulationOn) {

          switch ($returnMSG) {

            case '1':
              $returnMSG = "Your request has been saved succesfully";
              break;

            case '2':
            case '3':
              $returnMSG = "There was an error concerning your request. Please check your input data and password.";
              break;

            default:
            case '99':
              $returnMSG = "";
              break;
          }

          if ($returnMSG) {
            echo "
              <br />
              <p>".$returnMSG."</p>";
          }

          echo '
            <br />
            <table>
              <tbody>
                <form action="rating_saveItem.php" method="post">
                    <td><input type="number" name="formID" placeholder="Element ID" /></td>
                    <td><input type="text" name="itemName" placeholder="Element Name" /></td>
                    <td><input type="text" name="description" placeholder="Description" /></td>
                  </tr>
                  <tr>
                    <td><input type="password" id="confPW" name="confPW" placeholder="Password" /></td>
                    <td><input type="submit" name="" value="add new item" /></td>
                  </tr>
                </form>
              </tbody>
            </table>';
        }
      ?>

    </div>
  </div>
</body>
</html>