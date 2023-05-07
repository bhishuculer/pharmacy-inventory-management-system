<?php

class db {
  private $host = "localhost";
  private $user = "root";
  private $password = "";
  private $database = "medicines";
  public function getConnection() {
    $mysqli = new mysqli($this->host, $this->user, $this->password);
    if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
  }
}
