<?php
  include "includes/class-autoload.inc.php";
  session_start();

  $Zprava = "";
  $userControll = new UsersControll();

  if (!isset($_SESSION["username"])) {
    header("Location: /login.php");
  }


  if($_POST) {


    if(isset($_POST["ulozit"])) {

      if(isset($_POST["newUsername"])) {

          $userControll->changeUsername($_POST["newUsername"]);

      } else if(isset($_POST["newPassword"]) && isset($_POST["newPasswordAgain"]) && isset($_POST["oldPassword"])) {

          $userControll->changePassword($_POST["newPassword"], $_POST["newPasswordAgain"], $_POST["oldPassword"], $_SESSION["username"]);

      } else if(isset($_POST["newEmail"])) {

        $userControll->changeEmail($_POST["newEmail"]);

      } else {

        $Zprava = "Chyba";
      }

    }
  }

  $usersView = new UsersView();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/edit-profile.css">
    <link rel="stylesheet" href="css/login-form.css">
    <title>Penta Smurf</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/parallax.min.js"></script>
    <script src="js/dropdown.js"></script>
  </head>

  <body>
    <div class="container">

      <?php include "includes/header.inc.html";?>


      <div class="content">

      <?php if(isset($_GET["Zprava"])) {
              $Zprava = $_GET["Zprava"];
            }

          if(isset($_GET["editUsername"])) {


            echo('<center>'.htmlspecialchars($Zprava).'</center>');
          ?>
        <form method="post" action="edit_profile.php">
          <table class="edit">
            <tr>
              <td align="right"><b>Old username:</b></td>
              <td><?php echo htmlspecialchars($usersView->getUsername());?></td>
            </tr>

            <tr>
              <td align="right"><b>New username:</b></td>

              <td><div class="textEdit"><input type="text" class="edit" name="newUsername"></div></td>
            </tr>
          </table>
          <button type="submit" class="ulozit" name="ulozit">Změnit</button>
        </form>


          <?php


      } else if(isset($_GET["editEmail"])) {

            if(isset($_GET["editEmail"]) && isset($_GET["newEmail"]) && isset($_GET["email_string"])) {

              $userControll->makeEmailChange($_GET["newEmail"], $_GET["email_string"]);

            }
            echo('<center>'.htmlspecialchars($Zprava).'</center>');

        ?>
      <form method="post" action="edit_profile.php">
        <table class="edit">
          <tr>
            <td align="right"><b>Old E-mail:</b></td>
            <td><i><?php echo(htmlspecialchars($usersView->getEmail()));?></i></td>
          </tr>

          <tr>
            <td align="right"><b>New E-mail:</b></td>

            <td><div class="textEdit"><input type="email" class="edit" name="newEmail"></div></td>
          </tr>
        </table>
        <button type="submit" class="ulozit" name="ulozit">Změnit</button>
      </form>

        <?php


      } else if(isset($_GET["editPassword"])) {

          echo('<center>'.htmlspecialchars($Zprava).'</center>');
        ?>
      <form method="post" action="edit_profile.php">
        <table class="edit">
          <tr>
            <td align="right"><b>Old password:</b></td>

            <td><div class="textEdit"><input type="password" class="edit" name="oldPassword"></div></td>
          </tr>

          <tr>
            <td align="right"><b>New password:</b></td>

            <td><div class="textEdit"><input type="password" class="edit" name="newPassword"></div></td>
          </tr>

          <tr>
            <td align="right"><b>Password Again:</b></td>

            <td><div class="textEdit"><input type="password" class="edit" name="newPasswordAgain"></div></td>
          </tr>
        </table>
        <button type="submit" class="ulozit" name="ulozit">Změnit</button>
      </form>

        <?php

      } else {

          ?>
          <form action="edit_profile.php" method="get">
            <table>
              <tr>
                <td align="right"><b>Username:</b></td>
                <td><?php echo htmlspecialchars($usersView->getUsername());?></td>
                <td><button type="submit" class="editButton" name="editUsername">EDIT</button></td>
              </tr>

              <tr>
                <td align="right"><b>Password:</b></td>
                <td>*******</td>
                <td><button type="submit" class="editButton" name="editPassword">EDIT</button></td>
              </tr>

              <tr>
                <td align="right"><b>Email:</b></td>
                <td><?php echo htmlspecialchars($usersView->getEmail());?></td>
                <td><button type="submit" class="editButton" name="editEmail">EDIT</button></td>
              </tr>
            </table>
          </form>
          <?php
      }?>

      </div>


    </div>

    <?php include "includes/footer.inc.html";?>

  </body>
</html>
