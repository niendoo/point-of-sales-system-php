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

//A4 width : 219mm
//Default margin : 10mm each side
//Writable horizontal: 219-(10*2)=199mm

//Create PDF object

$pdf = new FPDF('P','mm','A4');
//String Orientation (P or L) - Portrait or Landscape
//String Unit (pt,mm,cm,and in) - measure unit
//Mixed format (A3, A4, A5, Letter and Legal) - format of pages


//Add new page
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(80,10,'N-TECH POS SYSTEM',0,0,''); 

$pdf->SetFont('Arial','B',12);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(112,10,'Invoice',0,1,'C'); 

$pdf->SetFont('Arial','',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(80,5,'Address: Second Ring Road Kakpagyilli',0,0,''); 

$pdf->SetFont('Arial','',10);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(112,5,'Invoice No : '.$row->invoice_id,0,1,'C'); 

$pdf->SetFont('Arial','',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(80,5,'Phone Number: +233206421152',0,0,''); 

$pdf->SetFont('Arial','',10);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(112,5,'Date :'.$row->order_date,0,1,'C'); 

$pdf->SetFont('Arial','',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(80,5,'Email: support@ntechpos.com',0,1,''); 

$pdf->SetFont('Arial','',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(80,5,'Website: ntechpos.com',0,1,''); 

//Line(x1,y1,x2,y2);

//$pdf->Line(5,10,205,10);
$pdf->Line(5,40,205,40); 
$pdf->Line(5,41,205,41); 
$pdf->Line(5,42,205,42); 
$pdf->Ln(10);//Line Break

$pdf->SetFont('Arial','BI',12);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(20,10,'Bill To :',0,0,''); 

$pdf->SetFont('Courier','BI',12);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(50,10,$row->customer_name,0,1,''); 
$pdf->Cell(50,10,'',0,1,''); 

$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(105,10,'PRODUCT',1,0,'C',true); 
$pdf->Cell(20,10,'QTY',1,0,'C',true); 
$pdf->Cell(30,10,'PRICE',1,0,'C',true); 
$pdf->Cell(35,10,'TOTAL',1,1,'C',true);



$select=$connection->prepare("select * from invoice_details_table where invoice_id=$id");
$select->execute();
 
while($item=$select->fetch(PDO::FETCH_OBJ)){

    $pdf->SetFont('Courier','',10);
    // $pdf->SetFillColor(224,247,250);
    $pdf->Cell(105,10,$item->product_name,1,0,'L'); 
    $pdf->Cell(20,10,$item->qty,1,0,'C'); 
    $pdf->Cell(30,10,$item->price,1,0,'L'); 
    $pdf->Cell(35,10,$item->qty*$item->price,1,1,'L'); 

}


$pdf->SetFont('Courier','',12);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(105,10,'',0,0,'L'); 
$pdf->Cell(20,10,'',0,0,'L'); 
$pdf->Cell(30,10,'Subtotal',1,0,'L',true); 
$pdf->Cell(35,10,'GHS'.$row->subtotal,1,1,'L'); 

$pdf->SetFont('Courier','',12);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(105,10,'',0,0,'L'); 
$pdf->Cell(20,10,'',0,0,'L'); 
$pdf->Cell(30,10,'Tax',1,0,'L',true); 
$pdf->Cell(35,10,'GHS'.$row->tax,1,1,'L'); 

$pdf->SetFont('Courier','',12);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(105,10,'',0,0,'L'); 
$pdf->Cell(20,10,'',0,0,'L'); 
$pdf->Cell(30,10,'Discount',1,0,'L',true); 
$pdf->Cell(35,10,'GHS'.$row->discount,1,1,'L'); 

$pdf->SetFont('Courier','',12);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(105,10,'',0,0,'L'); 
$pdf->Cell(20,10,'',0,0,'L'); 
$pdf->Cell(30,10,'Grand Total',1,0,'L',true); 
$pdf->Cell(35,10,'GHS'.$row->total,1,1,'L'); 

$pdf->SetFont('Courier','',12);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(105,10,'',0,0,'L'); 
$pdf->Cell(20,10,'',0,0,'L'); 
$pdf->Cell(30,10,'Amount Paid',1,0,'L',true); 
$pdf->Cell(35,10,'GHS'.$row->paid,1,1,'L'); 

$pdf->SetFont('Courier','',12);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(105,10,'',0,0,'L'); 
$pdf->Cell(20,10,'',0,0,'L'); 
$pdf->Cell(30,10,'Amount Due',1,0,'L',true); 
$pdf->Cell(35,10,'GHS'.$row->due,1,1,'L'); 

$pdf->SetFont('Courier','',11);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(105,10,'',0,0,'L'); 
$pdf->Cell(20,10,'',0,0,'L'); 
$pdf->Cell(30,10,'Payment Type',1,0,'L',true); 
$pdf->Cell(35,10,strtoupper($row->payment_type),1,1,'L'); 

$pdf->Cell(50,10,'',0,1,'');  

$pdf->SetFont('Arial','B',11);
$pdf->Cell(38,10,'Importance Notice : ',0,0,'',true); 

$pdf->SetFont('Arial','',10);
$pdf->Cell(150,10,'Items Purchased are Non Refundable Without This Invoice!',0,0,'', ); 
//Output results

$pdf->Output();
?>