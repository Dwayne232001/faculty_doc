<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the user to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "paper";

include_once("includes/connection.php");

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
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
					<h3 class="box-title"><b>Analysis</b></h3>
					<br>
					</div>
                </div><!-- /.box-header -->
				<div style="text-align:right">
		<!--		<a href="menu.php?menu=1 " style="text-align:right"> <u>Back to Paper Publication/Presentation Menu</u></a>&nbsp&nbsp  -->
                </div>
                <!-- form start -->
    		<form role="form" action = "count_your.php" method="post">
                <div class="box-body">
                    
					<div class="form-group">
                        <label for="InputDateFrom">Date from :</label>
						<input type="date" name="min_date">
 						<label for="InputDateTo">Date To :</label>
						<input type="date" name="max_date"></p>
                    </div>
                   
                   
                    
                </div><!-- /.box-body -->

                <div class="box-footer">
					<input type="submit" class="btn btn-primary" name="count_total" value = "Count Publications"></input>
                    <a href="2_dashboard.php" type="button" class="btn btn-primary">Back to View Mode </a>
                </div>
<?php 
   	$display = 0;
	$Fac_ID = $_SESSION['Fac_ID'];	
	$a=0;
	$dateset=0;
	$flag=1;
	$set = 0;
	$sql="";
	$_SESSION['count1'] = 0;						
	$_SESSION['count4'] = 0;
	$_SESSION['count5'] = 0;
	$_SESSION['count6'] = 0;
	$_SESSION['count7'] = 0;
	$_SESSION['count8'] = 0;
	$_SESSION['count9'] = 0;
	$_SESSION['count10'] = 0;
	$_SESSION['count11'] = 0;

						if(isset($_POST['count_total']))
						{
							if (!empty($_POST['min_date']) && !empty($_POST['max_date']))
							{
								$set = 1;
								if((strtotime($_POST['min_date']))>(strtotime($_POST['max_date'])))
								{
									$result="Incorrect date entered. Date from cannot be greater than Date to<br>";
									$a=1;
									$dateset = 1;
								}
								
								// if(strtotime($_POST['max_date'])>strtotime(date('Y-m-d H:i:s')))
								// {
								// 	$result="Date to cannot be greater than today's date<br>";
								// 	$a=1;
								// 	$dateset = 1;										
								// }
								
								if($a == 1)
								{	
									echo '<div class="error">'.$result.'</div>';
								}
																				
								else if($dateset== 0 && $a == 0)
								{
									$_SESSION['from_date'] = $_POST['min_date'];
									$_SESSION['to_date'] = $_POST['max_date'];
									$from_date = $_SESSION['from_date'] ;
									$to_date = $_SESSION['to_date'] ;
									$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID ";
									$display = 1;
								}
								$_SESSION['dateset'] = $dateset;
							}
							else
							{
								$set=1;
								$to_date = date("Y/m/d");
								$prevyear=date("Y")-1;
								$from_date=$prevyear.'/06/01';
								$_SESSION['from_date'] = $from_date;
								$_SESSION['to_date'] = $to_date;
								$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID ";
								$display = 1;
							}
						}
		
		if($set == 1)
		{
			$from_date = $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;
			$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and Paper_type = 'conference' and Paper_N_I = 'national'" ;						
			if($res1 = mysqli_query($conn,$sql))
			{
				if(mysqli_num_rows($res1) > 0)
				{
					while($row = $res1->fetch_assoc()) 
					{
						$papertitle1[] = $row['Paper_title'];
						$conf1[] = $row['conf_journal_name'];
						$_SESSION['count4']++;
					}
				}
			}
			$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and Paper_type = 'conference' and Paper_N_I = 'international'" ;
			if($res1 = mysqli_query($conn,$sql))
			{
				if(mysqli_num_rows($res1) > 0)
				{
					while($row = $res1->fetch_assoc()) 
					{
						$papertitle2[] = $row['Paper_title'];
						$conf2[] = $row['conf_journal_name'];
						$_SESSION['count5']++;	
					}
				}
			}		
			$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and Paper_type = 'journal' and Paper_N_I = 'national'" ;
			if($res1 = mysqli_query($conn,$sql))
			{
				if(mysqli_num_rows($res1) > 0)
				{
					while($row = $res1->fetch_assoc()) 
					{
						$papertitle3[] = $row['Paper_title'];
						$conf3[] = $row['conf_journal_name'];	
						$_SESSION['count6']++;					
					}
				}
			}
			$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and Paper_type = 'journal' and Paper_N_I = 'international'" ;
			if($res1 = mysqli_query($conn,$sql))
			{
				if(mysqli_num_rows($res1) > 0)
				{
					while($row = $res1->fetch_assoc()) 
					{
						$papertitle4[] = $row['Paper_title'];
						$conf4[] = $row['conf_journal_name'];
						$_SESSION['count7']++;
					}
				}
			}
			$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and paper_category = 'peer reviewed'" ;
			if($res1 = mysqli_query($conn,$sql))
			{
				if(mysqli_num_rows($res1) > 0)
				{
					while($row = $res1->fetch_assoc()) 
					{
						$papertitle5[] = $row['Paper_title'];
						$conf5[] = $row['conf_journal_name'];
						$_SESSION['count8']++;				
					}
				}
			}
			$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and paper_category = 'non peer reviewed'" ;
			if($res1 = mysqli_query($conn,$sql))
			{
				if(mysqli_num_rows($res1) > 0)
				{
					while($row = $res1->fetch_assoc()) 
					{
						$papertitle6[] = $row['Paper_title'];
						$conf6[] = $row['conf_journal_name'];	
						$_SESSION['count9']++;				
					}
				}
			}
			$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and presentation_status='Presented'";
			if($res1 = mysqli_query($conn,$sql))
			{
				if(mysqli_num_rows($res1) > 0)
				{
					while($row = $res1->fetch_assoc()) 
					{
						$papertitle7[] = $row['Paper_title'];
						$conf7[] = $row['conf_journal_name'];
						$_SESSION['count10']++;					
					}
				}
			}
			$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and presentation_status='Not Presented'";
			if($res1 = mysqli_query($conn,$sql))
			{
				if(mysqli_num_rows($res1) > 0)
				{
					while($row = $res1->fetch_assoc()) 
					{
						$papertitle8[] = $row['Paper_title'];
						$conf8[] = $row['conf_journal_name'];	
						$_SESSION['count11']++;				
					}
				}
			}
		}
		$_SESSION['count1']=$_SESSION['count10']+$_SESSION['count11'];
		$_SESSION['set'] = $set;
		$_SESSION['sql'] = $sql;
