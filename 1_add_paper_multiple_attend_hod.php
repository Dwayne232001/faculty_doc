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
$_SESSION['currentTab'] = "sttp";

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


//setting error variables
$nameError="";
$emailError="";
$Act_title = $startDate = $endDate = $Act_type =  $location = $organized_by = $status_activities=$awards=$certificate1_path=$permissionpath=$reportpath="";
$flag = 0;
$success = 0;
$s = 1;
$error1 = $error2 = $error3 = "";
$no_of_hours=0;
		$fid = $_SESSION['Fac_ID'];
        $faculty_name= $_SESSION['loggedInUser'];

$query="SELECT * from faculty where Fac_ID = $fid ";
    $result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
		
	}
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    //the form was submitted
    $fname_array = $_POST['fname'];
	$Act_title_array = $_POST['Act_Title'];
	$Act_type_array = $_POST['Act_type'];
	$organized_by_array = $_POST['organized_by'];
	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$location_array = $_POST['location'];
	$applicablefdc_array = $_POST['applicablefdc'];
	
	$fdc_array = $_POST['fdc'];
	$status_activities_array=$_POST['status_activities'];
    $no_of_hours_array=$_POST['no_of_hours'];
    $awards_array=$_POST['awards'];
	

	/*	$min_no_array=$_POST['min_no'];
		$serial_no_array=$_POST['serial_no'];
				$period_array = $_POST['period'];

		$od_approv_array=$_POST['od_approv'];
		$od_avail_array=$_POST['od_avail'];
		$fee_sac_array=$_POST['fee_sac'];
		$fee_avail_array=$_POST['fee_avail'];*/
	
	
    //check for any blank input which are required
    		

