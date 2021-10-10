<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser']) && ($_SESSION['type'] != 'faculty')){
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
.colour
{
	color:#ff0000;
}
</style>

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
					<div class="icon">
					<i style="font-size:18px" class="fa fa-signal"></i>
					<h3 class="box-title"><b>STTP/Workshop/FDP Activities Attended/Organised Analysis</b></h3>
					<br>
					</div>              
			  </div><!-- /.box-header -->
				<div style="text-align:right">
				<!--	<a href="menu.php?menu=3 "> <u>Back to STTP/Workshop/FDP Attended/Organised Menu</u></a> -->
				</div>  
				<!-- form start -->
                <form role="form" action = "count_your_attend.php" method="post">
                  <div class="box-body">
				  <div class="form-group col-md-6">
					 <div class="form-group col-md-6">
                        <label for="InputDateFrom">Date from :</label>
						<input type="date" name="min_date" class="form-control">
					</div>

					<div class="form-group col-md-6">
 						<label for="InputDateTo">Date To :</label>
						<input type="date" name="max_date" class="form-control">
                    </div>
					
					 <div class="form-group col-md-6">
                        <label for="activities">Select Activity:</label> <span class="colour"><b> *</b></span>
						<select required name="activities" class="form-control">
						<option value="" disabled selected>Select your option</option>
						<option name= "ALL" value="ALL">ALL</option>
						<option name="STTP" value="STTP">STTP</option>
						<option name="Workshop" value="Workshop">Workshop</option>
						<option name="FDP" value="FDP">FDP</option>
						<option name="QIP" value="QIP">QIP</option>
						<option name="SEMINAR" value="SEMINAR">SEMINAR</option>		
						<option name="WEBINAR" value="WEBINAR">WEBINAR</option>		
						</select>
                    </div>
					
					<div class="form-group col-md-6">
						<label for="type">Select Type:</label><span class="colour"><b> *</b></span>
						<br><select required  name='type' id='type' class='form-control'>
							<option value="" disabled selected>Select your type</option>

							<option value="Attended">Attended</option>
							<option value="Organised">Organised</option>
						</select>
					</div>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="count_attend" value = "Count Activities"></input>
                    <a href="2_dashboard_attend.php" type="button" class="btn btn-primary">Back to View Mode </a>
                  </div>
                  </div>
				   <?php 
				   $count=0;
				   if (isset($_POST['count_attend']))
					{
						if (!empty($_POST['min_date']) && !empty($_POST['max_date'])){
							$from_date = $_POST['min_date'];
							$to_date = $_POST['max_date'];
							$Fac_ID = $_SESSION['Fac_ID'];
							$_SESSION['from_date'] = $from_date;
							$_SESSION['to_date'] = $to_date;
							
					// $my_var = isset($_POST['myPostData']) ? $_POST['myPostData'] : "";
						}
						else{
							$Fac_ID = $_SESSION['Fac_ID'];
							$to_date = date("Y/m/d");
							$prevyear=date("Y")-1;
							$from_date=$prevyear.'/06/01';
							$_SESSION['from_date'] = $from_date;
							$_SESSION['to_date'] = $to_date;
						}
						if($_POST['type'] == 'Attended')
						{		
							$activities = $_POST['activities'];
							$_SESSION['activities'] = $activities;
							$_SESSION['type']='faculty';
							if($activities == "ALL"){
								$sql1 = "SELECT * from attended where Fac_ID = $Fac_ID and Date_from >= '$from_date' and Date_from <= '$to_date'";
							}else{
								$sql1 = "SELECT * from attended where Fac_ID = $Fac_ID and Date_from >= '$from_date' and Date_from <= '$to_date' and Act_type = '$activities'";
							}
							$res1 = mysqli_query($conn,$sql1);
							$_SESSION['sql'] = $sql1;
										
						while($row = $res1->fetch_assoc()) 
						{
							$count = $count + 1;
						}
						$_SESSION['count1'] = $count;
						echo "<br>";

						$res1 = mysqli_query($conn,$sql1);

						if($count > 0 )
								{									
									echo "<div class='box box-primary'><div class='box-header with-border'>
                  						<h2 class='box-title'><strong>Analysis</strong></h2>
   										<h3 class='box-title'><strong>Total Number of $activities Attended are: $count</strong></h3> <br>
			
										</div><div class='box-body'><div class='scroll'><table  class='table table-stripped table-bordered ' id = 'example1'>
										<thead><tr> 
										<td><strong>Activity Title</font></strong></td>
										<td><strong>Activity Type</font></strong></td>
										<td><strong>Organized by</font></strong></td>
										<td><strong>From Date</strong></td>
										<td><strong>To Date</strong></td>
										<td><strong>Number of days</strong></td>
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
							else{
								echo "<div class='error'>No records to display</div>";
							}
				
				 if($count > 0)
			  { ?> 
			  	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
  	    <a href="export_to_excel_sttp_attend_analysis.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a> 

			   
			<?php 
			  }
					}
					else if($_POST['type'] == 'Organised')
					{
						$Fac_ID = $_SESSION['Fac_ID'];
						$activities = $_POST['activities'];
						$_SESSION['activities'] = $activities;
						$_SESSION['type']='faculty';
									
						if($activities == "ALL"){
							$sql1 = "SELECT * from organised where Fac_ID = $Fac_ID  and Date_from >= '$from_date' and Date_from <= '$to_date'";
						}else{
							$sql1 = "SELECT * from organised where Fac_ID = $Fac_ID  and Date_from >= '$from_date' and Date_from <= '$to_date' and Act_type = '$activities'";
						}	
						$res1 = mysqli_query($conn,$sql1);
						$_SESSION['sql'] = $sql1;
										
						while($row = $res1->fetch_assoc()) 
						{
							$count = $count + 1;
						}
						$_SESSION['count1'] = $count;
						echo "<br>";

						$res1 = mysqli_query($conn,$sql1);

						if($count > 0 )
						{									
							echo "<div class='box box-primary'><div class='box-header with-border'>
                  			<h2 class='box-title'><strong>Analysis</strong></h2> <br>
							<h3 class='box-title'><strong>Total Number of $activities organised are: $count</strong></h3> <br>

                			</div>
							
							<div class='box-body'><div class='scroll'><table  class='table table-stripped table-bordered ' id = 'example1'>
										<thead><tr> 
							
							<td><strong>Activity Title</font></strong></td>
							<td><strong>Activity Type</font></strong></td>
							<td><strong>Organized by</font></strong></td>
							<td><strong>From Date</strong></td>
							<td><strong>To Date</strong></td>
							
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
										echo "<td>".$row['Location']."</td>";
										echo "</tr>";
										
									}	
									echo "</table></div></div>";
								
							}
							else{
								echo "<div class='error'>No records to display</div>";
							}
							 if($count > 0){?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
								<a href="export_to_excel_sttp_organised_analysis.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a> 
							<?php }
						}
					}
	//end of if
			?>
                </form>
                
                </div>
              </div>
           </div>      
        </section>
	</div>	
	
	   
    
<?php include_once('footer.php'); ?>