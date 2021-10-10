
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

$fid=$_SESSION['Fac_ID'];

$_SESSION['currentTab'] = "paper";

//connect to the database 
include("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");

//include config file
include_once("includes/config.php");

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}

//setting error variables
$nameError="";
$emailError="";
$paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coauthors = $volume = "";
$presentationStatus =  $publication = $awards = $presentedby =$paperpath=$reportpath=$certipath= "";
$flag = 1;
$s = 1;
$p_id = 0;
$error1 = $error2 = $error3 = "";
$success = 0;
//		$fid = $_SESSION['Fac_ID'];
	
    $faculty_name= $_SESSION['loggedInUser'];


//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){
    //print_r($_POST);
    //the form was submitted
    $fname_array = $_POST['fname'];
	$paperTitle_array = $_POST['paperTitle'];
	$paperType_array = $_POST['paperType'];
	$paperLevel_array = $_POST['paperLevel'];
	$conf_array = $_POST['conf'];
	$paperCategory_array = $_POST['paperCategory'];
	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$location_array = $_POST['location'];
	$volume_array = $_POST['volume'];
	
		if(isset($_POST['index']))
		{
			$index_array = $_POST['index'];
		}
		else
			$index_array = "NA";

		if(isset($_POST['presentedby']))
		{
			$presentedby_array = $_POST['presentedby'];
		}
		else
			$presentedby_array = "NULL";
		
	$scopus_array = $_POST['scopus'];
	
	$hindex_array = $_POST['hindex'];
	$citation_array = $_POST['citation'];
	
		$applicablefdc_array = $_POST['applicablefdc'];
	
	$fdc_array = $_POST['fdc'];

	$presentationStatus_array = $_POST['presentationStatus'];
	$awards_array = $_POST['awards'];
	$publication_array = $_POST['publication'];
		
    //check for any blank input which are required
    		
