<?php
ob_start();
session_start();
include_once('head.php');
include_once('header.php');
//check if user has logged in or not
if (!isset($_SESSION['loggedInUser'])) {
	//send the iser to login page
	header("location:index.php");
}

$_SESSION['currentTab'] = "books";

//connect to the database
include_once("includes/connection.php");

$fid = $_SESSION['Fac_ID'];

$queryrun = "SELECT * FROM facultydetails where Fac_ID=$fid ";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
	$_SESSION['Dept'] = $row['Dept'];
	$_SESSION['type'] = $row['type'];
	$namefac = $row['F_NAME'];
	$_SESSION['F_NAME'] = $row['F_NAME'];
	$F_NAME = $row['F_NAME'];
}
//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");

//include config file
include_once("includes/config.php");

if ($_SESSION['type'] != 'faculty') {
	header("location:index.php");
}

//setting error variables
$nameError = "";
$emailError = "";
$papererror = "";
// $paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coauthors = $volume = ""; 
// $presentationStatus = $index= $publication = $awards = $presentedby = $paperpath=$reportpath=$certipath="";
$flag = 1;
$success = 0;
$count1 = 1;
$s = 1;
$p_id = 0;
$faculty_name = $_SESSION['loggedInUser'];
$error1 = $error2 = $error3 = "";

