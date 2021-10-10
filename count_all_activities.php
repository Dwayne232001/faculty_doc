<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp";

include_once("includes/connection.php");


include_once('head.php'); ?>
<?php include_once('header.php'); ?>
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


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-xs-12">
              <!-- general form elements -->
			  			  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">
                  	<strong style="margin-left: 10px">STTP/Workshop/FDP Activities Attended and Organised Analysis</strong>
                  </h3>
                </div><!-- /.box-header -->
				<!-- <div style="text-align:right">
				</div> -->
                
				<!-- form start -->
                <form role="form" action = "count_all_activities.php" method="post">
                  <div class="box-body">
				  <div class="form-group col-md-6">
					 <div class="form-group col-md-6">
                        <label for="InputDateFrom">Date from :</label>
						<input type="date" name="min_date" class="form-control" required>
					</div>

					<div class="form-group col-md-6">
 						<label for="InputDateTo">Date To :</label>
						<input type="date" name="max_date" class="form-control" required>
                    </div>
					
					 <div class="form-group col-md-6">
                        <label for="type">Select Activity:</label>
						<select required name="activities" class="form-control">
						<option value="" disabled selected>Select your option</option>
						<option name="STTP" value="STTP">STTP</option>
						<option name="Workshop" value="Workshop">Workshop</option>
						<option name="FDP" value="FDP">FDP</option>
						</select>
                    </div>
					
					<div class="form-group col-md-6">
						<label for="type">Select Type:</label>
						<select required name='type' id='type' class='form-control'>
							<option value="" disabled selected>Select your type</option>
							<option value="Attended">Attended</option>
							<option value="Organised">Organised</option>
						</select>
					</div>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="count_total" value = "Count Activities"></input>
                    <a href="2_dashboard_attend.php" type="button" class="btn btn-primary">Back to View Mode </a>

                  </div>



                  </div>
				   <?php 
				   $count=0;
				   if (isset($_POST['count_total']))
					{
						
						$from_date = $_POST['min_date'];
						$Fac_ID = $_SESSION['Fac_ID'];
						$to_date = $_POST['max_date'];
					
						$_SESSION['from_date'] = $from_date;
						$_SESSION['to_date'] = $to_date;
						$type = $_POST['type'];
						$_SESSION['type'] = $type;
						$activities = $_POST['activities'];
						$_SESSION['activities'] = $activities;
						// $my_var = isset($_POST['myPostData']) ? $_POST['myPostData'] : "";

											if($_POST['type'] == 'Attended')
											{
												$sql1 = "SELECT * from attended inner join facultydetails on attended.Fac_ID = facultydetails.Fac_ID and attended.Date_from >= '$from_date' and attended.Date_to <= '$to_date' and attended.Act_type = '$activities'";
												
											}
											else if($_POST['type'] == 'Organised'){
												$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID  and organised.Date_from >= '$from_date' and organised.Date_to <= '$to_date' and organised.Act_type = '$activities'";
											
											}
												
											$res1 = mysqli_query($conn,$sql1);
											$_SESSION['sql'] = $sql1;
										
											while($row = $res1->fetch_assoc()) 
											{
												$count = $count + 1;
											}
											$_SESSION['count1'] = $count;
											echo "<br>";
											echo "<strong>Number of ".$activities." ".$type." are: ".$count."</strong><br>";
											echo "<br>";

								if($_POST['type'] == 'Attended')
											{
												$sql1 = "SELECT * from attended inner join facultydetails on attended.Fac_ID = facultydetails.Fac_ID and attended.Date_from >= '$from_date' and attended.Date_to <= '$to_date' and attended.Act_type = '$activities'";
												$button=1;
											}
											else if($_POST['type'] == 'Organised'){
												$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID  and organised.Date_from >= '$from_date' and organised.Date_to <= '$to_date' and organised.Act_type = '$activities'";
												$button=2;
											}

										$res1 = mysqli_query($conn,$sql1);
								$_SESSION['sql'] = $sql1;

							
							
				

								if($count > 0 )
								{									
									echo "<div class='box box-primary'><div class='box-header with-border'>
                  <h2 class='box-title'><strong>Analysis</strong></h2>
                </div><div class='box-body'><div class='scroll'><table  class='table table-stripped table-bordered ' id = 'example1'>
										<thead><tr> 
							
							<td><strong>Activity Title</font></strong></td>
							<td><strong>Activity Type</font></strong></td>
							<td><strong>Organized by</font></strong></td>
							<td><strong>From Date</strong></td>
							<td><strong>To Date</strong></td>
							<td><strong>Number of days</font></strong></td>
							<td><strong>Location</font></strong></td>

							</tr></thead>";
									
									while($row =mysqli_fetch_assoc($res1)) 
									{
										echo "<tr>";
											
										echo "<td>".$row['Act_title']."</td>";
										echo "<td>".$row['Act_type']."</td>";
										echo "<td>".$row['Organized_by']."</td>";
										echo "<td>".$row['Date_from']."</td>";
										echo "<td>".$row['Date_to']."</td>";
										echo "<td>".$row['noofdays']."</td>";
										echo "<td>".$row['Location']."</td>";
										echo "</tr>";
										
									}	
									echo "</table></div></div>";
								
							}
				
				}
				
			if($count > 0){
			  if($_SESSION['type']=='Attended' )
			  { ?> 
			  	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
  	            <a href="export_to_excel_sttp_attend_analysis.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a> 

			   
			<?php 
			  }
				if($_SESSION['type']=='Organised')
				{?>			  
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
			 <a href="export_to_excel_sttp_organised_analysis.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a> 

	<?php			}}
	//end of if
			?>

			   
                </form>
                
                </div>
              </div>
           </div>      
        </section>
	</div>	
	
	   
    
<?php include_once('footer.php'); ?>