for($i=0; $i<1;$i++)
{
$fname = mysqli_real_escape_string($conn,$fname_array[$i]);
$_SESSION['F_NAME'] = $fname ;
$paperTitle = mysqli_real_escape_string($conn,$paperTitle_array[$i]);
$paperType = mysqli_real_escape_string($conn,$paperType_array[$i]);
$paperLevel = mysqli_real_escape_string($conn,$paperLevel_array[$i]);
$conf = mysqli_real_escape_string($conn,$conf_array[$i]);
$paperCategory = mysqli_real_escape_string($conn,$paperCategory_array[$i]);
$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$location = mysqli_real_escape_string($conn,$location_array[$i]);
$volume = mysqli_real_escape_string($conn,$volume_array[$i]);
$index = mysqli_real_escape_string($conn,$index_array[$i]);
$scopus = mysqli_real_escape_string($conn,$scopus_array[$i]);

$hindex = mysqli_real_escape_string($conn,$hindex_array[$i]);
$citation = mysqli_real_escape_string($conn,$citation_array[$i]);

$applicablefdc = mysqli_real_escape_string($conn,$applicablefdc_array[$i]);

$fdc = mysqli_real_escape_string($conn,$fdc_array[$i]);
$_SESSION['fdc'] = $fdc;
$presentationStatus = mysqli_real_escape_string($conn,$presentationStatus_array[$i]);
$awards = mysqli_real_escape_string($conn,$awards_array[$i]);
$publication = mysqli_real_escape_string($conn,$publication_array[$i]);
$presentedby = mysqli_real_escape_string($conn,$presentedby_array[$i]);

$time=time();
$start = new DateTime(date($startDate,$time));
$end = new DateTime(date($endDate,$time));
$days = date_diff($start,$end);
$noofdays = $days->format('%d');

 
        $paperTitle=validateFormData($paperTitle);
    
	
	    $paperType=validateFormData($paperType);
        $paperType = "$paperType";
   
        $paperLevel=validateFormData($paperLevel);
        $paperLevel = "$paperLevel";
   
        $conf=validateFormData($conf);
        $conf = "$conf";
   
        $paperCategory=validateFormData($paperCategory);
        $paperCategory = "$paperCategory";
		

   
			$startDate=validateFormData($startDate);
			$startDate = "$startDate";
			
			$endDate=validateFormData($endDate);
			$endDate = "$endDate";
			
        $location=validateFormData($location);
        $location = "$location";
  
	  
	  //following are not required so we can directly take them as it is
	  if($volume!=""){
		$volume=validateFormData($volume);
		$volume = "$volume";
	}else{
		$volume="NA";
	}

	if($hindex!=NULL){
		$hindex=validateFormData($hindex);
		$hindex = "$hindex";
	}
	else{
		$hindex='0';
	}
	
		$index=validateFormData($index);
		$index = "$index";
	
	if($scopus!=""){
		$scopus=validateFormData($scopus);
		$scopus = "$scopus";
	}else{
		$scopus="NA";
	}
	
	if($citation!=""){
		$citation=validateFormData($citation);
		$citation = "$citation";
	}else{
		$citation='NA';
	}
		
		$presentationStatus=validateFormData($presentationStatus);
        $presentationStatus = "$presentationStatus";
		
		if(isset($_POST['presentationStatus'])){

			$presentationStatus=validateFormData($presentationStatus);
       		$presentationStatus = "$presentationStatus";

			if($presentationStatus == 'Presented')
			{
				if(!$_POST['presentedby']){

					$nameError=$nameError."Please enter by whom it is presented<br>";
					$flag = 0;
				}
				else
				{
					 $presentedby=validateFormData($_POST['presentedby']);
					 $presentedby="$presentedby";

					 $publication=validateFormData($_POST["publication"]);
					 $publication = "$publication";
				}
			}
			
			if($presentationStatus == 'Not Presented')
			{
				//$presentedby="'".$presentedby."'";
				$presentedby= "NULL";
				$publication= "NULL";
				//echo "<script>alert('$presentedby')</script>";
			}
		}
   
	if($applicablefdc == 'Yes')
	{
		$fdc=validateFormData($_POST["fdc"]);
		$fdc = "Yes";		}
	else if($applicablefdc == 'No')
	{
		$fdc = 'Not applicable';
	}
		 
	if($awards!=""){
		$awards=validateFormData($awards);
		$awards = "$awards";
	}else{
		$awards="NA";
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
					  $targetName=$datapath."papers/".$_SESSION['F_NAME']."_papers_".date("d-m-Y H-i-s", time()).".".$fileExt;  
					  
					  if(empty($errors)==true) {
						if (file_exists($targetName)) {   
							unlink($targetName);
						}      
						 $moved = move_uploaded_file($fileTmp,"$targetName");
						 if($moved == true){
						 	$paperpath=$targetName;
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
					  $fileExt=strtolower(end($temp));
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
				//		 else{
				//			 			 echo "<h1> $targetName </h1>";
				//		 }
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
					//	 else{
					//		 			 echo "<h1> $targetName </h1>";
					//	 }
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

	
	  //checking if there was an error or not
	  //echo "<script>alert('$fname')</script>";
        $query = "SELECT Fac_ID from facultydetails where F_NAME = '$fname'";
        $result=mysqli_query($conn,$query);
		//echo "<script>alert('$result')</script>";
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
}
	print_r($flag, $success);
	if($awards==""){
		$awards='NA';
	}
	if($index=="N"){
		$index="NA";
	}
	if($publication==""){
		$publication='NA';
	}
	if($presentedby==""){
		$presentedby='NA';
	}
	if($flag == 1 && $s != 0)
	    {
			
			$sql="INSERT INTO faculty(Fac_ID,Paper_title,Paper_type,Paper_N_I,Paper_co_authors,conf_journal_name,paper_category,Date_from,Date_to, Location,volume,scopusindex,scopus, h_index, citations, FDC_Y_N, presentation_status, Paper_awards, presented_by, Link_publication,paper_path,certificate_path,report_path,noofdays) VALUES ('$author','$paperTitle','$paperType','$paperLevel','NA','$conf','$paperCategory','$startDate','$endDate','$location','$volume','$index','$scopus','$hindex','$citation','$fdc','$presentationStatus','$awards','$presentedby','$publication','".$paperpath."','".$certipath."','".$reportpath."',$noofdays)";
		
			if ($conn->query($sql) === TRUE) 
			{
				$success = 1;
				$p_id= $conn->insert_id;
			} 
			else
			{
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}

		if(!isset($_POST['co_authf']) && !isset($_POST['co_name']) && $s != 0){
			$coauthorname="NA";
			$sqlquery="UPDATE faculty SET Paper_co_authors='$coauthorname' where P_ID=$p_id";
			$conn->query($sqlquery);
	}
		$coauthorname="";
		if(isset($_POST["co_name"]) && $s != 0)
		{
			 for($count2 = 0; $count2 < count($_POST["co_name"]); $count2++)
			 {  	$co_name= $_POST["co_name"][$count2];
					$query="SELECT Fac_ID from facultydetails WHERE F_NAME = '$co_name'";
					$result=mysqli_query($conn, $query);
					$row=mysqli_fetch_assoc($result);
					$val=$row['Fac_ID'];

				  $query = "INSERT INTO co_author (c_id,c_name,p_id) VALUES ($val,'$co_name',$p_id)";
				  $conn->query($query);
				  if($coauthorname=="")
				  {
				  	$coauthorname=$co_name;
				  }
				  else{
				  $coauthorname=$coauthorname.", ".$co_name;	 
				}
			}
			if(!isset($_POST['co_authf']) && $s != 0){
				if($coauthorname==""){
					$coauthorname="NA";
				}
			}
			$sqlquery="UPDATE faculty SET Paper_co_authors='$coauthorname' where P_ID=$p_id";
			$conn->query($sqlquery);
		}
		if(isset($_POST["co_authf"]) && $s != 0)
		{
			for($count1 = 0; $count1 < count($_POST["co_authf"]); $count1++)
			 {  	
			 		$a=" ";
			 		$value="";
			 		$co_authf= $_POST["co_authf"][$count1];
			 		$co_authl=$_POST["co_authl"][$count1];
			 		$co_auth = $co_authf.$a.$co_authl;
			 		$query="SELECT c_id from co_author WHERE c_name = '$co_auth'";
					$result=mysqli_query($conn, $query);
					$row=mysqli_fetch_assoc($result);
					$value=$row['c_id'];
					if($value!=NULL){
						$query = "INSERT INTO co_author (c_id,c_name,p_id) VALUES ($value,'$co_auth',$p_id)";
						$conn->query($query);
					}else{
				  		$query = "INSERT INTO co_author (c_name,p_id) VALUES ('$co_auth',$p_id)";
				  		$conn->query($query);
					}
				  if($coauthorname=="")
				  {
				  	$coauthorname=$co_auth;
				  }
				  else{
				  $coauthorname=$coauthorname.", ".$co_auth;	 
				}
			}
			if($coauthorname==""){
				$coauthorname="NA";
			}
			$sqlquery="UPDATE faculty SET Paper_co_authors='$coauthorname' where P_ID=$p_id";
			$conn->query($sqlquery);
		}
					
 
}//end of for
			if($success == 1 && $s != 0)	
			{
				header("location:2_dashboard_hod.php?alert=success");
			}
			else if($s != 0){
  				header("location:2_dashboard_hod.php?alert=error");
			}

			        
}

}


//close the connection
mysqli_close($conn);

		function fill_unit_select_box($connect)
		{ 
		 $output = '';
		 $query = "SELECT * FROM facultydetails where type='faculty' ORDER BY F_NAME ASC";
		 $statement = $connect->prepare($query);
		 $statement->execute();
		 $result = $statement->fetchAll();
		 foreach($result as $row)
		 {
		  $output .= '<option value="'.$row["F_NAME"].'">'.$row["F_NAME"].'</option>';
		 }
		 return $output;
		}

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
#form {
	width: 100% !important;
	}
