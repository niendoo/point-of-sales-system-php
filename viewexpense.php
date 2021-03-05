<?php

//database connection
include_once 'config/db_connection.php';
session_start();
if ($_SESSION['email'] == "" or $_SESSION['role'] == "Sales") {
	header('location:index.php');
}

include_once 'templates/header.php';    ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Product Details
      <small><?php echo 'Welcome' . ' ' . $_SESSION['username']; ?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active">Expenditure Details</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

    <!--------------------------
		| Your Page Content Here |
		-------------------------->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><a href="expenses.php" class="btn btn-info" role="button">
            Back To Expenses </a></h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->

      <div class="box-body">
        <?php

				$id = $_GET['id'];
				$select = $connection->prepare("select * from expenses where expense_id = $id");
				$select->execute();
				while ($row = $select->fetch(PDO::FETCH_OBJ)) {
					echo '

<div class=col-md-12>
<ul class="list-group">
<center><p class="list-group-item list-group-item-success"><b>Expense Details</b></p></center>

<li class="list-group-item"><b>Expense ID: </b><span class="badge badge-info pull-right">' . $row->expense_id . '</span></li>
<li class="list-group-item"><b>Expense Amount: </b><span class="label label-primary pull-right">' . $amount = $row->expense_amount . ' </span></li>
<li class="list-group-item"><b>Expense Reason: </b><span class="label label-primary pull-right">' . $row->expense_reason . '</span></li>
<li class="list-group-item"><b>Expense Date: </b><span class="label label-warning pull-right">' . $row->expense_date . '</span></li>


</ul>
</div>
';
				}
				?>
      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once 'templates/footer.php';    ?>