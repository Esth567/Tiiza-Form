<?php
// Include WordPress core functionality
class Tracker {

    // (A) CONSTRUCTOR - CONNECT TO DATABASE
  public $pdo = null;
  public $stmt = null;
  public $error = "";
  function __construct () { try {
    $this->pdo = new PDO(
      "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET, 
      DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
  } catch (Exception $ex) { exit($ex->getMessage()); }}

  //  DESTRUCTOR - CLOSE DATABASE CONNECTION
  function __destruct () {
    if ($this->stmt !== null) { $this->stmt = null; }
    if ($this->pdo !== null) { $this->pdo = null; }
  }

   // (C) HELPER FUNCTION - EXECUTE SQL QUERY
   function query ($sql, $data=null) : void {
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute($data);
  }

}

//database settings
define("DB_HOST", "localhost");
define("DB_NAME", "tiizaco1_wp133");
define("DB_CHARSET", "utf8mb4");
define("DB_USER", "estherb");
define("DB_PASSWORD", "");
 
// (G) START!
$_TRACKER = new Tracker();
