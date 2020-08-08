<?php

class Users extends Dbh{

  protected $username;
  public $errorMessage;

  protected function newUser($username, $password, $passwordAgain, $email) {

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $randomString = $this->generateRandomString(16);

    if (strlen($username) > 0 && strlen(trim($username)) == 0) {
      $this->errorMessage = "Vyber si jméno.";
      return false;
    }

    if(strlen(trim($username)) > 40) {
      $this->errorMessage = "Vyber si jméno.";
    }

    if(!$uppercase) {
      $this->errorMessage = "Heslo musí obsahovat aspoň jedno velké písmeno.";
    } else if(!$lowercase) {
      $this->errorMessage = "Heslo musí obsahovat aspoň jedno malé písmeno.";
    } else if(!$number) {
      $this->errorMessage = "Heslo musí obsahovat aspoň jednu číslici.";
    } else if($password != $passwordAgain) {
      $this->errorMessage = "Hesla se neshodují.";
    } else {

      $sql = "INSERT INTO users(username, password, email, email_overeni, email_string, prava) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$username, $password, $email, 0, $randomString, 0]);

      $subject_text = "Ověření účtu";
      $text = '<html>
      <head>
        <meta charset="utf-8">
      </head>
      <body style="background-color: white;color: #9494a4; line-height: 1.425; color: #9494a4; font-size: 2vh; font-family: Source Sans Pro,sans-serif; padding-top: 5vh; padding-bottom: 5vh;">
        <center>
        <div class="container" style=";color: #9494a4; background-color: #121622; width: 40vw; border: 2px solid grey; text-align: justify; padding: 5vh 5vh 5vh 5vh; border-radius: 3vh;">
          <center><img src="http://pentasmurf.com/pentasmurf.PNG" alt="Logo" title="Logo" style="display:block" width="440" height="120" /></center>
          Dobrý den,<br><br>pro dokončení vaší registrace na <span style="text-decoration: none;">pentasmurf.com</span> klikněte na tlačítko níže.<br>
          <center><a href="http://pentasmurf.com/register.php?email_string='.$randomString.'&username='.$username.'"><button style="width: 20%; background: none; border: 0.2vh solid #9c1c8d; color: white; padding: 1vh; font-size: 2.2vh; border-radius: 2vh; cursor: pointer; margin-top: 3vh; color: #9494a4;">Ověřit</button></a></center>
          <br>
          Pokud jste to nebyl vy, tento email ignorujte.<br><br>
          <i>S pozdravem,<br>váš penta smurf team!</i>
          <br><br><footer style=" font-size: 1.4vh; color: #9494a4; text-align: center; padding-top: 1vh; padding-bottom: 1vh;">Website and design by ©Ondřej Šimků 2020</footer>
        </div>
        </center>
      </body>
      </html>
      ';

      $to = $email;
      if($this->sendEmail($subject_text, $text, $to)) {
        $this->errorMessage = "Email odeslán!";
      } else {
        $this->errorMessage = "Chyba!";
      }

    }

  }

  protected function checkUserExistence($username, $email) {

    $sql = "SELECT username FROM users WHERE username = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$username]);

    if($stmt->rowCount()) {
      $this->errorMessage = "Uživatelské jméno je již registrované!";
      return true;
    } else {

      $sql = "SELECT email FROM users WHERE email = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$email]);

      if($stmt->rowCount()) {
        $this->errorMessage = "Email je již registrován!";
        return true;
      } else {
        $this->errorMessage = "Byl ti odeslán ověřovací email!";
        return false;
      }

    }

  }

  protected function userAuthenticate($username, $password) {

    $sql = "SELECT username FROM users WHERE username = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$username]);

    if($stmt->rowCount()) {
      $jmeno = $stmt->fetch();
      $sql = "SELECT password FROM users WHERE username = ? AND BINARY password = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$username, $password]);

      if($stmt->rowCount()) {
        $sql = "SELECT email_overeni FROM users WHERE username = ? AND BINARY password = ? AND email_overeni = 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$username, $password]);

        if($stmt->rowCount()) {
          session_start();
          $this->username = $jmeno["username"];
          $_SESSION["username"] = $this->username;
          header("Location: /index.php");
        } else {
          $this->errorMessage = "Nemáš ověřený email";
          return false;
        }

      } else {
        $this->errorMessage = "Špatné heslo";
        return false;
      }

    } else {
      $this->errorMessage = "Uživatelské jméno neexistuje";
      return false;
    }

  }

