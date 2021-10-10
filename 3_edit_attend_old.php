<?php
ob_start();
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
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
$activitytitle = $startDate = $endDate = $activitytype = $location = $status_activities =$awards="";
$no_of_hours=0;

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $query = "SELECT * from attended where A_ID = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['Fac_ID'];

    $activitytitle = $row['Act_title'];
    $startDate = $row['Date_from'];
    $endDate = $row['Date_to'];
    $activitytype = $row['Act_type'];
	$organized = $row['Organized_by'];
    $location = $row['Location'];

    $status_activities=$row['Status_Of_Activity'];
    $no_of_hours=$row['Equivalent_Duration'];
    $awards=$row['Awards'];
    $fdc = $row['FDC_Y_N'];
    $last_updated=$row['LastUpdated'];

}
$_SESSION['a1'] = $activitytitle;
			
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
    if(!$_POST['status_activities']){
        $status_activities="Please enter status of activity<br>";
    }
    else{
        $status_activities=validateFormData($_POST['status_activities']);
        $status_activities = "'".$status_activities."'";
    }
    if(!$_POST['no_of_hours']){
        $no_of_hours="Please enter no of hours<br>";
    }
    else{
        $no_of_hours=validateFormData($_POST['no_of_hours']);
        $no_of_hours = "'".$no_of_hours."'";
    }
    if(!$_POST['awards']){
        $awards="Please enter award details<br>";
    }
    else{
        $awards=validateFormData($_POST['awards']);
        $awards = "'".$awards."'";
    }
   
   
    //following are not required so we can directly take them as it is
    
		
        $fdc=validateFormData($_POST["fdc"]);
        $fdc = "'".$fdc."'";
    
    //checking if there was an error or not
// echo '<script type="text/javascript">alert("$activitytitle") </script>';
$query = "SELECT Fac_ID from attended where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
            $_SESSION['author'] = $author;
            $author = $_SESSION['author'];
       }
                $succ = 0;
                $success1 = 0;


	$sql = "update attended set Act_title = $activitytitle,
                               Act_type = $activitytype,
							   Organized_by = $organized,
							   Date_from = $startDate,
							   Date_to = $endDate, 
							   Location = $location,
							   FDC_Y_N = $fdc,
                               Status_Of_Activity=$status_activities,
                               Equivalent_Duration=$no_of_hours,
                               Awards=$awards,
                               LastUpdated=CURRENT_TIMESTAMP
							   WHERE A_ID = $id";
							   
						/*$sql1= "update fdc_attended set Act_title = $activitytitle where A_ID = $id ";
						$result1=mysqli_query($conn, $sql1);

                $result = mysqli_query($conn,$sql);
				$success = 1; */
				
				if ($conn->query($sql) === TRUE) 
				{
					$success = 1;
				}

                if($success == 1 && preg_match('/no/', $fdc))
                {
                    $sql="delete from fdc_attended where A_ID = $id";
                    $result = mysqli_query($conn,$sql);
                    $succ =1;
                    
                }           
            
            
            if($success == 1 )
            {
				$sql_fdc = "update fdc_attended set Act_title = $activitytitle where WHERE A_ID = $id";
               
			   $result_fdc = mysqli_query($conn,$sql_fdc);
				
                    if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
                    {
                       header("location:2_dashboard_attend_hod.php?alert=update");

                    }
                    else
                    {
                        header("location:2_dashboard_attend.php?alert=update");

                    }
            }
            else 
			{
			if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )

					header("location:2_dashboard_attend_hod.php");
			   else
				    header("location:2_dashboard_attend.php");

			}
}


//close the connection
mysqli_close($conn);
?>





<?php include_once('head.php'); ?>
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
					<h3 class="box-title"><b>Edit STTP/Workshop/FDP Activities attended</b></h3>
					<br>	
				</div>               


			   </div><!-- /.box-header --><br>
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
                             <option <?php if($activitytype == "Workshop") echo "selected = 'selected'" ?> value = "Workshop">Workshop</option>
							 <option <?php if($activitytype == "FDP") echo "selected = 'selected'" ?> value = "FDP">FDP</option>
                         </select>
                     </div>
                     
					  <div class="form-group col-md-6">
                         <label for="conf">Organized by *</label>
                         <textarea required class="form-control input-lg" id="conf" name="organized" rows="2"><?php echo $organized; ?></textarea>
                     </div>
                     
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input 
                             <?php echo "value = '$startDate'"; ?>
                           required type="date" class="form-control input-lg" id="start-date" name="startDate"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input
                             <?php echo "value = '$endDate'"; ?>
                           required type="date" class="form-control input-lg" id="end-date" name="endDate"
                         placeholder="03:10:10">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="location">Location *</label>
                         <input
                             <?php echo "value = '$location'"; ?>
                           required type="text" class="form-control input-lg" id="location" name="location">
                     </div>

                     
					 
					  	
					 <div class="form-group col-md-6">
                         <label for="fdc">Applied for FDC ? *</label>
                         <select required class="form-control input-lg" id="fdc" name="fdc">
                             <option <?php if($fdc == "yes") echo "selected = 'selected'" ?> value = "yes">Yes</option>
                             <option <?php if($fdc == "no") echo "selected = 'selected'" ?> value = "no">No</option>
                         </select>
                     </div>   

                     <div class="form-group col-md-6">
                        <label for="status_activities">Status of Activity ( Local / State / National / International ) *</label>
                        <select required name="status_activities" id="status_activities" class="form-control input-lg">
                            <option value="" disabled selected>Select your option:</option>
                            <option <?php if($status_activities == "local") echo "selected = 'selected'" ?>  value="local">Local</option>
                            <option <?php if($status_activities == "state") echo "selected = 'selected'" ?>  value="state">State</option>
                            <option <?php if($status_activities == "national") echo "selected = 'selected'" ?>  value="national">National</option>
                            <option <?php if($status_activities == "international") echo "selected = 'selected'" ?> value="international">InterNational</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="no_of_hours">Equivalent duration ( In hours ) *</label>
                        <br><br>
                        <input value = '<?php echo $no_of_hours; ?>' class="form-control input-lg" type="text" name="no_of_hours" id="no_of_hours" placeholder="Hours" required>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="awards">Awards (If Any)</label>
                        <br>
                        <!-- <input class="form-control input-lg" type="text" name="awards" id="awards" value="<?php echo $awards; ?>"> -->
                        <textarea class="form-control input-lg" name="awards" id="awards"><?php echo $awards; ?></textarea>
                    </div>
                    

                    <div class="form-group col-md-12">
                        <button name="update"  type="submit" class="btn btn-success btn-lg">Submit</button>
					<?php	if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{ ?>
                         <a href="2_dashboard_attend_hod.php" type="button" class="btn btn-warning btn-lg pull-right">Cancel</a>

				<?php	}
				else
				{?>
					  <a href="2_dashboard_attend.php" type="button" class="btn btn-warning btn-lg pull-right">Cancel</a>

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
   
   