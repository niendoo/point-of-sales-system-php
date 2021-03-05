<?php

//database connection
include_once 'config/db_connection.php';

$id= $_GET["id"];
$select = $connection->prepare("select * from product_table where product_id= :product_id");
$select->bindParam(':product_id', $id);
$select->execute();
$row=$select->fetch(PDO::FETCH_ASSOC);

$respone=$row;

header('Content-Type: application/json');
echo json_encode($respone);







?>