</style> 

<?php
include("includes/connection.php");
$Fac_ID = $_SESSION['Fac_ID'];
$query = "SELECT * from facultydetails WHERE Fac_ID = '$Fac_ID' ";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$facultyName = $row['F_NAME'];
$deptName = $row['Dept'];
?>

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
					<h3 class="box-title"><b>Faculty Publication Form</b></h3>
					<br>
					</div>
                </div><!-- /.box-header -->
                <!-- form start -->
	<?php
			
					for($k=0; $k<1 ; $k++)
					{

				?>
				<br>
		 			
					<form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data">
						<?php
							if($flag==0)
							{
								echo '<div class="error">'.$nameError.'</div>';
							}	
						?>
						<?php 
						$replace_str = array('"', "'",'' ,'');
						if(isset($_POST['conf']))
							$conf = str_replace($replace_str, "", $conf);
						else
							$conf  = '';

						if($volume!=""){
							$replace_str = array('"', "'",'' ,'');
							$volume = str_replace($replace_str, "", $volume);
						}else{
							$volume="NULL";
							
						}

						if($awards!=""){
							$replace_str = array('"', "'",'' ,'');
							$awards = str_replace($replace_str, "", $awards);
						}else{
							$awards="NA";
						}

						if($publication!=""){
							$replace_str = array('"', "'",'' ,'');
							$publication = str_replace($replace_str, "", $publication);
						}else{
							$publication="NA";
						}
						?>	

						
							<div class="form-group col-md-6">
								<label for="department_name">Department Name</label>
								<input required type="text" class="form-control input-lg" id="dept" name="dept" value="<?php echo $_SESSION['Dept']; ?>" readonly>
							</div>

							<div class="form-group col-md-6">
								<label for="faculty-name">Faculty Name</label>
								<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
							</div>

							<div class="form-group col-md-6">

								<label for="c_name">Author</label>
								<div class="table-repsonsive">
									<span id="error"></span>
									<table class="table table-bordered" id="a_name">
										<tr>
											<th>Click to select </th>
											<th><button type="button" name="addauth" class="btn btn-success btn-sm addauth"><span class="glyphicon glyphicon-plus"></span></button></th>
										</tr>
									</table>
								</div>
							</div>

							<div class="form-group col-md-6">
								<label for="c_name">Co-Author (Faculty)</label>
								<div class="table-repsonsive">
									<span id="error"></span>
									<table class="table table-bordered" id="c_name">
										<tr>
											<th>Click to select </th>
											<th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
										</tr>
									</table>
								</div>
							</div>


							<div class="form-group col-md-6">
								<label for="co_auth">Co-Author (If Not From Faculty)</label>
								<div class="table-repsonsive">
									<span id="error"></span>
									<table class="table table-bordered" id="co_auth">
										<tr>
											<th>Click to select</th>
											<th><button type="button" name="add1" class="btn btn-success btn-sm add1"><span class="glyphicon glyphicon-plus"></span></button></th>
										</tr>
									</table>
								</div>
							</div>

							<div class="form-group col-md-6">
								<label for="ConfJour">Conference/Journal </label>
								<span class="colour"><b> *</b></span>
								<select required name="ConfJour[]" id="ConfJour" class="form-control input-lg">
									<option value="" disabled selected>Select your option:</option>
									<option name="national_journal" value="national_journal">National Journal(NJ)</option>
									<option name="international_journal" value="international_journal">International Journal(IJ)</option>
									<option name="national_conference" value="national_conference">National Conference(NC)</option>
									<option name="international_conference" value="international_conference">International Conference(IC)</option>
								</select>
							</div>

							<div class="form-group col-md-6">
								<label for="title">Title of Publication/Paper </label>
								<span class="colour"><b> *</b></span>
								<input type="text" required class="form-control input-lg" id="title" name="title[]" <?php if (isset($_POST['resource'])) echo "value = $resource"; ?>>
							</div>

							<div class="form-group col-md-6">
								<label for="jour_name">Affiliation of Publication</label>
								<span class="colour"><b> *</b></span>
								<input type="text" required class="form-control input-lg" id="jour_name" name="jour_name[]" placeholder="Name of Conference/Journal" <?php if (isset($_POST['resource'])) echo "value = $resource"; ?>>
							</div>

							<div class="form-group col-md-6">
								<label for="location">Orgainzed by Institution with Brief Address </label>
								<span class="colour"><b> *</b></span>
								<input type="text" required class="form-control input-lg" id="location" name="location[]" <?php if (isset($_POST['resource'])) echo "value = $resource"; ?>>
							</div>

							<div class="form-group col-md-6">
								<label for="PeerRev">Peer Reviewed </label>
								<span class="colour"><b> *</b></span>
								<select required name="PeerRev[]" id="PeerRev" class="form-control input-lg">
									<option value="" disabled selected>Select your option:</option>
									<option name="yes" value="yes">Yes</option>
									<option name="no" value="no">No</option>
								</select>
							</div>

							<div class="form-group col-md-6">
								<label for="Attended">Attended/Presented </label>
								<span class="colour"><b> *</b></span>
								<select required name="Attended[]" id="Attended" class="form-control input-lg">
									<option value="" disabled selected>Select your option:</option>
									<option name="yes" value="yes">Yes</option>
									<option name="no" value="no">No</option>
								</select>
							</div>

							<div class="form-group col-md-6">
								<label for="start-date">Start Date </label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['startDate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate[]" placeholder="03:10:10">
							</div>

							<div class="form-group col-md-6">
								<label for="end-date">End Date </label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['endDate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate[]" placeholder="03:10:10">
							</div>

							<div class="form-group col-md-6">
								<label for="scopus">Scopus </label>
								<span class="colour"><b> *</b></span>
								<select required name="scopus[]" id="scopus" class="form-control input-lg">
									<option value="" disabled selected>Select your option:</option>
									<option name="yes" value="yes">Yes</option>
									<option name="no" value="no">No</option>
								</select>
							</div>

							<div class="form-group col-md-6">
								<label for="scopus_index">Scopus Index Link </label>
								<input type="url" class="form-control input-lg" id="scopus_index" name="scopus_index[]">
							</div>

							<div class="form-group col-md-6">
								<label for="h_index">H-Index </label>
								<span class="colour"><b> *</b></span>
								<input required type="number" class="form-control input-lg" id="h_index" name="h_index[]">
							</div>

							<div class="form-group col-md-6">
								<label for="impact">Impact Factor </label>
								<span class="colour"><b> *</b></span>
								<input required type="number" class="form-control input-lg" id="impact" name="impact[]">
							</div>

							<div class="form-group col-md-6">
								<label for="sci">SCI Indexed </label>
								<span class="colour"><b> *</b></span>
								<select required name="sci[]" id="sci" class="form-control input-lg">
									<option value="" disabled selected>Select your option:</option>
									<option name="yes" value="yes">Yes</option>
									<option name="no" value="no">No</option>
								</select>
							</div>

							<div class="form-group col-md-6">
								<label for="sci_index">SCI Index Link </label>
								<input type="url" class="form-control input-lg" id="sci_index" name="sci_index[]">
							</div>

							<div class="form-group col-md-6 col-md-offset-1"></div>

							<div class="form-group col-md-6">
								<div>

									&nbsp;<label for="course">Upload paper : Applicable ?<br></label>
									<span class="colour"><b> *</b></span>
									<span class="error" style="border : none;"> <?php echo $error1 ?> </span>
									<br> &nbsp;<input required type='radio' name='applicable' class='non-vac' value='1' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "1") ? 'checked' : '' ?>> Yes <br>
									&nbsp;<input type='radio' name='applicable' class='vac' value='2' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>

									&nbsp;<input type='radio' name='applicable' class='vac' value='3' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "3") ? 'checked' : '' ?>> No <br>
								</div>
								<br>
								<div class='second-reveal' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "1") ? 'style = "display : block" ' : '' ?>>
									<div>
										<label for="card-image">Paper </label><span class="colour"><b> *</b></span>
										<input type="file" class="form-control input-lg" id="card-image" name="paper">
									</div>
								</div>
								<br>
								<div>
									&nbsp;<label for="course">Upload certificate : Applicable ?<br></label><span class="colour"><b> *</b></span>
									<span class="error" style="background-color: none; border : none;"> <?php echo $error2 ?> </span>
									<br> &nbsp;<input required type='radio' name='applicable1' class='non-vac1' value='1' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "1") ? 'checked' : '' ?>> Yes <br>
									&nbsp;<input type='radio' name='applicable1' class='vac1' value='2' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>

									&nbsp;<input type='radio' name='applicable1' class='vac1' value='3' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "3") ? 'checked' : '' ?>> No <br>
								</div>
								<br>
								<div class='second-reveal1' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "1") ? 'style = "display : block" ' : '' ?>>
									<div>
										<label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
										<input type="file" class="form-control input-lg" id="card-image" name="certificate">
									</div>
								</div>
								<br>
								<div>

									&nbsp;<label for="course">Upload report : Applicable ?<br></label><span class="colour"><b> *</b></span>
									<span class="error" style="border : none;"> <?php echo $error3 ?> </span>
									<br> &nbsp;<input required type='radio' name='applicable2' class='non-vac2' value='1' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "1") ? 'checked' : '' ?>> Yes <br>
									&nbsp;<input type='radio' name='applicable2' class='vac2' value='2' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>

									&nbsp;<input type='radio' name='applicable2' class='vac2' value='3' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "3") ? 'checked' : '' ?>> No <br>
								</div>
								<br>
								<div class='second-reveal2' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable2'] == "1") ? 'style = "display : block" ' : '' ?>>
									<div>

										<label for="card-image">Report </label><span class="colour"><b> *</b></span>
										<input type="file" class="form-control input-lg" id="card-image" name="report">
									</div>
								</div>
					</div>
					 
					 <script>
					 
					 $('.presentation-status').each(function(){
						 $('.presentation-status').on('change',myfunction);
					 });
					 
					  $('.applicable-fdc').each(function(){
						 $('.applicable-fdc').on('change',myfunction1);
					 });
					 
						function myfunction(){
						var x = this.value;
					
						if(x=='Presented')
						{
				
							//document.getElementById("demo").innerHTML = "You selected:" +x;
							$(this).parent().next().next()[0].style.display = "block";
							$(this).parent().next().next().next()[0].style.display = "block";
						}
						else
						{
								$(this).parent().next().next()[0].style.display = "none";
							$(this).parent().next().next().next()[0].style.display = "none";
						}
						}
						
							function myfunction1(){
						var x = this.value;
					
						if(x=='Yes')
						{
							$("#fdcapp").show();
						}
						else
						{
							$("#fdcapp").hide();
						}
						}
					 </script>	
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                         <a href="list_of_activities_user.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>

                         <input name="add" value="Submit" type="submit" class="demo btn btn-success pull-right btn-lg">
                         <!--</button>-->
                    </div>
                </form>
                </div>
				
              </div>
           </div>  			   
        </section>

    
</div>
   
<script>

$(document).ready(function(){
 
 $(document).on('click', '.add', function(){
  var html = '';
  html += '<tr>';
  html += '<td><select name="co_name[]" class="form-control item_unit" id="search"><option value="">Select Co-author</option><?php echo fill_unit_select_box($connect); ?></select></td>';
  html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
  $('#c_name').append(html);
 });
 
 $(document).on('click', '.remove', function(){
  $(this).closest('tr').remove();
 });
});

$(document).ready(function(){
 
 $(document).on('click', '.add1', function(){
  var html = '';
  html += '<tr>';
  html += '<td><input type="text" name="co_authf[]" placeholder="First name" class="form-control item_name" /></td>';
  html += '<td><input type="text" name="co_authl[]" placeholder="Last name" class="form-control item_name" /></td>';
  html += '<td><button type="button" name="remove1" class="btn btn-danger btn-sm remove1"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
  $('#co_auth').append(html);
 });
 
 $(document).on('click', '.remove1', function(){
  $(this).closest('tr').remove();
 });
 
});
 </script>   
    
    
    
<?php include_once('footer.php'); ?>
   
   