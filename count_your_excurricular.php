<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab']="ex";

include_once("includes/connection.php");
include_once 'dompdf/dompdf_config.inc.php';

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<script scr="jquery-3.3.1.js">
</script>
<style type="text/css">

#k1,#k2,#k3
{
	display:none;
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
                 <div class="icon">
					<i style="font-size:18px" class="fa fa-signal"></i>
					<h3 class="box-title"><b>Extra-curricular Activities Analysis</b></h3>
					<br>	
					</div>
				
				</div><!-- /.box-header -->
				<div style="text-align:right">
			<!--		<a href="menu.php?menu=7 "> <u>Back to Extra-curricular Menu</u></a> -->
				</div>
                <!-- form start -->
                <form role="form" action = "count_your_excurricular.php" method="post">
                  <div class="box-body">
                    <div class="form-group">
					 <div class="form-group col-md-6">
                        <label for="InputDateFrom">Date from :</label>
					<input type="date" name="min_date">
					<label for="InputDateTo">Date To :</label>
						<input type="date" name="max_date">
					</div>
					<br><br><br>

                 <input id="sub1" type="submit" class="btn btn-primary" name="count_total" value = "View Activities"></input> 
				<a href="2_dashboard_excurricular.php" type="button" class="btn btn-primary" >Back to View Mode </a>
				 </form>
                   
                     <?php
				       if(isset($_POST['count_total']))
					   {
						  ?>
						  <style>
						  #k1,#k2,#k3
							{
								display:initial;
							}
							</style>						   
						   <?php
						   if (!empty($_POST['min_date']) && !empty($_POST['max_date'])){
							$from_date = $_POST['min_date'];
							$to_date = $_POST['max_date'];
						
							$_SESSION['from_date'] = $from_date;
							$_SESSION['to_date'] = $to_date;
							
					// $my_var = isset($_POST['myPostData']) ? $_POST['myPostData'] : "";
						}
						else{
							$to_date = date("Y/m/d");
							$prevyear=date("Y")-1;
							$from_date=$prevyear.'/06/01';
							$_SESSION['from_date'] = $from_date;
							$_SESSION['to_date'] = $to_date;
						}
						$Fac_ID = $_SESSION['Fac_ID'];
						   
						   $q1="select * from ex_curricular where Date_from>='$from_date' AND Date_from<='$to_date' and Fac_ID='$Fac_ID'";
						   $_SESSION['sql']=$q1;
						   $result=$conn->query($q1);
						   $num= $result->num_rows;
						   $_SESSION['count'] = $num;
						   echo "<br><p id='ps1'>Total number of Activities : $num</p>";
						   if($num != 0)
						   {
						   echo "<table class='table table-stripped table-bordered' id = 'example1'>
				           <tr id='tr1'>
						   <th>Activity Name</th>
						   <th>Organised By</th>
						   <th>Purpose</th>
				           <th>Date from</th>
						   <th>Date to</th>
						   <td>Number of days</td>
				           </tr>";
						   for($i=1;$i<=$num;$i++)
						   {
							  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
							  echo '
							  <tr>
							  <td>'.$row["activity_name"].'</td>
							  <td>'.$row['organized_by'].'</td>
							  <td>'.$row['purpose_of_activity'].'</td>
							  <td>'.$row["Date_from"].'</td>
							  <td>'.$row["Date_to"].'</td>
							  <td>'.$row["noofdays"].'</td>
							  </tr>';
							}
						   }
					   $op = "<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<table  border='1' class='table table-stripped table-bordered' id = 'example1'>";
				?>
				   
				   </table>
				   <?php
				   if(isset($_SESSION['count']))
				   {
					   if($_SESSION['count'] > 0)
					   {
					   ?>
				  		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
						<a href="export_to_excel_analysis_excurricular.php" type="button" class="btn btn-primary" id="k2" class="hide1" target="_blank">Export to Excel</a>
                  
				   <?php
					   }
					   else{
						$result="No Records Found..<br>";
						echo '<div class="error">'.$result.'</div>';
					   }
				   }
				}  
			?>
				  </div><!-- /.box-body -->

                  <div class="box-footer"> 

                  </div>
                </div>
                </div>
              </div>
           </div>      
        </section>
	</div>	
	
	   
    
<?php include_once('footer.php'); ?>