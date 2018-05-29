<?php
class Product
{
  // database connection and table name
  private $conn;
  private $table_name="products";

  //object property
  public $id;
  public $name;
  public $price;
  public $description;
  public $category_id;
  public $timestamp;

  public function __construct($db)
  {
    $this->conn=$db;
  }

  //create product
  function create()
  {
    //write query
    $query="insert into " . $this->table_name . "
            set name=:name, price=:price, description=:description,
            category_id=:category_id, created=:created";

    $stmt=$this->conn->prepare($query);

    //posted values
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));

    // to get time-stamp for 'created' field
    $this->timestamp=date('Y-m-d H:i:s');

    //bind values
    $stmt->bindParam(":name",$this->name);
    $stmt->bindParam(":price",$this->price);
    $stmt->bindParam(":description",$this->description);
    $stmt->bindParam(":category_id",$this->category_id);
    $stmt->bindParam(":created",$this->timestamp);

    if($stmt->execute())
      return true;
    else
      return false;

  }

  function readAll($from_record_num,$records_per_page)
  {
    $query = "select id,name,description,price,category_id
              from " . $this->table_name . " order by name asc
              limit {$from_record_num}, {$records_per_page}";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;

  }

  // used for paging products
  public function countAll()
  {
    $query="select id from ".$this->table_name;
    $stmt=$this->conn->prepare($query);
    $stmt->execute();
    $num=$stmt->rowCount();
    return $num;
  }

  function readOne()
  {
    $query="select name,price,description,category_id
            from " . $this->table_name . " where id = ? limit 0,1";

    $stmt=$this->conn->prepare($query);
    $stmt->bindParam(1,$this->id);
    $stmt->execute();

    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $this->name=$row['name'];
    $this->price=$row['price'];
    $this->description=$row['description'];
    $this->category_id=$row['category_id'];
  }

  // delete the product
  function delete()
  {
    $query = "delete from " . $this->table_name . " where id = ?";

    $stmt=$this->conn->prepare($query);
    $stmt->bindParam(1,$this->id);

    if($result=$stmt->execute())
      return true;
    else
      return false;

  }

  function update()
  {
    $query="update " . $this->table_name . " set name= :name, price= :price, description= :description,
            category_id= :category_id where id= :id";

    $stmt=$this->conn->prepare($query);

    //posted value
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
    $this->id=htmlspecialchars(strip_tags($this->id));

    // bind parameters
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':price', $this->price);
    $stmt->bindParam(':description', $this->description);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':id', $this->id);
 

    // execute the query
    if($stmt->execute())
      return true;

    return false;


  }


} // End class


 ?>
