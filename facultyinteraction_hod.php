<?php
ob_start();
session_start();
//check if user has logged in or not
 include_once('head.php'); 
 include_once('header.php'); 

 //connect ot database
include_once("includes/connection.php");

  $_SESSION ["currentTab"] = "faculty"; 
if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}

if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod' && $_SESSION['type'] != 'cod' && $_SESSION['type']!='com'){
	//if not hod then send the user to login page
	session_destroy();
	header("location:index.php");
  }
  }  
  
  $fid=$_SESSION['Fac_ID'];
  
  $queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
  $resultrun = mysqli_query($conn, $queryrun);
  while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
  }



//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");
include_once("includes/config.php");


if($_SESSION['type'] == 'hod')
  {
	    include_once('sidebar_hod.php');

  }elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
  		include_once('sidebar_cod.php');
  }
  else{
  	include_once('sidebar.php');
  }

//setting error variables
$nameError="";
$topic=$award=$organized=$details="";
$durationf=$durationt="";
$invitation_path=$invitation2 =$paperpath=$certipath= "";
$flag= 1;
$success = 0;
$s = 1;
$p_id = 0;
$error1 = $error2 = "";
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
   
	$topic_array = $_POST['topic'];
	$durationf_array= $_POST['durationf'];
	$durationt_array = $_POST['durationt'];
		$organized_array = $_POST['organized'];
		$details_array = $_POST['details'];

	$resource_array = $_POST ['resource'];
	$award_array = $_POST['award'];
    		
for($i=0; $i<1;$i++)
{
	$fname = mysqli_real_escape_string($conn,$fname_array[$i]);
	$_SESSION['F_NAME'] = $fname;
$topic = mysqli_real_escape_string($conn,$topic_array[$i]);

$durationf = mysqli_real_escape_string($conn,$durationf_array[$i]);
$details = mysqli_real_escape_string($conn,$details_array[$i]);
$durationt = mysqli_real_escape_string($conn,$durationt_array[$i]);
$organized = mysqli_real_escape_string($conn,$organized_array[$i]);
$resource = mysqli_real_escape_string($conn,$resource_array[$i]);
$award = mysqli_real_escape_string($conn,$award_array[$i]);



 
        $topic=validateFormData($topic);
        $topic = "'".$topic."'";
		
        $organized=validateFormData($organized);
        $organized = "'".$organized."'";
		
        $durationf=validateFormData($durationf);
        $durationf = "'".$durationf."'";
		
        $durationt=validateFormData($durationt);
        $durationt = "'".$durationt."'";
		
		if ($durationf > $durationt)		
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}
		
        $resource=validateFormData($resource);
        $resource = "'".$resource."'";
		
        $award=validateFormData($award);
        $award = "'".$award."'";
	
	
        $details=validateFormData($details);
        $details = "'".$details."'";
		
	
	  //checking if there was an error or not
         $query = "SELECT Fac_ID from facultydetails where F_NAME = '$fname'";
        $result=mysqli_query($conn,$query);
		//echo "<script>alert('$result')</script>";
       if($result){
		   //echo "<script>alert('$author')</script>";
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
	$replace_str = array('"', "'",'' ,'');
if(isset($_POST['award']))
$award = str_replace($replace_str, "", $award);
else
	$award  = '';

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['details']))
{
$details = str_replace($replace_str, "", $details);
$details = str_replace("rn",'', $details);

}
else
	$details  = '';



$replace_str = array('"', "'",'' ,'');
if(isset($_POST['topic']))
{
$topic = str_replace($replace_str, "", $topic);
$topic = str_replace("rn",'', $topic);

}
else
	$topic  = '';   
		
