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
$_SESSION['currentTab']="Online";
//setting error variables
$nameError="";
$emailError="";
$courseName = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coAuthors = "";

$Fac_ID=null;

date_default_timezone_set("Asia/Kolkata");


if(isset($_POST['id'])){
    $id = $_POST['id'];
    $query = "SELECT * from online_course_organised where OC_O_ID = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    //print_r($row);
    $Fac_ID = $row['Fac_ID'];
    $courseName = $row['Course_Name'];
    $startDate = $row['Date_From'];
    $endDate = $row['Date_To'];
    $organised = $row['Organised_By'];
    $purpose = $row['Purpose'];
    $target_audience = $row['Target_Audience'];
    $faculty_role = $row['faculty_role'];
    $full_part_time = $row['full_part_time'];
    $no_of_part = $row['no_of_part'];
    $duration = $row['duration'];
    $status = $row['status'];
    $sponsored = $row['sponsored'];
    $name_of_sponsor = $row['name_of_sponsor'];
    $is_approved = $row['is_approved'];
}			
			$query2 = "SELECT * from facultydetails where Fac_ID = $Fac_ID";
			$result2 = mysqli_query($conn,$query2);
			if($result2)
			{
	            $row = mysqli_fetch_assoc($result2);
				$F_NAME = $row['F_NAME'];

			}
	   
