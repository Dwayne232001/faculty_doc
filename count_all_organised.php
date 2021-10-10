<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp";

include_once("includes/connection.php");

$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}


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
                  <h3 class="box-title"><strong>Analysis for Organised Activities</strong></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action = "count_all_organised.php" method="post">
                  <div class="box-body">
                  	<div class="form-group col-md-6">
                    <div class="form-group col-md-6">
                        <label for="InputName">Enter your name :</label>
						<input type="text" placeholder="FirstName  MiddleName  LastName" name="fn" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Select Activity Type :</label>
						<select name="activities" class="form-control">
						<option value="" disabled selected>Select your option</option>
						<option name="STTP" value="STTP">STTP</option>
						<option name="Workshop" value="Workshop">Workshop</option>
						<option name="FDP" value="FDP">FDP</option>
						</select>
                    </div>
					 <div class="form-group col-md-6">
                        <label for="InputDateFrom">Date from :</label>
						<input type="date" name="min_date" class="form-control">
					</div>
					<div class="form-group col-md-6">
						<label for="InputDateTo">Date To :</label>
						<input type="date" name="max_date" class="form-control">
					</div>
 						
                    
                    </div>
                   
                    
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="count_total" value = "Count Activties"></input>
                    <a href="2_dashboard_organised_hod.php" type="button" class="btn btn-primary">Back to View Mode </a>

                  </div>
				   
				 <?php
				 if (isset($_POST['count_total']))
				{
					$from_date = $_POST['min_date'];
					$Fac_ID = $_SESSION['Fac_ID'];
					$to_date = $_POST['max_date'];
					$act_name = $_POST['activities'];
					$a=0;
					$dateset=0;
					$count = 0;
					$sname = $_POST['fn'];
					if (($sname==null && $from_date==null))
					{
						if(strtotime($_POST['min_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date from cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;

										}
										if(strtotime($_POST['max_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date to cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;
											
										}
										if (($a==0) && ($dateset==0))
										{
											$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and organised.Act_type = '$act_name'";
											$res1 = mysqli_query($conn,$sql1);
										
										
									while($row = $res1->fetch_assoc()) 
									{
										$count = $count + 1;
									}
									echo "<br>";
									echo "Number of ".$act_name." activities are: ".$count."<br>";
									echo "<br>";
									$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and organised.Act_type = '$act_name'";
											$res1 = mysqli_query($conn,$sql1);
									echo "<div class='box-body'>
				<div class='scroll'><table class=table table-stripped table-bordered border=1px solid black>
										<tr> 
							
							<td><strong>Activity Title</font></strong></td>
							<td><strong>Activity Type</font></strong></td>
							<td><strong>Organized by</font></strong></td>
							<td><strong>From Date</strong></td>
							<td><strong>To Date</strong></td>		
							<td><strong>Location</font></strong></td>
							<td><strong>Resource</font></strong></td>
							<td><strong>Coordinated by</font></strong></td>
							</tr>";
							
	

							
				
				
									while($row = $res1->fetch_assoc()) 
									{
										echo "<tr>";
												
										echo "<td>".$row['Act_title']."</td>";
										echo "<td>".$row['Act_type']."</td>";
										echo "<td>".$row['Organized_by']."</td>";
										echo "<td>".$row['Date_from']."</td>";
										echo "<td>".$row['Date_to']."</td>";
										echo "<td>".$row['Location']."</td>";
										echo "<td>".$row['Resource']."</td>";
										echo "<td>".$row['Coordinated_by']."</td>";
										echo "</tr>";
										echo "</table></div></div>";
									}	
									?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
									<?php
				}
					goto start;
				}
				if (($to_date==null && $from_date==null && $act_name==null))
					{
						if(strtotime($_POST['min_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date from cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;

										}
										if(strtotime($_POST['max_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date to cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;
											
										}
										if (($a==0) && ($dateset==0))
										{
											$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%'";
											$res1 = mysqli_query($conn,$sql1);
										
										
									while($row = $res1->fetch_assoc()) 
									{
										$count = $count + 1;
									}
									echo "<br>";
									echo " Number of ".$act_name." activities are: ".$count."<br>";
									echo "<br>";
									$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%'";
											$res1 = mysqli_query($conn,$sql1);
									echo "<div class='box-body'>
				<div class='scroll'><table class=table table-stripped table-bordered border=1px solid black>
										<tr> 
							<td><strong>Faculty Name</font></strong></td>
							<td><strong>Activity Title</font></strong></td>
							<td><strong>Activity Type</font></strong></td>
							<td><strong>Organized by</font></strong></td>	
							<td><strong>From Date</strong></td>
							<td><strong>To Date</strong></td>
							<td><strong>Location</font></strong></td>
							<td><strong>Resource</font></strong></td>
							<td><strong>Coordinated by</font></strong></td>
							
							</tr>";
							
	

							
				
				
									while($row = $res1->fetch_assoc()) 
									{
										echo "<tr>";
										echo "<td>".$sname."</td>";		
										echo "<td>".$row['Act_title']."</td>";
										echo "<td>".$row['Act_type']."</td>";
										echo "<td>".$row['Organized_by']."</td>";
										echo "<td>".$row['Date_from']."</td>";
										echo "<td>".$row['Date_to']."</td>";
										echo "<td>".$row['Location']."</td>";
										echo "<td>".$row['Resource']."</td>";
										echo "<td>".$row['Coordinated_by']."</td>";
										echo "</tr>";
										
									}	
									echo "</table></div></div>";
									?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
									<?php
				}
					goto start;
				}
				if (($to_date==null && $from_date==null))
					{
						if(strtotime($_POST['min_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date from cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;

										}
										if(strtotime($_POST['max_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date to cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;
											
										}
										if (($a==0) && ($dateset==0))
										{
											$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and organised.Act_type = '$act_name'";
											$res1 = mysqli_query($conn,$sql1);
										
										
									while($row = $res1->fetch_assoc()) 
									{
										$count = $count + 1;
									}
									echo "<br>";
									echo "Number of ".$act_name." activities are: ".$count."<br>";
									echo "<br>";
									$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and organised.Act_type = '$act_name'";
											$res1 = mysqli_query($conn,$sql1);
									echo "<div class='box-body'>
				<div class='scroll'><table class=table table-stripped table-bordered border=1px solid black>
										<tr> 
							<td><strong>Faculty Name</font></strong></td>
							<td><strong>Activity Title</font></strong></td>
							<td><strong>Activity Type</font></strong></td>
							<td><strong>Organized by</font></strong></td>	
							<td><strong>Location</font></strong></td>
							<td><strong>Resource</font></strong></td>
							<td><strong>Coordinated by</font></strong></td>
							</tr>";
							
	

							
				
				
									while($row = $res1->fetch_assoc()) 
									{
										echo "<tr>";
										echo "<td>".$sname."</td>";		
										echo "<td>".$row['Act_title']."</td>";
										echo "<td>".$row['Act_type']."</td>";
										echo "<td>".$row['Organized_by']."</td>";
										echo "<td>".$row['Location']."</td>";
										echo "<td>".$row['Resource']."</td>";
										echo "<td>".$row['Coordinated_by']."</td>";
										echo "</tr>";
										echo "</table></div></div>";
									}	
									?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>									<?php
				}
					goto start;
				}	
					
					if ($sname==null)
					{
						if(strtotime($_POST['min_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date from cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;

										}
										if(strtotime($_POST['max_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date to cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;
											
										}
										if (($a==0) && ($dateset==0))
										{
											$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and organised.Date_from >= '$from_date' and organised.Date_from <= '$to_date' and organised.Act_type = '$act_name'";
											$res1 = mysqli_query($conn,$sql1);
										
										
									while($row = $res1->fetch_assoc()) 
									{
										$count = $count + 1;
									}
									echo "<br>";
									echo "Number of ".$act_name." activities are: ".$count."<br>";
									echo "<br>";
									$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and organised.Date_from >= '$from_date' and organised.Date_from <= '$to_date' and organised.Act_type = '$act_name'";
											$res1 = mysqli_query($conn,$sql1);
									echo "<div class='box-body'>
				<div class='scroll'><table class=table table-stripped table-bordered border=1px solid black>
										<tr> 
							
							<td><strong>Activity Title</font></strong></td>
							<td><strong>Activity Type</font></strong></td>
							<td><strong>Organized by</font></strong></td>
							<td><strong>From Date</strong></td>
							<td><strong>To Date</strong></td>
							<td><strong>Location</font></strong></td>
							<td><strong>Resource</font></strong></td>
							<td><strong>Coordinated by</font></strong></td>
							</tr>";
							
	

							
				
				
									while($row = $res1->fetch_assoc()) 
									{
										echo "<tr>";
												
										echo "<td>".$row['Act_title']."</td>";
										echo "<td>".$row['Act_type']."</td>";
										echo "<td>".$row['Organized_by']."</td>";
										echo "<td>".$row['Date_from']."</td>";
										echo "<td>".$row['Date_to']."</td>";
										echo "<td>".$row['Location']."</td>";
										echo "<td>".$row['Resource']."</td>";
										echo "<td>".$row['Coordinated_by']."</td>";
										echo "</tr>";
										echo "</table></div></div>";
									}	
									?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>									<?php
				}
				}
				
				
				
					
					else
					{
					if(strtotime($_POST['min_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date from cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;

										}
										if(strtotime($_POST['max_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date to cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;
											
										}
										if (($a==0) && ($dateset==0))
										{
											$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and organised.Date_from >= '$from_date' and organised.Date_from <= '$to_date' and organised.Act_type = '$act_name'";
											$res1 = mysqli_query($conn,$sql1);
										
										
									while($row = $res1->fetch_assoc()) 
									{
										$count = $count + 1;
									}
									echo "<br>";
									echo "Number of ".$act_name." activities are: ".$count."<br>";
									echo "<br>";
									$sql1 = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and organised.Date_from >= '$from_date' and organised.Date_from <= '$to_date' and organised.Act_type = '$act_name'";
											$res1 = mysqli_query($conn,$sql1);
									echo "<div class='box-body'>
				<div class='scroll'><table class=table table-stripped table-bordered >
										<tr> 
							<td><strong>Faculty Name</strong></td>
							<td><strong>Activity Title</font></strong></td>
							<td><strong>Activity Type</font></strong></td>
							<td><strong>Organized by</font></strong></td>
							<td><strong>From Date</strong></td>
							<td><strong>To Date</strong></td>
							<td><strong>Number of days</strong></td>
							
							<td><strong>Location</font></strong></td>
							<td><strong>Resource</font></strong></td>
							<td><strong>Coordinated by</font></strong></td>
							</tr>";
							
	

							
				
				
									while($row = $res1->fetch_assoc()) 
									{
										echo "<tr>";
										echo "<td>".$sname."</td>";		
										echo "<td>".$row['Act_title']."</td>";
										echo "<td>".$row['Act_type']."</td>";
										echo "<td>".$row['Organized_by']."</td>";
										echo "<td>".$row['Date_from']."</td>";
										echo "<td>".$row['Date_to']."</td>";
										echo "<td>".$row['noofdays']."</td>";
										echo "<td>".$row['Location']."</td>";
										echo "<td>".$row['Resource']."</td>";
										echo "<td>".$row['Coordinated_by']."</td>";
										echo "</tr>";
										echo "</table></div></div>";
									}	
									?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>									<?php
				}
					}
				}
				start:
				 ?>
			
               
				<br>
                </form>
                
                </div>
              </div>
           </div>      
        </section>
	</div>	
	
	   
    
<?php include_once('footer.php'); ?>