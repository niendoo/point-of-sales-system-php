<?php

include_once 'config/db_connection.php';

session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales"  )
{
	header('location:index.php');
}
$expense_id = $_POST['pid'];
$sql ="delete from expenses where expense_id = $expense_id";
$delete = $connection->prepare($sql);
if($delete->execute()){
}else{
echo'Error in Deleting Expense';
}










?>
