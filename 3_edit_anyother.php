<?php
ob_start();
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}

$_SESSION['currentTab']="anyOther";
include_once('head.php');
include_once('header.php');

//connect ot database
include_once("includes/connection.php");

$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/config.php");



//setting error variables
$nameError="";
$emailError="";
$flag = 1;

$activityname = $startDate = $endDate = $purposeofactivity  = $organizedby = $F_NAME = $currentTimestamp =$paperpath=$certipath=$reportpath= "";

$id = "";

if(isset($_POST['rid'])){
    $_SESSION['id'] = $_POST['rid'];
    
}

$id = $_SESSION['id'];

$query = "SELECT * from any_other_activity where any_other_ID = '$id'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['Fac_ID'];
  $activityname = $row['activity_name'];
  $organizedby=$row['organized_by'];
    $purposeofactivity=$row['purpose_of_activity'];
    $startDate = $row['Date_from'];
  $endDate= $row['Date_to'];
  $currentTimestamp = $row['currentTimestamp'];
  $paperpath=$row['permission_path'];
	$certipath=$row['certificate_path'];
	$reportpath=$row['report_path'];

    $query2 = "SELECT * from facultydetails where Fac_ID = '$Fac_ID'";
    $result2 = mysqli_query($conn,$query2);
    if($result2)
    {
        $row = mysqli_fetch_assoc($result2);
        $F_NAME = $row['F_NAME'];
    }
	$_SESSION['F_NAME'] = $F_NAME ;
	   

