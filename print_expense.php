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

$select=$connection->prepare("select * from expenses where expense_id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

//A4 width : 219mm
//Default margin : 10mm each side
//Writable horizontal: 219-(10*2)=199mm

//Create PDF object

$pdf = new FPDF('L','mm','A4');
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
$pdf->Cell(112,10,'Expenditure',0,1,'C'); 

$pdf->SetFont('Arial','',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(80,5,'Address: Second Ring Road Kakpagyilli',0,0,''); 

$pdf->SetFont('Arial','',10);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(112,5,'Expense ID : '.$row->expense_id,0,1,'C'); 

$pdf->SetFont('Arial','',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(80,5,'Phone Number: +233206421152',0,0,''); 

$pdf->SetFont('Arial','',10);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(112,5,'Date :'.$row->expense_date,0,1,'C'); 

$pdf->SetFont('Arial','',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(80,5,'Email: support@musedigital.co',0,1,''); 

$pdf->SetFont('Arial','',8);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(80,5,'Website: musedigital.co',0,1,''); 

//Line(x1,y1,x2,y2);

//$pdf->Line(5,10,205,10);
$pdf->Line(10,40,270,40); 
$pdf->Line(10,41,270,41); 
$pdf->Line(10,42,270,42); 
$pdf->Ln(10);//Line Break

$pdf->SetFont('Arial','BI',12);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(40,10,'Expense by :',0,0,''); 

$pdf->SetFont('Courier','BI',12);
//$pdf->SetFillColor(224,247,250);
$pdf->Cell(50,10,$row->created_by,0,1,''); 
$pdf->Cell(50,10,'',0,1,''); 

$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(224,247,250);
$pdf->Cell(60,10,'EXPENSE REASON',1,0,'C',true); 
$pdf->Cell(60,10,'EXPENSE AMOUNT',1,0,'C',true); 
$pdf->Cell(60,10,'EXPENSE DATE',1,0,'C',true); 
$pdf->Cell(80,10,'EXPENSE BY',1,1,'C',true);



$select=$connection->prepare("select * from expenses where expense_id=$id");
$select->execute();
 
while($item=$select->fetch(PDO::FETCH_OBJ)){

    $pdf->SetFont('Courier','',10);
    // $pdf->SetFillColor(224,247,250);
    $pdf->Cell(60,10,$item->expense_reason,1,0,'C'); 
    $pdf->Cell(60,10,$item->expense_amount,1,0,'C'); 
    $pdf->Cell(60,10,$item->expense_date,1,0,'C'); 
    $pdf->Cell(80,10,$item->created_by,1,1,'C'); 

}

//Output results

$pdf->Output();
?>