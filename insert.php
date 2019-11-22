<?php
//insert.php;

if(isset($_POST["certificate"]))
{
 $connect = new PDO("mysql:host=localhost;dbname=testing4", "root", "");
 $order_id = uniqid();
 for($count = 0; $count < count($_POST["certificate"]); $count++)
 {  
  $query = "INSERT INTO tbl_order_items 
  (order_id, state_name, reason, certificate, begin_date, end_date, notes) 
  VALUES (:order_id, :state_name, :reason, :certificate, :begin_date, :end_date, :notes)
  ";
  $statement = $connect->prepare($query);
  $statement->execute(
   array(
    ':order_id'   => $order_id,
    ':state_name'  => $_POST["state_name"][$count], 
    ':reason' => $_POST["reason"][$count], 
    ':certificate'  => $_POST["certificate"][$count],
	':begin_date'  => $_POST["begin_date"][$count],
	':end_date'  => $_POST["end_date"][$count],
	':notes'  => $_POST["notes"][$count]
   )
  );
 }
 $result = $statement->fetchAll();
 if(isset($result))
 {
  echo 'ok';
 }
}
?>