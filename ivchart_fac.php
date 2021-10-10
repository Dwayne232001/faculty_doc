<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
include_once("includes/connection.php");

$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php $_SESSION['currentTab']="iv"?>
<?php 
if($_SESSION['type'] == 'hod')
{
	  include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
		include_once('sidebar_cod.php');
}
else{
	include_once('sidebar.php');
}
 ?>
 <style>
div.scroll
{
overflow:scroll;
}

.error
{
	color:red;
	border:1px solid red;
	background-color:#ebcbd2;
	border-radius:10px;
	margin:5px;
	padding:5px;
	font-family:Arial, Helvetica, sans-serif;
	width:510px;
}


</style>

 <div class="content-wrapper">
    <section class="content">
          <div class="row">
            <div class="col-xs-12">
						  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
					<div class="icon">
					<i style="font-size:18px" class="fa fa-signal"></i>
					<h3 class="box-title"><b>IV Analysis</b></h3>
					<br>	
					</div>               

			   </div><!-- /.box-header -->
				<div style="text-align:right">
			<!--	<a href="menu.php?menu=11 " style="text-align:right"> <u>Back to Research Menu</u></a> -->
                </div>
				<form role="form" action = "ivchart_fac.php" method="post">
                  <div class="box-body">
                    
					 <div class="form-group">
                        <label for="InputDateFrom">Date from :</label>
					<input type="date" name="fromDate">

 						<label for="InputDateTo">Date To :</label>
						<input type="date" name="toDate"></p>
                    </div>
                    
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="getDetails" value = "Count IV Details"></input>
                    <a href="2_dashboard_iv.php" type="button" class="btn btn-primary">Back to View Mode </a>

                  </div>

<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		if(isset($_POST['getDetails']))
		{
			$Fac_ID = $_SESSION['f_id'];
			$dateIsSet = 0;
			$dateSetError = 0;
			$fromDate = "";
			$toDate = "";
			$successMessage = "";
			$result = "";
			if (!empty($_POST['fromDate']) && !empty($_POST['toDate']))
			{
					$_SESSION['dateIsSet'] = 1;
					$dateIsSet = 1;
					if((strtotime($_POST['fromDate']))>(strtotime($_POST['toDate'])))
					{
						/*echo $_POST['fromDate']; echo "<br>";
						echo $_POST['toDate']; echo "<br>";
						echo "Date is set wrongly!"; echo "<br>";*/
						$dateSetError = 1;
						$successMessage = "Enter proper date";
					}
					else
					{
						$_SESSION['generate'] = 'yes';
						$_SESSION['fromDate'] = $_POST['fromDate'];
						$_SESSION['toDate'] = $_POST['toDate'];
						$fromDate =  $_SESSION['fromDate'] ;
						$toDate = $_SESSION['toDate'] ;
						$_SESSION['dateIsSet'] = 1;
						$dateIsSet = 1;
					}
				}else{
					$toDate = date("Y/m/d");
					$prevyear=date("Y")-1;
					$fromDate=$prevyear.'/06/01';
					$_SESSION['fromDate'] = $fromDate;
					$_SESSION['toDate'] = $toDate;
				}

				$query = " SELECT * FROM iv_organized WHERE t_from >= '$fromDate' AND t_from <= '$toDate' AND f_id = $Fac_ID ";
				$_SESSION['sql']=$query;
				$result = mysqli_query($conn,$query);
				if(mysqli_num_rows($result)>0)
				{
					$count = 	mysqli_num_rows($result);
					$_SESSION['count'] = mysqli_num_rows($result);
							//we have data to display 
						echo "<div class='scroll'>
						<table  class='table table-stripped table-bordered ' id = 'example1'> 
								<thead>
									<tr>
										<th>Industry Name</th>
										<th>City</th>
										<th>Purpose</th>
										<th>Audience</th>
										<th>Start Date</th>
										<th>End Date</th>
									</tr>
								</thead>";
    
							while($row =mysqli_fetch_assoc($result))
							{
								echo "<tr>";
								echo "<td>".$row['ind']."</td>";
								echo "<td>".$row['city']."</td>";
								echo "<td>".$row['purpose']."</td>";
								echo "<td>".$row['t_audience']."</td>";
								echo "<td>".$row['t_from']."</td>";
								echo "<td>".$row['t_to']."</td>";
								$_SESSION['iv_id'] = $row['id'];
								echo"</tr>";
							}
						echo "</table></div>";			
						if($count > 0)
						{ ?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
								<a href="export_to_excel_analysis_iv.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a>
							<?php	}
							
				}
				else
				{
					//if ther are no entries
					echo "<div class='error'>You don't have IV details</div>";
				}
		}
	}

 ?>
 		
 

<br>				  
   
			
</form>
</div>
</div>
</div>
</section>
</div> 
 
<?php include_once('footer.php'); ?>