    
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
//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");

include_once("includes/config.php");

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

$_SESSION['currentTab']="Online";


//setting error variables
$nameError="";
$emailError="";
$faculty_role=$full_part_time=$no_of_part=$duration=$status=$sponsored=$name_of_sponsor=$is_approved=$course = $startDate = $endDate = $organised = $purpose = $target = "";
$flag= 1;
$success = 0;
$fid = $_SESSION['Fac_ID'];
$s = 1;
$p_id = 0;
$error1 = $error2 = $error3 = "";
    
    $queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}
    $faculty_name= $_SESSION['loggedInUser'];
    $_SESSION['F_NAME'] = $faculty_name ;

//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    //the form was submitted
	$type_of_course_array = $_POST['typeofcourse'];
    
	$course_array = $_POST['course'];
	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$organised_array = $_POST['organised'];
  $purpose_array = $_POST['purpose'];
  $target_array = $_POST['target'];
  $faculty_role_array = $_POST['role'];
  $full_part_time_array = $_POST['type'];
  $no_of_part_array = $_POST['participants'];
  $duration_array = $_POST['duration'];
  $status_array = $_POST['status'];
  $sponsored_array = $_POST['sponsored'];
  
 if(isset($_POST['sponsor']))
	$name_of_sponsor_array = $_POST['sponsor'];
else
	$name_of_sponsor_array = 'NULL';
  
  if(isset($_POST['isApproved']))
	$is_approved_array = $_POST['isApproved'];
else
	$is_approved_array = 'NULL';

	
    //check for any blank input which are required
    		