//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['add'])) {
		//the form was submitted
		// $auth_array = $_POST['auth_name'];
		$first_array = $_POST['first_name'];
		$last_array = $_POST['last_name'];
		$type_array = $_POST['book_type'];
		$title_array = $_POST['title'];
		$edition_array = $_POST['edition'];
		$publisher_array = $_POST['publisher_name'];
		$chapter_array = $_POST['chapter_no'];
		$issn_array = $_POST['issn_no'];
		$date_array = $_POST['date'];
		$url_array = $_POST['url'];

		// $paperTitle_array = $_POST['paperTitle'];
		// $paperType_array = $_POST['paperType'];
		// $paperLevel_array = $_POST['paperLevel'];
		// $conf_array = $_POST['conf'];
		// $paperCategory_array = $_POST['paperCategory'];
		// $startDate_array = $_POST['startDate'];
		// $endDate_array = $_POST['endDate'];
		// $location_array = $_POST['location'];
		// $volume_array = $_POST['volume'];

		if (isset($_POST['index'])) {
			$index_array = $_POST['index'];
		} else {
			$index_array = 'NA';
		}


		// $scopus_array = $_POST['scopus'];

		// $hindex_array = $_POST['hindex'];
		// $citation_array = $_POST['citation'];

		// $applicablefdc_array = $_POST['applicablefdc'];

		// $fdc_array = $_POST['fdc'];
		// $presentationStatus_array = $_POST['presentationStatus'];
		// $awards_array = $_POST['awards'];
		// $presentedby_array = $_POST['presentedby'];
		// $publication_array = $_POST['publication'];


		for ($i = 0; $i < 1; $i++) {
			// $auth = mysqli_real_escape_string($conn,$auth_array[$i]);
			$first = mysqli_real_escape_string($conn, $first_array[$i]);
			$last = mysqli_real_escape_string($conn, $last_array[$i]);
			$type = mysqli_real_escape_string($conn, $type_array[$i]);
			$title = mysqli_real_escape_string($conn, $title_array[$i]);
			$edition = mysqli_real_escape_string($conn, $edition_array[$i]);
			$publisher = mysqli_real_escape_string($conn, $publisher_array[$i]);
			$chapter = mysqli_real_escape_string($conn, $chapter_array[$i]);
			$issn = mysqli_real_escape_string($conn, $issn_array[$i]);
			$date = mysqli_real_escape_string($conn, $date_array[$i]);
			$url = mysqli_real_escape_string($conn, $url_array[$i]);

			$time = time();
			$date_calc = new DateTime(date($date, $time));
			$month = $date_calc->format('n');
			$year = $date_calc->format('Y');

			// $paperTitle = mysqli_real_escape_string($conn,$paperTitle_array[$i]);
			// $paperType = mysqli_real_escape_string($conn,$paperType_array[$i]);
			// $paperLevel = mysqli_real_escape_string($conn,$paperLevel_array[$i]);
			// $conf = mysqli_real_escape_string($conn,$conf_array[$i]);
			// $paperCategory = mysqli_real_escape_string($conn,$paperCategory_array[$i]);
			// $startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
			// $endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
			// $location = mysqli_real_escape_string($conn,$location_array[$i]);
			// $volume = mysqli_real_escape_string($conn,$volume_array[$i]);
			// $index = mysqli_real_escape_string($conn,$index_array[$i]);
			// $scopus = mysqli_real_escape_string($conn,$scopus_array[$i]);

			// $hindex = mysqli_real_escape_string($conn,$hindex_array[$i]);
			// $citation = mysqli_real_escape_string($conn,$citation_array[$i]);

			// $applicablefdc = mysqli_real_escape_string($conn,$applicablefdc_array[$i]);

			// $fdc = mysqli_real_escape_string($conn,$fdc_array[$i]);
			// $_SESSION['fdc'] = $fdc;
			// $presentationStatus = mysqli_real_escape_string($conn,$presentationStatus_array[$i]);
			// $awards = mysqli_real_escape_string($conn,$awards_array[$i]);
			// $publication = mysqli_real_escape_string($conn,$publication_array[$i]);
			// $presentedby = mysqli_real_escape_string($conn,$presentedby_array[$i]);

			// $auth=validateFormData($auth);
			// $auth = "$auth";

			$first = validateFormData($first);
			$first = "$first";

			$last = validateFormData($last);
			$last = "$last";

			$type = validateFormData($type);
			$type = "$type";

			$title = validateFormData($title);
			$title = "$title";

			$edition = validateFormData($edition);
			$edition = "$edition";

			$publisher = validateFormData($publisher);
			$publisher = "$publisher";



			//if (strtotime($_POST['startDate']) > strtotime($_POST['endDate']))
			// if ($startDate > $endDate)		
			// {
			// 		$nameError=$nameError."Start Date cannot be greater than end date<br>";
			// 		$flag = 0;
			// }

			$chapter = validateFormData($chapter);
			$chapter = "$chapter";

			$issn = validateFormData($issn);
			$issn = "$issn";

			$date = validateFormData($date);
			$date = "$date";

			$url = validateFormData($url);
			$url = "$url";

			// if($volume!=""){
			// 	$volume=validateFormData($volume);
			// 	$volume = "$volume";
			// }else{
			// 	$volume="NA";
			// }

			// if($hindex!=NULL){
			// 	$hindex=validateFormData($hindex);
			//     $hindex = "$hindex";
			// }
			// else{
			// 	$hindex='0';
			// }

			// 	$index=validateFormData($index);
			// 	$index = "$index";

			// if($scopus!=""){
			// 	$scopus=validateFormData($scopus);
			//     $scopus = "$scopus";
			// }else{
			// 	$scopus="NA";
			// }

			// if($citation!=""){
			// 	$citation=validateFormData($citation);
			// 	$citation = "$citation";
			// }else{
			// 	$citation='NA';
			// }

			// 	if(isset($_POST['presentationStatus'])){

			// 		$presentationStatus=validateFormData($presentationStatus);
			//    		$presentationStatus = "$presentationStatus";

			// 		if($presentationStatus == 'Presented')
			// 		{
			// 			if(!$_POST['presentedby']){

			// 				$nameError=$nameError."Please enter by whom it is presented<br>";
			// 				$flag = 0;
			// 			}
			// 			else
			// 			{
			// 				 $presentedby=validateFormData($_POST['presentedby']);
			// 				 $presentedby="$presentedby";

			// 				 $publication=validateFormData($_POST["publication"]);
			// 				 $publication = "$publication";
			// 			}
			// 		}

			// 		if($presentationStatus == 'Not Presented')
			// 		{
			// 			//$presentedby="'".$presentedby."'";
			// 			$presentedby= "NULL";
			// 			$publication= "NULL";
			// 			//echo "<script>alert('$presentedby')</script>";
			// 		}
			// 	}	


			// if($applicablefdc == 'Yes')
			// {
			// 	$fdc=validateFormData($_POST["fdc"]);
			// 	$fdc = 'Yes';		
			// }else if($applicablefdc == 'No')
			// {
			// 	$fdc = 'Not applicable';
			// }
			// 	if($awards!=""){
			// 		$awards=validateFormData($awards);
			// 		$awards = "$awards";
			// 	}else{
			// 		$awards="NA";
			// 	}

			//checking if there was an error or not
			$query = "SELECT Fac_ID from facultydetails where Email='" . $_SESSION['loggedInEmail'] . "';";
			$result = mysqli_query($conn, $query);
			if ($result) {
				$row = mysqli_fetch_assoc($result);
				$author = $row['Fac_ID'];
			}

			// if(isset($_POST['applicable']))
			// {
			// 	// console.log($_POST['applicable']);
			// 	if($_POST['applicable'] == 2)
			// 	{
			// 		$paperpath='NULL';
			// 		$success=1;
			// 	}
			// 	if($_POST['applicable'] == 3)
			// 	{
			// 		$paperpath='not_applicable';
			// 		$success=1;	
			// 	}
			// 	if($_POST['applicable'] == 1)
			// 	{
			// 		if(isset($_FILES['paper']) && $_FILES['paper']['name'] != NULL && $_FILES['paper']['name'] !="")
			// 		{
			// 			echo "Name : ".$_FILES['paper']['name']."\t\tDone";
			// 		  $errors= array();
			// 		  $fileName = $_FILES['paper']['name'];
			// 		  $fileSize = $_FILES['paper']['size'];
			// 		  $fileTmp = $_FILES['paper']['tmp_name'];
			// 		  $fileType = $_FILES['paper']['type'];
			// 		  $temp=explode('.',$fileName);
			// 		  $fileExt=strtolower(end($temp));
			// 		  date_default_timezone_set('Asia/Kolkata');
			// 		  $targetName=$datapath."papers/".$_SESSION['F_NAME']."_papers_".date("d-m-Y H-i-s", time()).".".$fileExt;  

			// 		  if(empty($errors)==true) {
			// 			if (file_exists($targetName)) {   
			// 				unlink($targetName);
			// 			}      
			// 			 $moved = move_uploaded_file($fileTmp,"$targetName");
			// 			 if($moved == true){
			// 			 	$paperpath=$targetName;
			// 			 	$success=1;
			// 			 }
			// 			 else{
			// 				 //not successful
			// 				 //header("location:error.php");
			// 				// echo "<h1> $targetName </h1>";
			// 			 }
			// 		  }else{
			// 			 print_r($errors);
			// 			//header("location:else.php");
			// 		  }
			// 		}
			// 		else{
			// 			$s = 0;
			// 			$error1 = "No file selected";
			// 		}
			// 	}
			// }

			// if(isset($_POST['applicable1']))
			// {
			// 	if($_POST['applicable1'] == 2)
			// 	{
			// 		$certipath='NULL';		
			// 		$success=1;		 
			// 	}
			// 	if($_POST['applicable1'] == 3)
			// 	{
			// 		$certipath='not_applicable';
			// 		$success=1;		
			// 	}
			// 	if($_POST['applicable1'] == 1)
			// 	{
			// 		if(isset($_FILES['certificate']) && $_FILES['certificate']['name'] != NULL && $_FILES['certificate']['name'] !="")
			// 		{
			// 		  $errors= array();
			// 		  $fileName = $_FILES['certificate']['name'];
			// 		  $fileSize = $_FILES['certificate']['size'];
			// 		  $fileTmp = $_FILES['certificate']['tmp_name'];
			// 		  $fileType = $_FILES['certificate']['type'];
			// 		  $temp=explode('.',$fileName);
			// 		  $fileExt=strtolower(end($temp));					  
			// 		  date_default_timezone_set('Asia/Kolkata');
			// 		  $targetName=$datapath."certificates/".$_SESSION['F_NAME']."_certificates_".date("d-m-Y H-i-s", time()).".".$fileExt;  

			// 		  if(empty($errors)==true) {
			// 			if (file_exists($targetName)) {   
			// 				unlink($targetName);
			// 			}      
			// 			 $moved = move_uploaded_file($fileTmp,"$targetName");
			// 			 if($moved == true){
			// 			 	$certipath=$targetName;
			// 			 	$success=1;		
			// 			 }
			// 			//  else{
			// 			// 	echo "<h1> $targetName </h1>";
			// 			//  }
			// 		  }else{
			// 			 print_r($errors);
			// 		  }
			// 		}
			// 		else{
			// 			$s = 0;
			// 			$error2 = "No file selected";
			// 		}
			// 	}
			// }
			// if(isset($_POST['applicable2']))
			// {
			// 	if($_POST['applicable2'] == 2)
			// 	{
			// 		$reportpath='NULL';
			// 		$success=1;						 
			// 	}
			// 	if($_POST['applicable2'] == 3)
			// 	{
			// 		$reportpath='not_applicable';
			// 		$success=1;
			// 	}
			// 	if($_POST['applicable2'] == 1)
			// 	{
			// 		if(isset($_FILES['report']) && $_FILES['report']['name'] != NULL && $_FILES['report']['name'] !="" )
			// 		{
			// 		  $errors= array();
			// 		  $fileName = $_FILES['report']['name'];
			// 		  $fileSize = $_FILES['report']['size'];
			// 		  $fileTmp = $_FILES['report']['tmp_name'];
			// 		  $fileType = $_FILES['report']['type'];
			// 		  $temp=explode('.',$fileName);
			// 		  $fileExt=strtolower(end($temp));
			// 		  date_default_timezone_set('Asia/Kolkata');
			// 		  $targetName=$datapath."reports/".$_SESSION['F_NAME']."_reports_".date("d-m-Y H-i-s", time()).".".$fileExt;  

			// 		  if(empty($errors)==true) {
			// 			if (file_exists($targetName)) {   
			// 				unlink($targetName);
			// 			}      
			// 			 $moved = move_uploaded_file($fileTmp,"$targetName");
			// 			 if($moved == true){
			// 			 	$reportpath=$targetName;
			// 			 	$success=1;		
			// 			 }
			// 			//  else{
			// 			// 	echo "<h1> $targetName </h1>";
			// 			//  }
			// 		  }else{
			// 			 print_r($errors);
			// 		  }
			// 		}
			// 		else{
			// 			$s = 0;
			// 			$error3 = "No file selected";
			// 		}
			// 	}
			// }

			// if($index=="N"){
			// 	$index="NA";
			// }
			// if($publication==""){
			// 	$publication='NA';
			// }
			// if($presentedby==""){
			// 	$presentedby='NA';
			// }

			if (!isset($_POST['auth_name']) && $s != 0) {
				$authorname = "NA";
			}
			$authorname = "";
			$autharray = array();
			if (isset($_POST["auth_name"]) && $s != 0) {
				for ($count = 0; $count < count($_POST["auth_name"]); $count++) {
					$auth_name = $_POST["auth_name"][$count];
					array_push($autharray, $auth_name);
				}
				$authorname = implode(',', $autharray);
			}

			if ($flag != 0 && $s != 0) {
				$sql = "INSERT INTO books_published (Fac_ID,author,first_name,last_name,book_type,title,edition,publisher_name,chapter_no,issn_no,date,month,year,url) VALUES ('$author','$authorname','$first','$last','$type','$title','$edition','$publisher','$chapter','$issn','$date','$month','$year','$url')";

				if ($conn->query($sql) === TRUE) {
					$success = 1;
					$p_id = $conn->insert_id;
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}

			// if(!isset($_POST['co_authf']) && !isset($_POST['co_name']) && $s != 0 ){
			// 		$coauthorname="NA";
			// 		$sqlquery="UPDATE faculty SET Paper_co_authors='$coauthorname' where P_ID=$p_id";
			// 		$conn->query($sqlquery);
			// }
			// $coauthorname="";
			// if(isset($_POST["co_name"]) && $s != 0)
			// {
			// 	 for($count2 = 0; $count2 < count($_POST["co_name"]); $count2++)
			// 	 {  	$co_name= $_POST["co_name"][$count2];
			// 			$query="SELECT Fac_ID from facultydetails WHERE F_NAME = '$co_name'";
			// 			$result=mysqli_query($conn, $query);
			// 			$row=mysqli_fetch_assoc($result);
			// 			$val=$row['Fac_ID'];

			// 		  $query = "INSERT INTO co_author (c_id,c_name,p_id) VALUES ($val,'$co_name',$p_id)";
			// 		  $conn->query($query);
			// 		  if($coauthorname=="")
			// 		  {
			// 		  	$coauthorname=$co_name;
			// 		  }
			// 		  else{
			// 		  $coauthorname=$coauthorname.", ".$co_name;	 
			// 		}
			// 	}
			// 	if(!isset($_POST['co_authf'])){
			// 		if($coauthorname==""){
			// 			$coauthorname="NA";
			// 		}
			// 	}
			// 	$sqlquery="UPDATE faculty SET Paper_co_authors='$coauthorname' where P_ID=$p_id";
			// 	$conn->query($sqlquery);
			// }
			// if(isset($_POST["co_authf"]) && $s != 0)
			// {
			// 	for($count1 = 0; $count1 < count($_POST["co_authf"]); $count1++)
			// 	 {  	
			// 	 		$a=" ";
			// 	 		$value="";
			// 	 		$co_authf= $_POST["co_authf"][$count1];
			// 	 		$co_authl=$_POST["co_authl"][$count1];
			// 	 		$co_auth = $co_authf.$a.$co_authl;
			// 	 		$query="SELECT c_id from co_author WHERE c_name = '$co_auth'";
			// 			$result=mysqli_query($conn, $query);
			// 			$row=mysqli_fetch_assoc($result);
			// 			$value=$row['c_id'];
			// 			if($value!=NULL){
			// 				$query = "INSERT INTO co_author (c_id,c_name,p_id) VALUES ($value,'$co_auth',$p_id)";
			// 				$conn->query($query);
			// 			}else{
			// 		  		$query = "INSERT INTO co_author (c_name,p_id) VALUES ('$co_auth',$p_id)";
			// 		  		$conn->query($query);
			// 			}
			// 		  if($coauthorname=="")
			// 		  {
			// 		  	$coauthorname=$co_auth;
			// 		  }
			// 		  else{
			// 		  $coauthorname=$coauthorname.", ".$co_auth;	 
			// 		}
			// 	}
			// 	if($coauthorname==""){
			// 		$coauthorname="NA";
			// 	}
			// 	$sqlquery="UPDATE faculty SET Paper_co_authors='$coauthorname' where P_ID=$p_id";
			// 	$conn->query($sqlquery);
			// }
		}
	} //end of for
	if ($success == 1 && $s != 0) {
		header("location:2_dashboard_books.php?alert=success");
	} else if ($s != 0) {
		header("location:2_dashboard_books.php?alert=error");
	}
}