if(isset($_POST['applicable']))
			{
				// console.log($_POST['applicable']);
				if($_POST['applicable'] == 2)
				{
					$invitation_path='NULL';
					$success=1;
				}
				else if($_POST['applicable'] == 3)
				{
					$invitation_path='not_applicable';
					$success=1;	
				}
				else if($_POST['applicable'] == 1)
				{
					if(isset($_FILES['invitation']) && $_FILES['invitation']['name'] != NULL && $_FILES['invitation']['name'] !="")
					{
					  $errors= array();
					  $fileName = $_FILES['invitation']['name'];
					  $fileSize = $_FILES['invitation']['size'];
					  $fileTmp = $_FILES['invitation']['tmp_name'];
					  $fileType = $_FILES['invitation']['type'];
					  $temp=explode('.',$fileName);
					  $fileExt=strtolower(end($temp));
					  date_default_timezone_set('Asia/Kolkata');
					  $targetName=$datapath."invitations/".$_SESSION['F_NAME']."_invitations_".date("d-m-Y H-i-s", time()).".".$fileExt;  
					  
					  if(empty($errors)==true) {
						if (file_exists($targetName)) {   
							unlink($targetName);
						}      
						 $moved = move_uploaded_file($fileTmp,"$targetName");
						 if($moved == true){
							$invitation_path=$targetName;
						 	$success=1;
						 }
					//	 else{
							 //not successful
							 //header("location:error.php");
					//		 			 echo "<h1> $targetName </h1>";
					//	 }
					  }else{
						 print_r($errors);
						//header("location:else.php");
					  }
					}
					else{
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
				//		 else{
				//			 			 echo "<h1> $targetName </h1>";
				//		 }
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
			if($award==""){
				$award="NA";
			}
			if($topic==""){
				$topic="NA";
			}
			if($details==""){
				$details="NA";
			}
	if($flag == 1 && $s != 0)
	{		
        $sql="INSERT INTO invitedlec(Fac_ID,organized,durationf,durationt, res_type, award,topic,details,invitation_path,certificate_path) VALUES ('$author',$organized,$durationf,$durationt, $resource, '$award','$topic','$details','".$invitation_path."','".$certipath."')";

			if ($conn->query($sql) === TRUE) {
				header("location:view_invited_hod_lec.php?alert=success");
			} else if($s != 0){
				header("location:view_invited_hod_lec.php?alert=error");
			}
						

	}
	
}//end of for
		
			        
}

}


//close the connection
//mysqli_close($conn);
?>
<script>

function yesnoCheck() {
    if (document.getElementById('lec').checked) {
        document.getElementById('ifYesLec').style.visibility = 'visible';
		document.getElementById('ifYesOther').style.visibility = 'hidden';
    }
    else if (document.getElementById('other').checked) {
		document.getElementById('ifYesLec').style.visibility = 'hidden';
		document.getElementById('ifYesOther').style.visibility = 'visible';
	}

}
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
					<h3 class="box-title"><b>Faculty Interaction Details Form</b></h3>
					<br>
					</div>				 
					<br> 				 
                </div><!-- /.box-header -->
                <!-- form start -->

	<?php
			
					for($k=1; $k<=1; $k++)
					{

				?>
			<form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data">
	<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
			?>		

<?php 
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['invitation']))
$invitation = str_replace($replace_str, "", $invitation);
else
	$invitation  = '';

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['certificate']))
{
$certificate = str_replace($replace_str, "", $certificate);
$certificate = str_replace("rn",'', $certificate);

}
else
	$certificate  = '';



// $replace_str = array('"', "'",'' ,'');
// if(isset($_POST['topic']))
// {
// $topic = str_replace($replace_str, "", $topic);
// $topic = str_replace("rn",'', $topic);