  protected function getSessionUsername() {
    if(isset($_SESSION["username"])) {
      $this->username = $_SESSION["username"];
      return $this->username;
    }
  }

  protected function getUserEmail($username) {
    $sql = "SELECT email FROM users WHERE username = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    return $user["email"];
  }

  protected function getUserPassword($username) {
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    return $user["password"];
  }

  public function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  protected function changeUserUsername($username) {

    if (empty(trim($username))) {
      header("Location: /edit_profile.php?editUsername=1&Zprava=Musíš+něco+vyplnit");
      return false;
    }

    if($this->getSessionUsername() == trim($username)) {
      header("Location: /edit_profile.php?editUsername=1&Zprava=Tohle+jméno+už+máš");
      return false;
    }

    $sql = "SELECT username FROM users WHERE username=?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([trim($username)]);

    if($stmt->rowCount()) {
      header("Location: /edit_profile.php?editUsername=1&Zprava=Jméno+je+již+zabrané");
      return false;
    }

    $sql = "UPDATE users SET username=? WHERE username=?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([trim($username), $this->getSessionUsername()]);

    if($stmt->rowCount()) {
      $_SESSION["username"] = trim($username);
      header("Location: /edit_profile.php");
      return true;
    } else {
      header("Location: /edit_profile.php?editUsername=1&Zprava=Chyba");
      return false;
    }
  }

  protected function changeUserEmail($email) {

    if($this->getUserEmail($this->getSessionUsername()) == $email) {
      header("Location: /edit_profile.php?editEmail=1&Zprava=Tento+email+již+máš");
      return false;
    }

    $sql = "SELECT email FROM users WHERE email=?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$email]);

    if($stmt->rowCount()) {
      header("Location: /edit_profile.php?editEmail=1&Zprava=Email+je+již+zabraný");
      return false;
    }

    $randomString = $this->generateRandomString(16);


    $sql = "UPDATE users SET email_string=? WHERE username=?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$randomString, $this->getSessionUsername()]);

    if($stmt->rowCount()) {


          $subject_text = "Ověření účtu";
          $text = '<html>
          <head>
            <meta charset="utf-8">
          </head>
          <body style="background-color: white;color: #9494a4; line-height: 1.425; color: #9494a4; font-size: 2vh; font-family: Source Sans Pro,sans-serif; padding-top: 5vh; padding-bottom: 5vh;">
            <center>
            <div class="container" style=";color: #9494a4; background-color: #121622; width: 40vw; border: 2px solid grey; text-align: justify; padding: 5vh 5vh 5vh 5vh; border-radius: 3vh;">
              <center><img src="http://pentasmurf.com/pentasmurf.PNG" alt="Logo" title="Logo" style="display:block" width="440" height="120" /></center>
              Dobrý den,<br><br>pro dokončení změny emailu na <span style="text-decoration: none;">pentasmurf.com</span> klikněte na tlačítko níže.<br>
              <center><a href="http://pentasmurf.com/edit_profile.php?editEmail=1&newEmail='.$email.'&email_string='.$randomString.'"><button style="width: 20%; background: none; border: 0.2vh solid #9c1c8d; color: white; padding: 1vh; font-size: 2.2vh; border-radius: 2vh; cursor: pointer; margin-top: 3vh; color: #9494a4;">Ověřit</button></a></center>
              <br>
              Pokud jste to nebyl vy, tento email ignorujte.<br><br>
              <i>S pozdravem,<br>váš penta smurf team!</i>
              <br><br><footer style=" font-size: 1.4vh; color: #9494a4; text-align: center; padding-top: 1vh; padding-bottom: 1vh;">Website and design by ©Ondřej Šimků 2020</footer>
            </div>
            </center>
          </body>
          </html>
          ';


          $to = $email;
          if($this->sendEmail($subject_text, $text, $to)) {
            header("Location: /edit_profile.php?editEmail=1&Zprava=Byl+ti+odeslán+ověřovací+email");
            return false;
          } else {
            header("Location: /edit_profile.php?editEmail=1&Zprava=Nepodařilo+se+odeslat+email");
          }


    } else {
      header("Location: /edit_profile.php?editEmail=1&Zprava=Chyba");
      return false;
    }

  }

