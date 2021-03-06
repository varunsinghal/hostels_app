<?php

require_once(LIB_PATH.DS."config.php");

class MySQLDatabase
{
  private $connection;
  public $last_query;
  private $magic_quotes_active;
  private $real_escape_string_exists;
  function __construct(){
    $this->open_connection();
    $this->magic_quotes_active = get_magic_quotes_gpc();
    $this->real_escape_string_exists = function_exists("mysql_real_escape_string");  // i.e. PHP >= v4.3.0
  }
  public function open_connection(){
    $this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
    if(!$this->connection){
      die(mysql_error());
    }
    $db_select = mysql_select_db(DB_NAME, $this->connection);
    if(!$db_select){
      die(mysql_error());
    }
  }
  public function close_connection(){
    if(isset($this->connection)){
      mysql_close($this->connection);
      unset($this->connection);
    }
  }
  public function query($sql){
    $this->last_query = $sql;
    $result = mysql_query($sql, $this->connection);
    $this->confirm_query($result);
    return $result;
  }
  public function escape_value($value){
    if($this->real_escape_string_exists){ // PHP v4.3.0 or higher
      // undo any magic quote effects so that mysql_real_escape_string can do the work
      if($this->magic_quotes_active) {$value = stripslashes($value);}
      $value = mysql_real_escape_string($value);
    }
    else
    {   // before PHP v.4.3.0
      // if magic quotes aren't already on then add slashes manually
      if(!$this->magic_quotes_active) {$value = addslashes($value);}
      // if magic quotes are active then slashe already exist
    }
    return $value;
  }
  public function fetch_array($result_set){
    // Returns an array of strings that corresponds to the fetched row, or FALSE if there are no more rows
    return mysql_fetch_array($result_set);
  }
  public function num_rows($result_set){
    // Returns The number of rows in a result set on success or FALSE on failure
    return mysql_num_rows($result_set);
  }
  public function insert_id(){
    // get the last id inserted over the current database connection
    return mysql_insert_id($this->connection);
  }
  public function affected_rows(){
    return mysql_affected_rows($this->connection);
  }
  private function confirm_query($result){
    if(!$result){
      $output = "Database Query failed ".mysql_error()."<br /><br />";
      $output .= "Last SQL query ".$this->last_query;
      die($output);
    }
  }
}

$database = new MySQLDatabase();
$db = &$database;

?>