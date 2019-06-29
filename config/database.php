<?php
class Database{
 
  // specify your own database credentials
  private $host;
  private $db_name;
  private $username;
  private $password;
  public $conn;

  public function __construct($host, $db_name, $username, $password){
    $this->host = $host;
    $this->db_name = $db_name;
    $this->username = $username;
    $this->password = $password;
  }

  // get the database connection
  public function getConnection(){
      $this->conn = null;
      try{
          $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
          $this->conn->exec("set names utf8");
      }catch(PDOException $exception){
          echo "Connection error: " . $exception->getMessage();
      }
      return $this->conn;
  }

  public static function qData($db, $q, $params=array()){
    $stmt = $db->prepare( $q );
    if($stmt->execute($params)){
      return true;
    }
    // var_dump($stmt->debugDumpParams());
    
    return false;
  }

  
  public static function gData($db, $q, $params=array()){
    $stmt = $db->prepare( $q );
    try{
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $exception){ 
      $result = $exception->getMessage(); 
    } 
    return $result;
  }
}