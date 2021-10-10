<?php
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the user to login page
    header("location:index.php");
}
//connect to database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/config.php");

$_SESSION['currentTab']="Online";
//setting error variables
$nameError="";
$flag = 1;
$emailError="";
$courseName = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coAuthors = "";

$Fac_ID=null;
date_default_timezone_set("Asia/Kolkata");
if(isset($_POST['rid'])){
    $id = $_POST['rid'];
    $_SESSION['id']=$_POST['rid'];
}
    $id = $_SESSION['id'];
    $query = "SELECT * from online_course_organised where OC_O_ID = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    //print_r($row);
    $Fac_ID = $row['Fac_ID'];
    $type_of_course_db = $row['type_of_course'];
    $courseName = $row['Course_Name'];
    $startDate = $row['Date_From'];
    $endDate = $row['Date_To'];
    $organised = $row['Organised_By'];
    $purpose = $row['Purpose'];
    $target_audience = $row['Target_Audience'];
    $faculty_role = $row['faculty_role'];
    $full_part_time_db = $row['full_part_time'];
    $no_of_part = $row['no_of_part'];
    $duration = $row['duration'];
    $status_db = $row['status'];
    $sponsored = $row['sponsored'];
    $sponsor = $row['name_of_sponsor'];
    $is_approved = $row['is_approved'];
    $paperpath=$row['attendence_path'];
    $certipath=$row['certificate_path'];
    $reportpath=$row['report_path'];		
			$query2 = "SELECT * from facultydetails where Fac_ID = $Fac_ID";
			$result2 = mysqli_query($conn,$query2);
			if($result2)
			{
	            $row = mysqli_fetch_assoc($result2);
				$F_NAME = $row['F_NAME'];
            }
            $_SESSION['F_NAME'] =$F_NAME ;
	   
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if(isset($_POST['update'])){
    //the form was submitted
    $clientName=$clientEmail=$clientPhone=$clientAddress=$clientCompany=$clientNotes="";
    
    //check for any blank input which are required
    
   $type_of_course=validateFormData($_POST['type_of_course']);
        $type_of_course = "'".$type_of_course."'";  
		
        $courseName=validateFormData($_POST['courseName']);
        $courseName = "'".$courseName."'";
		
		if ((strtotime($_POST['startDate'])) > (strtotime($_POST['endDate'])))
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}	
   
        $startDate=validateFormData($_POST['startDate']);
        $endDate=validateFormData($_POST['endDate']);

        $time=time();
        $start = new DateTime(date($startDate,$time));
        $end = new DateTime(date($endDate,$time));
        $days = date_diff($start,$end);
        $noofdays = $days->format('%d');
        
        $startDate = "'".$startDate."'";
        $endDate = "'".$endDate."'";

        $organised=validateFormData($_POST['organised']);
        $organised = "'".$organised."'";
    
        $target_audience=validateFormData($_POST['target_audience']);
        $target_audience = "'".$target_audience."'";
    
        $role=validateFormData($_POST['role']);
        $role = "'".$role."'";
    
        if(isset($_POST['type']))
		{
			$type=validateFormData($_POST['type']);
			$type = "'".$type."'";
		}
		else
		{
			 $type = '';
			 $type = "'".$type."'";
		}
		
        $participants=validateFormData($_POST['participants']);
        $participants = "'".$participants."'";
    
        $duration=validateFormData($_POST['duration']);
        $duration = "'".$duration."'";
    
        $status=validateFormData($_POST['status']);
        $status = "'".$status."'";
   
  
        $sponsor=validateFormData($_POST['sponsor']);
        $sponsor = "'".$sponsor."'";
   
        $isApproved=validateFormData($_POST['isApproved']);
        $isApproved = "'".$isApproved."'";
	
    
    $purpose=validateFormData($_POST["purpose"]);
    $purpose = "'".$purpose."'";	

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
                $targetName=$datapath."attendance/".$_SESSION['F_NAME']."_attendance_".date("d-m-Y H-i-s", time()).".".$fileExt;  
                      
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
		
    
    //checking if there was an error or not
  $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
				$succ = 0;
				$success1 = 0;

