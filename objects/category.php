<?php
class Category
{
  //database connection adn table name
  private $conn;
  private $table_name="categories";

  //object properties
  public $id;
  public $name;

  public function __construct($db)
  {
    $this->conn=$db;
  }

  //used by select drop-down list
  function read()
  {
    //select all data
    $query="select id, name from " . $this->table_name . " order by name";
    $stmt=$this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  //used to read category name by its ID
  function readName()
  {
    $query="select name from ".$this->table_name." where id = ? limit 0,1";
    $stmt=$this->conn->prepare($query);
    $stmt->bindParam(1,$this->id);
    $stmt->execute();

    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $this->name=$row['name'];
  }
}


 ?>
