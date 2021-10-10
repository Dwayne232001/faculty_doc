
<?php
ob_start();
session_start();
include_once('head.php'); 
 include_once('header.php'); 
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod'){
    //if not hod then send the user to login page
		session_destroy();
		header("location:index.php");
	}
}

$_SESSION['currentTab']="co";

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");
include_once("includes/config.php");
 
$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}


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
//setting error variables
$nameError="";
$emailError="";
$activity_name = $startDate = $endDate = $purpose_of_activity  = $organized_by = $paperpath=$certipath=$reportpath= "";
$flag= 1;
$success = 0;
$s = 1;
$error1 = $error2 = $error3 = "";
		//$fid = $_SESSION['Fac_ID'];
	
        $faculty_name= $_SESSION['loggedInUser'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    $activityname_array = $_POST['activityname'];
	$organizedby_array=$_POST['organizedby'];
    $purposeofactivity_array=$_POST['purposeofactivity'];
    $startDate_array = $_POST['startdate'];
	$endDate_array = $_POST['enddate'];
	$facid_array = $_POST['fname'];
	
for($i=0; $i<1;$i++)
{
$activityname = mysqli_real_escape_string($conn,$activityname_array[$i]);
$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$organizedby = mysqli_real_escape_string($conn,$organizedby_array[$i]);
$purposeofactivity = mysqli_real_escape_string($conn,$purposeofactivity_array[$i]);
$facid = mysqli_real_escape_string($conn,$facid_array[$i]);
$_SESSION['F_NAME'] = $facid;

$time=time();
$start = new DateTime(date($startDate,$time));
$end = new DateTime(date($endDate,$time));
$days = date_diff($start,$end);
$noofdays = $days->format('%d');

        $activityname=validateFormData($activityname);
        $activityname = "'".$activityname."'";
		
        $organizedby=validateFormData($organizedby);
        $organizedby = "'".$organizedby."'";
		
        $purposeofactivity=validateFormData($purposeofactivity);
        $purposeofactivity = "'".$purposeofactivity."'";
	
    	

        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
		
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
			if ($startDate > $endDate)		
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}
		
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
					if(isset($_FILES['paper']) && $_FILES['paper']['name'] != NULL && $_FILES['paper']['name'] !="")
					{
					  $errors= array();
					  $fileName = $_FILES['paper']['name'];
					  $fileSize = $_FILES['paper']['size'];
					  $fileTmp = $_FILES['paper']['tmp_name'];
					  $fileType = $_FILES['paper']['type'];
					  $temp=explode('.',$fileName);
					  $fileExt=strtolower(end($temp));
					  date_default_timezone_set('Asia/Kolkata');
					  $targetName=$datapath."permissions/".$_SESSION['F_NAME']."_permissions_".date("d-m-Y H-i-s", time()).".".$fileExt;  
					  
					  if(empty($errors)==true) {
						if (file_exists($targetName)) {   
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
					}else{
                        $s = 0;
                        $error1 = "No file selected";
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
					if(isset($_FILES['certificate']) && $_FILES['certificate']['name'] != NULL && $_FILES['certificate']['name'] !="")
					{
					  $errors= array();
					  $fileName = $_FILES['certificate']['name'];
					  $fileSize = $_FILES['certificate']['size'];
					  $fileTmp = $_FILES['certificate']['tmp_name'];
					  $fileType = $_FILES['certificate']['type'];
					  $temp=explode('.',$fileName);
					  $fileExt=strtolower(end($temp));					  date_default_timezone_set('Asia/Kolkata');
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
					}else{
                        $s = 0;
                        $error2 = "No file selected";
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
					if(isset($_FILES['report']) && $_FILES['report']['name'] != NULL && $_FILES['report']['name'] !="")
					{
					  $errors= array();
					  $fileName = $_FILES['report']['name'];
					  $fileSize = $_FILES['report']['size'];
					  $fileTmp = $_FILES['report']['tmp_name'];
					  $fileType = $_FILES['report']['type'];
					  $temp=explode('.',$fileName);
					  $fileExt=strtolower(end($temp));
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
					}else{
                        $s = 0;
                        $error3 = "No file selected";
                    }
				}
			}
	
		$query = "SELECT Fac_ID from facultydetails where F_NAME = '$facid'";
        $result=mysqli_query($conn,$query);
		//echo "<script>alert('$result')</script>";
       if($result){
		   //echo "<script>alert('$author')</script>";
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }

		if($flag!=0 && $s!=0)
		{
		
        $sql="INSERT INTO co_curricular(Fac_ID,activity_name,Date_from,Date_to,purpose_of_activity,organized_by,permission_path,certificate_path,report_path,noofdays) VALUES ('$author',$activityname,$startDate,$endDate,$purposeofactivity,$organizedby,'".$paperpath."','".$certipath."','".$reportpath."',$noofdays)";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
				header("location:2_dashboard_hod_cocurricular.php?alert=success");
			} else {
				header("location:2_dashboard_hod_cocurricular.php?alert=error");
			}	
		} 
}//end of for
			
}
//close the connection
mysqli_close($conn);
}
?>

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
#form {
	width: 100% !important;
}
</style>