if($flag!= 0)
{				
	if(isset($_POST['sponsored'])){
		$udate = date("Y-m-d h:i:sa");

		$sponsored=$_POST['sponsored'];
		if($sponsored == 'n'){
			$name_of_sponsor="NA";
			$isApproved="NA";
			$sql = "update online_course_organised set 
							   type_of_course = $type_of_course,
							   Course_Name = $courseName,
							   Date_from = $startDate,
							   Date_to = $endDate, 
							   Organised_by = $organised,
							   Purpose =$purpose,
                               Target_Audience = $target_audience,
                               faculty_role=$role,
                               full_part_time=$type,
                               no_of_part=$participants,
                               duration=$duration,
                               status=$status,
                               name_of_sponsor='$name_of_sponsor',
                               is_approved='$isApproved',
                               attendence_path = '".$paperpath."',
                               certificate_path ='".$certipath."',
                               report_path = '".$reportpath."',
                               sponsored='$sponsored',
                               noofdays = $noofdays
							   WHERE OC_O_ID = '".$_SESSION['id']."'";	
		}else{
			$sql = "update online_course_organised set Course_Name = $courseName,
							   Date_from = $startDate,
							   Date_to = $endDate, 
							   Organised_by = $organised,
							   Purpose =$purpose,
                               Target_Audience = $target_audience,
                               faculty_role=$role,
                               full_part_time=$type,
                               no_of_part=$participants,
                               duration=$duration,
                               status=$status,
                               name_of_sponsor=$sponsor,
                               is_approved=$isApproved,
								sponsored='$sponsored',
                                attendence_path = '".$paperpath."',
                               certificate_path ='".$certipath."',
                               report_path = '".$reportpath."',
                               noofdays = $noofdays							   
							   WHERE OC_O_ID = '".$_SESSION['id']."' ";
		}
	}
	        if ($conn->query($sql) === TRUE)
			{
                if($_SESSION['type'] == 'hod')
                {
                    header("location:2_dashboard_hod_online_organised.php?alert=update");
                }
                else
                {
                    header("location:2_dashboard_online_organised.php?alert=update");
                }
			}
			else
			{
                if($_SESSION['type'] == 'hod'){
                    header("location:2_dashboard_hod_online_organised.php?alert=error");
                }
                else
                {
                    header("location:2_dashboard_online_organised.php?alert=error");
                }
			}
}

}

}
//close the connection
mysqli_close($conn);
?>
<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php include_once("includes/scripting.php");?>
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
.sponsored-div {display:none;}
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
					<h3 class="box-title"><b>Online/Offline Course Organised Edit Form</b></h3>
					<br>
				</div>                      </div><!-- /.box-header -->
				<div style="text-align:right">
				</div>	
                <!-- form start -->
            <form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data">
   <?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
					//echo '<script type="text/javascript">alert("INFO:  '.$nameError.'");</script>';				
				}	
				
	
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['purpose']))
$purpose = str_replace($replace_str, "", $purpose);

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['target_audience']))
$target_audience = str_replace($replace_str, "", $target_audience);



			
			?>	

                <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
				<input type="hidden" name="Udate" value="<?php echo date("Y-m-d h:i:sa"); ?>" />
                <div class="form-group col-md-6">

                         <label for="faculty-name">Faculty Name</label>
                         <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                     </div>
				<div class="form-group col-md-6">
                    <label for="type_of_course">Type Of Course*</label>
                    <select required class="form-control input-lg" value='<?php echo $type_of_course; ?>' id="type_of_course" name="type_of_course">
                        <option <?php if($type_of_course_db == "online") echo "selected = 'selected'" ?> value = "online">Online</option>
                        <option <?php if($type_of_course_db == "offline") echo "selected = 'selected'" ?> value = "offline">Offline</option>
                    </select>
                </div>		
                     <div class="form-group col-md-6">
                         <label for="paper-title">Name of course *</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="course[]">-->
                      <input required type="text" class="form-control input-lg" value='<?php echo $courseName; ?>'  name="courseName">
                     </div>

                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input  <?php echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input  <?php echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate"
                         placeholder="03:10:10">
                     </div>
                    <div class="form-group col-md-6">
                         <label for="location">Course organised by *</label>
                         <input value='<?php echo $organised ?>' required type="text" class="form-control input-lg"  id="organised" name="organised">
                     </div>
                       
                     <div class="form-group col-md-6">
                         <label for="details">Purpose of Course * </label>
                         <input type="text"  required class="form-control input-lg"  id="purpose" name="purpose" rows="2" value="<?php echo $purpose; ?>">
                     </div>
                     <div class="form-group col-md-6">
                         <label for="target_audience">Target Audience * </label>
                         <input type="text"  required class="form-control input-lg"  id="target_audience" name="target_audience" rows="2" value="<?php echo $target_audience; ?>">
                     </div>

                    <br/>
                    <div class="form-group col-md-6">
                    <label for="role">Faculty Role</label>
                    <input type="text"  required class="form-control input-lg" id="role" name="role" rows="2" value="<?php echo $faculty_role; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="type">Fulltime/Part-time</label>
                    <select required class="form-control input-lg" id="type" name="type">
                        <option <?php if($full_part_time_db == "fulltime") echo "selected = 'selected'" ?> value = "fulltime">Full time</option>
                        <option <?php if($full_part_time_db == "parttime") echo "selected = 'selected'" ?> value="parttime">Part time</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="noofparticipants">Number Of Participants</label>
                    <input <?php echo "value = $no_of_part"; ?> required type="text" class="form-control input-lg"  id="participants" name="participants">
                </div>
                <div class="form-group col-md-6">
                    <label for="status">Status Of Activity *</label>
                    <select required class="form-control input-lg" id="status" value='<?php echo $status; ?>' name="status">
                        <option <?php if($status_db == "local") echo "selected = 'selected'" ?> value = "local">Local</option>
                        <option <?php if($status_db == "state") echo "selected = 'selected'" ?> value = "state">State</option>
                        <option <?php if($status_db == "national") echo "selected = 'selected'" ?> value="national">National</option>
                        <option <?php if($status_db == "international") echo "selected = 'selected'" ?> value="international">International</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="duration">Enter the durationof the course in hrs/week</label>
                    <input <?php echo "value = $duration"; ?> required type="text" class="form-control input-lg" id="duration" name="duration">
                </div>

                
