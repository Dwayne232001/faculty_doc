<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab']="technical_review";

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
					<h3 class="box-title"><b>Technical Papers Reviewed Analysis</b></h3>
					<br>
					</div>
                </div><!-- /.box-header -->
				<div style="text-align:right">
				<!--	<a href="menu.php?menu=2 "> <u>Back to Technical Papers Reviewed Menu</u></a> -->
				</div>
                <!-- form start -->
                <form role="form" action = "count_your_review.php" method="post">
                  <div class="box-body">
                    
					 <div class="form-group">
                        <label for="InputDateFrom">Date from :</label>
					<input type="date" name="min_date">

 						<label for="InputDateTo">Date To :</label>
						<input type="date" name="max_date"></p>
                    </div>
                   
                   
                    
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="count_total" value = "Count Technical Papers Reviewed"></input>
                    <a href="2_dashboard_review.php" type="button" class="btn btn-primary">Back to View Mode  </a>

                  </div>
				   <?php 
   							$display = 0;
							$Fac_ID = $_SESSION['Fac_ID'];	
							$a=0;
							$dateset=0;
							$flag=1;
							$set = 0;
							$_SESSION['count1'] = 0;
							$_SESSION['count4'] = 0;
							$_SESSION['count5'] = 0;
							$_SESSION['count6'] = 0;
							$_SESSION['count7'] = 0;
							$_SESSION['count8'] = 0;
							$_SESSION['count9'] = 0;							

						if(isset($_POST['count_total']))
						{
							
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
										/*
										if(strtotime($_POST['max_date'])>strtotime(date('Y-m-d H:i:s')))
										{
											$result="Date to cannot be greater than today's date<br>";
											echo '<div class="error">'.$result.'</div>';
											$a=1;
											$dateset = 1;
											
										}*/
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
											$sql = "select * from paper_review where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID ";
											$display = 1;
										}
								}
								else
								{	
									$set=1;
									$to_date = date("Y/m/d");
									$prevyear=date("Y")-1;
									$from_date=$prevyear.'/06/01';
									$_SESSION['from_date'] = $from_date;
									$_SESSION['to_date'] = $to_date;
									$sql = "select * from paper_review where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID ";
									$display = 1;
								}	

						}

				if($set == 1)
				{
					$from_date =  $_SESSION['from_date'] ;
					$to_date = $_SESSION['to_date'] ;

					$sql = "select * from paper_review where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and Paper_type = 'conference' and Paper_N_I = 'national'" ;
						if($res1 = mysqli_query($conn,$sql)){
							if(mysqli_num_rows($res1) > 0){
								while($row = $res1->fetch_assoc()) 
								{
									$papertitle1[] = $row['Paper_title'];
									$conf1[] = $row['conf_journal_name'];
									$_SESSION['count4']++;
								}
							}
						}
					$sql = "select * from paper_review where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and Paper_type = 'conference' and Paper_N_I = 'international'" ;
						if($res1 = mysqli_query($conn,$sql)){
							if(mysqli_num_rows($res1) > 0){
								while($row = $res1->fetch_assoc()) 
								{
									$papertitle2[] = $row['Paper_title'];
									$conf2[] = $row['conf_journal_name'];
									$_SESSION['count5']++;
								}
							}
						}
					$sql = "select * from paper_review where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and Paper_type = 'journal' and Paper_N_I = 'national'" ;
						if($res1 = mysqli_query($conn,$sql)){
							if(mysqli_num_rows($res1) > 0){
								while($row = $res1->fetch_assoc()) 
								{
									$papertitle3[] = $row['Paper_title'];
									$conf3[] = $row['conf_journal_name'];
									$_SESSION['count6']++;
								}
							}
						}
					$sql = "select * from paper_review where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and Paper_type = 'journal' and Paper_N_I = 'international'" ;
						if($res1 = mysqli_query($conn,$sql)){
							if(mysqli_num_rows($res1) > 0){

								while($row = $res1->fetch_assoc()) 
								{
									$papertitle4[] = $row['Paper_title'];
									$conf4[] = $row['conf_journal_name'];
									$_SESSION['count7']++;
								}
							}
						}
					$sql = "select * from paper_review where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and paper_category = 'peer reviewed'" ;
						if($res1 = mysqli_query($conn,$sql)){
							if(mysqli_num_rows($res1) > 0){

								while($row = $res1->fetch_assoc()) 
								{
									$papertitle5[] = $row['Paper_title'];
									$conf5[] = $row['conf_journal_name'];
									$_SESSION['count8']++;
								}
							}
						}
					$sql = "select * from paper_review where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID and paper_category = 'non peer reviewed'" ;
						if($res1 = mysqli_query($conn,$sql)){
							if(mysqli_num_rows($res1) > 0){

								while($row = $res1->fetch_assoc()) 
								{
										$papertitle6[] = $row['Paper_title'];
										$conf6[] = $row['conf_journal_name'];
										$_SESSION['count9']++;
								}
							}
						}
					}
					$_SESSION['count1']=$_SESSION['count8']+$_SESSION['count9'];
					$_SESSION['set'] = $set;
			?>
	<?php 
		if($_SESSION['count1']>0){
	?>
				 <h4>Total Papers Reviewed</h4>
				<div class="scroll">
				<table  class="table table-stripped table-bordered " id = 'example1'>
				<thead>
						<tr>
						<th>Total Technical Papers Reviewed Count</th>
						<th>National Conferences</th>
						<th>International Conferences</th>
						<th>National Journal</th>
						<th>International Journal</th>
						<th>Peer reviewed</th>
						<th>Non-peer reviewed</th>

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

				</tr>	
				<tr> 
							<td></td>
							<td><?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count4'] == 0)
								echo "None";
							else{
							for($i = 0; $i<$_SESSION['count4']; $i++)
							{
								echo "-"." " ;
								echo $papertitle1[$i];
								if($conf1[$i] != '')
									echo " ".","." ". $conf1[$i] ;
								else
									echo " ".","." ". "NULL" ;
								echo "<br>";
							}
							}
							?></td>
							<td><?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count5'] == 0)
								echo "None";
							else{
							for($i = 0; $i<$_SESSION['count5']; $i++)
							{
								echo "-"." " ;
								echo $papertitle2[$i];
								if($conf2[$i] != '')
									echo " ".","." ". $conf2[$i] ;
								else
									echo " ".","." ". "NULL" ;

								echo "<br>";
							}
							}
							?></td>
							<td><?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count6'] == 0)
								echo "None";
							else{
								for($i = 0; $i<$_SESSION['count6']; $i++)
								{
									echo "-"." " ;
									echo $papertitle3[$i];
								if($conf3[$i] != '')
									echo " ".","." ". $conf3[$i] ;
								else
									echo " ".","." ". "NULL" ;
									
									echo "<br>";
								}
							}
							?></td>
							<td><?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count7'] == 0)
								echo "None";
							else{
								for($i = 0; $i<$_SESSION['count7']; $i++)
								{
									echo "-"." " ;
									echo $papertitle4[$i];
								if($conf4[$i] != '')
									echo " ".","." ". $conf4[$i] ;
								else
									echo " ".","." ". "NULL" ;
									echo "<br>";
								}
							}
							?></td>
							<td><?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
								if($_SESSION['count8'] == 0)
									echo "None";
								else{
								for($i = 0; $i<$_SESSION['count8']; $i++)
								{
									echo "-"." " ;
									echo $papertitle5[$i];
									if($conf5[$i] != '')
										echo " ".","." ". $conf5[$i] ;
									else
										echo " ".","." ". "NULL" ;
									echo "<br>";
								}
								}
						?></td>
						<td><?php 
							echo "<strong>Papers and Conf/Journal</strong>"."<br>";
							if($_SESSION['count9'] == 0)
								echo "None";
							else{
							for($i = 0; $i<$_SESSION['count9']; $i++)
							{
								echo "-"." " ;
								echo $papertitle6[$i];
								if($conf6[$i] != '')
									echo " ".","." ". $conf6[$i] ;
								else
									echo " ".","." ". "NULL" ;
								echo "<br>";
							}
							}
							?></td>

				</tr>						
			</table>
		</div>
			
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
  	        <a href="export_to_excel_review_analysis.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a> 

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