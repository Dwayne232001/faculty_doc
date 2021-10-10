<?php 
ob_start();
session_start();

if($_SESSION['type'] =='faculty' && !isset($_SESSION['loggedInUser'])){
	header("location:index.php");
 }

if(!isset($_SESSION['loggedInUser']) && $_SESSION['type'] !='hod' && $_SESSION['type']!='cod' && $_SESSION['type']!='com'){  
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
<?php 
include_once("includes/functions.php");
//include custom functions files 
include_once("includes/scripting.php");
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
					<i style="font-size:18px" class="fa fa-signal"></i>
					<h3 class="box-title"><b>Online/offline course Attended/Organised Analysis</b></h3>   
					<br>
	
					</div><!-- /.box-header -->
				
                <!-- form start -->
                <form role="form" action = "" method="post">
                  <div class="box-body">
                  <div class="form-group col-md-8">
					<div class="form-group col-md-8">
                        <label for="InputName">Select Faculty Name :</label><br>
                        <select id='search' name='fn' class="form-control" style="width: 220px;">
                          <option value=""></option>
                        <?php
                          include_once("includes/connection.php");
                          $sql= " SELECT * FROM facultydetails WHERE facultydetails.Dept='".$_SESSION['Dept']."' AND type='faculty' ORDER BY F_NAME";
                          $result= mysqli_query($conn,$sql);
                          while($row=mysqli_fetch_array($result))
                          {
                            echo"<option>".$row['F_NAME']."</option>";
                          }
                        ?>
                        </select>
                    </div>
				
                  	<div class="form-group col-md-8" style="display:block ; margin-left:5px " >
						<label for="type">Select Type:</label><br>
						<select required name='type' id='type' class='form-control' style="width: 220px;">
							<option value=''>Select your choice</option>
							<option value="Attended">Attended</option>
							<option value="Organised">Organised</option>
						</select>
						</div>
					
					 <div class="form-group col-md-6" style="margin-left:5px ">
                        <label for="InputDateFrom">Date from :</label><br>
						<input type="date" name="min_date" style="width: 220px;">
					</div>
 					
					 <div class="form-group col-md-6" style="margin-left:5px ">

					<label for="InputDateTo">Date To :</label><br>
					<input type="date" name="max_date" style="width: 220px;">
                    </div>    
					</div>					
                </div><!-- /.box-body -->
                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="count" value = "Count Courses"></input>
                    <a href="2_dashboard_hod_online_attended.php" type="button" class="btn btn-primary">Back to View Mode </a>
                  </div>
				   <?php 
						if(isset($_POST['count']))
						{
							$f = 0;
							$v = 0;
							$flag1=0;
							$both_set = 0;
							$_SESSION['flag_count'] = 0;
							$_SESSION['value'] = 4;
							$_SESSION['online_type'] = $_POST['type'];
							if(empty($_POST['type'])){
								$result="Select type of course<br>";
								echo $result;
								$flag1=1;
							}
							if (empty($_POST['min_date']) && empty($_POST['max_date']))
							{
								$result="Date field cannot be empty<br>";
 								$v = 1;
							}
							if (empty($_POST['fn']))
							{
								$result="Name cannot be empty<br>";
								$v = 2;
							}
							if(empty($_POST['fn']) && empty($_POST['min_date']))
							{
								$result="Both fields cannot be empty<br>";
								$f = 1;
								$both_set = 1;
							}
							if(!empty($_POST['fn']) && !empty($_POST['min_date']))
							{	
								$both_set = 2;
							}
							if((strtotime($_POST['min_date']))>(strtotime($_POST['max_date'])))
							{
								$result="Incorrect date entered. Date from cannot be greater than Date to<br>";
								echo '<div class="error">'.$result.'</div>';
								$flag=1;
							}
							 
							if($f == 1)
							{
								echo '<div class="error">'.$result.'</div>';
							}
							if($f!=1 && $both_set != 2 && $flag1!=1)
							{
								if ($v !=1 )
								{
									$_SESSION['from_date'] = $_POST['min_date'];
									$_SESSION['to_date'] = $_POST['max_date'];
									$_SESSION['flag_count'] = 1;
									execute_query()	;	

								}
								else if($v !=2)
								{
									$_SESSION['sname'] = validateFormData($_POST['fn']);
									$_SESSION['flag_count'] = 2;
									execute_query();	

								}
							}
							else if($both_set == 2)
							{
								$_SESSION['from_date'] = $_POST['min_date'];
								$_SESSION['to_date'] = $_POST['max_date'];
								$_SESSION['sname'] = validateFormData($_POST['fn']);

								$_SESSION['flag_count'] = 3;
								execute_query();
							}
							
	
						}	//end of count
						
				   ?>


<?php	
function execute_query()
{
		include("includes/connection.php");


	$flag=1;
	$display = 0;	
	$type = $_SESSION['online_type'];
	if($_SESSION['online_type']=='Attended'){
	
		if($_SESSION['flag_count'] == 1)
		{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;
			$sql1 = "select count(*) from online_course_attended inner join facultydetails on online_course_attended.Fac_ID = facultydetails.Fac_ID where Date_From >= '$from_date' and Date_From <= '$to_date' ";

			$result=mysqli_query($conn,$sql1);
			$row =mysqli_fetch_assoc($result);
			$pr="<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Online Courses $type analysis</p>";
				 
			$pr.="<table class='table table-stripped table-bordered ' border='1' cellpadding=5px cellspacing = 0px style='margin-bottom: 0px;'>
				<tr>
				<th>Total Count</th>";
					$pr.= "<th>".$row['count(*)']."</th></tr></table>";
			?>
			<table class="table table-stripped table-bordered " style="margin-bottom: 0px">
				<tr>
				<?php
					if(mysqli_num_rows($result)>0){
							?>
					<th>Total Count</th>
						
				
					<?php
						echo "<th>".$row['count(*)']."</th></tr>";
				}
				?>
			</table>
<?php
			$sql1 = "select F_NAME,Date_From,Date_To,Course_Name,Organised_by,Purpose,type_of_course,status_of_activity,duration,credit_audit from online_course_attended inner join facultydetails on online_course_attended.Fac_ID = facultydetails.Fac_ID where Date_From >= '$from_date' and Date_From <= '$to_date' ";
			//$_SESSION['Sql_1']=$sql1;
			$_SESSION['sql']=$sql1;
			$display = 1;
					$result=mysqli_query($conn,$sql1);
					if(mysqli_num_rows($result)>0){
					
?>	
						<div class="scroll">
						<table class="table table-stripped table-bordered ">
							<tr>
								<th>Faculty</th>
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
						$pr.="<table border='1' cellspacing = 0px class='table table-stripped table-bordered'>
							<tr>
								<th>Faculty</th>
								<th>Date From</th>
								<th>Date To</th>
								<td><strong>Number of days</strong></td>
								<th>Course Name</th>

								
								
								<th>Organised By</th>
				                <th>Purpose</th>
				                <th>Type of Course</th>
				                <th>Status</th>
				                <th>Duration</th>
				                <th>Credit/Audit</th>

				                

							</tr>";

						while($row =mysqli_fetch_assoc($result)){
							$fname = $row['F_NAME'];
							$name = $row['Course_Name'];
							$startdate = $row['Date_From'];
							$enddate = $row['Date_To'];
							echo "<tr>";
							echo "<td>".$fname."</td>";
							echo "<td>".$startdate."</td>";
							echo "<td>".$enddate."</td>";
							echo "<td>".$name."</td>";

							

							echo "<td>".$row['Organised_by']."</td>";
			                echo "<td>".$row['Purpose']."</td>";
			                echo "<td>".$row['type_of_course']."</td>";
			                echo "<td>".$row['status_of_activity']."</td>";
			                echo "<td>".$row['duration']."</td>";
			                echo "<td>".$row['credit_audit']."</td>";                

							
							
							echo "</tr>";
							$pr.= "<tr>";
							$pr.= "<td>".$fname."</td>";
							$pr.= "<td>".$startdate."</td>";
							$pr.= "<td>".$enddate."</td>";
							$pr.= "<td>".$name."</td>";
							
							

							$pr.= "<td>".$row['Organised_by']."</td>";
			                $pr.= "<td>".$row['Purpose']."</td>";
			                $pr.= "<td>".$row['type_of_course']."</td>";
			                $pr.= "<td>".$row['status_of_activity']."</td>";
			                $pr.= "<td>".$row['duration']."</td>";
			                $pr.= "<td>".$row['credit_audit']."</td>";                

							

							$pr.= "</tr>";
						}
						echo "</table>";
						$pr.= "</table>";
						$_SESSION['A_1']=$pr;
						?>
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>

						&nbsp;<a href='ExportToExcel_online_hod.php' type='button' class='btn btn-primary' target='_blank'>Export To Excel</a>
						<?php 
					}
					else{
						echo "<div class='error'>No records to display</div>";
					}
		}
		else if ($_SESSION['flag_count'] == 2)
		{
				$sname = $_SESSION['sname'] ;
				$to_date = date("Y/m/d");
				$prevyear=date("Y")-1;
				$from_date=$prevyear.'/06/01';
				$sql1 = "SELECT count(*) from online_course_attended inner join facultydetails on online_course_attended.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' where Date_From >= '$from_date' and Date_From <= '$to_date' ";
				$result=mysqli_query($conn,$sql1);
				$row =mysqli_fetch_assoc($result);
				$pr="<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Online Courses $type analysis</p>";
				$pr.="<table class='table table-stripped table-bordered ' border='1' cellspacing =0 style='margin-bottom: 0px'>
				<tr>
				<th>Faculty</th>
				<th>Total Count</th></tr><tr>";
					$pr.= "<td>".$sname."</td>";
					$pr.= "<td>".$row['count(*)']."</td></tr></table>";
			?>
			<table class="table table-stripped table-bordered " style="margin-bottom: 0px">
				<tr>
				<th>Faculty</th>
				<?php
					if(mysqli_num_rows($result)>0){
							?>
					<th>Total Count</th>
						
				
					<?php
						echo "<th>".$row['count(*)']."</th></tr>";
				}
				?>
			</table>
<?php




				$sql1 = "SELECT F_NAME,Date_From,Date_To,Course_Name,Organised_by,Purpose,type_of_course,status_of_activity,duration,credit_audit from online_course_attended inner join facultydetails on online_course_attended.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' ";
				$display = 2;
				//$_SESSION['Sql_2']=$sql1;
				$_SESSION['sql']=$sql1;
				$result=mysqli_query($conn,$sql1);
					if(mysqli_num_rows($result)>0){
					
?>
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
						$pr.="<table border='1' cellspacing =0 class='table table-stripped table-bordered '>
							<tr>
								<th>Date From</th>
								<th>Date To</th>
								<th>Course Name</th>

								
								
								<th>Organised By</th>
				                <th>Purpose</th>
				                <th>Type of Course</th>
				                <th>Status</th>
				                <th>Duration</th>
				                <th>Credit/Audit</th>

				                

							</tr>";
						while($row =mysqli_fetch_assoc($result)){
							$name = $row['Course_Name'];
							$startdate = $row['Date_From'];
							$enddate = $row['Date_To'];
							echo "<tr>";
							echo "<td>".$startdate."</td>";
							echo "<td>".$enddate."</td>";
							echo "<td>".$name."</td>";

							

							echo "<td>".$row['Organised_by']."</td>";
			                echo "<td>".$row['Purpose']."</td>";
			                echo "<td>".$row['type_of_course']."</td>";
			                echo "<td>".$row['status_of_activity']."</td>";
			                echo "<td>".$row['duration']."</td>";
			                echo "<td>".$row['credit_audit']."</td>";                

							
							
							echo "</tr>";
							$pr.= "<tr>";
							$pr.= "<td>".$startdate."</td>";
							$pr.= "<td>".$enddate."</td>";
							$pr.= "<td>".$name."</td>";
							$pr.= "<td>".$row['Organised_by']."</td>";
			                $pr.= "<td>".$row['Purpose']."</td>";
			                $pr.= "<td>".$row['type_of_course']."</td>";
			                $pr.= "<td>".$row['status_of_activity']."</td>";
			                $pr.= "<td>".$row['duration']."</td>";
			                $pr.= "<td>".$row['credit_audit']."</td>";                

							$pr.= "</tr>";
						}
						echo "</table>";
						$pr.="</table>";
						$_SESSION['A_2']=$pr;
						?>
						
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>	
	&nbsp;<a href='ExportToExcel_online_hod.php' type='button' class='btn btn-primary' target='_blank'>Export To Excel</a><?php
					}
					
					else{
						echo "<div class='error'>No records to display</div>";
					}
		}
		else if($_SESSION['flag_count'] == 3)
		{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;
			$sname = $_SESSION['sname'] ;
$sql1 = "SELECT count(*) from online_course_attended inner join facultydetails on online_course_attended.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and online_course_attended.Date_from >= '$from_date' and online_course_attended.Date_from <= '$to_date'";
			$display = 3;
			$result=mysqli_query($conn,$sql1);
			$row =mysqli_fetch_assoc($result);
			$pr="<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Online Courses $type analysis</p>";
			$pr.="<table class='table table-stripped table-bordered ' border='1' cellspacing = 0px style='margin-bottom: 0px'>
				<tr>
				<th>Faculty</th>
				<th>Total Count</th></tr><tr>";

					$pr.= "<td>".$sname."</td>";
					$pr.= "<td>".$row['count(*)']."</td></tr></table>";
			?>
			<table class="table table-stripped table-bordered " style="margin-bottom: 0px">
				<tr>
				<th>Faculty</th>
				<?php
					if(mysqli_num_rows($result)>0){
							?>
					<th>Total Count</th>
						
				
					<?php
						echo "<th>".$row['count(*)']."</th></tr>";
				}
				?>
			</table>
<?php



			$sql1 = "SELECT F_NAME,Date_From,Date_To,Course_Name,Organised_By,Purpose,type_of_course,status_of_activity,duration,credit_audit from online_course_attended inner join facultydetails on online_course_attended.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and online_course_attended.Date_from >= '$from_date' and online_course_attended.Date_from <= '$to_date'";
			//$_SESSION['Sql_3']=$sql1;
			$_SESSION['sql']=$sql1;
					$result=mysqli_query($conn,$sql1);
					if(mysqli_num_rows($result)>0){
					
?>
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
						$pr.="<table class='table table-stripped table-bordered ' border='1' cellspacing = 0px >
							<tr>
								<th>Date From</th>
								<th>Date To</th>
								<th>Course Name</th>

								
								
								<th>Organised By</th>
				                <th>Purpose</th>
				                <th>Type of Course</th>
				                <th>Status</th>
				                <th>Duration</th>
				                <th>Credit/Audit</th>

				                

							</tr>";
						while($row =mysqli_fetch_assoc($result)){
							$name = $row['Course_Name'];
							$startdate = $row['Date_From'];
							$enddate = $row['Date_To'];
							echo "<tr>";
							echo "<td>".$startdate."</td>";
							echo "<td>".$enddate."</td>";
							echo "<td>".$name."</td>";

							echo "<td>".$row['Organised_By']."</td>";
			                echo "<td>".$row['Purpose']."</td>";
			                echo "<td>".$row['type_of_course']."</td>";
			                echo "<td>".$row['status_of_activity']."</td>";
			                echo "<td>".$row['duration']."</td>";
			                echo "<td>".$row['credit_audit']."</td>";                
							
							echo "</tr>";
							$pr.= "<tr>";
							$pr.= "<td>".$startdate."</td>";
							$pr.= "<td>".$enddate."</td>";
							$pr.= "<td>".$name."</td>";
							$pr.= "<td>".$row['Organised_By']."</td>";
			                $pr.= "<td>".$row['Purpose']."</td>";
			                $pr.= "<td>".$row['type_of_course']."</td>";
			                $pr.= "<td>".$row['status_of_activity']."</td>";
			                $pr.= "<td>".$row['duration']."</td>";
			                $pr.= "<td>".$row['credit_audit']."</td>"; 
							$pr.= "</tr>";
						}
						echo "</table>";
						$pr.= "</table>";
						$_SESSION['A_3']=$pr;
						?>
					<!--	<a href='print_all_online.php?display=<?php// echo $display;?>' style='margin-left:5px' type='button' class='btn btn-primary' target='_blank'>Print</a>
						-->
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>	
	&nbsp;<a href='ExportToExcel_online_hod.php' type='button' class='btn btn-primary' target='_blank'>Export To Excel</a><?php
					}
					else{
						echo "<div class='error'>No records to display</div>";
					}
		}
	}
	if($_SESSION['online_type']=='Organised'){
	
		if($_SESSION['flag_count'] == 1)
		{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;
			$sql1 = "select count(*) from online_course_organised inner join facultydetails on online_course_organised.Fac_ID = facultydetails.Fac_ID where Date_From >= '$from_date' and Date_From <= '$to_date' ";

			$result=mysqli_query($conn,$sql1);
			$row =mysqli_fetch_assoc($result);
			$pr="<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Online Courses $type analysis</p>";
			$pr.="<table border='1' cellspacing = 0px class='table table-stripped table-bordered ' style='margin-bottom: 0px'>
				<tr>
				<th>Total Count</th>";
					$pr.= "<th>".$row['count(*)']."</th></tr></table>";
			?>
			<table class="table table-stripped table-bordered " style="margin-bottom: 0px">
				<tr>
				<?php
					if(mysqli_num_rows($result)>0){
							?>
					<th>Total Count</th>
						
				
					<?php
						echo "<th>".$row['count(*)']."</th></tr>";
				}
				?>
			</table>
<?php


			$sql1 = "select F_NAME,Course_Name, Date_From, Date_To, Organised_By, Purpose, Target_Audience, faculty_role, full_part_time, no_of_part, duration, status, sponsored, name_of_sponsor, is_approved from online_course_organised inner join facultydetails on online_course_organised.Fac_ID = facultydetails.Fac_ID where Date_From >= '$from_date' and Date_From <= '$to_date' ";
			//$_SESSION['Sql_4']=$sql1;
			$_SESSION['sql']=$sql1;
			$display = 4;
					$result=mysqli_query($conn,$sql1);
					if(mysqli_num_rows($result)>0){
					
?>
						<table class="table table-stripped table-bordered ">
							<tr>
								<th>Faculty</th>
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
						$pr.="<table border='1' cellspacing = 0px class='table table-stripped table-bordered '>
							<tr>
								<th>Faculty</th>
								<th>Date From</th>
								<th>Date To</th>
								<th>Course Name</th>
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
							</tr>";
						while($row =mysqli_fetch_assoc($result)){
							$fname = $row['F_NAME'];
							$name = $row['Course_Name'];
							$startdate = $row['Date_From'];
							$enddate = $row['Date_To'];
							echo "<tr>";
							echo "<td>".$fname."</td>";
							echo "<td>".$startdate."</td>";
							echo "<td>".$enddate."</td>";
							echo "<td>".$name."</td>";
							
							

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

			                

							
							echo "</tr>";
							$pr.= "<tr>";
							$pr.= "<td>".$fname."</td>";
							$pr.= "<td>".$startdate."</td>";
							$pr.= "<td>".$enddate."</td>";
							$pr.= "<td>".$name."</td>";
							$pr.= "<td>".$row['Organised_By']."</td>";
			                $pr.= "<td>".$row['Purpose']."</td>";
			                $pr.= "<td>".$row['Target_Audience']."</td>";
			                $pr.= "<td>".$row['faculty_role']."</td>";
			                $pr.= "<td>".$row['full_part_time']."</td>";
			                $pr.= "<td>".$row['no_of_part']."</td>";
			                $pr.= "<td>".$row['duration']."</td>";
			                $pr.= "<td>".$row['status']."</td>";
			                $pr.= "<td>".$row['sponsored']."</td>";
			                $pr.= "<td>".$row['name_of_sponsor']."</td>";
			                $pr.= "<td>".$row['is_approved']."</td>";
							
							$pr.= "</tr>";
						}
						echo "</table>";
						$pr.= "</table>";
						$_SESSION['O_1']=$pr;
						?>
											
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
	&nbsp;<a href='ExportToExcel_online_hod.php' type='button' class='btn btn-primary' target='_blank'>Export To Excel</a><?php
					}
					else{
						echo "<div class='error'>No records to display</div>";
					}
		}
		else if ($_SESSION['flag_count'] == 2)
		{
				$sname = $_SESSION['sname'] ;
				$sql1 = "SELECT count(*) from online_course_organised inner join facultydetails on online_course_organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' ";
				$result=mysqli_query($conn,$sql1);
				$row =mysqli_fetch_assoc($result);
			$pr="<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Online Courses $type analysis</p>";
				$pr.="<table class='table table-stripped table-bordered ' border='1' cellspacing = 0px style='margin-bottom: 0px'>
				<tr>
				<th>Faculty</th>
				<th>Total Count</th></tr><tr>";
					$pr.= "<td>".$sname."</td>";
					$pr.= "<td>".$row['count(*)']."</td></tr></table>";
			?>
			<table class="table table-stripped table-bordered " style="margin-bottom: 0px">
				<tr>
				<th>Faculty</th>
				<?php
					if(mysqli_num_rows($result)>0){
							?>
					<th>Total Count</th>
			
					<?php
						echo "<th>".$row['count(*)']."</th></tr>";
				}
				?>
			</table>
<?php

				$sql1 = "SELECT F_NAME,`Course_Name`, `Date_From`, `Date_To`, `Organised_By`, `Purpose`, `Target_Audience`, `faculty_role`, `full_part_time`, `no_of_part`, `duration`, `status`, `sponsored`, `name_of_sponsor`, `is_approved` from online_course_organised inner join facultydetails on online_course_organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' ";
				$display = 5;
				//$_SESSION['Sql_5']=$sql1;
				$_SESSION['sql']=$sql1;
				$result=mysqli_query($conn,$sql1);
					if(mysqli_num_rows($result)>0){
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
				                <th>Name of sponsor</th>
				                <th>Approved</th>

				                <!-- My Code End  -->

							</tr>
<?php
						$pr.="<table class='table table-stripped table-bordered ' border='1' cellspacing = 0px >
							<tr>
								<th>Date From</th>
								<th>Date To</th>
								<th>Course Name</th>
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
							</tr>";
						while($row =mysqli_fetch_assoc($result)){
							$name = $row['Course_Name'];
							$startdate = $row['Date_From'];
							$enddate = $row['Date_To'];
							echo "<tr>";
							echo "<td>".$startdate."</td>";
							echo "<td>".$enddate."</td>";
							echo "<td>".$name."</td>";

							

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

			                

							
							echo "</tr>";
							$pr.= "<tr>";
							$pr.= "<td>".$startdate."</td>";
							$pr.= "<td>".$enddate."</td>";
							$pr.= "<td>".$name."</td>";
							$pr.= "<td>".$row['Organised_By']."</td>";
			                $pr.= "<td>".$row['Purpose']."</td>";
			                $pr.= "<td>".$row['Target_Audience']."</td>";
			                $pr.= "<td>".$row['faculty_role']."</td>";
			                $pr.= "<td>".$row['full_part_time']."</td>";
			                $pr.= "<td>".$row['no_of_part']."</td>";
			                $pr.= "<td>".$row['duration']."</td>";
			                $pr.= "<td>".$row['status']."</td>";
			                $pr.= "<td>".$row['sponsored']."</td>";
			                $pr.= "<td>".$row['name_of_sponsor']."</td>";
			                $pr.= "<td>".$row['is_approved']."</td>";
							$pr.= "</tr>";
						}
						echo "</table>";
						$pr.= "</table>";
						$_SESSION['O_2']=$pr;
						?>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
	&nbsp;<a href='ExportToExcel_online_hod.php' type='button' class='btn btn-primary' target='_blank'>Export To Excel</a><?php
					}
					else{
						echo "<div class='error'>No records to display</div>";
					}
		}
		else if($_SESSION['flag_count'] == 3)
		{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;
			$sname = $_SESSION['sname'] ;
			$sql1 = "SELECT count(*) from online_course_organised inner join facultydetails on online_course_organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and online_course_organised.Date_from >= '$from_date' and online_course_organised.Date_from <= '$to_date'";
			$display = 6;

$result=mysqli_query($conn,$sql1);
			$row =mysqli_fetch_assoc($result);
			$pr="<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Online Courses $type analysis</p>";
			$pr.="<table border='1' cellspacing = 0px class='table table-stripped table-bordered ' style='margin-bottom: 0px'>
				<tr>
				<th>Faculty</th>
				<th>Total Count</th></tr><tr>";				
					$pr.= "<td>".$sname."</td>";
					$pr.= "<td>".$row['count(*)']."</td></tr></table>";
			?>
			<table class="table table-stripped table-bordered " style="margin-bottom: 0px">
				<tr>
				<th>Faculty</th>
				<?php
					if(mysqli_num_rows($result)>0){
							?>
					<th>Total Count</th>
					<?php
						echo "<th>".$row['count(*)']."</th></tr>";
					
				}
				?>
			</table>
<?php
			$sql1 = "SELECT F_NAME,`Course_Name`, `Date_From`, `Date_To`, `Organised_By`, `Purpose`, `Target_Audience`, `faculty_role`, `full_part_time`, `no_of_part`, `duration`, `status`, `sponsored`, `name_of_sponsor`, `is_approved` from online_course_organised inner join facultydetails on online_course_organised.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and online_course_organised.Date_from >= '$from_date' and online_course_organised.Date_from <= '$to_date'";
			$_SESSION['sql']=$sql1;
			//$_SESSION['Sql_6']=$sql1;
					$result=mysqli_query($conn,$sql1);
					if(mysqli_num_rows($result)>0){
					
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
						$pr.="<table border='1' cellspacing = 0px class='table table-stripped table-bordered '>
							<tr>
								<th>Date From</th>
								<th>Date To</th>
								<th>Course Name</th>
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
							</tr>";
						while($row =mysqli_fetch_assoc($result)){
							$name = $row['Course_Name'];
							$startdate = $row['Date_From'];
							$enddate = $row['Date_To'];
							echo "<tr>";
							echo "<td>".$startdate."</td>";
							echo "<td>".$enddate."</td>";
							echo "<td>".$name."</td>";
							
							

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

			                

							
							echo "</tr>";
							$pr.= "<tr>";
							$pr.= "<td>".$startdate."</td>";
							$pr.= "<td>".$enddate."</td>";
							$pr.= "<td>".$name."</td>";
							$pr.= "<td>".$row['Organised_By']."</td>";
			                $pr.= "<td>".$row['Purpose']."</td>";
			                $pr.= "<td>".$row['Target_Audience']."</td>";
			                $pr.= "<td>".$row['faculty_role']."</td>";
			                $pr.= "<td>".$row['full_part_time']."</td>";
			                $pr.= "<td>".$row['no_of_part']."</td>";
			                $pr.= "<td>".$row['duration']."</td>";
			                $pr.= "<td>".$row['status']."</td>";
			                $pr.= "<td>".$row['sponsored']."</td>";
			                $pr.= "<td>".$row['name_of_sponsor']."</td>";
			                $pr.= "<td>".$row['is_approved']."</td>";
							
							$pr.= "</tr>";
						}
						echo "</table>";
						$pr.= "</table>";
						$_SESSION['O_3']=$pr;
						?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>					
						<a href='ExportToExcel_online_hod.php' type='button' class="btn btn-primary" target='_blank'>Export To Excel</a>
						<?php
					}
					else{
						echo "<div class='error'>No records to display</div>";
					}
		}

	}
}

?>

<?php 
function print1($op){
	$dompdf = new DOMPDF();
	$dompdf->load_html($op);
	$dompdf->set_paper('a4', 'portrait');
	$dompdf->render();
	$dompdf->stream('hi',array('Attachment'=>0));
}
?>
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
  $("#type").chosen();
</script>
</head>   

<?php include_once('footer.php'); ?>
