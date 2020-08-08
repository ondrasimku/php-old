<?php

 class Jobs extends Dbh {

    public $errorMessage;

    protected function newJob($division, $lane, $server, $type, $message, $autor, $currentdate, $ingame_username) {

      if($this->checkJobValid($autor) == true) {
        $sql = "INSERT INTO jobs(division, lane, server, type, message, autor, currentdate, ingame_username, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$division, $lane, $server, $type, $message, $autor, $currentdate, $ingame_username, "unpaid"]);
      } else {
        $this->errorMessage = "Již máš dvě práce!";
        }

    }

    private function checkJobValid($autor) {

      $sql = "SELECT autor FROM jobs WHERE autor = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$autor]);

      if($stmt->rowCount() >= 2) {
        return false;
      } else {
        return true;
      }

    }

    protected function viewJobs($autor) {

      $sql = "SELECT * FROM jobs WHERE autor = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$autor]);
      $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $row = array();

      foreach ($user as $rows) {
        $row[] = $rows;
      }

      return $row;
    }

 }
