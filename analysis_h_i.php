<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}

if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod' && $_SESSION['type']!='cod' && $_SESSION['type']!='com'){
    //if not hod then send the user to login page
    	header("location:index.php");
	}
}
$_SESSION['currentTab']= "organised_guest";

include("includes/connection.php");


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
	div.scroll {
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
					<h3 class="box-title"><b>Guest Lecture Organised Analysis</b></h3>
					<br>

					</div>
				
				</div><!-- /.box-header -->
				<div style="text-align:right">
				</div>
                <!-- form start -->
              <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                <div class="box-body">
				  <div class="form-group">
                    <div class="form-group col-md-8">
                        <label for="InputName">Select Faculty Name :</label><br>
                        <select id='search' name='fn' class="form-control" style="width: 220px;">
                          <option value=""></option>
                        <?php
                          $sql= " SELECT * FROM facultydetails WHERE facultydetails.Dept='".$_SESSION['Dept']."' AND type='faculty' ORDER BY F_NAME";
                          $result= mysqli_query($conn,$sql);
                          while($row=mysqli_fetch_array($result))
                          {
                            echo"<option>".$row['F_NAME']."</option>";
                          }
                        ?>
                        </select>
                    </div>
                   
					<div class="form-group col-md-8">
                        <label for="InputDateFrom">Date from :</label>
						<input type="date" name="min_date" class="form-control " style="width:220px;">
					</div>
					<div class="form-group col-md-8	">
 						<label for="InputDateTo">Date To :</label>
				 		<input type="date" name="max_date" class="form-control " style="width:220px;"></p>
					</div>
					
                   </div>              
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="count" value = "Count lectures"></input>
                            <a href="view_organised_hod_lec.php" type="button" class="btn btn-primary">Back to View Mode </a><br>
           
                  </div>
				   		
						   <?php 
						  
   							$display = 0;
							$Fac_ID = $_SESSION['Fac_ID'];	
							$a=0;
							
							$dateset=0;
							$flag=1;
							
							$count = 0;
							$set = 0;
							
											

						if(isset($_POST['count']))
						{
							 //$_SESSION['type'] = $_POST['type'];
							//searching datewise
								if (!empty($_POST['min_date']) && !empty($_POST['max_date']) && empty($_POST['fn']))
								{
										$set = 1;
										if((strtotime($_POST['min_date']))>(strtotime($_POST['max_date'])))
										{
												$result="Incorrect date entered. Date from cannot be greater than Date to<br>";
												echo '<div class="error">'.$result.'</div>';
												$a=1;
												$dateset = 1;
										}
										
										if($a == 1)
										{	
											echo '<div class="error">'.$result.'</div>';
										}
													
							
										else if($dateset== 0 && $a == 0)
										{
											$_SESSION['from_date'] = $_POST['min_date'];
											$_SESSION['to_date'] = $_POST['max_date'];
										
										
											$from_date =  $_SESSION['from_date'] ;
											$to_date = $_SESSION['to_date'] ;
											
											
												$sql1 = $sql1 = "SELECT * from guestlec inner join facultydetails on guestlec.fac_id = facultydetails.Fac_ID  and  guestlec.durationf >= '$from_date' and guestlec.durationt <= '$to_date' ";
										
											
											$_SESSION['sql'] = $sql1;
											$display = 1;
											$_SESSION['display'] = $display;
										}
								}
							//searching namewise

								else if (!empty($_POST['fn']) && empty($_POST['min_date']) && empty($_POST['max_date'])) 
								{
									$to_date = date("Y/m/d");
									$prevyear=date("Y")-1;
									$from_date=$prevyear.'/06/01';
										$sname=$_POST['fn'];
										
									
											$sql1 = "SELECT * from guestlec inner join facultydetails on guestlec.fac_id = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and guestlec.durationf>= '$from_date' and guestlec.durationt <= '$to_date'";
										
										
										$_SESSION['sql'] = $sql1;
								 $display = 2;
								$_SESSION['display'] = $display;
								}
								
								
							//searching name and datewise

								else if (!empty($_POST['min_date']) && !empty($_POST['max_date'])&& !empty($_POST['fn']))
								{
										$set = 1;
										if((strtotime($_POST['min_date']))>(strtotime($_POST['max_date'])))
										{
												$result="Incorrect date entered. Date from cannot be greater than Date to<br>";
												echo '<div class="error">'.$result.'</div>';
												$a=1;
												$dateset = 1;
										}
										
										if($a == 1)
										{	
											echo '<div class="error">'.$result.'</div>';
										}
													
							
										else if($dateset== 0 && $a == 0)
										{
											$_SESSION['from_date'] = $_POST['min_date'];
											$_SESSION['to_date'] = $_POST['max_date'];
										
										
											$from_date =  $_SESSION['from_date'] ;
											$to_date = $_SESSION['to_date'] ;
											
											$sname=$_POST['fn'];
											
											
												$sql1 = "SELECT * from guestlec inner join facultydetails on guestlec.fac_id = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and  guestlec.durationf >= '$from_date' and guestlec.durationt <= '$to_date' ";
											
											
											$_SESSION['sql'] = $sql1;

											$display = 3;
											$_SESSION['display'] = $display;
										}
								}
								else if (empty($_POST['min_date']) && empty($_POST['max_date'])&& empty($_POST['fn']))

								{
									$result="<strong>Please provide either Name , Date or both</strong><br>";
									echo '<div class="error">'.$result.'</div>';

								}

						
			
					if($_SESSION['display'] == 1)
					{
						$sql1 = $_SESSION['sql'];
						$res1 = mysqli_query($conn,$sql1);
						while($row = $res1->fetch_assoc()) 
									{
										$count = $count + 1;
									}
						$_SESSION['count'] = $count;			
						if($count > 0 )
						{			
						echo "<strong>Number of Guest Lectures Organised are: " . $count ."</strong><br><br>" ;
						
					
									echo "<div class='scroll'><table  class='table table-stripped table-bordered ' id = 'example1'>
										<thead><tr> 
							            <td><strong>From Date</strong></td>
										<td><strong>To Date</strong></td>
										<td><strong>Number of days</strong></td>
										<td><strong>Faculty Name</font></strong></td>
										<td><strong>Topic</font></strong></td>
										
										
										<td><strong>Resource Person</font></strong></td>										
										<td><strong>Organisation</font></strong></td>										
										<td><strong>Target Audience</font></strong></td>										

										</tr></thead>";
										
									$res1 = mysqli_query($conn,$sql1);

									while($row = $res1->fetch_assoc()) 
									{
										echo "<tr>";
										echo "<td>".$row['durationf']."</td>";
										echo "<td>".$row['durationt']."</td>";
										echo "<td>".$row['noofdays']."</td>";
										echo "<td>".$row['F_NAME']."</td>";
										echo "<td>".$row['topic']."</td>";
										
										echo "<td>".$row['name']."</td>";
										echo "<td>".$row['organisation']."</td>";
										echo "<td>".$row['targetaudience']."</td>";

										echo "</tr>";
									}//end of while
	
									echo "</table></div>";
						}//count if
						else
						{
							$display=0;
							echo "<div class='error'>No records to display</div>";
						}
					}//display if
					if($_SESSION['display'] == 2 || $_SESSION['display'] == 3)
					{
						$sql1 = $_SESSION['sql'];
						$res1 = mysqli_query($conn,$sql1);
						while($row = $res1->fetch_assoc()) 
									{
										$count = $count + 1;
									}
						$_SESSION['count'] = $count;			
						if($count > 0 )
						{		
							echo "<strong>Number of Guest Lectures Organised are: " . $count ."</strong><br><br>" ;

							
							
									echo "<div class='scroll'><table  class='table table-stripped table-bordered ' id = 'example1'>
										<thead><tr> 
										<td><strong>From Date</strong></td>
										<td><strong>To Date</strong></td>
										<td><strong>Faculty Name</font></strong></td>
							
										<td><strong>Topic</font></strong></td>
										
										
										<td><strong>Resource Person</font></strong></td>										
										<td><strong>Organisation</font></strong></td>										
										<td><strong>Target Audience</font></strong></td>										

										</tr></thead>";
										
									$res1 = mysqli_query($conn,$sql1);

									while($row = $res1->fetch_assoc()) 
									{
										echo "<tr>";
										echo "<td>".$row['durationf']."</td>";
										echo "<td>".$row['durationt']."</td>";
										echo "<td>".$row['F_NAME']."</td>";

										echo "<td>".$row['topic']."</td>";
										
										echo "<td>".$row['name']."</td>";
										echo "<td>".$row['organisation']."</td>";
										echo "<td>".$row['targetaudience']."</td>";

										echo "</tr>";
										
									}	
									echo "</table></div>";
							
						}//count if
						else
						{
							$display=0;
							echo "<div class='error'>No records to display</div>";
						}
					}//display if
					
					
							
		
				}//button count if
								
				if($display != 0)
				{		?>	
				
							
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
 	         <a href="export_to_excel_guestlec_analysis_all.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a> 

				<?php }		?>
			
			
                </form>
                
                </div>
              </div>
           </div>      
        </section>
	</div>	
	
	   

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
  $("#search").chosen();
</script>
</head>   
<?php include_once('footer.php'); ?>