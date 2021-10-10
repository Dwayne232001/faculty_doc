<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
include_once("includes/connection.php");

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php $_SESSION['currentTab']="faculty"?>
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

$_SESSION['fromDate']=$_SESSION['toDate']=$_SESSION['sql']="";
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
					<h3 class="box-title"><b>Faculty Analysis</b></h3>
					<br>	
					</div>               

			   </div><!-- /.box-header -->
				<div style="text-align:right">
                </div>
				<form role="form" action = "faculty_interaction_analysis.php" method="post">
                  <div class="box-body">
                    
					 <div class="form-group">
                        <label for="InputDateFrom">Date from :</label>
					<input type="date" name="fromDate">

 						<label for="InputDateTo">Date To :</label>
						<input type="date" name="toDate"></p>
                    </div>
                   
                   
                    
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="getDetails" value = "Count Invited Guest Lecture Details"></input>
                    <a href="view_invited_lec.php" type="button" class="btn btn-primary">Back to View Mode </a>

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
					$successMessage = "Enter proper date";
				}
				else
				{
					$_SESSION['generate'] = 'yes';
					$_SESSION['fromDate'] = $_POST['fromDate'];
					$_SESSION['toDate'] = $_POST['toDate'];
					$fromDate =  $_SESSION['fromDate'] ;
					$toDate = $_SESSION['toDate'] ;
				}
			}else{
				$toDate = date("Y/m/d");
				$prevyear=date("Y")-1;
				$fromDate=$prevyear.'/06/01';
				$_SESSION['fromDate']=$fromDate;
				$_SESSION['toDate']=$toDate;
			}
			$query = " SELECT * FROM invitedlec WHERE durationf >= '$fromDate' AND durationf <= '$toDate' AND Fac_ID = $Fac_ID ";
			$_SESSION['sql']=$query;
			$result = mysqli_query($conn,$query);
			$count = mysqli_num_rows($result);
			if(mysqli_num_rows($result)>0)
			{
				
				$_SESSION['count'] = mysqli_num_rows($result);
				//we have data to display 
				echo "<div class='scroll'>
					<table  class='table table-stripped table-bordered ' id = 'example1'> 
						<thead>
							<tr>
								<th>Organized By</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Bumber of days</th>
								<th>Awards</th>
								<th>Invited as Resource person for</th>
								<th>Topic Of Lecture</th>
								<th>Details if Any Other Activity</th>				
							</tr>
						</thead>";

				while($row =mysqli_fetch_assoc($result))
				{
					echo "<tr>";
					echo "<td>".$row['organized']."</td>";
					echo "<td>".$row['durationf']."</td>";
					echo "<td>".$row['durationt']."</td>";
					echo "<td>".$row['noofdays']."</td>";
					echo "<td>".$row['award']."</td>";
					echo "<td>".$row['res_type']."</td>";
					echo "<td>".$row['topic']."</td>";
					echo "<td>".$row['details']."</td>";								
					$_SESSION['invited_id'] = $row['invited_id'];
					echo"</tr>";
				}
				echo "</table></div>";			
			}
			else
			{
				//if ther are no entries
				echo "<div class='error'>You don't have any analysis details</div>";
			}
			if($count > 0){?>
				<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
				<a href="export_to_excel_facultyinteraction_analysis.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a> 
			<?php }
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