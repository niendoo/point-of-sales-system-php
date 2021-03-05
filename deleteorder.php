<?php

include_once 'config/db_connection.php';

session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales"  )
{
	header('location:index.php');
}

$product_id = $_POST['pid'];


// $sql ="delete from product_table where product_id = $product_id";
//DELETE T1, T2 FROM T1 INNER JOIN T2 ON T1.Key = T2.key WHERE condition T1.key=id;
$sql="delete invoice_table, invoice_details_table FROM invoice_table INNER JOIN invoice_details_table ON 
invoice_table.invoice_id = invoice_details_table.invoice_id where invoice_table.invoice_id = $product_id";

$delete = $connection->prepare($sql);

if($delete->execute()){

}else{
echo'Error in Deleting Product';
}










?>
