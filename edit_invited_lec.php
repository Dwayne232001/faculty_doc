<?php
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the user to login page
    header("location:index.php");
}
$_SESSION['currentTab']="faculty";

//connect to database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/config.php");

$Fac_ID=$_SESSION['Fac_ID'];
$queryrun="SELECT * FROM facultydetails where Fac_ID=$Fac_ID";
$resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}


//setting error variables
$nameError="";
$flag = 1;
$emailError="";

// $Fac_ID=null;
// date_default_timezone_set("Asia/Kolkata");
$id = $_SESSION['id'];
if(isset($_POST['rid'])){
	$id = $_POST['rid'];
	$_SESSION['id']=$_POST['rid'];
}
    // $interac_id = $_SESSION['id'];
    // $query = "SELECT * from facInteraction";
	$query = "SELECT * from facInteraction where invited_id = $id";

    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    //print_r($row);
    // $Fac_ID = $row['Fac_ID'];
    $organized = $row['organised_by'];
    $durationf = $row['date_from'];
    $durationt = $row['date_to'];
    $resource = $row['invitation'];
    // $award = $row['award'];
    // $topic = $row['topic'];
    // $details = $row['details'];
    // $tdate = $row['tdate'];
    $paperpath=$row['invitation_path'];
	$certipath=$row['certificate'];

	$fid = $_SESSION['Fac_ID'];	

	$query2 = "SELECT * from facultydetails where Fac_ID = $fid";
	$result2 = mysqli_query($conn,$query2);
	if($result2)
	{
		$row = mysqli_fetch_assoc($result2);
		$F_NAME = $row['F_NAME'];
	}
	$_SESSION['F_NAME'] = $F_NAME;
	   
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['update'])){
    //the form was submitted
    
    //check for any blank input which are required
        $organized=validateFormData($_POST['organised_by']);
		$organized = "'".$organized."'";
		
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

		
        // if($award = ""){
        // 	$award='NA';
        // }else{
		// 	$award=validateFormData($_POST['award']);
		// 	$award="$award";
		// }
   
    //following are not required so we can directly take them as it is
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
				$temp=explode('.',$fileName);
				$fileExt=strtolower(end($temp));
				date_default_timezone_set('Asia/Kolkata');
				$targetName=$datapath."invitations/".$_SESSION['F_NAME']."_invitations_".date("d-m-Y H-i-s", time()).".".$fileExt;  
					  
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
				$temp=explode('.',$fileName);
				$fileExt=strtolower(end($temp));
				date_default_timezone_set('Asia/Kolkata');
				$targetName=$datapath."certificates/".$_SESSION['F_NAME']."_Certificates_".date("d-m-Y H-i-s", time()).".".$fileExt;  	  
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
    //checking if there was an error or not
  $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
				$succ = 0;
				$success1 = 0;	
		$tdate = date("Y-m-d h:i:sa");

		$resource=$_POST['resource'];
       	// $topic=validateFormData($_POST['topic']);
       	// if($topic==""){
       	// 	$topic='NA';
       	// }
		// $details=validateFormData($_POST['details']);
		// if($details = ""){
		// 	$details='NA';
		// }
		// $replace_str = array('"', "'",'' ,'');
		// if(isset($_POST['award']))
		// 	$award = str_replace($replace_str, "", $award);
		// $replace_str = array('"', "'",'' ,'');
		// if(isset($_POST['details']) && $_POST['details']!="")
		// {
		// 	$details = $_POST['details'];
		// 	$details = "$details";
		// }else{
		// 	$details="NA";
		// }
		// $replace_str = array('"', "'",'' ,'');
		// if(isset($_POST['topic']))
		// {
		// 	$topic = str_replace($replace_str, "", $topic);
		// 	$topic = str_replace("rn",'', $topic);
		// }
		if($flag==1)
		{		
			$sql = "update facInteraction set organised_by = $organized,
								date_from = $startDate,
							    date_to = $endDate,
								invitation ='$resource',
								invitation_path='".$paperpath."',
								certificate='".$certipath."',
								noofdays = $noofdays
								WHERE invited_id = '".$_SESSION['id']."' ";

								// award = '$award',
								// topic = '$topic',
								// details = '$details',

			if ($conn->query($sql) === TRUE)
			{
				if($_SESSION['type'] == 'hod')
					{
					   header("location:view_invited_hod_lec.php?alert=update");
					}
					else
					{
						header("location:view_invited_lec.php?alert=update");
					}
			}
			else
			{
				if($_SESSION['type'] == 'hod')
					{
					   header("location:view_invited_hod_lec.php?alert=error");
					}
					else
					{
						header("location:view_invited_lec.php?alert=error");
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
					<h3 class="box-title"><b>Faculty Interaction Edit Form</b></h3>
					<br>
					<br>
					
            <form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data">
<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
			?>		

<?php 
// $replace_str = array('"', "'",'' ,'');
// if(isset($_POST['award']))
// $award = str_replace($replace_str, "", $award);

// $replace_str = array('"', "'",'' ,'');
// if(isset($_POST['topic']))
// {
// $topic = str_replace($replace_str, "", $topic);
// $topic = str_replace("rn",'', $topic);

// }

?>					
                <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
				<input type="hidden" name="Udate" value="<?php echo date("Y-m-d h:i:sa"); ?>" />
				
	<?php if($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'faculty' )
					{ ?>					
				<div class="form-group col-md-6">

                         <label for="faculty-name">Faculty Name</label>
                         <input type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                     </div>
					<?php } ?>			

                     <div class="form-group col-md-6">
                         <label for="organized">Organized By *</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="course[]">-->
                      <input required  type="text" class="form-control input-lg"  name="organised_by" value='<?php echo $organized ?>'>
                     </div>

                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input <?php echo "value = $durationf"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate"
                         >
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input <?php echo "value = $durationt"; ?> required type="date" class="form-control input-lg"  id="end-date" name="endDate"
                         >
                     </div>
<!--              
                     <div class="form-group col-md-6">
                         <label for="details">Awards, If Any   </label>
                         <input type="text" class="form-control input-lg"  id="award" name="award" rows="2"  value="<?php echo $award; ?>">
                     </div>
                -->
					 <div class="form-group col-md-6 col-md-offset-1"></div>
          			<div class="form-group col-md-6" >
                        <label for="resource">Invited As A Resource Person For</label><span class="colour"><b> *</b></span>
						<select required class="form-control input-lg resource" id="resource" name="resource">
                            <option <?php if($resource == "paper_review") echo "selected = 'selected'" ?> value ="paper_review">Paper Review</option>
                            <option  <?php if($resource == "session_chair") echo "selected = 'selected'" ?> value ="session_chair">Session Chair</option>
                            <option  <?php if($resource == "mem_of_program_committee") echo "selected = 'selected'" ?> value ="mem_of_program_committee">Member of Program Committee</option>
                            <option  <?php if($resource == "editor") echo "selected = 'selected'" ?> value ="editor">Editor</option>
                            <option  <?php if($resource == "board_of_studies") echo "selected = 'selected'" ?> value ="board_of_studies">Board of Studies</option>
							<option  <?php if($resource == "mentor") echo "selected = 'selected'" ?> value ="mentor">Mentor</option>
                            <option  <?php if($resource == "judge") echo "selected = 'selected'" ?> value ="judge">Judge</option>
                            <option  <?php if($resource == "guest_speaker") echo "selected = 'selected'" ?> value ="guest_speaker">Guest Speaker</option>
                            <option  <?php if($resource == "evaluator") echo "selected = 'selected'" ?> value ="evaluator">Evaluator</option>
                            <option  <?php if($resource == "Examiner_for_M.Tech/Ph.D") echo "selected = 'selected'" ?> value ="Examiner_for_M.Tech/Ph.D">Examiner for M.Tech or Ph.D</option>
                            <option  <?php if($resource == "Paper_setter") echo "selected = 'selected'" ?> value ="Paper_setter">Paper Setter</option>
                            <option  <?php if($resource == "interviewer") echo "selected = 'selected'" ?> value ="interviewer">Interviewer</option>
                            <option  <?php if($resource == "others") echo "selected = 'selected'" ?> value ="others">Other</option>

                        </select>
                  </div>
			
					<!-- <div id="lecture" class="form-group col-md-6">
						<label for="topic">Topic Of Lecture</label><span class="colour"><b> *</b></span>
						<input type="text" class="form-control input-lg" id= "topic" name="topic" rows="2" value="<?php echo $topic; ?>">
					</div>
					 
					<div id="activity"  class= "form-group col-md-6">
						<label for="details">Details Of The Activity</label><span class="colour"><b> *</b></span>
						<input type="text" class="form-control input-lg" id= "details" name="details" rows="2" value="<?php echo $details; ?>">
                    </div> -->

					<div class="form-group col-md-6 col-md-offset-1"></div>

<div class="form-group col-md-6">
                    <div>
                         <label for="Index">Invitation : </label><br/>
						  <input type="radio" id="r1" name="applicable" value="1" class="non-vac" <?php  echo($paperpath!=NULL)?'checked':'' ?>>Yes
						  <br>
						<input type="radio" name="applicable" value="2" class="vac" <?php echo ($paperpath=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
						<input type="radio" name="applicable" value="3" class="vac" <?php echo ($paperpath=='not_applicable')?'checked':'' ?>> No
					</div>
					<br>
                    <div class='second-reveal' id='f1'>
						 <div>
							 
                    	     <label for="card-image">Invitation </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="paper">
		        	          <a <?php 
		        	          $f=0;
		        	          if($paperpath!="not_applicable" && $paperpath!="NULL" && $paperpath!='no status' && $paperpath!=""){
		        	          	echo "href='$paperpath'";
		        	          	$f=1;
		        	          }else{
		        	          	echo "style='display:none'";
		        	          }
		        	          ?> target="_blank"><h4><?php if($f==1){echo "View Existing Invitation";} ?></h4></a>
	        	        </div> 
					</div>
					<br>
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
		        	          if($certipath!="not_applicable" && $certipath!="NULL" && $certipath!="no status" && $certipath!=""){
		        	          	echo "href='$certipath'";
		        	          	$f1=1;
		        	          }else{
		        	          	echo "style='display:none'";
		        	          }
		        	          ?> target="_blank"><h4><?php if($f1==1){echo "View Existing Certificate";} ?></h4></a>
	        	        </div> 
					</div>
	</div>

					 <script>
					 
					 window.onload = function() {
						 mycheck1();
						mycheck2();
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

					 </script>
                    <br/>
                    <div class="form-group col-md-12">
	<?php if($_SESSION['type'] == 'hod')
					{ ?>				
                        <a href="view_invited_hod_lec.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
					<?php }
					else{  ?>
        <a href="view_invited_lec.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
	
					<?php } ?>			
                        <button name="update"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>
</div>
<?php include_once('footer.php'); ?>