//close the connection
mysqli_close($conn);

function fill_unit_select_box($connect)
{
	$output = '';
	$query = "SELECT * FROM facultydetails WHERE type='faculty' ORDER BY F_NAME ASC";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach ($result as $row) {
		$output .= '<option value="' . $row["F_NAME"] . '">' . $row["F_NAME"] . '</option>';
	}
	return $output;
}
?>
<?php
if ($_SESSION['type'] == 'hod') {
	include_once('sidebar_hod.php');
} elseif ($_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
	include_once('sidebar_cod.php');
} else {
	include_once('sidebar.php');
}

?>
<style>
	.error {
		color: red;
		border: 1px solid red;
		background-color: #ebcbd2;
		border-radius: 10px;
		margin: 5px;
		padding: 0px;
		font-family: Arial, Helvetica, sans-serif;
		width: 510px;
	}

	.colour {
		color: #ff0000;
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
				<br /><br /><br />

				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="icon">
							<i style="font-size:20px" class="fa fa-edit"></i>
							<h3 class="box-title"><b>Books/Chapter Published Form</b></h3>
							<br>
						</div>
					</div><!-- /.box-header -->
					<div style="text-align:right">
						<!--	<a href="menu.php?menu=1 " style="text-align:right"> <u>Back to Paper Publication/Presentation Menu</u>&nbsp &nbsp </a> -->
					</div>
					<!-- form start -->

					<?php

					for ($k = 0; $k < 1; $k++) {

					?>

						<form id="insert_form" role="form" method="POST" enctype="multipart/form-data" class="row" action="" style="margin:10px;">



							<?php
							if ($flag == 0) {
								echo '<div class="error">' . $nameError . '</div>';
							}
							?>

							<?php
							// $replace_str = array('"', "'",'' ,'');
							// if(isset($_POST['conf']))
							// 	$conf = str_replace($replace_str, "", $conf);
							// else
							// 	$conf  = '';

							// if($volume!=""){
							// 	$replace_str = array('"', "'",'' ,'');
							// 	$volume = str_replace($replace_str, "", $volume);
							// }else{
							// 	$volume="NULL";

							// }

							// if($awards!=""){
							// 	$replace_str = array('"', "'",'' ,'');
							// 	$awards = str_replace($replace_str, "", $awards);
							// }else{
							// 	$awards="NA";
							// }

							// if($publication!=""){
							// 	$replace_str = array('"', "'",'' ,'');
							// 	$publication = str_replace($replace_str, "", $publication);
							// }else{
							// 	$publication="NA";
							// }
							?>

							<div class="form-group col-md-6">
								<label for="department_name">Department Name</label>
								<input required type="text" class="form-control input-lg" id="department_name" name="department_name[]" value="<?php echo strtoupper($_SESSION['Dept']); ?>" readonly>
							</div>

							<div class="form-group col-md-6">
								<label for="faculty-name">Faculty Name</label>
								<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName[]" value="<?php echo $faculty_name; ?>" readonly>
							</div>

							<div class="form-group col-md-6">
								<label for="c_name">Author</label>
								<span class="colour"><b> *</b></span>
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

							<div class="form-group col-md-3">
								<label for="fn_co-author">Co-Author (First Name)</label>
								<span class="colour"><b> *</b></span>
								<input class="form-control input-lg" type="text" name="first_name[]" id="fn_co-author" placeholder="First Name" value="">
							</div>

							<div class="form-group col-md-3">
								<label for="ln_co-author">Co-Author (Last Name)</label>
								<span class="colour"><b> *</b></span>
								<input class="form-control input-lg" type="text" name="last_name[]" id="ln_co-author" placeholder="Last Name" value="">
							</div>

							<div class="form-group col-md-6">
								<label for="book_type">Book Type</label>
								<span class="colour"><b> *</b></span>
								<select required name="book_type" id="book_type" class="form-control input-lg">
									<option value="" disabled selected>Select your option:</option>
									<option name="Individual" value="Individual">Individual</option>
									<option name="Extended" value="Extended">Extended (Conference Proceeding as Chapter)</option>
								</select>
							</div>

							<div class="form-group col-md-6">
								<label for="title_book">Title of Book/Chapter </label>
								<span class="colour"><b> *</b></span>
								<input type="text" required class="form-control input-lg" id="title_book" name="title[]" placeholder="" value="">
							</div>

							<div class="form-group col-md-6">
								<label for="Edition">Edition</label>
								<input type="text" class="form-control input-lg" id="Edition" name="edition[]" placeholder="Numeric" value="">
							</div>

							<div class="form-group col-md-6">
								<label for="name_of_publisher">Name of the Publisher</label>
								<span class="colour"><b> *</b></span>
								<input type="text" required class="form-control input-lg" id="name_of_publisher" name="publisher_name[]">
							</div>

							<div class="form-group col-md-6">
								<label for="chapter_num">Chapter Number</label>
								<input type="text" class="form-control input-lg" id="chapter_num" name="chapter_no[]" placeholder="(If a chapter of the book)" value="">
							</div>

							<div class="form-group col-md-6">
								<label for="Month">Date</label>
								<span class="colour"><b> *</b></span>
								<input required type="date" class="form-control input-lg" id="Month" name="date[]" value="">
							</div>

							<div class="form-group col-md-6">
								<label for="number">ISSN/eISSN/ISBN Number</label>
								<span class="colour"><b> *</b></span>
								<input type="text" required class="form-control input-lg" id="number" name="issn_no[]" placeholder="" value="">
							</div>

							<div class="form-group col-md-6">
								<label for="book_chapter_url">Book/Chapter Link(URL)</label>
								<span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['url'])) echo "value = $url"; ?> required type="url" class="form-control input-lg" id="location" name="url[]">
							</div>

							<script>
								$('.presentation-status').each(function() {
									$('.presentation-status').on('change', myfunction);
								});

								$('.applicable-fdc').each(function() {
									$('.applicable-fdc').on('change', myfunction1);
								});


								function myfunction() {
									var x = this.value;

									if (x == 'Presented') {
										//document.getElementById("demo").innerHTML = "You selected:" +x;
										$(this).parent().next().next()[0].style.display = "block";
										$(this).parent().next().next().next()[0].style.display = "block";
									} else {
										$(this).parent().next().next()[0].style.display = "none";
										$(this).parent().next().next().next()[0].style.display = "none";
									}
								}


								function myfunction1() {
									var x = this.value;

									if (x == 'Yes') {

										$(this).parent().next()[0].style.display = "block";

									} else {
										$(this).parent().next()[0].style.display = "none";
									}
								}
							</script>
						<?php
					}
						?>
						<br />
						<div class="form-group col-md-12">
							<a href="list_of_activities_user.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>

							<button name="add" type="submit" class="demo btn btn-success pull-right btn-lg">Submit</button>
						</div>
						</form>
				</div>
			</div>
		</div>

	</section>