<div class="form-group col-md-6 col-md-offset-1"></div>

                <div class="form-group col-md-6">
                    <label for="sponsored">Sponsored?</label><br>
                    <input required type='radio' name='sponsored' <?php if($sponsored == "n") echo 'checked'; ?> class='not-sponsored' value='n' >Not Sponsored <br>
                    <input required type='radio' name='sponsored' <?php if($sponsored == "s") echo 'checked'; ?> class='sponsored' value='s' > Sponsored
                </div>
                
                        <div class="second-reveal3 form-group col-md-6">
                            <div>
                                <label for="sponsorer">Name Of Sponsorer</label>
                                <input <?php if($sponsor!= '') echo "value = $sponsor"; ?> type="text" class="form-control input-lg" id="sponsor" name="sponsor">
                            </div>
                            <div>
                                <label for="isApproved">Approval Details</label>
                                <input type="text" class="form-control input-lg" name="isApproved" id="isApproved" name="isApproved"  rows="2" value="<?php echo $is_approved; ?>">
                            </div>
                        </div>

                        <div class="form-group col-md-6 col-md-offset-1"></div>
                <div class="form-group col-md-6">
                    <div>
                         <label for="Index">Attendence : </label><br/>
                          <input type="radio" id="r1" name="applicable" value="1" class="non-vac" <?php  echo($paperpath!=NULL)?'checked':'' ?>>Yes
                          <br>
                        <input type="radio" name="applicable" value="2" class="vac" <?php echo ($paperpath=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
                        <input type="radio" name="applicable" value="3" class="vac" <?php echo ($paperpath=='not_applicable')?'checked':'' ?>> No
                    </div> 
                    <br>
                    <div class='second-reveal' id='f1'>

                         <div>
                             <label for="card-image">Attendence </label><span class="colour"><b> *</b></span>
                             <input  type="file"   class="form-control input-lg" id="card-image" name="paper">
                              <a <?php 
                              $f=0;
                              if($paperpath!="not_applicable" && $paperpath!="NULL" && $paperpath!='no status' && $paperpath!=""){
                                echo "href='$paperpath'";
                                $f=1;
                              }else{
                                echo "";
                              }
                              ?> target="_blank"><h4><?php if($f==1){echo "View Existing Attendence copy";} ?><h4></a>
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
                         <div>
                             
                             <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
                             <input  type="file"   class="form-control input-lg" id="card-image" name="certificate">
                             <a <?php 
                              $f1=0;
                              if($certipath!="not_applicable" && $certipath!="NULL" && $certipath!='no status' && $certipath!=""){
                                echo "href='$certipath'";
                                $f1=1;
                              }else{
                                echo "";
                              }
                              ?> target="_blank"><h4><?php if($f1==1){echo "View Existing Certificate";} ?></h4></a>
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
                         <div>
                             
                             <label for="card-image">Report </label><span class="colour"><b> *</b></span>
                             <input  type="file"   class="form-control input-lg" id="card-image" name="report">
                             <a <?php 
                              $f2=0;
                              if($reportpath!="not_applicable" && $reportpath!="NULL" && $reportpath!='no status' && $reportpath!=""){
                                echo "href='$reportpath'";
                                $f2=1;
                              }else{
                                echo "";
                              }
                              ?> target="_blank"><h4><?php if($f2==1){echo "View Existing Report";} ?></h4></a>
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
                    <br/>
                    <div class="form-group col-md-12">
                    <?php
						if($_SESSION['type'] == 'hod')
						{
						   echo ' <a href="2_dashboard_hod_online_organised.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
						}
						else
						{
							echo ' <a href="2_dashboard_online_organised.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
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
<?php include_once('footer.php'); ?>