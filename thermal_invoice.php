<?php
//Call the PDF library
require('fpdf/fpdf.php');

include_once 'config/db_connection.php';

session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] ==""  )
{
	header('location:index.php');
}

$id=$_GET['id'];

$select=$connection->prepare("select * from invoice_table where invoice_id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

//Create PDF object

$pdf = new FPDF('P','mm',[80,200]);


//Add new page
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->SetFillColor(224,247,250);
//Cell(Width, Height, Text, Border, End Line, Alignment)
$pdf->Cell(62,10,'N-TECH POS SYSTEM',1,1,'C', true);
$pdf->SetFont('Courier','BI',9); 
$pdf->Cell(60,5,'Address: Second Ring Road Kakpagyilli',0,1,'C'); 
$pdf->Cell(60,5,'Phone Number: +233206421152',0,1,'C'); 
$pdf->Cell(60,5,'Email: support@ntechpos.com',0,1,'C'); 
$pdf->Cell(60,5,'Website: ntechpos.com',0,1,'C');

//Line Break 
$pdf->Line(7,40,72,40); 
$pdf->Line(7,41,72,41); 
$pdf->Line(7,42,72,42);
//Empty Cell
$pdf->Cell(40,4,'',0,1,''); 

$pdf->SetFont('Courier','BI',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,4,'Bill To :',0,0,''); 

$pdf->SetFont('Courier','BI',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,4,$row->customer_name,0,1,''); 
// $pdf->Cell(40,4,'',0,1,''); 
  
$pdf->SetFont('Courier','BI',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,4,'Invoice No:',0,0,'L'); 
$pdf->Cell(20,4,'#'.$row->invoice_id,0,1,'L'); 
$pdf->Cell(20,4,'Date:',0,0,''); 
$pdf->Cell(20,4,$row->order_date,0,1,'L'); 

$pdf->SetX(7);
$pdf->SetFont('Courier','',8);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(35,5,'PRODUCT',1,0,'C'); 
$pdf->Cell(10,5,'QTY',1,0,'C'); 
$pdf->Cell(9,5,'PRICE',1,0,'C'); 
$pdf->Cell(12,5,'TOTAL',1,1,'C');

$select=$connection->prepare("select * from invoice_details_table where invoice_id=$id");
$select->execute();

while($item=$select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetX(7);
    $pdf->SetFont('Courier','',8);
    // $pdf->SetFillColor(224,247,250);
    $pdf->Cell(35,5,$item->product_name,1,0,'L'); 
    $pdf->Cell(10,5,$item->qty,1,0,'C'); 
    $pdf->Cell(9,5,number_format($item->price),1,0,'L'); 
    $pdf->Cell(12,5,number_format($item->qty*$item->price),1,1,'L'); 

}

$pdf->SetX(7); 
$pdf->SetFont('Courier','',8);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,5,'',0,0,'L');  
$pdf->Cell(25,5,'SUB-TOTAL',1,0,'L'); 
$pdf->Cell(21,5,'GHS'.number_format($row->subtotal),1,1,'L'); 

$pdf->SetX(7); 
$pdf->SetFont('Courier','',8);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,5,'',0,0,'L');  
$pdf->Cell(25,5,'TAX(5%)',1,0,'L'); 
$pdf->Cell(21,5,'GHS'.number_format($row->tax),1,1,'L'); 

$pdf->SetX(7); 
$pdf->SetFont('Courier','',8);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,5,'',0,0,'L');  
$pdf->Cell(25,5,'DISCOUNT',1,0,'L'); 
$pdf->Cell(21,5,'GHS'.number_format($row->discount),1,1,'L'); 

$pdf->SetX(7); 
$pdf->SetFont('Courier','',8);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,5,'',0,0,'L');  
$pdf->Cell(25,5,'GRAND-TOTAL',1,0,'L'); 
$pdf->Cell(21,5,'GHS'.number_format($row->total),1,1,'L'); 

$pdf->SetX(7); 
$pdf->SetFont('Courier','',8);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,5,'',0,0,'L');  
$pdf->Cell(25,5,'AMOUNT PAID',1,0,'L'); 
$pdf->Cell(21,5,'GHS'.number_format($row->paid),1,1,'L'); 
$pdf->SetX(7); 
$pdf->SetFont('Courier','',8);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,5,'',0,0,'L');  
$pdf->Cell(25,5,'AMOUNT DUE',1,0,'L'); 
$pdf->Cell(21,5,'GHS'.number_format($row->due),1,1,'L'); 

$pdf->SetX(7); 
$pdf->SetFont('Courier','',8);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,5,'',0,0,'L');  
$pdf->Cell(25,5,'PAYMENT TYPE',1,0,'L'); 
$pdf->Cell(21,5,strtoupper($row->payment_type),1,1,'L'); 

$pdf->Cell(20,5,'',0,1,''); 
$pdf->SetX(2); 
$pdf->SetFont('Courier','B',8);
$pdf->Cell(32,5,'Importance Notice: ',0,1,'',true); 
 
$pdf->SetX(2);
$pdf->SetFont('Courier','',8);
$pdf->Cell(65,5,'Items Purchased are Not Refundable Without',0,2,'', ); 
$pdf->SetX(2);
$pdf->SetFont('Courier','',8);
$pdf->Cell(65,5,'This Invoice!',0,2,'', ); 


//Output results
$pdf->Output();



?>


















?>