  protected function makeFinalEmailChange($newEmail, $emailString) {

    $sql = "SELECT email FROM users WHERE email=?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$newEmail]);

    if($stmt->rowCount()) {
      return false;
    }

    $oldEmail = $this->getUserEmail($this->getSessionUsername());

    $sql = "SELECT email_string, newEmail FROM users WHERE email = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$oldEmail]);
    $result = $stmt->fetch();

    if($result["email_string"] == $emailString && $result["newEmail"] == $newEmail) {

      $sql = "UPDATE users SET email=? WHERE username=?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$newEmail, $this->getSessionUsername()]);

      if($stmt->rowCount()) {

        $randomString = $this->generateRandomString(16);
        $sql = "UPDATE users SET email_string=? WHERE username=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$randomString, $this->getSessionUsername()]);

        if($stmt->rowCount()) {
        return true;
        } else {
          header("Location: /edit_profile.php?editEmail=1&Zprava=Nelze+vygenerovat+string+kontaktujte+admina");
          return true;
        }
      } else {
        header("Location: /edit_profile.php?editEmail=1&Zprava=Chyba");
        return false;
      }

    }

  }

  protected function changeUserPassword($password, $passwordAgain, $oldPassword, $username) {

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    $sql = "SELECT password FROM users WHERE username = ? AND BINARY password = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$username, $oldPassword]);

    if($stmt->rowCount() != 0) {

          if(!$uppercase) {
            header("Location: /edit_profile.php?editPassword=1&Zprava=Heslo+musí+obsahovat+aspoň+jedno+velké+písmeno.");
            return false;
          } else if(!$lowercase) {
            header("Location: /edit_profile.php?editPassword=1&Zprava=Heslo+musí+obsahovat+aspoň+jedno+malé+písmeno.");
            return false;
          } else if(!$number) {
            header("Location: /edit_profile.php?editPassword=1&Zprava=Heslo+musí+obsahovat+aspoň+jednu+číslici.");
            return false;
          } else if($password != $passwordAgain) {
            header("Location: /edit_profile.php?editPassword=1&Zprava=Hesla+se+neshodují");
            return false;
          } else {

            if (strlen($password) > 0 && strlen(trim($password)) == 0) {
              header("Location: /edit_profile.php?editPassword=1&Zprava=Nic+jsi+nezadal");
              return false;
            }

            if($this->getUserPassword($this->getSessionUsername()) == $password) {
              header("Location: /edit_profile.php?editPassword=1&Zprava=Musíš+si+vybrat+nové+heslo");
              return false;
            }

            $sql = "UPDATE users SET password=? WHERE username=?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$password, $this->getSessionUsername()]);

            if($stmt->rowCount()) {
              header("Location: /edit_profile.php?editPassword=1&Zprava=Změněno");
              return true;
            } else {
              header("Location: /edit_profile.php?editPassword=1&Zprava=Chyba");
              return false;
            }

          }

    } else {
      header("Location: /edit_profile.php?editPassword=1&Zprava=Nesprávné+staré+heslo");
    }

  }

  private function sendEmail($subject_text, $text, $to) {

    header('Content-Type: text/html; charset=utf-8');
    $subject = '=?UTF-8?B?' . base64_encode($subject_text) . '?=';
    $sender = "pentasmurf.com";

    $headers = 'Content-Type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'Content-Transfer-Encoding: base64';
    $headers .= 'From: ' . $sender;

    $message = $text;
    $success = mail($to, $subject, $message, $headers, "-f coaching@pentasmurf.com");

    if ($success) {
        return true;
    } else {
        print_r(error_get_last());
        return false;
    }

  }


  protected function checkEmailString($string, $username) {
    $sql = "SELECT email_string FROM users WHERE username = ? AND BINARY email_string = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$username, $string]);

    if($stmt->rowCount()) {
      $sql = "UPDATE users SET email_overeni=? WHERE username=?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([1, $username]);

      if($stmt->rowCount()) {
        return true;
      }

    } else {
      return false;
    }

  }

}
