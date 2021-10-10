<?php
session_start();
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

$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}

//include custom functions files 
include_once("includes/functions.php");

$Fac_ID = $_SESSION['Fac_ID'];


//setting error variables
$nameError="";
$emailError="";
$activitytitle = $startDate = $endDate = $activitytype = $location = $coordinated= $resource = $role_of_faculty=$time_activities=$status_act=$sponsors=$sponsor_details=$approval_details=$last_updated="";
$no_of_participants=0;
$no_hours=0;

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $query = "SELECT * from organised where A_ID = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['Fac_ID'];

    $activitytitle = $row['Act_title'];
    $startDate = $row['Date_from'];
    $endDate = $row['Date_to'];
    $activitytype = $row['Act_type'];
	$organized = $row['Organized_by'];
	$coordinated = $row['Coordinated_by'];
    $location = $row['Location'];
	$resource = $row['Resource'];
    $role_of_faculty=$row['Role_Of_Faculty'];
    $time_activities=$row['Time_Activities'];
    $no_of_participants=$row['No_Of_Participants'];
    $no_hours=$row['Equivalent_Duration'];
    $status_act=$row['Status_Of_Activity'];
    $sponsors=$row['Sponsorship'];
    $sponsor_details=$row['Sponsor_Details'];
    $approval_details=$row['Approval_Details'];
    $last_updated=$row['LastUpdated'];
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
	echo '<script type="text/javascript">alert("$activitytitle") </script>'; 
    //the form was submitted
    $clientName=$clientEmail=$clientPhone=$clientAddress=$clientCompany=$clientNotes="";
    
    //check for any blank input which are required
    
   if(!$_POST['activitytitle']){
        $nameError="Please enter a Title<br>";
    }
    else{
        $activitytitle=validateFormData($_POST['activitytitle']);
        $activitytitle = "'".$activitytitle."'";
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
    if(!$_POST['activitytype']){
        $nameError="Please select activity type<br>";
    }
    else{
        $activitytype=validateFormData($_POST['activitytype']);
        $activitytype = "'".$activitytype."'";
    }
    if(!$_POST['location']){
        $location="Please enterlocation<br>";
    }
    else{
        $location=validateFormData($_POST['location']);
        $location = "'".$location."'";
    }
    if(!$_POST['organized']){
        $organized="Please enter name<br>";
    }
    else{
        $organized=validateFormData($_POST['organized']);
        $organized = "'".$organized."'";
    }

	$coordinated = $_POST['coordinated'];
	$resource = $_POST['resource'];
    //following are not required so we can directly take them as it is
    
    if(!$_POST['role_of_faculty']){
        $nameError="Please enter a Role_Of_Faculty<br>";
    }
    else{
        $role_of_faculty=validateFormData($_POST['role_of_faculty']);
        $role_of_faculty = "'".$role_of_faculty."'";
    }
    if(!$_POST['time_activities']){
        $nameError="Please enter a Time_Activities<br>";
    }
    else{
        $time_activities=validateFormData($_POST['time_activities']);
        $time_activities = "'".$time_activities."'";
    }
    if(!$_POST['no_of_participants']){
        $nameError="Please enter a No_Of_Participants<br>";
    }
    else{
        $no_of_participants=validateFormData($_POST['no_of_participants']);
        $no_of_participants = "'".$no_of_participants."'";
    }
    if(!$_POST['no_hours']){
        $nameError="Please enter a Equivalent_Duration<br>";
    }
    else{
        $no_hours=validateFormData($_POST['no_hours']);
        $no_hours = "'".$no_hours."'";
    }
    if(!$_POST['status_act']){
        $nameError="Please enter a Status_Of_Activity<br>";
    }
    else{
        $status_act=validateFormData($_POST['status_act']);
        $status_act = "'".$status_act."'";
    }
	
  
    
    //checking if there was an error or not

if($_POST['sponsors'] == 'sponsored')
	{
		
		
			 $sponsor_details=validateFormData($_POST['sponsor_details']);
			 $sponsor_details="'".$sponsor_details."'";
			 $approval_details=validateFormData($_POST["approval_details"]);
			 $approval_details = "'".$approval_details."'";
		
	}
	
	if($_POST['sponsors'] == 'not-sponsored')
	{
		//$presentedby="'".$presentedby."'";
		$sponsor_details= "NULL";
		$approval_details= "NULL";
		//echo "<script>alert('$presentedby')</script>";
	}
	
	
	$sponsors = $_POST['sponsors'];
	
	$sql = "update organised set Act_title = $activitytitle,
                               Act_type = $activitytype,
							   Organized_by = $organized,
							   Date_from = $startDate,
							   Date_to = $endDate, 
							   Location = $location,
							   Resource = '$resource',
							   Coordinated_by = '$coordinated',
                               Role_Of_Faculty=$role_of_faculty,
                               Time_Activities=$time_activities,
                               No_Of_Participants=$no_of_participants,
                               Equivalent_Duration=$no_hours,
                               Status_Of_Activity=$status_act,
                               Sponsorship='$sponsors',
                               Sponsor_Details=$sponsor_details,
                               Approval_Details=$approval_details,
                               LastUpdated=CURRENT_TIMESTAMP
							   WHERE A_ID = $id";
							   

			if ($conn->query($sql) === TRUE) 
			{
				$success = 1;
				
					if($success == 1)
					{
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						{
						   header("location:2_dashboard_organised_hod.php?alert=update");

						}
						else
						{
							header("location:2_dashboard_organised.php?alert=update");

						}
					}
					else 
					{
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )

								header("location:2_dashboard_organised_hod.php");
						   else
								header("location:2_dashboard_organised.php");

					}

					
			}	
			else 
			{
				echo "Error: " . $sql . "<br>" . $conn->error;
			}	
			} 
			
			
			
			
			


