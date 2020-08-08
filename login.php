<?php
  include "includes/class-autoload.inc.php";
  session_start();

  $Zprava = "";

  if($_POST)
  {
    if(isset($_POST["prihlasit"]))
    {
      if(isset($_POST["jmeno"]) && isset($_POST["heslo"]))
      {
        $jmeno = $_POST["jmeno"];
        $heslo = $_POST["heslo"];

        $userControll = new UsersControll();

        if(!$userControll->loginUser($jmeno, $heslo)) {
          $Zprava = $userControll->errorMessage;
        }

      }
    }
  }

  if($_GET) {

    if(isset($_GET["odhlasit"])) {
      if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
          setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
          );
      }

      // Finally, destroy the session.
      session_destroy();
    }
  }


  if (isset($_SESSION["username"])) {
    header("Location: /index.php");
  }


?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login-form.css">
    <title>Penta Smurf</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/parallax.min.js"></script>
    <script src="js/dropdown.js"></script>
  </head>

  <body>
    <div class="container">

    <?php include "includes/header.inc.html"; ?>


      <div class="content">

        <div class="login-box">
          <center><?php if(isset($Zprava)) echo(htmlspecialchars($Zprava)); ?></center>
          <h1>Welcome to Penta Smurf!</h1>
          <form action="login.php" method="post">
          <div class="textbox">
            <i class="fas fa-user"></i>
            <input type="text" name="jmeno" placeholder="Uživatelské jméno">
          </div>

          <div class="textbox">
            <i class="fas fa-lock"></i>
            <input type="password" name="heslo" placeholder="Heslo">
          </div>

          <div class="submit">
          <button type="submit" class="btn" name="prihlasit">Přihlásit</button>
          </div>

        </form>
        </div>

      </div>


    </div>

    <?php include "includes/footer.inc.html"; ?>

  </body>
</html>