//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['update'])){
	
    //the form was submitted
    
    $activityname = $_POST['activityname'];
	$organizedby=$_POST['organizedby'];
    $purposeofactivity=$_POST['purposeofactivity'];
    $startDate = $_POST['startdate'];
	$endDate = $_POST['enddate'];
	
		$activityname=validateFormData($_POST['activityname']);
        $activityname = "'".$activityname."'";
  
		if ((strtotime($_POST['startdate'])) > (strtotime($_POST['enddate'])))
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}	
		$startdate=validateFormData($_POST['startdate']);
        $endDate=validateFormData($_POST['enddate']);
  
		$time=time();
        $start = new DateTime(date($startdate,$time));
        $end = new DateTime(date($endDate,$time));
        $days = date_diff($start,$end);
		$noofdays = $days->format('%d');
		
        $startdate = "'".$startdate."'";
        $endDate = "'".$endDate."'";
	
        $organizedby=validateFormData($organizedby);
        $organizedby = "'".$organizedby."'";
		
        $purposeofactivity=validateFormData($purposeofactivity);
        $purposeofactivity = "'".$purposeofactivity."'";

    if(isset($_POST['applicable']))
	{
		// console.log($_POST['applicable']);
		if($_POST['applicable'] == 2)
		{
			$paperpath='NULL';
			$success=1;
					 
		}
		else if($_POST['applicable'] == 3)
		{
			$paperpath='not_applicable';
			$success=1;
		}
		else if($_POST['applicable'] == 1)
		{
			if(isset($_FILES['paper']))
			{
				$errors= array();
				$fileName = $_FILES['paper']['name'];
				$fileSize = $_FILES['paper']['size'];
				$fileTmp = $_FILES['paper']['tmp_name'];
				$fileType = $_FILES['paper']['type'];
				$fileExt=strtolower(end(explode('.',$fileName)));
				date_default_timezone_set('Asia/Kolkata');
				$targetName=$datapath."permissions/".$_SESSION['F_NAME']."_permissions_".date("d-m-Y H-i-s", time()).".".$fileExt;  
					  
				if(empty($errors)==true) 
				{
					if (file_exists($targetName)) 
					{   
						unlink($targetName);
					}      
					$moved = move_uploaded_file($fileTmp,"$targetName");
					if($moved == true){
						$paperpath=$targetName;
						$success=1;
					}
					else{
					 //not successful
					 //header("location:error.php");
						echo "<h1> $targetName </h1>";
					}
				}else{
					print_r($errors);
						//header("location:else.php");
	        	}
			}
		}
	}

	if(isset($_POST['applicable1']))
	{
		if($_POST['applicable1'] == 2)
		{
			$certipath='NULL';		
			$success=1;		 
		}
		else if($_POST['applicable1'] == 3)
		{
			$certipath='not_applicable';
			$success=1;		
		}
		else if($_POST['applicable1'] == 1)
		{
			if(isset($_FILES['certificate']))
			{
				$errors= array();
				$fileName = $_FILES['certificate']['name'];
				$fileSize = $_FILES['certificate']['size'];
				$fileTmp = $_FILES['certificate']['tmp_name'];
				$fileType = $_FILES['certificate']['type'];
				$fileExt=strtolower(end(explode('.',$fileName)));
				date_default_timezone_set('Asia/Kolkata');
				$targetName=$datapath."certificates/".$_SESSION['F_NAME']."_certificates_".date("d-m-Y H-i-s", time()).".".$fileExt;  	  
				if(empty($errors)==true) {
					if (file_exists($targetName)) {   
						unlink($targetName);
					}      
					$moved = move_uploaded_file($fileTmp,"$targetName");
					if($moved == true){
					 	$certipath=$targetName;
					 	$success=1;		
					}
					else{
						echo "<h1> $targetName </h1>";
					}
				}else{
					print_r($errors);
				}
			}
		}
	}
	if(isset($_POST['applicable2']))
	{
		if($_POST['applicable2'] == 2)
		{
			$reportpath='NULL';
			$success=1;						 
		}
		else if($_POST['applicable2'] == 3)
		{
			$reportpath='not_applicable';
			$success=1;
		}
		else if($_POST['applicable2'] == 1)
		{
			if(isset($_FILES['report']))
			{
				$errors= array();
				$fileName = $_FILES['report']['name'];
				$fileSize = $_FILES['report']['size'];
				$fileTmp = $_FILES['report']['tmp_name'];
				$fileType = $_FILES['report']['type'];
				$fileExt=strtolower(end(explode('.',$fileName)));
				date_default_timezone_set('Asia/Kolkata');
				$targetName=$datapath."reports/".$_SESSION['F_NAME']."_reports_".date("d-m-Y H-i-s", time()).".".$fileExt;  
				if(empty($errors)==true) {
					if (file_exists($targetName)) {   
						unlink($targetName);
					}      
					$moved = move_uploaded_file($fileTmp,"$targetName");
					if($moved == true){
					 	$reportpath=$targetName;
						$success=1;		
					}
					else{
						echo "<h1> $targetName </h1>";
					}
				}else{
					print_r($errors);
				}
			}
		}
	}				
	
    if($flag==1)
	{	
							 
	$sql="UPDATE any_other_activity set activity_name= $activityname, 
										Date_from=$startdate, Date_to=$endDate, 
										organized_by=$organizedby,
										purpose_of_activity=$purposeofactivity,
										permission_path='".$paperpath."',
										certificate_path='".$certipath."',
										report_path='".$reportpath."',
										currentTimestamp = CURRENT_TIMESTAMP ,
										noofdays = $noofdays
										WHERE any_other_ID = '$id'";
           
			if ($conn->query($sql) === TRUE) 
			{
				if($_SESSION['type']=='hod'){
					header("location:2_dashboard_hod_anyother.php?alert=update");
				}else{
					header("location:2_dashboard_anyother.php?alert=update");
				}
			} 
			else 
			{
				if($_SESSION['type']=='hod'){
					header("location:2_dashboard_hod_anyother.php?alert=error");
				}else{
					header("location:2_dashboard_anyother.php?alert=error");
				}
			}
			
	}	

}
}
//close the connection
mysqli_close($conn);
?>
 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script>
	$(document).ready(function(){
		// jQuery methodS ...
		$(".yes").click(function(){
			$(".reveal-if-active").show();
		});
		$(".no").click(function(){
			$(".reveal-if-active").hide();
		});		

		$(".non-vac").click(function(){
			$(".second-reveal").show();
		});
		$(".non-vac1").click(function(){
			$(".second-reveal1").show();
		});
		$(".non-vac2").click(function(){
			$(".second-reveal2").show();
		});
		$(".vac").click(function(){
			$(".second-reveal").hide();
		});
		$(".vac1").click(function(){
			$(".second-reveal1").hide();
		});
		$(".vac2").click(function(){
			$(".second-reveal2").hide();
		});
		$(".1").click(function(){
			$(".reveal-if-active").show();
		});
		$(".0").click(function(){
			$(".reveal-if-active").hide();
		});		
		$(".applicable_yes").click(function(){
			$(".reveal-if-active").show();
		});
		$(".applicable_no").click(function(){
			$(".reveal-if-active").hide();
		});	
		
		$(".sponsored").click(function(){
			$(".second-reveal").show();
		});
		$(".not-sponsored").click(function(){
			$(".second-reveal").hide();
		});
		
	});
	
