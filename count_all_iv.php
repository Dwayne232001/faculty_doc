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

$_SESSION['currentTab']="iv";

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
					<h3 class="box-title"><b>Industrial Visit Analysis</b></h3>
					<br>
					</div>
				
				</div><!-- /.box-header -->
				<div style="text-align:right">
			<!--		<a href="menu.php?menu=8 "> <u>Back to Any Other Activity Menu</u></a> -->
				</div>
                <!-- form start -->
                <form role="form" action = "" method="post">
                  <div class="box-body">
				  <div class="form-group col-md-8">
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
                        <label for="min_date">Date from :</label>
						<input type="date" name="min_date" id="min_date" class="form-control " style="width:220px;">
					 </div>
					<!-- <div class="form-group col-md-6">
					</div> -->
					<div class="form-group col-md-8">
 						<label for="max_date">Date To :</label>
						<input type="date" name="max_date" id="max_date" class="form-control " style="width:220px;">
                    </div>
                   
					
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="count_total" value = "Count Activities"></input>
                    <a href="2_dashboard_iv_hod.php" type="button" class="btn btn-primary">Back to View Mode </a>

                  </div>
				   
				 <?php
				 $show = 1;
				 if (isset($_POST['count_total']))
				{
					$from_date = $_POST['min_date'];
					$Fac_ID = $_SESSION['Fac_ID'];
					$to_date = $_POST['max_date'];
					$act_name=null;
					$a=0;
					$dateset=0;
					$count = 0;
					$sname = $_POST['fn'];
					$_SESSION['sname'] = $sname;
					$_SESSION['from_date'] = $from_date;
					$_SESSION['to_date'] = $to_date;
					$_SESSION['faculty_name'] = $sname;
					
					if (($to_date==null && $from_date==null && $sname != null))
					{
						$display = 2;
						$to_date = date("Y/m/d");
						$prevyear=date("Y")-1;
						$from_date=$prevyear.'/06/01';
						$_SESSION['display'] = $display ; 
										
										if (($a==0) && ($dateset==0))
										{
											
												$sql1 = "SELECT * from iv_organized inner join facultydetails on iv_organized.f_id = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and iv_organized.t_from >= '$from_date' and iv_organized.t_from <= '$to_date' ";
											
											
											$res1 = mysqli_query($conn,$sql1);
										
										$_SESSION['sql'] = $sql1;
									while($row = $res1->fetch_assoc()) 
									{
										$count = $count + 1;
									}
									$_SESSION['count'] = $count;
									
									
										$sql1 = "SELECT * from iv_organized inner join facultydetails on iv_organized.f_id = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and iv_organized.t_from >= '$from_date' and iv_organized.t_from <= '$to_date'";
									
										$res1 = mysqli_query($conn,$sql1);
								$_SESSION['sql'] = $sql1;
								if($count > 0)
								{					
									echo "<br>";
									echo "<strong>Number of Any Other Activities are : " .$count. "</strong><br>";
									echo "<br>";				
									echo "<div class='scroll'><table  class='table table-stripped table-bordered ' id = 'example1'>
										<thead><tr> 
										<td><strong>Faculty Name</font></strong></td>
										<td><strong>Industry Name</font></strong></td>
										<td><strong>City</font></strong></td>	
										<td><strong>Purpose</font></strong></td>
                                        <td><strong>Audience</font></strong></td>
										<td><strong>From Date</strong></td>
										<td><strong>To Date</strong></td>
										
										</tr></thead>";
				
									while($row = $res1->fetch_assoc()) 
									{
										echo "<tr>";
										echo "<td>".$sname."</td>";		
										echo "<td>".$row['ind']."</td>";
                                        echo "<td>".$row['city']."</td>";
                                        echo "<td>".$row['purpose']."</td>";
                                        echo "<td>".$row['t_audience']."</td>";
										echo "<td>".$row['t_from']."</td>";
										echo "<td>".$row['t_to']."</td>";
										echo "</tr>";
										
									}	
									echo "</table></div>";
								}
								else{
									echo "<div class='error'>No records to display</div>";
								}	
				}
					goto start;
				}
					
				if ($sname==null && $to_date!=null && $from_date!=null)
					{
						$display = 1;
						$_SESSION['display'] = $display ; 
										
										if (($a==0) && ($dateset==0))
										{
											
												$sql1 = "SELECT * from iv_organized inner join facultydetails on iv_organized.f_id = facultydetails.Fac_ID and iv_organized.t_from >= '$from_date' and iv_organized.t_from <= '$to_date' ";
											
											$res1 = mysqli_query($conn,$sql1);
										$_SESSION['sql'] = $sql1;
										
									while($row = $res1->fetch_assoc()) 
									{
										$count = $count + 1;
									}
									$_SESSION['count'] = $count;
								
										$sql1 = "SELECT * from iv_organized inner join facultydetails on iv_organized.f_id = facultydetails.Fac_ID and iv_organized.t_from >= '$from_date' and iv_organized.t_from <= '$to_date' ";
									
										$res1 = mysqli_query($conn,$sql1);
									$_SESSION['sql'] = $sql1;
								if($count > 0 )
								{				
									echo "<br>";
									echo "<strong>Number of Any Other Activities are".$count."</strong><br>";
									echo "<br>";
														
									echo "<div class='scroll'><table  class='table table-stripped table-bordered ' id = 'example1'>
										<thead><tr> 
                                        <td><strong>Faculty Name</font></strong></td>
										<td><strong>Industry Name</font></strong></td>
										<td><strong>City</font></strong></td>	
										<td><strong>Purpose</font></strong></td>
                                        <td><strong>Audience</font></strong></td>
										<td><strong>From Date</strong></td>
										<td><strong>To Date</strong></td>
										

										</tr></thead>";
										
				
									while($row = $res1->fetch_assoc()) 
									{
                                        echo "<tr>";
                                        echo "<td>".$sname."</td>";	
										echo "<td>".$row['ind']."</td>";
										echo "<td>".$row['city']."</td>";
                                        echo "<td>".$row['purpose']."</td>";
                                        echo "<td>".$row['t_audience']."</td>";
										echo "<td>".$row['t_from']."</td>";
										echo "<td>".$row['t_to']."</td>";
										echo "</tr>";
										
									}	
									echo "</table></div>";
								}
								else{
									echo "<div class='error'>No records to display</div>";
								}
				    }
				}					
					if($sname != null && $from_date != null && $to_date != null)
					{
						$display = 3;
						$_SESSION['display'] = $display ; 
										if (($a==0) && ($dateset==0))
										{
											
												$sql1 = "SELECT * from iv_organized inner join facultydetails on iv_organized.f_id = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and iv_organized.t_from >= '$from_date' and iv_organized.t_from <= '$to_date'";
											
												$res1 = mysqli_query($conn,$sql1);
										
											$_SESSION['sql'] = $sql1;
											while($row = $res1->fetch_assoc()) 
											{
												$count = $count + 1;
											}
											$_SESSION['count'] = $count;
											
													$sql1 = "SELECT * from iv_organized inner join facultydetails on iv_organized.f_id = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and iv_organized.t_from >= '$from_date' and iv_organized.t_from <= '$to_date'";
													
													$res1 = mysqli_query($conn,$sql1);
												
												$_SESSION['sql'] = $sql1;
									
									if($count > 0 )
									{
										echo "<br>";
											echo "<strong>Number of Any Other Activities are".$count."</strong><br>";
											echo "<br>";
									echo "<div class='scroll'><table  class='table table-stripped table-bordered ' id = 'example1'>
										<thead><tr> 
										<td><strong>Faculty Name</font></strong></td>
										<td><strong>Industry Name</font></strong></td>
										<td><strong>City</font></strong></td>	
										<td><strong>Purpose</font></strong></td>
                                        <td><strong>Audience</font></strong></td>
                                        <td><strong>From Date</strong></td>
										<td><strong>To Date</strong></td>
										<td><strong>Number of days</strong></td>

										</tr></thead>";
				
									while($row = $res1->fetch_assoc()) 
									{
										echo "<tr>";
										echo "<td>".$sname."</td>";		
										echo "<td>".$row['ind']."</td>";
										echo "<td>".$row['city']."</td>";
                                        echo "<td>".$row['purpose']."</td>";
                                        echo "<td>".$row['t_audience']."</td>";
										echo "<td>".$row['t_from']."</td>";
										echo "<td>".$row['t_to']."</td>";
										echo "<td>".$row['noofdays']."</td>";
										echo "</tr>";
									}	
							echo "</table></div>";
							}
							else{
								echo "<div class='error'>No records to display</div>";
							}

						}
					}
					if($sname == null && $from_date == null && $to_date == null)
					{
						$show = 0;
						$result="<strong>Please provide either Name , Date or both</strong><br>";
						echo '<div class="error">'.$result.'</div>';
					}
				
				start:
								
				 ?>
						<?php if($show == 1 && $_SESSION['count'] > 0)
						{?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
						
								<a href="export_to_excel_analysis_all_iv.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a> 
						<?php
						}
				} ?>
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