<div class="content-wrapper">
    
    <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-8" id="form">
              <!-- general form elements -->
			  <br/><br/><br/>
            
			<div class="box box-primary">
                <div class="box-header with-border">
				  <div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Extra-Curricular Activity Activities Form</b></h3>
					<br>
				</div>
                </div><!-- /.box-header -->
                <!-- form start -->
	
	<?php
			
					for($k=0; $k<1 ; $k++)
					{

				?>

			<form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data">
					
            <?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
			?>	
				<div class="form-group col-md-6">

                         <label for="faculty-name">Faculty Name</label>
                         <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="activity-name">Activity Name</label><span class="colour"><b> *</b></span>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input  type="text" class="form-control input-lg"  name="activityname[]" value = '<?php if(isset($_POST['activityname'])) echo $activityname; ?>'>
                     </div>
                     
                     
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date</label><span class="colour"><b> *</b></span>
                         <input required type="date" class="form-control input-lg" id="start-date" name="startdate[]"
                         value = '<?php if(isset($_POST['startdate'])) echo $startDate; ?>' placeholder="03:10:10">
                     </div><br><br>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date</label><span class="colour"><b> *</b></span>
                         <input required type="date" class="form-control input-lg" id="end-date" name="enddate[]"
                         value = '<?php if(isset($_POST['enddate'])) echo $endDate; ?>'placeholder="03:10:10">
                     </div><br><br>
                    
                    <div class="form-group col-md-6">
                         <label for="organized-by">Organized By</label><span class="colour"><b> *</b></span>
                         <input  type="text" class="form-control input-lg"  name="organizedby[]" value = '<?php if(isset($_POST['organizedby'])) echo $organizedby; ?>'>
                     </div><br><br>
					 
					 <div class="form-group col-md-6">
                         <label for="purpose-of-activity">Purpose of Activity</label><span class="colour"><b> *</b></span>
                         <input  type="text" class="form-control input-lg"  name="purposeofactivity[]" value = '<?php if(isset($_POST['purposeofactivity'])) echo $purposeofactivity; ?>'>
                     </div><br>

        <div class="form-group col-md-6">
                    <div>
                        &nbsp;<label for="course"> Upload Permission Record : Applicable ?<br></label><span class="colour"><b> *</b></span>
                        <span class="error" style = "border : none;"> <?php echo $error3 ?> </span>
                        <br>&nbsp;<input type='radio' name='applicable' class='non-vac' value='1' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'checked':'' ?>> Yes <br>
                        &nbsp;<input type='radio' name='applicable' class='vac' value='2' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="2")?'checked':'' ?>>Applicable, but not yet available <br>
                        &nbsp;<input type='radio' name='applicable' class='vac' value='3' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="3")?'checked':'' ?> > No <br>
                    </div>
                    <br>
                    <div class='second-reveal'>
                        <div> 
                            <label for="card-image">Permission Record </label><span class="colour"><b> *</b></span>
                            <input  type="file"   class="form-control input-lg" id="card-image" name="permission">
                        </div> 
                    </div>

                    <div>
                        &nbsp;<label for="course"> Certificate copy : Applicable ?<br></label><span class="colour"><b> *</b></span>
                        <span class="error" style = "border : none;"> <?php echo $error1 ?> </span>
                        <br>    &nbsp;<input type='radio' name='applicable1' class='non-vac1' value='1' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'checked':'' ?>	> Yes <br>
                        &nbsp;<input type='radio' name='applicable1' class='vac1' value='2' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="2")?'checked':'' ?>	> Applicable, but not yet available <br>             
                        &nbsp;<input type='radio' name='applicable1' class='vac1' value='3' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="3")?'checked':'' ?>	> No <br>
                    </div>
                    <br>
                    <div class='second-reveal1'>
                        <div>
                            <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
                            <input  type="file"   class="form-control input-lg" id="card-image" name="certificate">
                        </div> 
                    </div>
                
                    <div>
                        &nbsp;<label for="course"> Report copy : Applicable ?<br></label><span class="colour"><b> *</b></span>
                        <span class="error" style = "border : none;"> <?php echo $error2 ?> </span>
                        <br>&nbsp;<input type='radio' name='applicable2' class='non-vac2' value='1' <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="1")?'checked':'' ?> > Yes <br>
                        &nbsp;<input type='radio' name='applicable2' class='vac2' value='2'<?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="2")?'checked':'' ?>>Applicable, but not yet available <br>
                        &nbsp;<input type='radio' name='applicable2' class='vac2' value='3'<?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="3")?'checked':'' ?> > No <br>
                    </div>
                    <br>

                    <div class='second-reveal2'>
                        <div >
                            <label for="card-image">Report </label><span class="colour"><b> *</b></span>
                            <input  type="file"   class="form-control input-lg" id="card-image" name="report">
                        </div> 
                    </div>
                </div>
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                         

                         <button name="add"  type="submit" class="btn pull-right btn-success btn-lg">Submit</button>
                         <a href="list_of_activities_user.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   