</script>

<style>
.error
{
	color:red;
	border:1px solid red;
	background-color:#ebcbd2;
	border-radius:10px;
	margin:5px;
	padding:0px;
	font-family:Arial, Helvetica, sans-serif;
	width:510px;
}
.colour
{
	color:#ff0000;
}
.demo {
	width: 120px;
}
.reveal-if-active, .second-reveal, .second-reveal1, .second-reveal2 {display:none;}
.second-reveal, .second-reveal1, .second-reveal2, .reveal-if-active {padding-left:20px;}
</style>


<div class="content-wrapper">
    
    <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
			  			  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
               <div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Any Other Activity</b></h3>
					<br>	
				</div>
			   
			   </div><!-- /.box-header -->
                <div style="text-align:right">
              <!--      <a href="menu.php?menu=8 "> <u>Back to Any Other Menu</u></a> -->
                </div>
                <!-- form start -->
                <form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data">
              <?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
					//echo '<script type="text/javascript">alert("INFO:  '.$nameError.'");</script>';				
				}	
			?>		
<?php 


$replace_str = array('"', "'",'' ,'');
$organizedby = str_replace($replace_str, "", $organizedby);	

$replace_str = array('"', "'",'' ,'');
$purposeofactivity = str_replace($replace_str, "", $purposeofactivity);	