for($i=0; $i<1;$i++)
{
$type_of_course = mysqli_real_escape_string($conn,$type_of_course_array[$i]);

$course = mysqli_real_escape_string($conn,$course_array[$i]);

$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$organised = mysqli_real_escape_string($conn,$organised_array[$i]);
$purpose = mysqli_real_escape_string($conn,$purpose_array[$i]);
$target = mysqli_real_escape_string($conn,$target_array[$i]);
$faculty_role = mysqli_real_escape_string($conn,$faculty_role_array[$i]);
$full_part_time = mysqli_real_escape_string($conn,$full_part_time_array[$i]);
$no_of_part = mysqli_real_escape_string($conn,$no_of_part_array[$i]);
$duration = mysqli_real_escape_string($conn,$duration_array[$i]);
$status = mysqli_real_escape_string($conn,$status_array[$i]);
$sponsored = mysqli_real_escape_string($conn,$sponsored_array[$i]);
$name_of_sponsor = mysqli_real_escape_string($conn,$name_of_sponsor_array[$i]);
$is_approved = mysqli_real_escape_string($conn,$is_approved_array[$i]);

$time=time();
$start = new DateTime(date($startDate,$time));
$end = new DateTime(date($endDate,$time));
$days = date_diff($start,$end);
$noofdays = $days->format('%d');

		$type_of_course=validateFormData($type_of_course);
        $type_of_course = "'".$type_of_course."'";
 
        $course=validateFormData($course);
        $course = "'".$course."'";
		
		$organised=validateFormData($organised);
        $organised = "'".$organised."'";
		
		$purpose=validateFormData($purpose);
        $purpose = "'".$purpose."'";
		
		$target=validateFormData($target);
        $target = "'".$target."'";
		
		$faculty_role=validateFormData($faculty_role);

        if($faculty_role == "''"){
        	$faculty_role='NA';
	    }
		
		$no_of_part=validateFormData($no_of_part);
        $no_of_part = "'".$no_of_part."'";
		
		$duration=validateFormData($duration);
        $duration = "'".$duration."'";
		
        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
		
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";

        if ($name_of_sponsor=="") {
			$name_of_sponsor='NA';
		}
		
        if($is_approved==""){
			$is_approved='NA';
		}
    
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
            $targetName=$datapath."attendance/".$_SESSION['F_NAME']."_attendance_".date("d-m-Y H-i-s", time()).".".$fileExt;  
            
            if(empty($errors)==true) {
            if (file_exists($targetName)) {   
              unlink($targetName);
            }      
             $moved = move_uploaded_file($fileTmp,"$targetName");
             if($moved == true){
              $paperpath=$targetName;
              $success=1;
             }
       //      else{
               //not successful
               //header("location:error.php");
                //     echo "<h1> $targetName </h1>";
           //  }
            }else{
             print_r($errors);
            //header("location:else.php");
            }
          }
          else{
            $s = 0;
            $error3 = "No file selected";
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
            $fileExt=strtolower(end($temp));            date_default_timezone_set('Asia/Kolkata');
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
          //   else{
            //         echo "<h1> $targetName </h1>";
        //     }
            }else{
             print_r($errors);
            }
          }
          else{
            $s = 0;
            $error1 = "No file selected";
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
         //    else{
          //           echo "<h1> $targetName </h1>";
            // }
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

	
	  
	  //following are not required so we can directly take them as it is

		
	
	  //checking if there was an error or not
        $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }

 if($flag!=0 && $s != 0)
	   {
	   	echo "Name of sponsor".$name_of_sponsor;
	   	echo "IS Approved".$is_approved;
	   	echo "Sponsored".$sponsored;
        $sql="INSERT INTO online_course_organised(Fac_ID,type_of_course, Course_Name, Date_from, Date_to,Organised_by, Purpose, Target_Audience,faculty_role, full_part_time, no_of_part, duration, status, sponsored, name_of_sponsor, is_approved,attendence_path,certificate_path,report_path,noofdays) VALUES ('$author',$type_of_course,$course,$startDate,$endDate,$organised,$purpose,$target,'$faculty_role', '$full_part_time', $no_of_part, $duration, '$status', '$sponsored', '$name_of_sponsor', '$is_approved','".$paperpath."','".$certipath."','".$reportpath."',$noofdays)";
			if ($conn->query($sql) === TRUE) {
        $success = 1;
        header("location:2_dashboard_online_organised.php?alert=success");
			} 
      else if($s != 0)
      {
				header("location:2_dashboard_online_organised.php?alert=error");
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
					<h3 class="box-title"><b>Online/Offline Course Organised Form</b></h3>
					<br>
					</div>
                </div>
                <div style="text-align:right">
       <!--   <a href="menu.php?menu=5 "> <u>Back to Online Course Attended Activities Menu</u></a> -->
        </div>
                <!-- /.box-header -->
                <!-- form start -->
	
				

	<?php
			
					for($k=0; $k<1; $k++)
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
                          <label for="typeofcourse">Type Of Course*</label>
                          <select required class="form-control input-lg" id="typeofcourse" name="typeofcourse[]">
                              <option value = "online">Online</option>
                              <option value = "offline">Offline</option>
                          </select>
                      </div>
				
                     <div class="form-group col-md-6">
                         <label for="course-name">Name of course *</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="course[]">-->
					  <input <?php if(isset($_POST['course'])) echo "value = $course"; ?> required type="text" class="form-control input-lg"  name="course[]">
                     </div>

                     <div class="form-group col-md-6">
                         <label for="start-date">Duration From *</label>
                         <input <?php if(isset($_POST['startDate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate[]"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">Duration To *</label>
                         <input <?php if(isset($_POST['endDate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate[]"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="organised">Course organised by *</label>
                         <input <?php if(isset($_POST['organised'])) echo "value = $organised"; ?> required type="text" class="form-control input-lg" id="organised" name="organised[]">
                     </div>

                     <div class="form-group col-md-6">
                         <label for="purpose">Purpose of Course * </label>
                         <input type="text" <?php if(isset($_POST['purpose'])) echo "value = $purpose"; ?>  required class="form-control input-lg" id="purpose" name="purpose[]" >
                     </div>
                     
					 <div class="form-group col-md-6">
                         <label for="target">Target Audience * </label>
                         <input type="text"  required class="form-control input-lg" id="target" name="target[]" <?php if(isset($_POST['target'])) echo "value = $target"; ?>>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="role">Faculty Role *</label>
                         <input type="text"  required class="form-control input-lg" id="role" name="role[]" <?php if(isset($_POST['role'])) echo "value = $faculty_role"; ?>>
                     </div>
                      <div class="form-group col-md-6">
                          <label for="type">Fulltime/Part-time</label>
                          <select required class="form-control input-lg" id="type" name="type[]">
                              <option value = "fulltime">Full time</option>
                              <option value="parttime">Part time</option>
                          </select>
                      </div>
                      <div class="form-group col-md-6">
                          <label for="noofparticipants">Number Of Participants *</label>
                          <input <?php if(isset($_POST['participants'])) echo "value = $no_of_part"; ?>  required type="text" class="form-control input-lg"  id="participants" name="participants[]">
                      </div>
                      <div class="form-group col-md-6">
                          <label for="duration">Enter the duration of the course in hrs/week *</label>
                          <input <?php if(isset($_POST['duration'])) echo "value = $duration"; ?>  required type="text" class="form-control input-lg" id="duration" name="duration[]">
                      </div>
                      <div class="form-group col-md-6">
                          <label for="status">Status Of Activity *</label>
                          <select required class="form-control input-lg" id="status" name="status[]">
                              <option value = "local">Local</option>
                              <option value = "state">State</option>
                              <option value="national">National</option>
                              <option value="international">International</option>
                          </select>
                      </div>

            
<div class="form-group col-md-6 col-md-offset-1"></div>

                      <div class="form-group col-md-6">
                          <label for="sponsored">Sponsored?</label><br>
                          <input required type='radio' name='sponsored' class='not-sponsored' value='not-sponsored' <?php if(isset($_POST['sponsored'])) if($_POST['sponsored']=='not-sponsored') echo "checked"; ?>> Not Sponsored <br>
                          <input required type='radio' name='sponsored' class='sponsored' value='sponsored' <?php if(isset($_POST['sponsored'])) if($_POST['sponsored']== 'sponsored') echo "checked"; ?>> Sponsored
                      </div>
					<div class='second-reveal3 form-group col-md-6'>

                          <div>
                              <label for="sponsorer">Name Of Sponsorer</label>
                              <input <?php if(isset($_POST['sponsor'])) echo $name_of_sponsor; ?> type="text" class="form-control input-lg" id="sponsor" name="sponsor[]">
                          </div>

                          <div >
                              <label for="isApproved">Approval Details</label>
							                <input type="text" class="form-control input-lg" id="isApproved" name="isApproved[]" >           
						             </div>
                     <!-- </div> -->
					 </div> 



           <div class="form-group col-md-6 col-md-offset-1"></div>
  <div class="form-group col-md-6">
         <div >

            &nbsp;<label for="course">Upload certificate : Applicable ?<br></label><span class="colour"><b> *</b></span>
            <span class="error" style = "border : none;"> <?php echo $error1 ?> </span>
            <br>  &nbsp;<input required type='radio' name='applicable1' class='non-vac1' value='1'  <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'checked':'' ?>> Yes <br>
            &nbsp;<input type='radio' name='applicable1' class='vac1' value='2' <?php if(isset($_POST['applicabl1'])) echo($_POST['applicable1'] =="2")?'checked':'' ?>	 > Applicable, but not yet available <br>
                     
            &nbsp;<input type='radio' name='applicable1' class='vac1' value='3'  <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="3")?'checked':'' ?>	> No <br>
          </div>
          <br>
          <div class='second-reveal1' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'style = "display : block" ':'' ?>>
             <div> 
                           <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
                       <input type="file"   class="form-control input-lg" id="card-image" name="certificate">
                    </div> 
          </div>
                     <div>

            &nbsp;<label for="course">Upload report : Applicable ?<br></label><span class="colour"><b> *</b></span>
            <span class="error" style = "border : none;"> <?php echo $error2 ?> </span>
            <br>  &nbsp;<input required type='radio' name='applicable2' class='non-vac2' value='1'  <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="1")?'checked':'' ?>	> Yes <br>
            &nbsp;<input type='radio' name='applicable2' class='vac2' value='2'  <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="2")?'checked':'' ?>	> Applicable, but not yet available <br>
                     
            &nbsp;<input type='radio' name='applicable2' class='vac2' value='3'  <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="3")?'checked':'' ?>> No <br>
          </div>
          <br>
          <div class='second-reveal2' <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="1")?'style = "display : block" ':'' ?>>
             <div>
               
                           <label for="card-image">Report </label><span class="colour"><b> *</b></span>
                       <input  type="file"   class="form-control input-lg" id="card-image" name="report">
                    </div> 
          </div>

          <div>

            &nbsp;<label for="course">Upload Attendace : Applicable ?<br></label><span class="colour"><b> *</b></span>
            <span class="error" style = "border : none;"> <?php echo $error3 ?> </span>
            <br>  &nbsp;<input required type='radio' name='applicable' class='non-vac' value='1'  <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'checked':'' ?>	> Yes <br>
            &nbsp;<input type='radio' name='applicable' class='vac' value='2'  <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="2")?'checked':'' ?>	> Applicable, but not yet available <br>
                     
            &nbsp;<input type='radio' name='applicable' class='vac' value='3'  <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="3")?'checked':'' ?>	> No <br>
          </div>
          <br>
          <div class='second-reveal' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'style = "display : block" ':'' ?>>
             <div >
               
                           <label for="card-image">Attendance </label><span class="colour"><b> *</b></span>
                       <input  type="file"   class="form-control input-lg" id="card-image" name="paper">
                    </div> 
          </div>
        </div>
					
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                         
                         <button name="add"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                         <a href="list_of_activities_user.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>
</div>
<?php include_once('footer.php'); ?>

