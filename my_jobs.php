<?php
  include "includes/class-autoload.inc.php";

  session_start();

  $usersView = new UsersView();

  $jobsView = new JobsView();

 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/my-jobs.css">
    <title>Penta Smurf</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/parallax.min.js"></script>
    <script src="js/dropdown.js"></script>

  </head>
  <body>


    <div class="container">

      <?php include "includes/header.inc.html"; ?>

      <div class="content" style="width: 100%; text-align: center;">

        <table>
          <tr>
            <th class="narrow">
              Division
            </th>
            <th class="narrow">
              Lane
            </th>
            <th class="narrow">
              Server
            </th>
            <th class="narrow">
              Type
            </th>
            <th class="narrow">
              Message
            </th>
            <th>
              Autor
            </th>
            <th>
              In-game username
            </th>
            <th class="narrow">
              Status
            </th>
            <th class="narrow">
              Actions
            </th>
          </tr>

      <?php
        $row = $jobsView->viewMyJobs($_SESSION["username"]);

        $i = 0;
        foreach($row as $rows) {
          ?>


            <tr>
              <td class="narrow">
                <?php echo(htmlspecialchars($rows["division"])); ?>
              </td>
              <td class="narrow">
                <?php echo(htmlspecialchars($rows["lane"])); ?>
              </td>
              <td class="narrow">
                <?php echo(htmlspecialchars($rows["server"])); ?>
              </td>
              <td>
                <?php echo(htmlspecialchars($rows["type"])); ?>
              </td>
              <td class="narrow">
                <button id="myBtn" onclick="alert('<?php echo(htmlspecialchars($rows["message"])); ?>');"><?php echo("Click me!"); ?></button>


              </td>
              <td>
                <?php echo(htmlspecialchars($rows["autor"])); ?>
              </td>
              <td>
                <?php echo(htmlspecialchars($rows["ingame_username"])); ?>
              </td>
              <td class="narrow">
                <?php echo(htmlspecialchars($rows["status"])); ?>
              </td>
              <td class="narrow">
                <button>Pay</button>
              </td>
            </tr>


          <?php
        }
      ?>
        </table>

      </div>

    </div>

    <?php include "includes/footer.inc.html"; ?>
  </body>
</html>