//close the connection
mysqli_close($conn);
?>





<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php 
include_once("includes/scripting.php");

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
					<h3 class="box-title"><b>Edit STTP/Workshop/FDP Activities Organised</b></h3>
					<br>	
				</div>      
				</div><!-- /.box-header -->
                <br>
                <br>
				
                <!-- form start -->
                <form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
                    <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
                    <div class="form-group col-md-6">

                         <label for="faculty-name">Faculty Name</label>
                         <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-title">Title *</label>
                         <input required type="text" class="form-control input-lg" id="paper-title" name="activitytitle" value = '<?php echo $activitytitle; ?>' >
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-type">Activity Type *</label>
                         <select required class="form-control input-lg" id="paper-type" name="activitytype">
                             <option <?php if($activitytype == "STTP") echo "selected = 'selected'" ?>  value = "STTP">STTP</option>
                             <option <?php if($activitytype == "Workshop") echo "selected = 'selected'" ?> value = "Worshop">Workshop</option>
							 <option <?php if($activitytype == "FDP") echo "selected = 'selected'" ?> value = "FDP">FDP</option>
							 <option <?php if($activitytype == "QIP") echo "selected = 'selected'" ?> value = " QIP">QIP</option>
							 <option <?php if($activitytype == "SEMINAR") echo "selected = 'selected'" ?> value = "SEMINAR">SEMINAR</option>
							 <option <?php if($activitytype == "WEBINAR") echo "selected = 'selected'" ?> value = "WEBINAR">WEBINAR</option>
							 <option <?php if($activitytype == "REFRESHER_PROGRAM") echo "selected = 'selected'" ?> value = "REFRESHER_PROGRAM">INDUCTION/REFRESHER PROGRAM</option>
                              

						</select>
                     </div>
                     
					  <div class="form-group col-md-6">
                         <label for="conf">Organized by *</label>
                         <textarea required class="form-control input-lg" id="organised" name="organized" rows="2"><?php echo $organized; ?></textarea>
                     </div>
					 
					  <div class="form-group col-md-6">
                         <label for="conf">Co-ordinated by *</label>
                         <textarea required class="form-control input-lg" id="coordinated" name="coordinated" rows="2"><?php echo $coordinated; ?></textarea>
                     </div>
                     
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label> <?php $value = date("Y-m-d\TH:i:s", strtotime($startDate));  ?>
                         <input 
                            
                           required type="datetime-local" class="form-control input-lg" id="start-date" name="startDate"
                         placeholder="03:10:10" value="<?php echo $value; ?>">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label> <?php  $value = date("Y-m-d\TH:i:s", strtotime($endDate)); ?>
                         <input
                            
                           required type="datetime-local" class="form-control input-lg" id="end-date" name="endDate"
                         placeholder="03:10:10" value="<?php echo $value; ?>">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="location">Location *</label>
                         <input
                            
                           required type="text" class="form-control input-lg" id="location" name="location" value='<?php echo $location ?>'>
                     </div>

                      <div class="form-group col-md-6">
                         <label for="resource">Resource *</label>
                         <input

                           required type="text" class="form-control input-lg" id="resource" name="resource" value='<?php echo $resource ?>'>
                     </div>

                     <div class="form-group col-md-6">
                        <label for="role_of_faculty">Role of Faculty: *</label>
                        <br>
                        <textarea id="role_of_faculty" class="form-control input-lg" name="role_of_faculty" required><?php echo $role_of_faculty ?></textarea></div>

                        <div class="form-group col-md-6">
                        <label id="time_activities">Full-Time/Part-Time: *</label>
                        <select required name="time_activities" id="time_activites" class="form-control input-lg">
                            <option value="" disabled selected>Select your option:</option>
                            <option <?php if($time_activities == "full-time") echo "selected = 'selected'" ?> value="full-time">Full-Time</option>
                            <option <?php if($time_activities == "part-time") echo "selected = 'selected'" ?>  value="part-time">Part-Time</option>
                        </select></div>

                        <div class="form-group col-md-6">
                        <label for="no_of_participants">Number of participants: *</label>
                        <input type="number" name="no_of_participants" id="no_of_participants" class="form-control input-lg" value = '<?php echo $no_of_participants; ?>' min="1" ></div>

                        <div class="form-group col-md-6">
                        <label for="no_hours">Equivalent duration(In hours) *</label>
                        <input type="text" name="no_hours" id="no_hours" placeholder="Enter the total hours" class="form-control input-lg" value = '<?php echo $no_hours; ?>' ></div>

                        <div class="form-group col-md-6">
                        <label for="status_act">Status of Activity: *</label>
                        <select required name="status_act" id="status_act" class="form-control input-lg">
                            <option value="" disabled selected>Select your option:</option>
                            <option <?php if($status_act == "local") echo "selected = 'selected'" ?>  value="local">Local</option>
                            <option <?php if($status_act == "state") echo "selected = 'selected'" ?>  value="state">State</option>
                            <option <?php if($status_act == "national") echo "selected = 'selected'" ?>  value="national">National</option>
                            <option <?php if($status_act == "international") echo "selected = 'selected'" ?>  value="international">InterNational</option>
                        </select>
                        </div>