for($i=0; $i<1;$i++)
{
    $fname = mysqli_real_escape_string($conn,$fname_array[$i]);
    $_SESSION['F_NAME'] = $fname ;
	$Act_title = mysqli_real_escape_string($conn,$Act_title_array[$i]);
	$Act_type = mysqli_real_escape_string($conn,$Act_type_array[$i]);
	$organized_by = mysqli_real_escape_string($conn,$organized_by_array[$i]);
	$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
	$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
	$location = mysqli_real_escape_string($conn,$location_array[$i]);
    $applicablefdc = mysqli_real_escape_string($conn,$applicablefdc_array[$i]);

    $time=time();
    $start = new DateTime(date($startDate,$time));
    $end = new DateTime(date($endDate,$time));
    $days = date_diff($start,$end);
    $noofdays = $days->format('%d');

	$fdc = mysqli_real_escape_string($conn,$fdc_array[$i]);
	$_SESSION['fdc'] = $fdc;

	$status_activities=mysqli_real_escape_string($conn,$status_activities_array[$i]);
	$no_of_hours=mysqli_real_escape_string($conn,$no_of_hours_array[$i]);
    $awards=mysqli_real_escape_string($conn,$awards_array[$i]);
 
  if(empty($_POST['Act_title[]'])){
        $nameError="Please enter a Title";
		$flag = 0;
    }
    else{
        $Act_title=validateFormData($Act_title);
        $Act_title = "'".$Act_title."'";
		$flag=1;
    }
	if(empty($_POST['organized_by[]'])){
        $nameError="Please enter a Title";
		$flag = 0;
    }
    else{
        $organized_by=validateFormData($organized_by);
        $organized_by = "'".$organized_by."'";
		$flag=1;
    }
	if(empty($_POST['Act_type[]'])){
        $nameError="Please enter a Type";
		$flag = 0;
    }
    else{
        $Act_type=validateFormData($Act_type);
        $Act_type = "$Act_type";
		$flag=1;
    }
		
		
    if(empty($_POST['startDate[]'])){
        $nameError="Please enter a start date";
		$flag = 0;
    }
    else{
        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
		$flag=1;
    }
	
	 if(empty($_POST['endDate[]'])){
        $nameError="Please enter end date";
		$flag = 0;
    }
    else{
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
		$flag=1;
    }
	 if(empty($_POST['location[]'])){
        $nameError="Please enter location";
    }
    else{
        $location=validateFormData($location);
        $location = "'".$location."'";
    }
    if(empty($_POST['status_activities[]'])){
        $nameError="Please enter status activity";
        $flag=0;
    }
    else{
        $status_activities=validateFormData($status_activities);
        $status_activities = "'".$status_activities."'";
        $flag=1;
    }
    if(empty($_POST['no_of_hours[]'])){
        $nameError="Please enter number of hours";
        $flag=0;
    }
    else{
        $no_of_hours=validateFormData($no_of_hours);
        $no_of_hours = "'".$no_of_hours."'";
        $flag=1;
    }
    if(empty($_POST['awards[]'])){
        $nameError="Please enter award details";
        $flag=0;
    }
    else{
        $awards=validateFormData($awards);
        $awards = "'".$awards."'";
        $flag=1;
    }
	
	if($applicablefdc == 'Yes')
	{
		$fdc=validateFormData($_POST["fdc"]);
		$fdc = 'Yes';		}
	else if($applicablefdc == 'No')
	{
		$fdc = 'Not applicable';
	}    
	  	$fname=validateFormData($fname);
    	$fname = "'".$fname."'";

	  //checking if there was an error or not
        $query = "SELECT Fac_ID from facultydetails where F_NAME= $fname";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
if(isset($_POST['applicable1']))
    {
        if($_POST['applicable1'] == 2)
        {
            $permissionpath='NULL';      
            $success=1;      
        }
        else if($_POST['applicable1'] == 3)
        {
            $permissionpath='not_applicable';
            $success=1;     
        }
        else if($_POST['applicable1'] == 1)
        {
            if(isset($_FILES['permission'])  && $_FILES['permission']['name'] != NULL && $_FILES['permission']['name'] !="")
            {
                $errors= array();
                $fileName = $_FILES['permission']['name'];
                $fileSize = $_FILES['permission']['size'];
                $fileTmp = $_FILES['permission']['tmp_name'];
                $fileType = $_FILES['permission']['type'];
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
                        $permissionpath=$targetName;
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
                $error1 = "No file selected";
            }
        }
    }
    if(isset($_POST['applicable3']))
    {
        if($_POST['applicable3'] == 2)
        {
            $reportpath='NULL';
            $success=1;                      
        }
        else if($_POST['applicable3'] == 3)
        {
            $reportpath='not_applicable';
            $success=1;
        }
        else if($_POST['applicable3'] == 1)
        {
            if(isset($_FILES['report'])  && $_FILES['report']['name'] != NULL && $_FILES['report']['name'] !="")
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
            }
            else{
                $s = 0;
                $error3 = "No file selected";
            }
        }
    }
    if(isset($_POST['applicable2']))
    {
        if($_POST['applicable2'] == 2)
        {
            $certificate1_path='NULL';
            $success=1;                      
        }
        else if($_POST['applicable2'] == 3)
        {
            $certificate1_path='not_applicable';
            $success=1;
        }
        else if($_POST['applicable2'] == 1)
        {
            if(isset($_FILES['certificate'])  && $_FILES['certificate']['name'] != NULL && $_FILES['certificate']['name'] !="")
            {
                $errors= array();
                $fileName = $_FILES['certificate']['name'];
                $fileSize = $_FILES['certificate']['size'];
                $fileTmp = $_FILES['certificate']['tmp_name'];
                $fileType = $_FILES['certificate']['type'];
                $temp=explode('.',$fileName);
                $fileExt=strtolower(end($temp));
                date_default_timezone_set('Asia/Kolkata');
                $targetName=$datapath."certificates/".$_SESSION['F_NAME']."_certificates_".date("d-m-Y H-i-s", time()).".".$fileExt;  
                if(empty($errors)==true) {
                    if (file_exists($targetName)) {   
                        unlink($targetName);
                    }      
                    $moved = move_uploaded_file($fileTmp,"$targetName");
                    if($moved == true){
                        $certificate1_path=$targetName;
                        $success=1;     
                    }
                    else{
                        echo "<h1> $targetName </h1>";
                    }
                }else{
                    print_r($errors);
                }
            }
            else{
                $s = 0;
                $error2 = "No file selected";
            }
        }
    }          
    if($awards==""){
        $awards="NA";
    }       
    if($s!= 0){

        $sql="INSERT INTO attended(Fac_ID,Act_title,Act_type,Organized_by,Date_from,Date_to, Location,FDC_Y_N,Status_Of_Activity,Equivalent_Duration,Awards,Permission_path,Report_path,Certificate_Path,noofdays) VALUES ('$author','$Act_title','$Act_type','$organized_by','$startDate','$endDate','$location','$fdc','$status_activities','$no_of_hours','$awards','".$permissionpath."','".$reportpath."','".$certificate1_path."',$noofdays)";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
                header("location:2_dashboard_attend_hod.php?alert=success");

			} else {
				header("location:2_dashboard_attend_hod.php?alert=error");
            }
        }
}//end of for
			
}
}

