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
include('includes/connection.php');

$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}


$_SESSION['currentTab'] = "paper";

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
<?php 
include_once("includes/functions.php");

//include custom functions files 
include_once("includes/scripting.php");

?>
<style>
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
                <div class="box-header with-border" >
					<div class="icon">
					<i style="font-size:18px" class="fa fa-signal"></i>
					<h3 class="box-title"><b>Analysis</b></h3>
					<br>
					</div>
                </div><!-- /.box-header -->
				<div style="text-align:right">
			<!--	<a href="menu.php?menu=1 " style="text-align:right"> <u>Back to Paper Publication/Presentation Menu</u></a>&nbsp&nbsp -->
                </div>
				<!-- form start -->
                <form role="form" action = "" method="post">
                  <div class="box-body">

                    <div class="form-group">
                        <label for="InputName">Select Faculty Name :  </label><br>
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

					 <div class="form-group">
                        <label for="InputDateFrom" >Date from :</label>
						<input type="date" name="min_date" class="form-control " style="width:220px;">

 					<br/><label for="InputDateTo">Date To :</label>
						<input type="date" name="max_date" class="form-control " style="width:220px;">
                    </div>
                   
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="count" value = "Count Publications"></input>
                    <a href="2_dashboard_hod.php" type="button" class="btn btn-primary">Back to View Mode </a>

                  </div>
				   <?php 
						if(isset($_POST['count']))
						{
							$f = 0;
							$v = 0;
							$both_set = 0;
							$_SESSION['flag_count'] = 0;
							$_SESSION['value'] = 4;
							
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
									/*if(strtotime($_POST['min_date'])>strtotime(date('Y-m-d H:i:s')))
									{
										$result="Date from cannot be greater than today's date<br>";
										echo '<div class="error">'.$result.'</div>';
										$flag=1;
									}
									if(strtotime($_POST['max_date'])>strtotime(date('Y-m-d H:i:s')))
									{
										$result="Date to cannot be greater than today's date<br>";
										echo '<div class="error">'.$result.'</div>';
										$flag=1;
									}*/
						
							
							if($f == 1)
							{
								echo '<div class="error">'.$result.'</div>';

							}
							
							if($f!=1 && $both_set != 2)
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
									execute_query()	;	

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
	$conference = 0;
	$journal = 0;
	$confnat = 0;
	$confinat = 0;
	$journat = 0;
	$jourinat = 0;
	$peer = 0;
	$nonpeer = 0;
	$pres = 0;
	$npres = 0;
	$display = 0;	
		
		if($_SESSION['flag_count'] == 1)
		{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;
			$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' ";
			$display = 1;
		}
		else if ($_SESSION['flag_count'] == 2)
		{
				$to_date = date("Y/m/d");
				$prevyear=date("Y")-1;
				$from_date=$prevyear.'/06/01';
				$sname = $_SESSION['sname'] ;
				$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%'  and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date'";
				$display = 2;
		}
		else if($_SESSION['flag_count'] == 3)
		{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;
			$sname = $_SESSION['sname'] ;
			$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date'";
		//	$sql = "select * from faculty where F_NAME like '%$sname%' and Date_from >= '$from_date' and Date_from <= '$to_date'" ;
			$display = 3;
		}
		$_SESSION['sql'] = $sql;
		$_SESSION['display'] = $display;
		
if($res1 = mysqli_query($conn,$sql))
{
	if(mysqli_num_rows($res1) > 0)
	{
		$count1 = mysqli_num_rows($res1);
		while($row = $res1->fetch_assoc()) 
		{	
					if($display == 2 || $display == 3)
					{
						$fname = $row['F_NAME'];
						$_SESSION['fname'] = $fname;
						//	$paper_title[] = $row['Paper_title'];
					}
					$paper_type = $row['Paper_type'];
					$paper_n_i = $row['Paper_N_I'];
					$cate=$row['paper_category'];
					$prstatus = $row['presentation_status'];
					//$fname = $row['F_NAME'];
			
			if($paper_type == 'conference')
			{
				
				$conference++;
				
			}	
			else if($paper_type == 'journal')
			{
				
				$journal++;
				
			}	
			else
			{
				$flag = 0;
			}
			
			
			if($paper_type == 'conference' && $paper_n_i == 'national')
			{
				
				$confnat++;
				
			}	
			else if($paper_type == 'conference' && $paper_n_i == 'international')
			{
				
				$confinat++;
				
			}	
			else if($paper_type == 'journal' && $paper_n_i == 'national')
			{
				
				$journat++;
				
			}	
			else if($paper_type == 'journal' && $paper_n_i == 'international')
			{
				
				$jourinat++;
				
			}		
			else
			{
				$flag = 0;
			}
			
			if($cate=='peer reviewed')
			{
				$peer++;
				
			}
			else if($cate=='non peer reviewed')
			{
				$nonpeer++;
			}
			else
			{
				$flag=0;
			}
			
			if($prstatus=='Presented')
			{
				$pres++;
				
			}
			else if($prstatus=='Not Presented')
			{
				$npres++;
			}
			else
			{
				$flag=0;
			}
				
		}//end of while		
	}//end of if
	else
			$count1=0;	
}	
		
	?>
		
		
		
			
				
		<?php
		
		
		
			if($count1 == 0)
			{
				echo "<div class='error'>No Records to Display</div>";
			}
			
			else if($count1 !=0 && $display == 1)
			{
		?>	
		<h4>Total Paper Publications</h4>

		<table class="table table-stripped table-bordered">
				<tr> 
							<td><strong>From Date</strong></td>
						  				  
						  <td><strong>To Date</strong></td>
 							<td><strong>Total Publications Count</font></strong></td>
		
				</tr>	
					<tr > 
						  <td><?php echo $from_date; 
						  $_SESSION['a1'] = $from_date;
						  ?></td>
						  <td><?php echo $to_date;
						  $_SESSION['a2'] = $to_date;
		
						  ?></td>	
						  <td><?php echo $count1;
							  $_SESSION['a3'] = $count1;

							?></td>
						  
         
					</tr>
		
				</table>
				<br>
				<table class="table table-stripped table-bordered">
				<tr> 
							<td><strong>From Date</strong></td>
						  				  
						  <td><strong>To Date</strong></td>
							<td><strong>National Conferences</font></strong></td>
							<td><strong>International Conferences</font></strong></td>

							<td><strong>National Journals</font></strong></td>
							<td><strong>International Journals</font></strong></td>
							

							
				
				</tr>	
					<tr> 
						 <td><?php echo $from_date; ?></td>
						  <td><?php echo $to_date; ?></td>			  
						  <td><?php echo $confnat;
							$_SESSION['a4'] = $confnat;
						  ?></td>
						  <td><?php echo $confinat; 
 							$_SESSION['a5'] = $confinat;

						  ?></td>
						  <td><?php echo $journat;
  							$_SESSION['a6'] = $journat;

						  
						  ?></td>
						  <td><?php echo $jourinat; 
  							$_SESSION['a7'] = $jourinat;

						  ?></td>

						           
         
					</tr>
					<?php
				if($confnat > 0)
				{
					$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Paper_type = 'conference' and Paper_N_I = 'national'";

				
					if($res1 = mysqli_query($conn,$sql)){
					if(mysqli_num_rows($res1) > 0){

						while($row = $res1->fetch_assoc()) 
						{
								$papertitle1[] = $row['Paper_title'];
								$conf1[] = $row['conf_journal_name'];
								
						}
					}
					}
					
				}
				if($confinat > 0)
				{
					$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Paper_type = 'conference' and Paper_N_I = 'international'";

					if($res1 = mysqli_query($conn,$sql)){
					if(mysqli_num_rows($res1) > 0){

						while($row = $res1->fetch_assoc()) 
						{
								$papertitle2[] = $row['Paper_title'];
								$conf2[] = $row['conf_journal_name'];
								
						}
					}
					}
					
				}
				if($journat > 0)
				{
					$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Paper_type = 'journal' and Paper_N_I = 'national'";

					if($res1 = mysqli_query($conn,$sql)){
					if(mysqli_num_rows($res1) > 0){

						while($row = $res1->fetch_assoc()) 
						{
								$papertitle3[] = $row['Paper_title'];
								$conf3[] = $row['conf_journal_name'];
								
						}
					}
					}
					
				}
				if($jourinat > 0)
				{
					$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Paper_type = 'journal' and Paper_N_I = 'international'";

					if($res1 = mysqli_query($conn,$sql)){
					if(mysqli_num_rows($res1) > 0){

						while($row = $res1->fetch_assoc()) 
						{
								$papertitle4[] = $row['Paper_title'];
								$conf4[] = $row['conf_journal_name'];
								
						}
					}
					}
					
				}
				
				?>
				<tr>
						<td></td>
						<td></td>
						<td><?php 
						echo "<strong>Papers</strong>"."<br>";
						if($confnat == 0)
							echo "None";
						else{
						for($i = 0; $i<$confnat; $i++)
						{
							echo "-"." " ;
							echo $papertitle1[$i];
							
							echo "<br>";
						}
						}
						?></td>
						<td><?php 
						echo "<strong>Papers</strong>"."<br>";
						if($confinat == 0)
							echo "None";
						else{
						for($i = 0; $i<$confinat; $i++)
						{
							echo "-"." " ;
							echo $papertitle2[$i];
							echo "<br>";
						}
						}
						?></td>
						<td><?php 
						echo "<strong>Papers</strong>"."<br>";
						if($journat == 0)
							echo "None";
						else{
							for($i = 0; $i<$journat; $i++)
							{
								echo "-"." " ;
								echo $papertitle3[$i];
								echo "<br>";
							}
						}
						?></td>
						<td><?php 
						echo "<strong>Papers</strong>"."<br>";
						if($jourinat == 0)
							echo "None";
						else{
							for($i = 0; $i<$jourinat; $i++)
							{
								echo "-"." " ;
								echo $papertitle4[$i];
								echo "<br>";
							}
						}
						?></td>
				</tr>
<tr>
						<td></td>
						<td></td>
						<td><?php 
						echo "<strong>Conference/Journal Name</strong>"."<br>";
						if($confnat == 0)
							echo "None";
						else{
						for($i = 0; $i<$confnat; $i++)
						{
							echo "-"." " ;
							echo $conf1[$i];
							
							echo "<br>";
						}
						}
						?></td>
						<td><?php 
						echo "<strong>Conference/Journal Name</strong>"."<br>";
						if($confinat == 0)
							echo "None";
						else{
						for($i = 0; $i<$confinat; $i++)
						{
							echo "-"." " ;
							echo $conf2[$i];
							echo "<br>";
						}
						}
						?></td>
						<td><?php 
						echo "<strong>Conference/Journal Name</strong>"."<br>";
						if($journat == 0)
							echo "None";
						else{
							for($i = 0; $i<$journat; $i++)
							{
								echo "-"." " ;
								echo $conf3[$i];
								echo "<br>";
							}
						}
						?></td>
						<td><?php 
						echo "<strong>Conference/Journal Name</strong>"."<br>";
						if($jourinat == 0)
							echo "None";
						else{
							for($i = 0; $i<$jourinat; $i++)
							{
								echo "-"." " ;
								echo $conf4[$i];
								echo "<br>";
							}
						}
						?></td>
				</tr>				
					
				</table>
				<br>
				
				
				
				
				
			<table class="table table-stripped table-bordered">
				<tr> 
							<td><strong>From Date</strong></td>
						  				  
						  <td><strong>To Date</strong></td>
							<td><strong>Peer Reviewed Journals</font></strong></td>
							<td><strong>Non Peer Reviewed Journal</font></strong></td>
							<td><strong>Presented Journal</font></strong></td>
							<td><strong>Not Presented Journal</font></strong></td>

						
							

							
				
				</tr>	
					<tr> 
						 <td><?php echo $from_date; ?></td>
						  <td><?php echo $to_date; ?></td>			  
						  <td><?php echo $peer; 
						  $_SESSION['a8'] = $peer;
						  ?></td>
						  <td><?php echo $nonpeer; 
						  $_SESSION['a9'] = $nonpeer;
						  ?></td>
						  <td><?php echo $pres; 
						  $_SESSION['a10'] = $pres;
						  ?></td>        
						  <td><?php echo $npres; 
						  $_SESSION['a11'] = $npres;
						  ?></td>
					</tr>
					<?php
					if($peer > 0)
					{
						$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and paper_category='peer reviewed' ";

						if($res1 = mysqli_query($conn,$sql)){
						if(mysqli_num_rows($res1) > 0){

							while($row = $res1->fetch_assoc()) 
							{
									$papertitle5[] = $row['Paper_title'];
									$conf5[] = $row['conf_journal_name'];
									
							}
						}
						}
						
					}
					
					if($nonpeer > 0)
					{
						$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and paper_category='non peer reviewed' ";
						if($res1 = mysqli_query($conn,$sql))
						{
							if(mysqli_num_rows($res1) > 0)
							{
								while($row = $res1->fetch_assoc()) 
								{
									$papertitle6[] = $row['Paper_title'];
									$conf6[] = $row['conf_journal_name'];		
								}
							}
						}	
					}
					
					if($pres > 0)
					{
						$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and presentation_status='Presented'";
						if($res1 = mysqli_query($conn,$sql))
						{
							if(mysqli_num_rows($res1) > 0)
							{
								while($row = $res1->fetch_assoc()) 
								{
									$papertitle7[] = $row['Paper_title'];
									$conf7[] = $row['conf_journal_name'];		
								}
							}
						}	
					}
					
					if($npres > 0)
					{
						$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and presentation_status='Not Presented'";
						if($res1 = mysqli_query($conn,$sql))
						{
							if(mysqli_num_rows($res1) > 0)
							{
								while($row = $res1->fetch_assoc()) 
								{
									$papertitle8[] = $row['Paper_title'];
									$conf8[] = $row['conf_journal_name'];		
								}
							}
						}	
					}
					?>
				<tr>
					<td></td>
					<td></td>
					<td>
						<?php 
							echo "<strong>Papers</strong>"."<br>";
							if($peer == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$peer; $i++)
								{
									echo "-"." " ;
									echo $papertitle5[$i];
									echo "<br>";
								}
							}
						?>
					</td>
						
					<td>
						<?php 
							echo "<strong>Papers</strong>"."<br>";
							if($nonpeer == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$nonpeer; $i++)
								{
									echo "-"." " ;
									echo $papertitle6[$i];
									echo "<br>";
								}
							}
						?>
					</td>
						
					<td>
						<?php 
							echo "<strong>Papers</strong>"."<br>";
							if($pres == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$pres; $i++)
								{
									echo "-"." " ;
									echo $papertitle7[$i];
									echo "<br>";
								}
							}
						?>
					</td>
					
					<td>
						<?php 
							echo "<strong>Papers</strong>"."<br>";
							if($npres == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$npres; $i++)
								{
									echo "-"." " ;
									echo $papertitle8[$i];
									echo "<br>";
								}
							}
						?>
					</td>
				</tr>
				
				<tr>
					<td></td>
					<td></td>
					<td>
						<?php 
							echo "<strong>Conference/Journal Name</strong>"."<br>";
							if($peer == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$peer; $i++)
								{
									echo "-"." " ;
									echo $conf5[$i];
									echo "<br>";
								}
							}
						?>
					</td>
					
					<td>
						<?php 
							echo "<strong>Conference/Journal Name</strong>"."<br>";
							if($nonpeer == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$nonpeer; $i++)
								{
									echo "-"." " ;
									echo $conf6[$i];
									echo "<br>";
								}
							}
						?>
					</td>
					
					<td>
						<?php 
							echo "<strong>Conference/Journal Name</strong>"."<br>";
							if($pres == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$pres; $i++)
								{
									echo "-"." " ;
									echo $conf7[$i];
									echo "<br>";
								}
							}
						?>
					</td>
					
					<td>
						<?php 
							echo "<strong>Conference/Journal Name</strong>"."<br>";
							if($npres == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$npres; $i++)
								{
									echo "-"." " ;
									echo $conf8[$i];
									echo "<br>";
								}
							}
						?>
					</td>
				</tr>
			</table>	
		<?php
			
			}
			else if($display == 2)
			{ ?>
				<table class="table table-stripped table-bordered">
				<tr> 
							<td><strong>Faculty Name</strong></td>
						  				  
 							<td><strong>Total Publications Count</font></strong></td>
		
				</tr>	
					<tr> 
						  <td><?php echo $fname; 
						  $_SESSION['name'] = $fname;
						  ?></td>
						  <td><?php echo $count1;
							$_SESSION['a3'] = $count1;
						  ?></td>
						  
         
					</tr>
		
				</table>
				<br>
				<table class="table table-stripped table-bordered">
				<tr> 
							<td><strong>Faculty Name</strong></td>
						  				  
							<td><strong>National Conferences</font></strong></td>
							<td><strong>International Conferences</font></strong></td>

							<td><strong>National Journals</font></strong></td>
							<td><strong>International Journals</font></strong></td>

							
				
				</tr>	
				<?php
				if($confnat > 0)
				{
					$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Paper_type = 'conference' and faculty.Paper_N_I = 'national'";

					if($res1 = mysqli_query($conn,$sql)){
					if(mysqli_num_rows($res1) > 0){

						while($row = $res1->fetch_assoc()) 
						{
								$papertitle1[] = $row['Paper_title'];
								$conf1[] = $row['conf_journal_name'];
						}
					}
					}
					
				}
				if($confinat > 0)
				{
					$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Paper_type = 'conference' and faculty.Paper_N_I = 'international'";

					if($res1 = mysqli_query($conn,$sql)){
					if(mysqli_num_rows($res1) > 0){

						while($row = $res1->fetch_assoc()) 
						{
								$papertitle2[] = $row['Paper_title'];
								$conf2[] = $row['conf_journal_name'];
								
						}
					}
					}
					
				}
				if($journat > 0)
				{
					$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Paper_type = 'journal' and faculty.Paper_N_I = 'national'";

					if($res1 = mysqli_query($conn,$sql)){
					if(mysqli_num_rows($res1) > 0){

						while($row = $res1->fetch_assoc()) 
						{
								$papertitle3[] = $row['Paper_title'];
								$conf3[] = $row['conf_journal_name'];
								
						}
					}
					}
					
				}
				if($jourinat > 0)
				{
					$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Paper_type = 'journal' and faculty.Paper_N_I = 'international'";

					if($res1 = mysqli_query($conn,$sql)){
					if(mysqli_num_rows($res1) > 0){

					while($row = $res1->fetch_assoc()) 
					{
							$papertitle4[] = $row['Paper_title'];
							$conf4[] = $row['conf_journal_name'];
							
					}
					}
					}
					
				}
				
				?>
					<tr> 
						  <td><?php echo $fname; ?></td>			  
						  <td><?php echo $confnat;
							$_SESSION['a4'] = $confnat;
						  ?></td>
						  <td><?php echo $confinat; 
  							$_SESSION['a5'] = $confinat;

						  ?></td>
						  <td><?php echo $journat; 
							$_SESSION['a6'] = $journat;
						  
						  ?></td>
						  <td><?php echo $jourinat; 
							$_SESSION['a7'] = $jourinat;
						  
						  ?></td>

						           
         
					</tr>
					<tr>
						<td><strong>Paper</strong></td>
						<td><?php 
						if($confnat == 0)
							echo "None";
						else{
						for($i = 0; $i<$confnat; $i++)
						{
							echo "-"." " ;
							echo $papertitle1[$i];
							echo "<br>";
						}
						}
						
						?></td>
						<td><?php 
						if($confinat == 0)
							echo "None";
						else{
						for($j = 0; $j<$confinat; $j++)
						{
							echo "-"." " ;
							echo $papertitle2[$j];
							echo "<br>";
						}
						}
						?></td>
						<td><?php 
						if($journat == 0)
							echo "None";
						else{
						for($j = 0; $j<$journat; $j++)
						{
							echo "-"." " ;
							echo $papertitle3[$j];
							echo "<br>";
						}
						}
						?></td>
						<td><?php 
						if($jourinat == 0)
							echo "None";
						else{
						for($j = 0; $j<$jourinat; $j++)
						{
							echo "-"." " ;
							echo $papertitle4[$j];
							echo "<br>";
						}
						}
						?></td>
						
						
					</tr>
					<tr>
						<td><strong>Conference/Journal Name</strong></td>
						<td><?php 
						if($confnat == 0)
							echo "None";
						else{
						for($i = 0; $i<$confnat; $i++)
						{
							echo "-"." " ;
							echo $conf1[$i];
							echo "<br>";
						}
						}
						
						?></td>
						<td><?php 
						if($confinat == 0)
							echo "None";
						else{
						for($j = 0; $j<$confinat; $j++)
						{
							echo "-"." " ;
							echo $conf2[$j];
							echo "<br>";
						}
						}
						?></td>
						<td><?php 
						if($journat == 0)
							echo "None";
						else{
						for($j = 0; $j<$journat; $j++)
						{
							echo "-"." " ;
							echo $conf3[$j];
							echo "<br>";
						}
						}
						?></td>
						<td><?php 
						if($jourinat == 0)
							echo "None";
						else{
						for($j = 0; $j<$jourinat; $j++)
						{
							echo "-"." " ;
							echo $conf4[$j];
							echo "<br>";
						}
						}
						?></td>
						
						
					</tr>
				</table>
				
				<br>
			<table class="table table-stripped table-bordered">
				<tr> 
							<td><strong>Faculty Name</strong></td>
						  				  
							<td><strong>Peer Reviewed Journals</font></strong></td>
							<td><strong>Non Peer Reviewed Journals</font></strong></td>
							<td><strong>Presented Journals</font></strong></td>
							<td><strong>Not Presented Journals</font></strong></td>
				
				</tr>	
				
				<?php 
				if($peer > 0)
				{
					$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.paper_category = 'peer reviewed'";

					if($res1 = mysqli_query($conn,$sql)){
					if(mysqli_num_rows($res1) > 0){

					while($row = $res1->fetch_assoc()) 
					{
							$papertitle5[] = $row['Paper_title'];
							$conf5[] = $row['conf_journal_name'];
					}
					}
					}
					
				}
				if($nonpeer > 0)
				{
					$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.paper_category = 'non peer reviewed'";

					if($res1 = mysqli_query($conn,$sql)){
					if(mysqli_num_rows($res1) > 0){

					while($row = $res1->fetch_assoc()) 
					{
							$papertitle6[] = $row['Paper_title'];
							$conf6[] = $row['conf_journal_name'];
							
					}
					}
					}
					
				}
				
				if($pres > 0)
				{
					$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.presentation_status = 'Presented'";
					if($res1 = mysqli_query($conn,$sql))
					{
						if(mysqli_num_rows($res1) > 0)
						{
							while($row = $res1->fetch_assoc()) 
							{
								$papertitle7[] = $row['Paper_title'];
								$conf7[] = $row['conf_journal_name'];	
							}
						}
					}
				}
				
				if($npres > 0)
				{
					$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.presentation_status = 'Not Presented'";
					if($res1 = mysqli_query($conn,$sql))
					{
						if(mysqli_num_rows($res1) > 0)
						{
							while($row = $res1->fetch_assoc()) 
							{
								$papertitle8[] = $row['Paper_title'];
								$conf8[] = $row['conf_journal_name'];	
							}
						}
					}
				}
				
				?>
				<tr> 
					<td><?php echo $fname; ?></td>			  
					<td><?php echo $peer;
							$_SESSION['a8'] = $peer;
						?>
					</td>
					<td><?php echo $nonpeer; 
 							$_SESSION['a9'] = $nonpeer;
						?>
					</td>
					<td><?php echo $pres; 
 							$_SESSION['a10'] = $pres;
						?>
					</td>	 
					<td><?php echo $npres; 
 							$_SESSION['a11'] = $npres;
						?>
					</td>
				</tr>
				
				<tr>
					<td><strong>Paper</strong></td>
					<td>
						<?php 
							if($peer == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$peer; $i++)
								{
									echo "-"." " ;
									echo $papertitle5[$i];
									echo "<br>";
								}
							}
						?>
					</td>
						
					<td>
						<?php 
							if($nonpeer == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$nonpeer; $i++)
								{
									echo "-"." " ;
									echo $papertitle6[$i];
									echo "<br>";
								}
							}
						?>
					</td>				
						
					<td>
						<?php 
							if($pres == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$pres; $i++)
								{
									echo "-"." " ;
									echo $papertitle7[$i];
									echo "<br>";
								}
							}
						?>
					</td>

					<td>
						<?php 
							if($npres == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$npres; $i++)
								{
									echo "-"." " ;
									echo $papertitle8[$i];
									echo "<br>";
								}
							}
						?>
					</td>		
				</tr>
				
				<tr>
					<td><strong>Conference/Journal Name</strong></td>
					<td>
						<?php 
							if($peer == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$peer; $i++)
								{
									echo "-"." " ;
									echo $conf5[$i];
									echo "<br>";
								}
							}
						?>
					</td>
						
					<td>
						<?php 
							if($nonpeer == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$nonpeer; $i++)
								{
									echo "-"." " ;
									echo $conf6[$i];
									echo "<br>";
								}
							}
						?>
					</td>

					<td>
						<?php 
							if($pres == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$pres; $i++)
								{
									echo "-"." " ;
									echo $conf7[$i];
									echo "<br>";
								}
							}
						?>
					</td>
						
					<td>
						<?php 
							if($npres == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$npres; $i++)
								{
									echo "-"." " ;
									echo $conf8[$i];
									echo "<br>";
								}
							}
						?>
					</td>	
				</tr>
			</table>
					
		<?php
			}
			else if($display == 3)
			{ ?>
				<table class="table table-stripped table-bordered">
				<tr> 
							<td><strong>Faculty Name</strong></td>
						  				  
 							<td><strong>Total Publications Count</font></strong></td>
		
				</tr>	
					<tr> 
						  <td><?php echo $fname; 
 						  $_SESSION['name'] = $fname;

						  ?></td>
						  <td><?php echo $count1;
						  $_SESSION['a3'] = $count1;

						  ?></td>
						  
         
					</tr>
		
				</table>
				<br>
				
			<table class="table table-stripped table-bordered">
				<tr> 
							<td><strong>Faculty Name</strong></td>
							<td><strong>From Date</strong></td>
							<td><strong>To Date</strong></td>
							
						  				  
							<td><strong>National Conferences</font></strong></td>
							<td><strong>International Conferences</font></strong></td>

							<td><strong>National Journals</font></strong></td>
							<td><strong>International Journals</font></strong></td>
							
	

							
				
				</tr>	
					<tr> 
						  <td><?php echo $fname; ?></td>	
						  <td><?php echo $from_date; ?></td>			  
						  <td><?php echo $to_date; ?></td>			  
						  
						  <td><?php echo $confnat;
							$_SESSION['a4'] = $confnat;
						  ?></td>
						  <td><?php echo $confinat;
							$_SESSION['a5'] = $confinat;

						  ?></td>
						  <td><?php echo $journat;
							$_SESSION['a6'] = $journat;

						  ?></td>
						  <td><?php echo $jourinat;
							$_SESSION['a7'] = $jourinat;

						  ?></td>
						         
         
					</tr>
					<?php
					$sname = $_SESSION['sname'];
					if($confnat > 0)
					{
						$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date' and faculty.Paper_type = 'conference' and faculty.Paper_N_I = 'national'";

						if($res1 = mysqli_query($conn,$sql)){
						if(mysqli_num_rows($res1) > 0){

							while($row = $res1->fetch_assoc()) 
							{
									$papertitle1[] = $row['Paper_title'];
									$conf1[] = $row['conf_journal_name'];
							}
						}
						}
						
					}
					if($confinat > 0)
					{
						$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date' and faculty.Paper_type = 'conference' and faculty.Paper_N_I = 'international'";

						if($res1 = mysqli_query($conn,$sql)){
						if(mysqli_num_rows($res1) > 0){

							while($row = $res1->fetch_assoc()) 
							{
									$papertitle2[] = $row['Paper_title'];
									$conf2[] = $row['conf_journal_name'];
									
							}
						}
						}
						
					}
					if($journat > 0)
					{
						$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date' and faculty.Paper_type = 'journal' and faculty.Paper_N_I = 'national'";

						if($res1 = mysqli_query($conn,$sql)){
						if(mysqli_num_rows($res1) > 0){

							while($row = $res1->fetch_assoc()) 
							{
									$papertitle3[] = $row['Paper_title'];
									$conf3[] = $row['conf_journal_name'];
									
							}
						}
						}
						
					}
					if($jourinat > 0)
					{
						$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date' and faculty.Paper_type = 'journal' and faculty.Paper_N_I = 'international'";

						if($res1 = mysqli_query($conn,$sql)){
						if(mysqli_num_rows($res1) > 0){

							while($row = $res1->fetch_assoc()) 
							{
									$papertitle4[] = $row['Paper_title'];
									$conf4[] = $row['conf_journal_name'];
									
							}
						}
						}
						
					}
					?>
					<tr>
						<td><strong>Paper</strong></td>
						<td></td>
						<td></td>

						<td><?php 
						if($confnat == 0)
							echo "None";
						else{
						for($i = 0; $i<$confnat; $i++)
						{
							echo "-"." " ;
							echo $papertitle1[$i];
							echo "<br>";
						}
						}
						
						?></td>
						<td><?php 
						if($confinat == 0)
							echo "None";
						else{
						for($i = 0; $i<$confinat; $i++)
						{
							echo "-"." " ;
							echo $papertitle2[$i];
							echo "<br>";
						}
						}
						
						?></td>
						<td><?php 
						if($journat == 0)
							echo "None";
						else{
						for($i = 0; $i<$journat; $i++)
						{
							echo "-"." " ;
							echo $papertitle3[$i];
							echo "<br>";
						}
						}
						
						?></td>
						<td><?php 
						if($jourinat == 0)
							echo "None";
						else{
						for($i = 0; $i<$jourinat; $i++)
						{
							echo "-"." " ;
							echo $papertitle4[$i];
							echo "<br>";
						}
						}
						
						?></td>
					</tr>	
					<tr>
						<td><strong>Conference/Journal Name</strong></td>
						<td></td>
						<td></td>

						<td><?php 
						if($confnat == 0)
							echo "None";
						else{
						for($i = 0; $i<$confnat; $i++)
						{
							echo "-"." " ;
							echo $conf1[$i];
							echo "<br>";
						}
						}
						
						?></td>
						<td><?php 
						if($confinat == 0)
							echo "None";
						else{
						for($i = 0; $i<$confinat; $i++)
						{
							echo "-"." " ;
							echo $conf2[$i];
							echo "<br>";
						}
						}
						
						?></td>
						<td><?php 
						if($journat == 0)
							echo "None";
						else{
						for($i = 0; $i<$journat; $i++)
						{
							echo "-"." " ;
							echo $conf3[$i];
							echo "<br>";
						}
						}
						
						?></td>
						<td><?php 
						if($jourinat == 0)
							echo "None";
						else{
						for($i = 0; $i<$jourinat; $i++)
						{
							echo "-"." " ;
							echo $conf4[$i];
							echo "<br>";
						}
						}
						
						?></td>
					</tr>	
					
				</table>
				
				<br>
				<table class="table table-stripped table-bordered">
				<tr> 
							<td><strong>Faculty Name</strong></td>
							<td><strong>From Date</strong></td>
							<td><strong>To Date</strong></td>
							
						
							<td><strong>Peer Reviewed Journals</font></strong></td>
							<td><strong>Non Peer Reviewed Journals</font></strong></td>
							<td><strong>Presented Journals</font></strong></td>
							<td><strong>Not Presented Journals</font></strong></td>

							
				
				</tr>	
					<tr> 
						<td><?php echo $fname; ?></td>	
							<td><?php echo $from_date; ?></td>	
								<?php $_SESSION['a1'] = $from_date;	?>					  
							<td><?php echo $to_date; ?></td>			  
								<?php $_SESSION['a2'] = $to_date;	?>					  
							<td><?php echo $peer; 
								$_SESSION['a8'] = $peer;?>
							</td>
							<td><?php echo $nonpeer; 
								$_SESSION['a9'] = $nonpeer;?>
							</td>
							<td><?php echo $pres; 
								$_SESSION['a10'] = $pres;?>
							</td>
							<td><?php echo $npres; 
								$_SESSION['a11'] = $npres;?>
							</td>
					</tr>
					
					<?php
					$sname = $_SESSION['sname'];
					if($peer > 0)
					{
						$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date' and faculty.paper_category = 'peer reviewed'";
						if($res1 = mysqli_query($conn,$sql))
						{
							if(mysqli_num_rows($res1) > 0)
							{
								while($row = $res1->fetch_assoc()) 
								{
									$papertitle5[] = $row['Paper_title'];
									$conf5[] = $row['conf_journal_name'];
								}
							}
						}	
					}
					
					if($nonpeer > 0)
					{
						$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date' and faculty.paper_category = 'non peer reviewed'";
						if($res1 = mysqli_query($conn,$sql))
						{
							if(mysqli_num_rows($res1) > 0)
							{
								while($row = $res1->fetch_assoc()) 
								{
									$papertitle6[] = $row['Paper_title'];
									$conf6[] = $row['conf_journal_name'];	
								}
							}
						}
					}
					
					if($pres > 0)
					{
						$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date' and faculty.presentation_status = 'Presented'";
						if($res1 = mysqli_query($conn,$sql))
						{
							if(mysqli_num_rows($res1) > 0)
							{
								while($row = $res1->fetch_assoc()) 
								{
									$papertitle7[] = $row['Paper_title'];
									$conf7[] = $row['conf_journal_name'];	
								}
							}
						}
					}
					
					if($npres > 0)
					{
						$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date' and faculty.presentation_status = 'Not Presented'";
						if($res1 = mysqli_query($conn,$sql))
						{
							if(mysqli_num_rows($res1) > 0)
							{
								while($row = $res1->fetch_assoc()) 
								{
									$papertitle8[] = $row['Paper_title'];
									$conf8[] = $row['conf_journal_name'];	
								}
							}
						}
					}
					?>
					<tr>
						<td><strong>Paper</strong></td>
						<td></td>
						<td></td>
						<td>
							<?php 
								if($peer == 0)
									echo "None";
								else
								{
									for($i = 0; $i<$peer; $i++)
									{
										echo "-"." " ;
										echo $papertitle5[$i];
										echo "<br>";
									}
								}	
							?>
						</td>
						
						<td>
							<?php 
								if($nonpeer == 0)
									echo "None";
								else
								{
									for($i = 0; $i<$nonpeer; $i++)
									{
										echo "-"." " ;
										echo $papertitle6[$i];
										echo "<br>";
									}
								}	
							?>
						</td>
						
						<td>
							<?php 
								if($pres == 0)
									echo "None";
								else
								{
									for($i = 0; $i<$pres; $i++)
									{
										echo "-"." " ;
										echo $papertitle7[$i];
										echo "<br>";
									}
								}	
							?>
						</td>
						
						<td>
							<?php 
								if($npres == 0)
									echo "None";
								else
								{
									for($i = 0; $i<$npres; $i++)
									{
										echo "-"." " ;
										echo $papertitle8[$i];
										echo "<br>";
									}
								}	
							?>
						</td>
					</tr>	
					
					<tr>
						<td><strong>Conference/Journal Name</strong></td>
						<td></td>
						<td></td>
						<td>
							<?php 
								if($peer == 0)
									echo "None";
								else
								{
									for($i = 0; $i<$peer; $i++)
									{
										echo "-"." " ;
										echo $conf5[$i];
										echo "<br>";
									}
								}
							?>
						</td>
						
						<td>
							<?php 
								if($nonpeer == 0)
									echo "None";
								else
								{
									for($i = 0; $i<$nonpeer; $i++)
									{
										echo "-"." " ;
										echo $conf6[$i];
										echo "<br>";
									}
								}
							?>
						</td>
						
						<td>
							<?php 
								if($pres == 0)
									echo "None";
								else
								{
									for($i = 0; $i<$pres; $i++)
									{
										echo "-"." " ;
										echo $conf7[$i];
										echo "<br>";
									}
								}
							?>
						</td>
						
						<td>
							<?php 
								if($npres == 0)
									echo "None";
								else
								{
									for($i = 0; $i<$npres; $i++)
									{
										echo "-"." " ;
										echo $conf8[$i];
										echo "<br>";
									}
								}
							?>
						</td>
					</tr>	
				</table>

		<?php
		
			}
			
			if($count1 != 0)
			{
			?>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
 	         <a href="export_to_excel_publication_analysis_all1.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a> 

			<?php } ?>

			
<?php 
}	//end of function

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
</script>
</head>
    
<?php include_once('footer.php'); ?>