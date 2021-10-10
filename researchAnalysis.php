<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
include_once("includes/connection.php");


include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php $_SESSION['currentTab']="research"?>
<?php 

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

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
					<h3 class="box-title"><b>Research Analysis</b></h3>
					<br>	
					</div>               

			   </div><!-- /.box-header -->
				<div style="text-align:right">
                </div>
				<form role="form" action = "researchAnalysis.php" method="post">
                  <div class="box-body">
                    
					 <div class="form-group">
                        <label for="InputDateFrom">Date from :</label>
					<input type="date" name="fromDate">

 						<label for="InputDateTo">Date To :</label>
						<input type="date" name="toDate"></p>
                    </div>
                                       
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="getDetails" value = "Count Research Details"></input>
                    <a href="researchView.php" type="button" class="btn btn-primary">Back to View Mode </a>

                  </div>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		if(isset($_POST['getDetails']))
		{
			$Fac_ID = $_SESSION['Fac_ID'];
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
					$successMessage = "Date to cannot be greater than Date From";
				}
				else
				{
					$_SESSION['generate'] = 'yes';
					$_SESSION['fromDate'] = $_POST['fromDate'];
					$_SESSION['toDate'] = $_POST['toDate'];
					$fromDate =  $_SESSION['fromDate'] ;
					$toDate =  $_SESSION['toDate'] ;
				}
			}else{
				$toDate = date("Y/m/d");
				$prevyear=date("Y")-1;
				$fromDate=$prevyear.'/06/01';
				$_SESSION['fromDate']= $fromDate;
				$_SESSION['toDate']=$toDate;
			}
			$query = " SELECT * FROM researchdetails WHERE fromDate >= '$fromDate' AND fromDate <= '$toDate' AND Fac_ID = $Fac_ID ";
			$result = mysqli_query($conn,$query);
			$num=mysqli_num_rows($result);
			if(mysqli_num_rows($result)>0)
			{
				$count =mysqli_num_rows($result);
				$_SESSION['count'] = mysqli_num_rows($result);
				echo $_SESSION['count'];				
				//we have data to display 
				echo "<div class='scroll'>
						<table  class='table table-stripped table-bordered ' id = 'example1'> 
							<thead>
								<tr>
									<th>Research Title</th>
									<th>Start Date</th>
									<th>End Date</th>
									<th>Number of Days</th>
									<th>Submitted To</th>
									<th>Proposed Amount</th>
									<th>Approved ?</th>
								</tr>
							</thead>";
		
							while($row =mysqli_fetch_assoc($result))
							{
								echo "<tr>";
								echo "<td>".$row['researchTitle']."</td>";
								echo "<td>".$row['fromDate']."</td>";
								echo "<td>".$row['toDate']."</td>";
								echo "<td>".$row['noofdays']."</td>";
								echo "<td>".$row['submittedTo']."</td>";
								echo "<td>".$row['proposedAmount']."</td>";
								echo "<td>".$row['radioApproval']."</td>";
						
								$_SESSION['researchId'] = $row['researchId'];
								echo"</tr>";
							}
					echo "</table></div>";			
					if($count > 0)
					{ ?> 
							<button class="btn btn-primary" id="print" onclick="window.print();">Print </button>
							<a href="researchAnalysis-ExportToExcel.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a>
					<?php	
					}
			}
			else
			{
				//if there are no entries
				echo "<div class='error'>You have no research details</div>";
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