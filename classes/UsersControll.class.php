<?php
  class UsersControll extends Users {

    public function registerUser($username, $password, $passwordAgain, $email) {
      if(!$this->registerCheckUser($username, $email))
      {
      $this->newUser($username, $password, $passwordAgain, $email);
      }
    }

    public function registerCheckUser($username, $email) {
      return $this->checkUserExistence($username, $email);
    }

    public function loginUser($username, $password) {
      return $this->userAuthenticate($username, $password);
    }

    public function changeUsername($username) {
      return $this->changeUserUsername($username);
    }

    public function changeEmail($email) {
      return $this->changeUserEmail($email);
    }

    public function makeEmailChange($newEmail, $emailString) {
      $this->makeFinalEmailChange($newEmail, $emailString);
    }

    public function changePassword($password, $passwordAgain, $oldPassword, $username) {
      return $this->changeUserPassword($password, $passwordAgain, $oldPassword, $username);
    }

    public function registerCheckEmailString($string, $username) {
      return $this->checkEmailString($string, $username);
    }

  }
