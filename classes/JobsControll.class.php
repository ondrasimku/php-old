<?php

class JobsControll extends Jobs {

    public function addNewJob($division, $lane, $server, $type, $message, $autor, $currentdate, $ingame_username) {
      $this->newJob($division, $lane, $server, $type, $message, $autor, $currentdate, $ingame_username);
    }

}