?>
	<?php 
		if($_SESSION['count1']>0){
	?>
		<h4>&nbsp Total Paper Publications</h4>
		<div class="scroll">		
			<table  class="table table-stripped table-bordered " id = 'example1'>
			<col width="200">
			<col width="200">
			<col width="200">
			<col width="200">
			<col width="200">
			<col width="200">
			<col width="200">
			<col width="200">
			<col width="200">
				<thead>
					<tr>
						<th>Total Paper Publication Count</th>
						<th>National Conferences</th>
						<th>International Conferences</th>
						<th>National Journal</th>
						<th>International Journal</th>
						<th>Peer reviewed</th>
						<th>Non-peer reviewed</th>
						<th>Papers Presented</th>
						<th>Papers Not Presented</th>
					</tr>
				</thead>
				
				<tr> 	
					<td><strong><?php echo $_SESSION['count1']; ?></strong></td>
					<td><?php echo $_SESSION['count4']; ?></td>
					<td><?php echo $_SESSION['count5']; ?></td>
					<td><?php echo $_SESSION['count6']; ?></td>
					<td><?php echo $_SESSION['count7']; ?></td>
					<td><?php echo $_SESSION['count8']; ?></td>
					<td><?php echo $_SESSION['count9']; ?></td>
					<td><?php echo $_SESSION['count10']; ?></td>
					<td><?php echo $_SESSION['count11']; ?></td>
				</tr>	
				
				<tr> 
					<td></td>
					<td>
						<?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count4'] == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$_SESSION['count4']; $i++)
								{
								echo "-"." " ;
								echo $papertitle1[$i];
								echo " ".","." ". $conf1[$i] ;
								echo "<br>";
								}
							}
						?>
					</td>

				
							
					<td>
						<?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count5'] == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$_SESSION['count5']; $i++)
								{
									echo "-"." " ;
									echo $papertitle2[$i];
									echo " ".","." ". $conf2[$i] ;
									echo "<br>";
								}
							}
						?>
					</td>
				 
							
					<td>
						<?php 
							echo "<strong>Papers and Conf/Journal&nbsp;</strong>"."<br>";
							if($_SESSION['count6'] == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$_SESSION['count6']; $i++)
								{
									echo "-"." " ;
									echo $papertitle3[$i];
									echo " ".","." ". $conf3[$i] ;		
									echo "<br>";
								}
							}
						?>
					</td>
							
					<td>
						<?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count7'] == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$_SESSION['count7']; $i++)
								{
									echo "-"." " ;
									echo $papertitle4[$i];
									echo " ".","." ". $conf4[$i] ;		
									echo "<br>";
								}
							}
						?>
					</td>
	
					<td>
						<?php 
							echo "<strong>Papers and Conf/Journal&nbsp</strong>"."<br>";
							if($_SESSION['count8'] == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$_SESSION['count8']; $i++)
								{
									echo "-"." " ;
									echo $papertitle5[$i];
									echo " ".","." ". $conf5[$i] ;		
									echo "<br>";
								}
							}
						?>
					</td>
									
					<td>
						<?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count9'] == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$_SESSION['count9']; $i++)
								{
									echo "-"." " ;
									echo $papertitle6[$i];
									echo " ".","." ". $conf6[$i] ;	
									echo "<br>";
								}
							}
						?>
					</td>	

					<td>
						<?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count10'] == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$_SESSION['count10']; $i++)
								{
									echo "-"." " ;
									echo $papertitle7[$i];
									echo " ".","." ". $conf7[$i] ;	
									echo "<br>";
								}
							}
						?>
					</td>
					
					<td>
						<?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count11'] == 0)
								echo "None";
							else
							{
								for($i = 0; $i<$_SESSION['count11']; $i++)
								{
									echo "-"." " ;
									echo $papertitle8[$i];
									echo " ".","." ". $conf8[$i] ;	
									echo "<br>";
								}
							}
						?>
					</td>
				</tr>		
			</table>
		</div>
			<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
  	        <a href="export_to_excel_publication_analysis.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a> 
			
			<?php 
			}
			if (isset($_POST['count_total']) && $_SESSION['count1']<=0){
				$result="No Records Found..<br>";
				echo '<div class="error">'.$result.'</div>';
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