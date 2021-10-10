<?php
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the user to login page
    header("location:index.php");
}
if(isset($_SESSION['type'])){
    if($_SESSION['type'] != 'hod'){
    //if not hod then send the user to login page
    session_destroy();
    header("location:index.php");
}
}

//connect to database
include_once("includes/connection.php");

$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}

date_default_timezone_set("Asia/Kolkata");

//include custom functions files 
include_once("includes/functions.php");
$_SESSION['currentTab']="Online";
//setting error variables
$nameError="";
$flag = 1;
$emailError="";
$courseName = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coAuthors = "";

$Fac_ID=null;
if(isset($_POST['id'])){
    $id = $_POST['id'];
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
    $name_of_sponsor = $row['name_of_sponsor'];
    $is_approved = $row['is_approved'];
}			


    /*Array ( [OC_O_ID] => 2 [Fac_Id] => 4 [Course_Name] => test [Date_From] => 2017-01-01 [Date_To] => 2017-12-31 [Organised_By] => chirag [Purpose] => testing [Target_Audience] => chirag [Certificate_Copy] => [Report] => [Attendence_Record] => [certificate_path] => [report_path] => [attendence_path] => ) update online_course_organised set Course_Name = 'test', Date_from = '2017-01-01', Date_to = '2017-12-31', Organised_by = 'bhavikk', Purpose ='testing', Target_Audience = 'bhavikk' WHERE OC_O_ID = 2*/
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
        $startDate = "'".$startDate."'";
    
        $endDate=validateFormData($_POST['endDate']);
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
		if($sponsored=='n'){
			$name_of_sponsor="";
			$isApproved="";
			
				
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
							   sponsored='$sponsored'
							   WHERE OC_O_ID = $id";	
		
		}else{
			
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
                               sponsored='$sponsored',
                               name_of_sponsor=$sponsor,
                               is_approved=$isApproved
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
			{
				if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{
					   header("location:2_dashboard_hod_online_organised.php");
					}
					else
					{
						header("location:2_dashboard_online_organised.php");
					}
			}
		
}

			

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
					<h3 class="box-title"><b>Online/Offline Course Organised Edit Form</b></h3>
					<br>
				</div>                   </div><!-- /.box-header -->
                <div style="text-align:right">
				</div>
                <!-- form start -->
     
            <form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
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
                      <input required  type="text" class="form-control input-lg"  name="courseName" value='<?php echo $courseName; ?>'>
                     </div>

                      <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input  <?php echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input  <input  <?php echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="location">Course organised by *</label>
                         <input value='<?php echo $organised ?>'  required type="text" class="form-control input-lg"  id="organised" name="organised">
                     </div>
                       
                     <div class="form-group col-md-6">
                         <label for="details">Purpose of Course * </label>
                         <textarea  required class="form-control input-lg"  id="purpose" name="purpose" rows="2" value="$row"><?php echo $purpose; ?></textarea>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="target_audience">Target Audience * </label>
                         <textarea  required class="form-control input-lg"  id="target_audience" name="target_audience" rows="2" value="$row"><?php echo $target_audience; ?></textarea>
                     </div>

                    <br/>
                    <div class="form-group col-md-6">
                    <label for="role">Faculty Role</label>
                    <textarea  required class="form-control input-lg" id="role" name="role" rows="2"><?php echo $faculty_role; ?></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label for="type">Fulltime/Part-time</label>
                    <select required class="form-control input-lg"  id="type" name="type">
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
                <div class="form-group col-md-6">
                    <label for="sponsored">Sponsored?</label>
                    <input required type='radio' name='sponsored' <?php if($sponsored == "n") echo 'checked'; ?> class='not-sponsored' value='n' >Not Sponsored <br>
                    <input type='radio' name='sponsored' <?php if($sponsored == "s") echo 'checked'; ?> class='sponsored' value='s' > Sponsored
                </div>
                 <div class='second-reveal'>
                    <div class="form-group col-md-6">
                        <label for="sponsorer">Name Of Sponsorer</label>
                        <input <?php if(isset($_POST['sponsor']))echo "value = $name_of_sponsor"; ?> type="text" class="form-control input-lg" id="sponsor" name="sponsor">
                    </div>
					                    <div class="form-group col-md-6">
										</div>
                    <div class="form-group col-md-6">
                        <label for="isApproved">Approval Details</label>
                        <textarea class="form-control input-lg" name="isApproved" id="isApproved" name="isApproved"  rows="2"><?php echo $is_approved; ?> </textarea>
                    </div>
                </div>
                    <br/>
                    <div class="form-group col-md-12">
                         <a href="2_dashboard_hod_online_organised.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

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