?>		  			
					<input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
					
				    
                     <div class="form-group col-md-6">
					 <label for="activity-name">Faculty Name</label>
						<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
					</div>
                     
					 <div class="form-group col-md-6">
					 <label for="activity-name">Activity Name *</label>
                      <input required   type="text" class="form-control input-lg"  name="activityname" value='<?php echo $activityname; ?>' >
					  </div><br><br>
                     
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input required  <?php echo "value = $startDate"; ?> type="date" class="form-control input-lg" id="start-date" name="startdate"
                         placeholder="03:10:10">
                     </div><br><br>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input required  <?php echo "value = $endDate"; ?> type="date" class="form-control input-lg" id="end-date" name="enddate"
                         placeholder="03:10:10" >
                     </div><br><br>
                    
                    <div class="form-group col-md-6">
                         <label for="organized-by">Organized By *</label>
                         <input type="text" required class="form-control input-lg" id="organizedby" name="organizedby" rows="2" value="<?php echo $organizedby; ?>">
                     </div>
					 
					<div class="form-group col-md-6">
                         <label for="purpose-of-activity">Purpose of Activity *</label>
                         <input type="text" required class="form-control input-lg" id="purposeofactivity" name="purposeofactivity" rows="2" value="<?php echo $purposeofactivity; ?>">
                    </div>
                     
				
                     <div class="form-group col-md-6">

					<div>
                        <label for="Index">Permission : </label><br/>
						<input type="radio" id="r1" name="applicable" value="1" class="non-vac" <?php  echo($paperpath!=NULL)?'checked':'' ?>>Yes
						<br>
						<input type="radio" name="applicable" value="2" class="vac" <?php echo ($paperpath=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
						<input type="radio" name="applicable" value="3" class="vac" <?php echo ($paperpath=='not_applicable')?'checked':'' ?>> No
					</div> 
					<br>
					<div class='second-reveal' id='f1'>
						 <div >
							 
                    	     <label for="card-image">Permission Letter </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="paper">
		        	          <a <?php 
		        	          $f=0;
		        	          if($paperpath!="not_applicable" && $paperpath!="NULL" && $paperpath!="no status" && $paperpath!=""){
		        	          	echo "href='$paperpath'";
		        	          	$f=1;
		        	          }else{
		        	          	echo "style='display:none' ";
		        	          }
		        	          ?> target="_blank"><h4><?php if($f==1){echo "View Existing permission letter";} ?><h4></a>
	        	        </div> 
					</div>


					<div>
                         <label for="Index">Certificate :  </label><br/>
						  <input type="radio" name="applicable1" id="r2" value="1" class="non-vac1" <?php  echo($certipath!=NULL)?'checked':'' ?>>Yes<br>
							<input type="radio" name="applicable1" value="2" class="vac1" <?php echo ($certipath=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
						<input type="radio" name="applicable1" value="3" class="vac1" <?php echo ($certipath=='not_applicable')?'checked':'' ?>> No
						 
					</div> 
					<br>
					<div class='second-reveal1'id='f2'>
						 <div >
							 
                    	     <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="certificate">
		        	         <a <?php 
		        	          $f1=0;
		        	          if($certipath!="not_applicable" && $certipath!="NULL" && $certipath!='no status' && $certipath!=""){
		        	          	echo "href='$certipath'";
		        	          	$f1=1;
		        	          }else{
		        	          	echo "style='display:none' ";
		        	          }
		        	          ?> target="_blank"><h4><?php if($f1==1){echo "View Existing Certificate";} ?><h4></a>
	        	        </div> 
					</div>
					
					<div>
                         <label for="Index">Report : </label><br/>
						  <input type="radio" name="applicable2" id="r3" value="1" class="non-vac2" <?php  echo($reportpath!=NULL)?'checked':'' ?>>Yes<br>
							<input type="radio" name="applicable2" value="2" class="vac2" <?php echo ($reportpath=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
						<input type="radio" name="applicable2" value="3" class="vac2" <?php echo ($reportpath=='not_applicable')?'checked':'' ?>> No
					</div> 
					<br>
					<div class='second-reveal2' id='f3'>
						 <div >
                    	     <label for="card-image">Report </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="report">
		        	         <a <?php 
		        	          $f2=0;
		        	          if($reportpath!="not_applicable" && $reportpath!="NULL" && $reportpath!="" && $reportpath!="no status"){
		        	          	echo "href='$reportpath'";
		        	          	$f2=1;
		        	          }else{
		        	          	echo "style='display:none' ";
		        	          }
		        	          ?> target="_blank"><h4><?php if($f2==1){echo "View Existing Report";} ?><h4></a>
	        	        </div> 
					</div>
				</div>
					<script>
					 
					 window.onload = function() {
						mycheck1();
						mycheck2();
						mycheck3();
					 }

					function mycheck1(){
						var radio1 = document.getElementById("r1");
						var file1 = document.getElementById("f1");
						if(radio1.checked==true){
							file1.style.display = "block";
						}else{
							file1.style.display= "none";
						}
					}
					function mycheck2(){
						var radio2 = document.getElementById("r2");
						var file2 = document.getElementById("f2");
						if(radio2.checked==true){
							file2.style.display = "block";
						}else{
							file2.style.display= "none";
						}
					}
					function mycheck3(){
						var radio3 = document.getElementById("r3");
						var file3 = document.getElementById("f3");
						if(radio3.checked==true){
							file3.style.display = "block";
						}else{
							file3.style.display= "none";
						}
					}

					 </script>
                    

                    <div class="form-group col-md-12">
					<?php
						if($_SESSION['type'] == 'hod')
						{
						   echo '<a href="2_dashboard_hod_anyother.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
						}
						else
						{
							echo '<a href="2_dashboard_anyother.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
						}
                    ?>						
                       <button name="update"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>

                    </div>
                </form>
                
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
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
    
    
<?php include_once('footer.php'); ?>