<div class="form-group col-md-6">
                         <label for="sponsors">Sponsored/Not-sponsored:  </label><span class="colour"><b> *</b></span>
                         <select required onchange="myfunction()"   class="form-control input-lg" id="sponsors" name="sponsors">
                             <option <?php if($sponsors == "not-sponsored") echo "selected = 'selected'" ?> value = "not-sponsored">Not Sponsored</option>
							 <option  <?php if($sponsors == "sponsored") echo "selected = 'selected'" ?> value = "sponsored">Sponsored</option>
                         </select>
                     </div>

						
                           
							
                        <div id="sponsor_details" class="form-group col-md-6" style="display:none">
                         <label for="sponsor_details">Sponsor Details:</label><span class="colour"><b> *</b></span>
                         <input
                             <?php echo "value = '$sponsor_details'";?>
                            type="text" class="form-control input-lg" id="sponsor_details"
						   name="sponsor_details"  >
                     </div>
				
				<div id="approval_details" class="form-group col-md-6" style="display:none">
                         <label for="approval_details">Approval Details:</label><span class="colour"><b> *</b></span>
                         <input
                             <?php echo "value = '$approval_details'";?>
                            type="text" class="form-control input-lg" id="approval_details"
						   name="approval_details"  >
                     </div>
					 
					 
			   <script>
					 
					 window.onload = function() {
						 myfunction();
					 }
					 
					 function myfunction()
					 {
						 var x = document.getElementById("sponsors").value;
						 
						 if(x=='sponsored')
						 {
							//document.getElementById("demo").innerHTML = "You selected: " + x;
							//console.log(document.getElementById("presented-by"));
							document.getElementById("sponsor_details").style.display = 'block';
							document.getElementById("approval_details").style.display = 'block';
						 }
						 else
						 {
							//document.getElementById("demo").innerHTML = "You selected: " + x;
							document.getElementById("sponsor_details").style.display = 'none';
							document.getElementById("approval_details").style.display = 'none'; 
						 }
					 }
					 </script>		 
                    

                    <div class="form-group col-md-12">
                         <button name="update"  type="submit" class="btn btn-success btn-lg">Submit</button>
				<?php	if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{ ?>
                         <a href="2_dashboard_organised_hod.php" type="button" class="btn btn-warning btn-lg pull-right">Cancel</a>

				<?php	}
				else
				{?>
					  <a href="2_dashboard_organised.php" type="button" class="btn btn-warning btn-lg pull-right">Cancel</a>

				<?php
				}
					
				?>	
                    </div>
                </form>
                
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
 
    
    
<?php include_once('footer.php'); ?>
   
   