</div>

<script>
	$(document).ready(function() {

		$(document).on('click', '.add', function() {
			var html = '';
			html += '<tr>';
			html += '<td><select name="co_name[]" class="form-control item_unit" id="search"><option value="">Select Co-author</option><?php echo fill_unit_select_box($connect); ?></select></td>';
			html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
			$('#c_name').append(html);
		});

		$(document).on('click', '.remove', function() {
			$(this).closest('tr').remove();
		});
	});


	$(document).ready(function() {

		$(document).on('click', '.addauth', function() {
			var html = '';
			html += '<tr>';
			html += '<td><select name="auth_name[]" class="form-control item_unit" id="search"><option value="">Select Author</option><?php echo fill_unit_select_box($connect); ?></select></td>';
			html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove1"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
			$('#a_name').append(html);
		});
		$(document).on('click', '.remove1', function() {
			$(this).closest('tr').remove();
		});



	});



	$(document).ready(function() {

		$(document).on('click', '.add1', function() {
			var html = '';
			html += '<tr>';
			html += '<td><input type="text" name="co_authf[]" placeholder="First name" class="form-control item_name" /></td>';
			html += '<td><input type="text" name="co_authl[]" placeholder="Last name" class="form-control item_name" /></td>';
			html += '<td><button type="button" name="remove1" class="btn btn-danger btn-sm remove1"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
			$('#co_auth').append(html);
		});

		$(document).on('click', '.remove1', function() {
			$(this).closest('tr').remove();
		});

	});
</script> -->



<?php include_once('footer.php'); ?>