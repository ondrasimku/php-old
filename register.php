<?php
  include "includes/class-autoload.inc.php";
  include "includes/header.inc.php";
  session_start();
  $Zprava = "";
  $userControll = new UsersControll();

  if($_POST && isset($_POST["registrovat"]))
  {
      if(!empty($_POST["jmeno"]) && !empty($_POST["heslo"]) && !empty($_POST["heslo_znovu"]) && !empty($_POST["email"]))
      {
        $jmeno = $_POST["jmeno"];
        $heslo = $_POST["heslo"];
        $hesloZnovu = $_POST["heslo_znovu"];
        $email = $_POST["email"];

        $userControll->registerUser($jmeno, $heslo, $hesloZnovu, $email);
        $Zprava = $userControll->errorMessage;
      }
      else {
        $Zprava = "Musíš vyplnit všechna pole!";
      }
  }

  if (isset($_SESSION["username"])) {
    header("Location: /index.php");
  }

  if($_GET) {
    if(isset($_GET["email_string"]) && isset($_GET["username"])) {
      if($userControll->registerCheckEmailString($_GET["email_string"], $_GET["username"])) {
        $Zprava = "Email ověřen!";
      } else {
        $Zprava = "Chyba!";
      }
    }
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
          <?php if(isset($Zprava)) echo $Zprava; ?>
          <h1>Welcome to Penta Smurf!</h1>
          <form action="register.php" method="post">
          <div class="textbox">
            <i class="fas fa-user"></i>
            <input type="text" name="jmeno" value="<?php if(isset($jmeno)){echo "$jmeno";}?>" placeholder="Uživatelské jméno">
          </div>

          <div class="textbox">
            <i class="fas fa-lock"></i>
            <input type="password" name="heslo" value="<?php if(isset($heslo)){echo "$heslo";}?>" placeholder="Heslo">
          </div>

          <div class="textbox">
            <i class="fas fa-lock"></i>
            <input type="password" name="heslo_znovu" value="<?php if(isset($hesloZnovu)){echo "$hesloZnovu";}?>" placeholder="Heslo znovu">
          </div>

          <div class="textbox">
            <i class="fas fa-home"></i>
            <input type="email" name="email" value="<?php if(isset($email)){echo "$email";}?>" placeholder="E-mail">
          </div>

          <div class="submit">
          <button type="submit" class="btn" name="registrovat">Registrovat</button>
          </div>

        </form>
        </div>

      </div>

    </div>

    <?php include "includes/footer.inc.html"; ?>

  </body>
</html>
