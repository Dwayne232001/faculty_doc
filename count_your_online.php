<?php 

ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}

include_once("includes/connection.php");
$fid=$_SESSION['Fac_ID'];

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}


include_once('head.php');
$_SESSION['currentTab']="Online";?>
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
					<h3 class="box-title"><b>Analysis</b></h3>
					<br>	
					</div>                </div><!-- /.box-header -->
				<div style="text-align:right">
				</div>
                <!-- form start -->
                <form role="form" action = "count_your_online.php" method="post">
               <div class="box-body">
                    <div class="form-group col-md-2">
						<label for="type">Select Type:</label>
						<select name='type' id='type' style="width:150px;">
							<option value="Attended">Attended</option>
							<option value="Organised">Organised</option>
						</select>
					</div><br><br><br><br>
					 <div class="form-group col-md-2">
                        <label for="InputDateFrom">Date from : * </label>
						<input type="date" name="min_date" ><br><br>
 						<label for="InputDateTo">Date To : * </label>
						<input type="date" name="max_date" >
                    </div>
                   
                   
                    
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="count_total" value = "Count Courses"></input>
                    <a href="2_dashboard_online_attended.php" type="button" class="btn btn-primary">Back to View Mode </a>

                  </div>
				   <?php 
				   
   							$display = 0;
							$Fac_ID = $_SESSION['Fac_ID'];	
							$a=0;
							$dateset=0;
							$flag=1;
							$count2 = 0;
							$count3 = 0;
							$set = 0;							

						if(isset($_POST['count_total']))
						{
							if($_POST['type']=='Attended'){
								$_SESSION['online_type'] = 'Attended';
						   }else{
								$_SESSION['online_type'] = 'Organised';
						   }
							
								if (!empty($_POST['min_date']) && !empty($_POST['max_date']))
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
								$_SESSION['type']=$_POST['type'];
								$total1=0;$total2=0;
				if($_POST['type']=='Attended'){
					?>
					<table class="table table-stripped table-bordered">
						<tr>
							<?php
								$sql1 = "select count(Course_Name) from online_course_attended where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID ";			
								$result=mysqli_query($conn,$sql1);
								$row =mysqli_fetch_assoc($result);

								echo "<th> Total Count is :".$row['count(Course_Name)']."</th></tr></table>";							
							?>
						</tr>
					</table>
					<?php
					$sql1 = "select Date_From,Date_To,Course_Name,Organised_by,Purpose,type_of_course,status_of_activity,duration,credit_audit from online_course_attended where Date_From >= '$from_date' and Date_From <= '$to_date' and Fac_ID = $Fac_ID ";
					$display = 1;
					$_SESSION['sql']=$sql1;
					$result=mysqli_query($conn,$sql1);
					if(mysqli_num_rows($result)>0){
						$total1+=1;
?>						<div class="scroll">
						<table class="table table-stripped table-bordered ">
							<tr>

								<th>Date From</th>
								<th>Date To</th>
								<th>Number of Days</th>
								<th>Course Name</th>					
								
								<!-- MY CODE -->
								
								<th>Organised By</th>
				                <th>Purpose</th>
				                <th>Type of Course</th>
				                <th>Status</th>
				                <th>Duration</th>
				                <th>Credit/Audit</th>


				                <!-- My Code End  -->

							</tr>
<?php
						while($row =mysqli_fetch_assoc($result)){
							$name = $row['Course_Name'];
							$startdate = $row['Date_From'];
							$enddate = $row['Date_To'];
							
							echo "<tr>";

							echo "<td>".$startdate."</td>";
							echo "<td>".$enddate."</td>";
							echo "<td>".$row['noofdays']."</td>";
							echo "<td>".$name."</td>";

							/* MY CODE */

							echo "<td>".$row['Organised_by']."</td>";
			                echo "<td>".$row['Purpose']."</td>";
			                echo "<td>".$row['type_of_course']."</td>";
			                echo "<td>".$row['status_of_activity']."</td>";
			                echo "<td>".$row['duration']."</td>";
			                echo "<td>".$row['credit_audit']."</td>";                

							/* MY CODE */

							echo "</tr>";
						}
						echo '</table></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>&nbsp;'; 
						echo "<a href='export_to_excel_analysis_online.php' type='button' class='btn btn-primary' target='_blank'>Export To Excel</a>"; 
					}
					else{
						echo "No records to display<br>";
					}
				}
				if($_POST['type']=='Organised'){
					?><div class="scroll">
					<table class="table table-stripped table-bordered" style="margin-bottom: 0px ">
						<tr>
							<?php
								$sql1 = "select count(Course_Name) from online_course_organised where Date_From >= '$from_date' and Date_From <= '$to_date' and Fac_ID = $Fac_ID ";			
								$result=mysqli_query($conn,$sql1);
								
								$row =mysqli_fetch_assoc($result);

								echo "<th> Total Count is :".$row['count(Course_Name)']."</th></tr></table>";							
							?>
						</tr>
					</table>
					<?php
					$sql1 = "select `Course_Name`, `Date_From`, `Date_To`, `Organised_By`, `Purpose`, `Target_Audience`, `faculty_role`, `full_part_time`, `no_of_part`, `duration`, `status`, `sponsored`, `name_of_sponsor`, `is_approved` from online_course_organised where Date_From >= '$from_date' and Date_From <= '$to_date' and Fac_ID = $Fac_ID ";
					$display = 1;
					$result=mysqli_query($conn,$sql1);
					$_SESSION['sql']=$sql1;
					if(mysqli_num_rows($result)>0){
						//$total2 = mysqli_num_rows($result)
						$total2+=1;
?>
						<table class="table table-stripped table-bordered ">
							<tr>

								<th>Date From</th>
								<th>Date To</th>
								<th>Course Name</th>

								<!-- MY CODE  -->

								<th>Organised By</th>
				                <th>Purpose</th>
								<th>Target Audience</th>
				                <th>Faculty Role</th>
				                <th>Full/Part time</th>
				                <th>Participants</th>
				                <th>Duration</th>
				                <th>Status</th>
				                <th>Sponsored</th>
				                <th>Name of sponsored</th>
				                <th>Approved</th>

				                <!-- My Code End  -->

							</tr>
<?php
						while($row =mysqli_fetch_assoc($result)){
							$name = $row['Course_Name'];
							$startdate = $row['Date_From'];
							$enddate = $row['Date_To'];
							echo "<tr>";
							
							echo "<td>".$startdate."</td>";
							echo "<td>".$enddate."</td>";
							echo "<td>".$name."</td>";

							/* MY CODE */

							echo "<td>".$row['Organised_By']."</td>";
			                echo "<td>".$row['Purpose']."</td>";
			                echo "<td>".$row['Target_Audience']."</td>";
			                echo "<td>".$row['faculty_role']."</td>";
			                echo "<td>".$row['full_part_time']."</td>";
			                echo "<td>".$row['no_of_part']."</td>";
			                echo "<td>".$row['duration']."</td>";
			                echo "<td>".$row['status']."</td>";
			                echo "<td>".$row['sponsored']."</td>";
			                echo "<td>".$row['name_of_sponsor']."</td>";
			                echo "<td>".$row['is_approved']."</td>";

			                /* My Code End */

							echo "</tr>";
						}
						echo '</table></scroll>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>&nbsp;'; 
					
						
				}
					else{
						$result="No Records Found..<br>";
				echo '<div class="error">'.$result.'</div>';
					}
				}
			}
		}
								else
								{
									$to_date = date("Y/m/d");
									$prevyear=date("Y")-1;
									$from_date=$prevyear.'/06/01';
									$_SESSION['from_date'] = $from_date;
									$_SESSION['to_date'] = $to_date;
									$total1=0;$total2=0;
									if($_POST['type']=='Attended'){
										?>
										<table class="table table-stripped table-bordered">
											<tr>
												<?php
													$sql1 = "select count(Course_Name) from online_course_attended where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID ";			
													$result=mysqli_query($conn,$sql1);
													$row =mysqli_fetch_assoc($result);
					
													echo "<th> Total Count is :".$row['count(Course_Name)']."</th></tr></table>";							
												?>
											</tr>
										</table>
										<?php
										$sql1 = "select Date_From,Date_To,Course_Name,Organised_by,Purpose,type_of_course,status_of_activity,duration,credit_audit from online_course_attended where Date_From >= '$from_date' and Date_From <= '$to_date' and Fac_ID = $Fac_ID ";
										$display = 1;
										$_SESSION['sql']=$sql1;
										$result=mysqli_query($conn,$sql1);
										if(mysqli_num_rows($result)>0){
											$total1+=1;
					?>						<div class="scroll">
											<table class="table table-stripped table-bordered ">
												<tr>
					
													<th>Date From</th>
													<th>Date To</th>
													<th>Course Name</th>					
													
													<!-- MY CODE -->
													
													<th>Organised By</th>
													<th>Purpose</th>
													<th>Type of Course</th>
													<th>Status</th>
													<th>Duration</th>
													<th>Credit/Audit</th>
					
													<!-- My Code End  -->
					
												</tr>
					<?php
											while($row =mysqli_fetch_assoc($result)){
												$name = $row['Course_Name'];
												$startdate = $row['Date_From'];
												$enddate = $row['Date_To'];
												echo "<tr>";
					
												echo "<td>".$startdate."</td>";
												echo "<td>".$enddate."</td>";
												echo "<td>".$name."</td>";
					
												/* MY CODE */
					
												echo "<td>".$row['Organised_by']."</td>";
												echo "<td>".$row['Purpose']."</td>";
												echo "<td>".$row['type_of_course']."</td>";
												echo "<td>".$row['status_of_activity']."</td>";
												echo "<td>".$row['duration']."</td>";
												echo "<td>".$row['credit_audit']."</td>";                
					
												/* MY CODE */
					
												echo "</tr>";
											}
											echo '</table></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>&nbsp;'; 
											echo "<a href='export_to_excel_analysis_online.php' type='button' class='btn btn-primary' target='_blank'>Export To Excel</a>"; 
										}
										else{
											echo "No records to display<br>";
										}
									}
									if($_POST['type']=='Organised'){
										?><div class="scroll">
										<table class="table table-stripped table-bordered" style="margin-bottom: 0px ">
											<tr>
												<?php
													$sql1 = "select count(Course_Name) from online_course_organised where Date_From >= '$from_date' and Date_From <= '$to_date' and Fac_ID = $Fac_ID ";			
													$result=mysqli_query($conn,$sql1);
													
													$row =mysqli_fetch_assoc($result);
					
													echo "<th> Total Count is :".$row['count(Course_Name)']."</th></tr></table>";							
												?>
											</tr>
										</table>
										<?php
										$sql1 = "select `Course_Name`, `Date_From`, `Date_To`, `Organised_By`, `Purpose`, `Target_Audience`, `faculty_role`, `full_part_time`, `no_of_part`, `duration`, `status`, `sponsored`, `name_of_sponsor`, `is_approved` from online_course_organised where Date_From >= '$from_date' and Date_From <= '$to_date' and Fac_ID = $Fac_ID ";
										$display = 1;
										$result=mysqli_query($conn,$sql1);
										$_SESSION['sql']=$sql1;
										if(mysqli_num_rows($result)>0){
											//$total2 = mysqli_num_rows($result)
											$total2+=1;
					?>
											<table class="table table-stripped table-bordered ">
												<tr>
													<th>Course Name</th>
					
													<!-- MY CODE  -->
													<th>Date From</th>
													<th>Date To</th>
													<th>Organised By</th>
													<th>Purpose</th>
													<th>Target Audience</th>
													<th>Faculty Role</th>
													<th>Full/Part time</th>
													<th>Participants</th>
													<th>Duration</th>
													<th>Status</th>
													<th>Sponsored</th>
					
													<!-- My Code End  -->
					
												</tr>
					<?php
											while($row =mysqli_fetch_assoc($result)){
												echo "<tr>";
												echo "<td>".$row['Course_Name']."</td>";
					
												/* MY CODE */
												echo "<td>".$row['Date_From']."</td>";
												echo "<td>".$row['Date_To']."</td>";
												echo "<td>".$row['Organised_By']."</td>";
												echo "<td>".$row['Purpose']."</td>";
												echo "<td>".$row['Target_Audience']."</td>";
												echo "<td>".$row['faculty_role']."</td>";
												echo "<td>".$row['full_part_time']."</td>";
												echo "<td>".$row['no_of_part']."</td>";
												echo "<td>".$row['duration']."</td>";
												echo "<td>".$row['status']."</td>";
												echo "<td>".$row['sponsored']."</td>";					
												/* My Code End */
												echo "</tr>";
											}
											echo '</table></scroll>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>&nbsp;'; 
											echo "<a href='export_to_excel_analysis_online.php' type='button' class='btn btn-primary' target='_blank'>Export To Excel</a>"; 											
									}
										else{
											$result="No Records Found..<br>";
				echo '<div class="error">'.$result.'</div>';
										}
									}
								}
								$_SESSION['set']=$set;
								$_SESSION['dateset']=$dateset;
						}
						?>
                </form>
                </div>
              </div>
           </div>      
        </section>
	</div>	
	
	   
    
<?php include_once('footer.php'); ?>