//close the connection
mysqli_close($conn);
?>





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
.errorshow{
    color: red;
    border: None;
    background-color: #ebcbd2;
    border-radius: 10px;
    margin: 5px;
    padding: 0px;
    font-family: Arial, Helvetica, sans-serif;
    width: 410px;
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
                <br /><br /><br />

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="icon">
                            <i style="font-size:20px" class="fa fa-edit"></i>
                            <h3 class="box-title"><b>STTP/WS/FDP/QIP/TR/S/IN Attended Form</b></h3>
                            <br>
                        </div>
                    </div><!-- /.box-header -->
                    <div style="text-align:right">
                    </div>
                    <br>
                    <!-- form start -->

                    <?php

                    for ($k = 1; $k <= 1; $k++) {

                    ?>
                        <form role="form" method="POST" class="row" action="" style="margin:10px;align:center" enctype="multipart/form-data">
                            <div class="form-group col-md-6">
                                <label for="department_name">Department Name</label>
                                <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $_SESSION['Dept']; ?>" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="faculty-name">Faculty Name</label>
                                <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="select_the_activity">Select the Activity</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <select required name="Act_type[]" id="Act_type" class="form-control input-lg">
                                    <option value="" disabled selected>Select your option:</option>
                                    <option name="short_term_training" value="STTP">Short Term Training Programme(STTP)</option>
                                    <option name="faculty_development" value="FDP">Faculty Development Programme(FDP)</option>
                                    <option name="workshop" value="WS">Workshop(WS)</option>
                                    <option name="training" value="TR">Training(TR)</option>
                                    <option name="seminar" value="S">Seminar(S)</option>
                                    <option name="internship" value="IN">Internship(IN)</option>
                                    <option name="expert_lecture" value="EL">Expert Lecture(EL)</option>
                                    <option name="other" value="other">Other(O)</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="paper-title">Name of the Activity</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="Act_title[]">-->
                                <input type="text" class="form-control input-lg" name="Act_title[]" id="paper-title" required value='<?php if (isset($_POST['Act_title'])) echo $Act_title; ?>'>
                            </div>




                            <!-- <div class="form-group col-md-6">
                         <label for="paper-title">Title *</label>
                      <input required type="text" class="form-control input-lg" id="paper-title" name="Act_title[]">
					  <input  type="text" class="form-control input-lg"  name="Act_title[]" id="paper-title" required value = '<?php if (isset($_POST['Act_title'])) echo $Act_title; ?>'>
                     </div> -->

                            <!-- <div class="form-group col-md-6">
					 
                        <label for="paper-type">Activity Type *</label>
						<select id="paper-type" required name="Act_type[]" class="form-control input-lg" value = '<?php if (isset($_POST['Act_type'])) echo $Act_type; ?>'>
                            <option value="" disabled selected>Select your option</option>
                            <option name="STTP" value="STTP">STTP</option>
                            <option name="Workshop" value="Workshop">Workshop</option>
                            <option name="FDP" value="FDP">FDP</option>
                            <option name="QIP" value="QIP">QIP</option>
                            <option name="SEMINAR" value="SEMINAR">SEMINAR</option>
                            <option name="WEBINAR" value="WEBINAR">WEBINAR</option>
                            <option name="REFRESHER" value="OTHER">OTHER</option>
                        </select>
                     </div> -->

                            <!-- <div class="form-group col-md-6 col-md-offset-1"></div> -->

                            <div class="form-group col-md-6">
                                <label for="start-date">Start Date</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input required type="date" class="form-control input-lg" id="start-date" name="startDate[]" placeholder="1999-12-31" value='<?php if (isset($_POST['startDate'])) echo $startDate; ?>'>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="end-date">End Date</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input required type="date" class="form-control input-lg" id="end-date" name="endDate[]" placeholder="1999-12-31" value='<?php if (isset($_POST['endDate'])) echo $endDate; ?>'>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="paper-title">Name of the Organising Institute/Industry</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="Act_title[]">-->
                                <input type="text" class="form-control input-lg" name="Org_under[]" id="Org_under" required value='<?php if (isset($_POST['Org_under'])) echo $Act_title; ?>'>
                            </div>


                            <br>
                            <!-- <div class="form-group col-md-6">
                                <label for="days/weeks/hours">Days/Weeks/Hours </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <select required name="duration[]" id="duration" class="form-control input-lg">
                                    <option value="" disabled selected>Select your option:</option>
                                    <option name="hours" value="hours">Hours</option>
                                    <option name="days" value="days">Days</option>
                                    <option name="weeks" value="weeks">Weeks</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="no_of_hours">Equivalent duration </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input class="form-control input-lg" type="text" name="no_of_hours[]" id="no_of_hours" placeholder="" value="">
                            </div> -->

                            <!-- <div class="form-group col-md-6">
                         <label for="resource">Name of Resource person *</label>
                         <input type="text" required class="form-control input-lg" id="resource" name="resource[]"  <?php if (isset($_POST['resource'])) echo "value = $resource"; ?>>
                     </div>

                     <div class="form-group col-md-6">
                         <label for="resource">Resource person Organization Name *</label>
                         <input type="text" required class="form-control input-lg" id="resource" name="resource[]"  <?php if (isset($_POST['resource'])) echo "value = $resource"; ?>>
                     </div>

                    <div class="form-group col-md-6">
                        <label for="organized_by">Organized by (With Brief Address) *</label>
                        <input type="text" required class="form-control input-lg" id="organized_by" name="organized_by[]" value = '<?php if (isset($_POST['organized_by'])) echo $organized_by; ?>'>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="organized_under">Organized Under *</label>
                        <input type="text" required class="form-control input-lg" id="organized_by" name="organized_by[]" value = '<?php if (isset($_POST['organized_by'])) echo $organized_by; ?>'>
                    </div>

                    <div class="form-group col-md-6">
                         <label for="venue">Venue *</label>
                         <input required type="text" class="form-control input-lg" id="location" name="location[]" value = '<?php if (isset($_POST['location'])) echo $location; ?>'>
                     </div>

                     <div class="form-group col-md-6">
                         <label for="target_audience">Target Audience *</label>
                         <input required type="text" class="form-control input-lg" id="location" name="location[]" value = '<?php if (isset($_POST['location'])) echo $location; ?>'>
                     </div> -->

                            <div class="form-group col-md-6">
                                <label for="category_of_program_organised">Category of Program Organised </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <select required name="category[]" id="category" class="form-control input-lg">
                                    <option value="" disabled selected>Select your option:</option>
                                    <option name="technical" value="technical">Technical</option>
                                    <option name="research" value="research">Research</option>
                                    <option name="project_innovation_based" value="project_innovation_based">Project and Innovation Based</option>
                                    <option name="entrepreneurship" value="entrepreneurship">Entrepreneurship</option>
                                    <option name="life_skills" value="life_skills">Life Skills</option>
                                    <option name="yoga_and_stress_management" value="yoga_and_stress_management">Yoga and Stress Management</option>
                                    <option name="other" value="other">Other</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="level">Level of Activity </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <select required name="level[]" id="level" class="form-control input-lg">
                                    <option value="" disabled selected>Select your option:</option>
                                    <option name="college" value="college">College</option>
                                    <option name="national" value="national">National</option>
                                    <option name="international" value="international">International</option>
                                </select>
                            </div>


                            <!-- New added elements -->
                            <!-- <br>

                        <div class="form-group col-md-6">
                        <label for="awards">Awards (If Any)</label>
                        <br>
                        <input type="text" class="form-control input-lg" name="awards[]" id="awards" value = '<?php if (isset($_POST['awards'])) echo $awards; ?>'>
                        <input class="form-control input-lg" type="text" name="awards[]" id="awards" value="No awards">
                        </div>
						

                        <div class="form-group col-md-6">
                        <label for="description_of_activity">Description of the Activity: *</label>
                            <textarea name="comment" rows="5" cols="55"></textarea>
                        </div> -->
                            <!-- <div class="form-group col-md-6">
                         <label for="applicable-fdc">Is FDC applicable? </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg applicable-fdc" id="applicable-fdc" name="applicablefdc[]" value = '<?php if (isset($_POST['applicablefdc'])) echo $applicablefdc; ?>'>
                             <option value ="No">No</option>
                             <option value ="Yes">Yes</option>
                         </select>
                     </div> -->

                            <div class="form-group col-md-6 col-md-offset-1"></div>

                            <div class="form-group col-md-6">
                                <!-- <div>
                        &nbsp;<label for="course">Upload Permission Letter : Applicable ?<br></label>
                        <span class="colour" style = "color : red"><b> *</b></span>
                        <span class="errorshow" style = "border : none;"> <?php echo $error1 ?> </span>	
                        <br>&nbsp;<input required type='radio' name='applicable1' class='non-vac' value='1' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "1") ? 'checked' : '' ?>> Yes <br>
                        &nbsp;<input type='radio' name='applicable1' class='vac' value='2' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>            
                        &nbsp;<input type='radio' name='applicable1' class='vac' value='3' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "3") ? 'checked' : '' ?>> No <br>
                    </div> -->
                                <br>
                                <div class='second-reveal' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "1") ? 'style = "display : block" ' : '' ?>>
                                    <div>
                                        <label for="card-image">Permission Letter</label><span class="colour"><b> *</b></span>
                                        <input type="file" class="form-control input-lg" id="card-image" name="permission">
                                    </div>
                                </div>

                                <div>
                                    &nbsp;<label for="course">Upload certificate : Applicable ?<br></label>
                                    <span class="colour" style="color : red"><b> *</b></span>
                                    <span class="errorshow" style="border : none;"> <?php echo $error2 ?> </span>
                                    <br> &nbsp;<input required type='radio' name='applicable2' class='non-vac1' value='1' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "1") ? 'checked' : '' ?>> Yes <br>
                                    &nbsp;<input type='radio' name='applicable2' class='vac1' value='2' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>
                                    &nbsp;<input type='radio' name='applicable2' class='vac1' value='3' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "3") ? 'checked' : '' ?>> No <br>
                                </div>
                                <br>
                                <div class='second-reveal1' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "1") ? 'style = "display : block" ' : '' ?>>
                                    <div>
                                        <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
                                        <input type="file" class="form-control input-lg" id="card-image" name="certificate">
                                    </div>
                                </div>

                                <div>
                                    &nbsp;<label for="course">Upload report : Applicable ?<br></label>
                                    <span class="colour" style="color : red"><b> *</b></span>
                                    <span class="errorshow" style="border : none;"> <?php echo $error3 ?> </span>
                                    <br>&nbsp;<input required type='radio' name='applicable3' class='non-vac2' value='1' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "1") ? 'checked' : '' ?>> Yes <br>
                                    &nbsp;<input type='radio' name='applicable3' class='vac2' value='2' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>
                                    &nbsp;<input type='radio' name='applicable3' class='vac2' value='3' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "3") ? 'checked' : '' ?>> No <br>
                                </div>
                                <br>
                                <div class='second-reveal2' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "1") ? 'style = "display : block" ' : '' ?>>
                                    <div>
                                        <label for="card-image">Report </label><span class="colour"><b> *</b></span>
                                        <input type="file" class="form-control input-lg" id="card-image" name="report">
                                    </div>
                                </div>
                            </div>

                        <?php
                    }
                        ?>
                        <br />
                        <div class="form-group col-md-12">
                            <button name="add" type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                            <a href="list_of_activities_user.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
                        </div>
                        </form>
                </div>
            </div>
        </div>
    </section>


</div>
    
<?php include_once('footer.php'); ?>
   
   