// }
// else
// 	$topic  = '';
?>
						<div class="form-group col-md-6">
							<label for="department_name">Department Name</label>
							<input required type="text" class="form-control input-lg" id="department_name" name="department_name" value="<?php echo $faculty_name; ?>" readonly>
						</div>

						<div class="form-group col-md-6">
						<label for="faculty-name">Faculty Name</label>
						<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
						</div>

                        <div class="form-group col-md-6">
                        <label for="invitation">Description of Invitation</label>
                        <span class="colour"><b> *</b></span>
                        <select required name="invitation[]" id="invitation" class="form-control input-lg" >
                            <option value="" disabled selected>Select your option:</option>
                            <option name="paper_review" value="paper_review">Paper Review</option>
                            <option name="session_chair" value="session_chair">Session Chair</option>
                            <option name="mem_of_program_committee" value="mem_of_program_committee">Member of Program Committee</option>
                            <option name="editor" value="editor">Editor</option>
                            <option name="board_of_studies" value="board_of_studies">Board of Studies</option>
                            <option name="mentor" value="mentor">Mentor</option>
							<option name="judge" value="judge">Judge</option>
							<option name="guest_speaker" value="guest_speaker">Guest Speaker</option>
							<option name="evaluator" value="evaluator">Evaluator</option>
							<option name="Examiner_for_M.Tech/Ph.D" value="Examiner_for_M.Tech/Ph.D">Examiner for M.Tech or Ph.D</option>
							<option name="Paper_setter" value="Paper_setter">Paper Setter</option>
							<option name="interviewer" value="interviewer">Interviewer</option>
							<option name="others" value="others">Other</option>
                        </select>
						</div>

					 	<div class="form-group col-md-6">
                         <label for="organization">Organized By </label>
						 <span class="colour"><b> *</b></span>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input required type="text" class="form-control input-lg" placeholder="Institute/Organisation/Agency"  name="organized_by[]" id="organized" <?php echo "value = $organized"; ?> ><br></div>
					  
					  
						
						<div class="form-group col-md-6">
						<label for="durationf">Duration From</label>
						<span class="colour"><b> *</b></span>
						<input <?php if(isset($_POST['durationf'])) echo "value = $durationf"; ?> required type="date" class="form-control input-lg" id="durationf" name="date_from[]" value=""><br> </div>
						
                    	<div class="form-group col-md-6">
                         <label for="durationt"> Duration To</label>
						 <span class="colour"><b> *</b></span>
                         <input <?php if(isset($_POST['durationt'])) echo "value = $durationt"; ?> required type="date" class="form-control input-lg" id="durationt" name="date_to[]" value=""><br>
						</div>

						<div class="form-group col-md-6">
						<label for="Month">Month</label>
						<input required type="text" class="form-control input-lg" id="month" name="month" value="<?php echo $faculty_name; ?>" readonly>
						</div>
						
						<div class="form-group col-md-6">
						<label for="Year">Year</label>
						<input required type="text" class="form-control input-lg" id="year" name="year" value="<?php echo $faculty_name; ?>" readonly>
						</div>

						 
						 <!-- <div class="form-group col-md-6">
						
					     <label for="award">Awards, If Any </label>
                         <input type="text" class="form-control input-lg" id="award" name="award[]" value = '<?php if(isset($_POST['award'])) echo $award; ?>'>
						 </div>
										
						 <div class="form-group col-md-6 col-md-offset-1"></div>
				 <div class="form-group col-md-6" >
                         <label for="resource">Invited As A Resource Person For</label><span class="colour"><b> *</b></span>
                         <select required  class="form-control input-lg resource" id="resource" name="resource[]">
                            <option  value ="" >--Select Category--</option>

							<option <?php if(isset($_POST['resource'])) if($resource == "lecture") echo "selected = 'selected'" ?>  value ="lecture">Lecture </option>
							<option <?php if(isset($_POST['resource'])) if($resource == "talk") echo "selected = 'selected'" ?>  value ="talk">Talk </option>
							<option <?php if(isset($_POST['resource'])) if($resource == "workshop") echo "selected = 'selected'" ?>  value ="workshop">Workshop </option>
							<option <?php if(isset($_POST['resource'])) if($resource == "conference") echo "selected = 'selected'" ?>  value ="conference">Conference </option>
                             <option <?php if(isset($_POST['resource'])) if($resource == "other") echo "selected = 'selected'" ?> value ="other">Any Other</option>
                         </select>
                  </div>
					   
						 
				<div  class="form-group col-md-6" id="lecture">
                    <label> Topic Of Lecture</label><span class="colour"><b> *</b></span>
					<input type="text" class="form-control input-lg" id= "topic" name="topic[]"  value = '<?php if(isset($_POST['topic'])) echo $topic; ?>'>
                </div>
					
				<div  class="form-group col-md-6" id="activity">
                    <label> Details Of The Activity</label><span class="colour"><b> *</b></span>
					<input type="text" class="form-control input-lg" id= "details" name="details[]"  value = '<?php if(isset($_POST['details'])) echo $details; ?>'>
				</div>

				<div class="form-group col-md-6 col-md-offset-1"></div>-->
				<div class="form-group col-md-6">
					<div> 

						&nbsp;<label for="course">Upload Invitation : Applicable ?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "background-color: none; border : none;"> <?php echo $error1 ?> </span>
						<br>	&nbsp;<input required type='radio' name='applicable' class='non-vac' value='1' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'checked':'' ?>> Yes <br>
						&nbsp;<input type='radio' name='applicable' class='vac' value='2' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="2")?'checked':'' ?>> Applicable, but not yet available <br>
										 
						&nbsp;<input type='radio' name='applicable' class='vac' value='3'  <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="3")?'checked':'' ?>> No <br>
					</div>
					<br>
					<div class='second-reveal'  <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'style = "display : block" ':'' ?>>
						 <div>
							 
                    	     <label for="card-image">Invitation </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="paper">
	        	        </div> 
					</div>
                     <div>

						&nbsp;<label for="course">Upload certificate : Applicable ?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "background-color: none; border : none;"> <?php echo $error2 ?> </span>
						<br>	&nbsp;<input required type='radio' name='applicable1' class='non-vac1' value='1' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'checked':'' ?>> Yes <br>
						&nbsp;<input type='radio' name='applicable1' class='vac1' value='2' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="2")?'checked':'' ?>> Applicable, but not yet available <br>
										 
						&nbsp;<input type='radio' name='applicable1' class='vac1' value='3' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="3")?'checked':'' ?>> No <br>
					</div>
					<br>
					<div class='second-reveal1'  <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'style = "display : block" ':'' ?>>
						 <div> 
                    	     <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="certificate">
	        	        </div> 
					</div>					 
			</div>	
                    
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                        

                         <input type="submit" name="add" class="btn btn-success pull-right btn-lg" value = "Submit" />
						 
						 <a href="list_of_activities_user.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
                    </div>
                </form>
				</div>
                </div>
              </div>
            
        </section>
   </div>
    
    
    
<?php include_once('footer.php'); ?>