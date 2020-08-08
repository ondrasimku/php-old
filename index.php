<?php
  include "includes/class-autoload.inc.php";

  session_start();

  $usersView = new UsersView();

 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Penta Smurf</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/parallax.min.js"></script>
    <script src="js/dropdown.js"></script>

  </head>
  <body>
    <div class="container">

      <?php include "includes/header.inc.html"; ?>

      <div class="content">
        <p>On your order page, you will be able to get in touch with your Booster whenever you want.
          This allows you to ask the Booster any questions about the game, such as champions, builds
          or tactics. You can also use chat for special requests such as screenshotting messages
          from your friends, or adding your smurf account which will allow you to spectate games
          played by our worker. Last but not least, you can simply have a nice chat about non-game
          stuff, our boosters are in fact very open-minded and absolutely friendly people. As you
          can see, there are, without a doubt, a lot of advantages that you can get with our live chat.
          We have only provided a few examples to help you make good use of this feature.</p>

        <p>Other notable features on your order page are the Match history and the Pause button that
          we developed, especially for your comfort and ability to control how your account will be
          boosted. Thanks to the match history you will be able to keep an eye on the entire progress
          made on your order, with lots of useful informations about the match, such as K/D/A, creep
          score, date of the match and even builds. The system is fully automatized, games are updated
          shortly after the match ends. The pause function will allow you to select the hours during
          which you will be boosted, simply press the pause button when you want to enter your account,
          or slow down the completion time of your order, then Unpause to reactivate your boost and
          let us continue our work.</p>

          <p>On your order page, you will be able to get in touch with your Booster whenever you want.
            This allows you to ask the Booster any questions about the game, such as champions, builds
            or tactics. You can also use chat for special requests such as screenshotting messages
            from your friends, or adding your smurf account which will allow you to spectate games
            played by our worker. Last but not least, you can simply have a nice chat about non-game
            stuff, our boosters are in fact very open-minded and absolutely friendly people. As you
            can see, there are, without a doubt, a lot of advantages that you can get with our live chat.
            We have only provided a few examples to help you make good use of this feature.</p>

            <p>Other notable features on your order page are the Match history and the Pause button that
              we developed, especially for your comfort and ability to control how your account will be
              boosted. Thanks to the match history you will be able to keep an eye on the entire progress
              made on your order, with lots of useful informations about the match, such as K/D/A, creep
              score, date of the match and even builds. The system is fully automatized, games are updated
              shortly after the match ends. The pause function will allow you to select the hours during
              which you will be boosted, simply press the pause button when you want to enter your account,
              or slow down the completion time of your order, then Unpause to reactivate your boost and
              let us continue our work.</p>
      </div>

    </div>

    <?php include "includes/footer.inc.html"; ?>
  </body>
</html>
