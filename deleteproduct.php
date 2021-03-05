<?php

include_once 'config/db_connection.php';

session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales"  )
{
	header('location:index.php');
}

$product_id = $_POST['pid'];


$sql ="delete from product_table where product_id = $product_id";


$delete = $connection->prepare($sql);

if($delete->execute()){

}else{
echo'Error in Deleting Product';
}










?>
