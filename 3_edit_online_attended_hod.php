<?php
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the user to login page
    header("location:index.php");
}
//connect to database
if(isset($_SESSION['type'])){
    if($_SESSION['type'] != 'hod'){
    //if not hod then send the user to login page
    session_destroy();
    header("location:index.php");
}
}

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
$_SESSION['currentTab']="Online";
//setting error variables
$nameError="";
$flag = 1;
$emailError="";
$courseName = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coAuthors = "";

$Fac_ID=null;
if(isset($_POST['id'])){
    $id = $_POST['id'];
    $query = "SELECT * from online_course_attended where OC_A_ID = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    //print_r($row);
    $Fac_ID = $row['Fac_ID'];
    $courseName = $row['Course_Name'];
    $startDate = $row['Date_From'];
    $endDate = $row['Date_To'];
    $organised = $row['Organised_by'];
    $purpose = $row['Purpose'];
    //echo $purpose."dhasjdjkanskdnkasnjkdnkjasnd".$endDate;
    $fdc_db = $row['FDC_Y_N'];

    /*  MY CODE */
    $type_of_course_db = $row['type_of_course'];
    $status_of_activity_db = $row['status_of_activity'];
    $duration = $row['duration'];
    $credit_audit_db = $row['credit_audit'];

}//Notice: Undefined variable: Fac_ID in C:\xampp\htdocs\extc\3_edit_online.php on line 41
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
    
		$type=validateFormData($_POST['type']);
        $type = "'".$type."'";
		
		$status=validateFormData($_POST['status']);
        $status = "'".$status."'";
		
		$duration=validateFormData($_POST['duration']);
        $duration = "'".$duration."'";
		
		$creau=validateFormData($_POST['creau']);
        $creau = "'".$creau."'";
    //following are not required so we can directly take them as it is

    $purpose=validateFormData($_POST["purpose"]);
    $purpose = "'".$purpose."'";
  

	$applicablefdc = $_POST["applicablefdc"];
	$fdc = $applicablefdc;
	
	if($applicablefdc == 'Yes')
	{
		$fdc=validateFormData($_POST["fdc"]);
	
	}
	else if($applicablefdc == 'No')
	{
		
		$fdc = "Not applicable";
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
	
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['purpose']))
{
$purpose = str_replace($replace_str, "", $purpose);
$purpose = str_replace("rn",'', $purpose);

}
else
	$purpose  = '';				

	if($flag != 0)
	{	

    $sql = "update online_course_attended set Course_Name = $courseName,
							   Date_from = $startDate,
							   Date_to = $endDate, 
							   Organised_by = $organised,
							   Purpose ='$purpose',
							   type_of_course=$type,
							   status_of_activity=$status,
							   duration=$duration,
							   credit_audit=$creau,
							   FDC_Y_N= '$fdc'
							   WHERE OC_A_ID = $id";

  /* $sql1= "update fdc_online_course set Course_Name = $courseName where OC_A_ID = $id ";
				$result1=mysqli_query($conn, $sql1);	 */
							   
							   
			$result = mysqli_query($conn,$sql);

				
				
				$success = 1;

				if($success == 1 && preg_match('/no/', $fdc))
				{
					$sql="delete from fdc_online_course where OC_A_ID = $id";
					$result = mysqli_query($conn,$sql);
					$succ =1;
					
				}			
				else if($success == 1 && preg_match('/Not applicable/', $fdc))
				{
					$sql="delete from fdc_online_course where OC_A_ID = $id";
					$result = mysqli_query($conn,$sql);
					$succ =1;
					
				}		
				
    if($success ==1 )
    {
        if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
        {
            header("location:2_dashboard_hod_online_attended.php?alert=update");

        }
        else
        {
            header("location:2_dashboard_online_attended.php?alert=update");

        }
    }
    else
        header("location:2_dashboard_online_attended.php");
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
					<h3 class="box-title"><b>Online/Offline Course Attended Edit Form</b></h3>
					<br>
				</div>                              </div><!-- /.box-header -->
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
				
	

			?>				
                        <div class="form-group col-md-6">
                            <label for="faculty-name">Faculty Name</label>
                            <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                        </div>
						<div class="form-group col-md-6">
                                <label for="type">Type Of Course*</label>
                                <select required class="form-control input-lg" value='<?php echo $type_of_course; ?>' id="type" name="type">
                                    <option <?php if($type_of_course_db == "online") echo "selected = 'selected'" ?> value = "online">Online</option>
                                    <option <?php if($type_of_course_db == "offline") echo "selected = 'selected'" ?> value = "offline">Offline</option>
                                </select>
                            </div>

                            <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
                            <div class="form-group col-md-6">
                                <label for="courseName">Name of course *</label>
                                <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="course[]">-->
                                <input required type="text" class="form-control input-lg" id="courseName"  name="courseName" value='<?php echo $courseName ?>' >
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
                                <label for="organised">Course organised by *</label>
                         <input value='<?php echo $organised ?>'  required type="text" class="form-control input-lg"  id="organised" name="organised">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="purpose">Purpose of Course * </label>
                         <textarea  required class="form-control input-lg"  id="purpose" name="purpose" rows="2" value="$row"><?php echo $purpose; ?></textarea>
                            </div>

                        

                            <div class="form-group col-md-6">
                                <label for="status">Status Of Activity *</label>
                                <select required class="form-control input-lg" id="status" value='<?php echo $status_of_activity; ?>' name="status">
                                     <option <?php if($status_of_activity_db == "local") echo "selected = 'selected'" ?> value = "local">Local</option>
									<option <?php if($status_of_activity_db == "state") echo "selected = 'selected'" ?> value = "state">State</option>
									<option <?php if($status_of_activity_db == "national") echo "selected = 'selected'" ?> value="national">National</option>
									<option <?php if($status_of_activity_db == "international") echo "selected = 'selected'" ?> value="international">International</option>
							</select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="duration">Enter the durationof the course in hrs/week</label>
                    <input <?php echo "value = $duration"; ?> type="text" class="form-control input-lg"  id="duration" name="duration">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="creau">Credit/Audit *</label>
                                <select required class="form-control input-lg" id="creau" value='<?php echo $credit_audit; ?>' name="creau">
                                    <option <?php if($credit_audit_db == "credit") echo "selected = 'selected'" ?> value = "credit">Credit</option>
                                    <option <?php if($credit_audit_db == "audit") echo "selected = 'selected'" ?> value = "audit">Audit</option>
                                </select>
                            </div>
							 <div class="form-group col-md-6">
                         <label for="applicable-fdc">Is FDC applicable? </label><span class="colour"><b> *</b></span>
                         <select required onchange="myfunction1()" class="form-control input-lg applicable-fdc" id="applicable-fdc" name="applicablefdc">
                             <option <?php if($fdc_db == "Not applicable") echo "selected = 'selected'" ?> value ="No">No</option>
                             <option <?php if($fdc_db == 'no' || $fdc_db =='yes' || $fdc_db == 'No' || $fdc_db == 'Yes') echo "selected = 'selected'" ?> value ="Yes">Yes</option>
                         </select>
                     </div>
					 <div class="form-group col-md-6" >
					  </div>
					  
					  <div id="fdc" class="form-group col-md-6" style="display:none">
                         <label for="fdc">Applied for FDC ? </label><span class="colour"><b> *</b></span>
                         <select  class="form-control input-lg" id="fdc" name="fdc">
                             <option <?php if($fdc_db == "yes") echo "selected = 'selected'" ?> value = "yes">Yes</option>
                             <option <?php if($fdc_db == "no") echo "selected = 'selected'" ?> value = "no">No</option>
                         </select>
                     </div>   
					  <script>
					 
					 window.onload = function() {
						 myfunction1();

					 }
					   	 
					 
					
			
						function myfunction1(){
								 var y = document.getElementById("applicable-fdc").value;

						if(y=='Yes')
						{
				
							document.getElementById("fdc").style.display = 'block';
						
						}
						else
						{
							document.getElementById("fdc").style.display = 'none';
						}
						}
					 
					 </script>

							
							
							
                            <br/>
                            <div class="form-group col-md-12">
                                <a href="2_dashboard_hod_online_attended.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

                                <button name="update"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php include_once('footer.php'); ?>