//check if the form was submitted
if(isset($_POST['update'])){
    //the form was submitted
    $clientName=$clientEmail=$clientPhone=$clientAddress=$clientCompany=$clientNotes="";
    
    //check for any blank input which are required
    
   if(!$_POST['courseName']){
        $nameError="Please enter a Course Name<br>";
    }
    else{
        $courseName=validateFormData($_POST['courseName']);
        $courseName = "'".$courseName."'";
    }
    
    if(!$_POST['startDate']){
        $nameError="Please enter a start date<br>";
    }
    else{
        $startDate=validateFormData($_POST['startDate']);
        $startDate = "'".$startDate."'";
    }
    if(!$_POST['endDate']){
        $nameError="Please enter an end date<br>";
    }
    else{
        $endDate=validateFormData($_POST['endDate']);
        $endDate = "'".$endDate."'";
    }
    if(!$_POST['organised']){
        $organised="Please enter Organised By<br>";
    }
    else{
        $organised=validateFormData($_POST['organised']);
        $organised = "'".$organised."'";
    }
    if(!$_POST['target_audience']){
        $target_audience="Please enter Target Audience<br>";
    }
    else{
        $target_audience=validateFormData($_POST['target_audience']);
        $target_audience = "'".$target_audience."'";
    }
    if(!$_POST['role']){
        $nameError="Please enter Target Audience<br>";
    }
    else{
        $role=validateFormData($_POST['role']);
        $role = "'".$role."'";
    }
    if(!$_POST['type']){
        $nameError="Please enter Target Audience<br>";
    }
    else{
        $type=validateFormData($_POST['type']);
        $type = "'".$type."'";
    }
    if(!$_POST['participants']){
        $nameError="Please enter Target Audience<br>";
    }
    else{
        $participants=validateFormData($_POST['participants']);
        $participants = "'".$participants."'";
    }
    if(!$_POST['duration']){
        $nameError="Please enter Target Audience<br>";
    }
    else{
        $duration=validateFormData($_POST['duration']);
        $duration = "'".$duration."'";
    }
    if(!$_POST['status']){
        $nameError="Please enter Target Audience<br>";
    }
    else{
        $status=validateFormData($_POST['status']);
        $status = "'".$status."'";
    }
    if(!$_POST['sponsored']){
        $nameError="Please enter Target Audience<br>";
    }
    else{
        $sponsored=validateFormData($_POST['sponsored']);
        $sponsored = "'".$sponsored."'";
    }
    if(!$_POST['sponsor']){
        $nameError="Please enter Target Audience<br>";
    }
    else{
        $sponsor=validateFormData($_POST['sponsor']);
        $sponsor = "'".$sponsor."'";
    }
    if(!$_POST['isApproved']){
        $nameError="Please enter Target Audience<br>";
    }
    else{
        $isApproved=validateFormData($_POST['isApproved']);
        $isApproved = "'".$isApproved."'";
    }
    //following are not required so we can directly take them as it is
    
    $purpose=validateFormData($_POST["purpose"]);
    $purpose = "'".$purpose."'";	
		
    
    //checking if there was an error or not
  $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
				$succ = 0;
				$success1 = 0;
	if(isset($_POST['sponsored'])){
		$udate = date("Y-m-d h:i:sa");

		$sponsored=$_POST['sponsored'];
		if($sponsored == 'n'){
			$name_of_sponsor="";
			$isApproved="";
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
                               name_of_sponsor='$name_of_sponsor',
                               is_approved='$isApproved',
							   sponsored='$sponsored',
							   updated_at = $udate
							   WHERE OC_O_ID = $id";	
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
                               sponsored='$sponsored',
                               name_of_sponsor=$sponsor,
                               is_approved=$isApproved,
   							   updated_at = $udate
							   WHERE OC_O_ID = $id";
		}
	}


			if ($conn->query($sql) === TRUE)
			{
				$success = 1;		
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			if($success ==1 )
			{
					if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{
					   header("location:2_dashboard_hod_online_organised.php?alert=update");
					}
					else
					{
						header("location:2_dashboard_online_organised.php?alert=update");
					}
			}
			else
			   header("location:2_dashboard_online_organised.php");

}

//close the connection
mysqli_close($conn);
?>

<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php include_once('includes/scripting.php');?>
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
    
     
            <form role="form" method="POST" class="row" action ="" style= "margin:10px;" >


                <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
				<input type="hidden" name="Udate" value="<?php echo date("Y-m-d h:i:sa"); ?>" />
                <div class="form-group col-md-6">

                         <label for="faculty-name">Faculty Name</label>
                         <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                     </div>

                     <div class="form-group col-md-6">
                         <label for="paper-title">Name of course *</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="course[]">-->
                      <input  type="text" class="form-control input-lg" value='<?php echo $courseName; ?>'  name="courseName">
                     </div>

                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input required type="date" class="form-control input-lg" value='<?php echo $startDate; ?>' id="start-date" name="startDate"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input required type="date" class="form-control input-lg" <?php echo "value= '$endDate'"; ?> id="end-date" name="endDate"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="location">Course organised by *</label>
                         <input required type="text" class="form-control input-lg" <?php echo "value= '$organised'"; ?> id="organised" name="organised">
                     </div>
                       
                     <div class="form-group col-md-6">
                         <label for="details">Purpose of Course * </label>
                         <textarea  required class="form-control input-lg"  id="purpose" name="purpose" rows="2" value="$row"><?php echo $purpose; ?></textarea>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="target_audience">Target Audience * </label>
                         <textarea  required class="form-control input-lg"  id="target_audience" name="target_audience" rows="2" value="$row"><?php echo $target_audience; ?></textarea>
                     </div>
                <div class="form-group col-md-6">
                    <label for="role">Faculty Role</label>
                    <textarea  required class="form-control input-lg" id="role" name="role" rows="2"><?php echo $faculty_role; ?></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label for="type">Fulltime/Part-time</label>
                    <select required class="form-control input-lg" <?php echo "value= '$full_part_time'"; ?> id="type" name="type">
                        <option <?php if($full_part_time == "local") echo "selected = 'selected'" ?> value = "fulltime">Full time</option>
                        <option <?php if($full_part_time == "local") echo "selected = 'selected'" ?> value="parttime">Part time</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="noofparticipants">Number Of Participants</label>
                    <input  required type="text" class="form-control input-lg" value='<?php echo $no_of_part; ?>' id="participants" name="participants">
                </div>
                <div class="form-group col-md-6">
                    <label for="status">Status Of Activity *</label>
                    <select required class="form-control input-lg" id="status" value='<?php echo $status; ?>' name="status">
                        <option <?php if($status == "local") echo "selected = 'selected'" ?> value = "local">Local</option>
                        <option <?php if($status == "state") echo "selected = 'selected'" ?> value = "state">State</option>
                        <option <?php if($status == "national") echo "selected = 'selected'" ?> value="national">National</option>
                        <option <?php if($status == "international") echo "selected = 'selected'" ?> value="international">International</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="duration">Enter the durationof the course in hrs/week</label>
                    <input required type="text" class="form-control input-lg" value='<?php echo $duration;?>' id="duration" name="duration">
                </div>
                <div class="form-group col-md-6">
                    <label for="sponsored">Sponsored?</label>
                    <input type='radio' name='sponsored' <?php if($sponsored == "n") echo 'checked'; ?> class='not-sponsored' value='n' >Not Sponsored <br>
                    <input type='radio' name='sponsored' <?php if($sponsored == "s") echo 'checked'; ?> class='sponsored' value='s' > Sponsored
                </div>
				
			
                <div class='second-reveal'>
				
                    <div class="form-group col-md-6">
                        <label for="sponsorer">Name Of Sponsorer</label>
                        <input  type="text" class="form-control input-lg" value='<?php echo $name_of_sponsor; ?>' id="sponsor" name="sponsor">
                    </div>
					 <div class="form-group col-md-6">
					 </div>
                    <div class="form-group col-md-6">
                        <label for="isApproved">Approval Details</label>
                        <textarea class="form-control input-lg" name="isApproved" id="isApproved" name="isApproved" cols="25" rows="2"><?php echo $is_approved; ?> </textarea>
                    </div>
					
                </div>
	
                    <br/>
                    <div class="form-group col-md-12">
                         <a href="2_dashboard_online_organised.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

                         <button name="update"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>
</div>
    <script>
        $(document).ready(function(){
            $(".sponsored").click(function () {
                $(".sponsored-div").show();
            });
            $(".not-sponsored").click(function () {
                $(".sponsored-div").hide();
            });
        });
    </script>
    <style>
        .sponsored-div {display:none;}

    </style>
<?php include_once('footer.php'); ?>