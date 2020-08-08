<?php

class UsersView extends Users {

  public function getUsername() {
    return $this->getSessionUsername();
  }

  public function getEmail() {
    return $this->getUserEmail($this->getUsername());
  }
}
