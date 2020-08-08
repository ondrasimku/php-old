<?php
  include "includes/class-autoload.inc.php";

  session_start();

  $usersView = new UsersView();

  if(!isset($_SESSION["username"])) {
    header('Location: login.php');
  }

  if (isset($_POST["submitJob"])) {
    if(!empty($_POST["division"]) && !empty($_POST["lane"]) && !empty($_POST["server"]) && !empty($_POST["type"]) && !empty($_POST["message"]) && !empty($_POST["ingame_username"])) {

      $division = $_POST["division"];
      $lane = $_POST["lane"];
      $server = $_POST["server"];
      $type = $_POST["type"];
      $message = $_POST["message"];
      $autor = $_SESSION["username"];
      date_default_timezone_set('Europe/Belgrade');
      $date = date("Y-m-d H:i:s");
      $ingame_username = $_POST["ingame_username"];

      $jobsControll = new JobsControll();

      $jobsControll->addNewJob($division, $lane, $server, $type, $message, $autor, $date, $ingame_username);
      if(!empty($jobsControll->errorMessage)){$Zprava = $jobsControll->errorMessage;}

    }
    else {
      $Zprava = "Chyba!";
    }
  }

 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/coach-form.css">
    <title>Penta Smurf</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/parallax.min.js"></script>
    <script src="js/dropdown.js"></script>


  </head>
  <body>
    <div class="container">

      <?php include "includes/header.inc.html"; ?>

      <div class="content">

    <?php if(isset($Zprava)) echo $Zprava; ?>
    <form method="post" id="coach-form" action="get_coach.php">
      <table>
        <tr>
          <td align="left"><b>Choose division</b></td>
          <td>
            <div class="select">
            <select class="custom-select" id="division" name="division">
                <option value="Bronze">Bronze</option>
                <option value="Silver">Silver</option>
                <option value="Gold">Gold</option>
                <option value="Platinum">Platinum</option>
                <option value="Diamond">Diamond</option>
            </select>
          </div>
          </td>
        </tr>

        <tr>
          <td align="left"><b>Choose lane</b></td>
          <td>
            <div class="select">
            <select id="lane" name="lane">
              <option value="Top">Top</option>
              <option value="Jungle">Jungle</option>
              <option value="Mid">Mid</option>
              <option value="Adc">ADC</option>
              <option value="Support">Support</option>
            </select>
          </div>
          </td>
        </tr>

        <tr>
          <td align="left"><b>Choose server</b></td>
          <td>
            <div class="select">
            <select id="server" name="server">
              <option value="NA">NA</option>
              <option value="EUW">EUW</option>
              <option value="EUNE">EUNE</option>
              <option value="RUSSIA">Russia</option>
              <option value="WHOKNOWS">Who fuckin knows?</option>
            </select>
          </div>
          </td>
        </tr>

        <tr>
          <td align="left"><b>Coaching type</b></td>
          <td>
            <div class="select">
            <select id="type" name="type">
              <option value="Live_coaching">Live coaching</option>
              <option value="Replay_coaching">Replay coaching</option>
            </select>
          </div>
          </td>
        </tr>

        <tr>
          <td align="left"><b>Ingame name</b></td>
            <td>
              <div class="textEdit"><input type="text" class="edit" name="ingame_username"></div>
            </td>
        </tr>
      </table>

      <div class="textarea">
        <textarea placeholder="Type your message here...." form="coach-form" name="message"></textarea>
      </div>

      <div class="submit">
        <button type="submit" class="btn" name="submitJob">Submit Job</button>
      </div>

    </form>


      </div>

    </div>

    <?php include "includes/footer.inc.html"; ?>
  </body>
</html>
