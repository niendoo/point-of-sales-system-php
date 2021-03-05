<?php 

//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales"  )
{
   header('location:index.php');
}
error_reporting(0);
include_once'templates/header.php';    ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Tabular Report
            <!-- <small><?php echo 'Welcome'.' '.$_SESSION['username'];?></small> -->
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Sales Report</a></li>
            <li class="active">Tabular Report</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">From: <?php echo $_POST['date_1'];?> >>> <span>To: <?php echo $_POST['date_2'];?></span></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="" method="post">
            <div class="box-body">
             
                    <div class="row">
                        <div class="col-md-5">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker1" name="date_1"
                                    data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker2" name="date_2"
                                    data-date-format="yyyy-mm-dd">
                            </div>


                        </div>

                        <div class="col-md-2">
                            <div align="left">

                                <input type="submit" name="btn_date_filter" value="Filter By Date"
                                    class="btn btn-success " title="Filter By Date">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <br>


					<?php
      $select = $connection -> prepare("select sum(subtotal) as subtotal, sum(total) as total, count(invoice_id) as invoice from invoice_table where order_date between :from_date AND :to_date");
      $select->bindParam(':from_date',$_POST['date_1']);
      $select->bindParam(':to_date',$_POST['date_2']);

	  $select -> execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$sub_total_sales=$row->subtotal;
$net_total_sales=$row->total;
$invoice_total=$row->invoice;




?>


                    <!-- Info boxes -->
                    <div class="row">
                        <!-- <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">CPU Traffic</span>
                                    <span class="info-box-number">90<small>%</small></span>
                                </div>
                            
                            </div>
                         
                        </div> -->
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-files-o"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total Invoice</span>
                                    <span class="info-box-number"><h2><?php echo number_format($invoice_total);?></h2></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                        <div class="clearfix visible-sm-block"></div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green">GH&#8373;<i class=""></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Subtotal Sales</span>
                                    <span class="info-box-number"><h2><?php echo number_format($sub_total_sales,2) ;?></h2></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow">GH&#8373;<i class=""></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Net Total Sales</span>
                                    <span class="info-box-number"><h2><?php echo number_format($net_total_sales,2) ;?></h2></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <br>
                    <table id="salesreportTable" class="table table-striped">

                    <thead>
						<tr>
							<th>Invoice ID</th>
							<th>Customer Name</th>
							<th>Sub Total</th>
							<th>Tax(5%)</th>
							<th>Discount</th>
							<th>Total</th>
							<th>Amount Paid</th>
							<th>Due</th>
                            <th>Order Date</th>
                            <th>Payment Type</th>

						</tr>
					</thead>
					<tbody>
						<?php
      $select = $connection -> prepare("select * from invoice_table where order_date between :from_date AND :to_date");
      $select->bindParam(':from_date',$_POST['date_1']);
      $select->bindParam(':to_date',$_POST['date_2']);

	  $select -> execute();
	  while ($row=$select->fetch(PDO::FETCH_OBJ)){

		echo '  <tr>
		<td>'.$row-> invoice_id.'</td>
		<td>'.$row-> customer_name.'</td>
		<td>'."GH&#8373; ".number_format($row-> subtotal,2).'</td>
		<td>'."GH&#8373; ".number_format($row-> tax,2).'</td>
		<td>'."GH&#8373; ".number_format($row-> discount,2).'</td>
		<td>'."GH&#8373; ".number_format($row-> total,2).'</td>
		<td>'."GH&#8373; ".number_format($row-> paid,2).'</td>
		<td>'."GH&#8373; ".number_format($row-> due).'</td>
        <td>'.$row-> order_date.'</td>
		

	
		';
if($row->payment_type=="cash")
{
echo'<td> <span  class="label label-primary">'.$row->payment_type.'</span></td>';
}
elseif($row->payment_type=="card")
{
    echo'<td> <span class="label label-info">'.$row->payment_type.'</span></td>';
}
elseif($row->payment_type=="check")
{
    echo'<td> <span class="label label-danger">'.$row->payment_type.'</span></td>';
}
else{
    echo'<td> <span class="label label-success">'.$row->payment_type.'</span></td>';
}

	  }
		?>
		</tr>			</tbody>





                    </table>
            </div>
            </form>
        </div>

    </section>
    <!-- /.content -->
</div>
<script>
//Date picker
$('#datepicker1').datepicker({
    autoclose: true
});
//Date picker
$('#datepicker2').datepicker({
    autoclose: true
});

$(document).ready(function() {
		$('#salesreportTable').DataTable({
			"order": [
				[0, "desc"]
			]
		});
	});
</script>
<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>