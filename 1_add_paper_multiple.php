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

$_SESSION['currentTab'] = "paper";

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
$paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coauthors = $volume = "";
$presentationStatus = $index = $publication = $awards = $presentedby = $paperpath = $reportpath = $certipath = "";
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

		$paperTitle_array = $_POST['title'];
		// $paperType_array = $_POST['paperType'];
		// $paperLevel_array = $_POST['paperLevel'];
		// $conf_array = $_POST['conf'];
		$peerev_array = $_POST['PeerRev'];
		// $paperCategory_array = $_POST['paperCategory'];
		$startDate_array = $_POST['startDate'];
		$endDate_array = $_POST['endDate'];
		$location_array = $_POST['location'];
		$confjour_array = $_POST['ConfJour'];
		$jour_name_array = $_POST['jour_name'];
		// $volume_array = $_POST['volume'];

		// if (isset($_POST['index'])) {

		// } else {
		// 	$index_array = 'NA';
		// }


		$scopus_array = $_POST['scopus'];
		$scopus_index_array = $_POST['scopus_index'];

		$hindex_array = $_POST['h_index'];
		$impact_array = $_POST['impact'];

		$sci_array = $_POST['sci'];
		$sci_index_array = $_POST['sci_index'];
		// $citation_array = $_POST['citation'];

		// $applicablefdc_array = $_POST['applicablefdc'];

		// $fdc_array = $_POST['fdc'];
		$presentationStatus_array = $_POST['Attended'];
		// $awards_array = $_POST['awards'];
		// $presentedby_array = $_POST['presentedby'];
		// $publication_array = $_POST['publication'];


		for ($i = 0; $i < 1; $i++) {
			$paperTitle = mysqli_real_escape_string($conn, $paperTitle_array[$i]);
			// $paperType = mysqli_real_escape_string($conn, $paperType_array[$i]);
			// $paperLevel = mysqli_real_escape_string($conn, $paperLevel_array[$i]);
			// $conf = mysqli_real_escape_string($conn, $conf_array[$i]);
			// $paperCategory = mysqli_real_escape_string($conn, $paperCategory_array[$i]);
			$startDate = mysqli_real_escape_string($conn, $startDate_array[$i]);
			$endDate = mysqli_real_escape_string($conn, $endDate_array[$i]);
			$location = mysqli_real_escape_string($conn, $location_array[$i]);
			$confjour = mysqli_real_escape_string($conn, $confjour_array[$i]);
			$jour_name = mysqli_real_escape_string($conn, $jour_name_array[$i]);
			$peerev = mysqli_real_escape_string($conn, $peerev_array[$i]);
			// $volume = mysqli_real_escape_string($conn, $volume_array[$i]);
			$scopus_index = mysqli_real_escape_string($conn, $scopus_index_array[$i]);
			$scopus = mysqli_real_escape_string($conn, $scopus_array[$i]);

			$hindex = mysqli_real_escape_string($conn, $hindex_array[$i]);
			$impact = mysqli_real_escape_string($conn, $impact_array[$i]);

			$sci = mysqli_real_escape_string($conn, $sci_array[$i]);
			$sci_index = mysqli_real_escape_string($conn, $sci_index_array[$i]);
			// $citation = mysqli_real_escape_string($conn, $citation_array[$i]);

			// $applicablefdc = mysqli_real_escape_string($conn, $applicablefdc_array[$i]);

			// $fdc = mysqli_real_escape_string($conn, $fdc_array[$i]);
			// $_SESSION['fdc'] = $fdc;
			$presentationStatus = mysqli_real_escape_string($conn, $presentationStatus_array[$i]);
			// $awards = mysqli_real_escape_string($conn, $awards_array[$i]);
			// $publication = mysqli_real_escape_string($conn, $publication_array[$i]);
			// $presentedby = mysqli_real_escape_string($conn, $presentedby_array[$i]);

			$paperTitle = validateFormData($paperTitle);

			// $paperType = validateFormData($paperType);
			// $paperType = "$paperType";

			// $paperLevel = validateFormData($paperLevel);
			// $paperLevel = "$paperLevel";

			// $conf = validateFormData($conf);
			// $conf = "$conf";

			// $paperCategory = validateFormData($paperCategory);
			// $paperCategory = "$paperCategory";
			$time = time();
			$start = new DateTime(date($startDate, $time));
			$end = new DateTime(date($endDate, $time));
			$days = date_diff($start, $end);
			$noofdays = $days->format('%d');
			$month = $start->format('n');
			$year = $start->format('Y');
			$noofweeks = $noofdays / 7;


			//if (strtotime($_POST['startDate']) > strtotime($_POST['endDate']))
			if ($startDate > $endDate) {
				$nameError = $nameError . "Start date cannot be greater than end date<br>";
				$error = "Start date cannot be greater than end date";
				$s = 0;
				$flag = 0;
			}

			$startDate = validateFormData($startDate);
			$startDate = "$startDate";

			$confjour = validateFormData($confjour);
			$confjour = "$confjour";

			$jour_name = validateFormData($jour_name);
			$jour_name = "$jour_name";

			$endDate = validateFormData($endDate);
			$endDate = "$endDate";

			$location = validateFormData($location);
			$location = "$location";



			// if ($volume != "") {
			// 	$volume = validateFormData($volume);
			// 	$volume = "$volume";
			// } else {
			// 	$volume = "NA";
			// }

			if ($hindex != NULL) {
				$hindex = validateFormData($hindex);
				$hindex = "$hindex";
			} else {
				$hindex = '0';
			}

			$impact = validateFormData($impact);
			$impact = "$impact";

			$scopus_index = validateFormData($scopus_index);
			$scopus_index = "$scopus_index";

			$sci_index = validateFormData($sci_index);
			$sci_index = "$sci_index";

			// if ($scopus != "") {
			// 	$scopus = validateFormData($scopus);
			// 	$scopus = "$scopus";
			// } else {
			// 	$scopus = "NA";
			// }

			// if ($citation != "") {
			// 	$citation = validateFormData($citation);
			// 	$citation = "$citation";
			// } else {
			// 	$citation = 'NA';
			// }

			if (isset($_POST['presentationStatus'])) {

				$presentationStatus = validateFormData($presentationStatus);
				$presentationStatus = "$presentationStatus";

				// if ($presentationStatus == 'Presented') {
				// 	if (!$_POST['presentedby']) {

				// 		$nameError = $nameError . "Please enter by whom it is presented<br>";
				// 		$flag = 0;
				// 	} else {
				// 		$presentedby = validateFormData($_POST['presentedby']);
				// 		$presentedby = "$presentedby";

				// 		$publication = validateFormData($_POST["publication"]);
				// 		$publication = "$publication";
				// 	}
				// }

				// if ($presentationStatus == 'Not Presented') {
				// 	//$presentedby="'".$presentedby."'";
				// 	$presentedby = "NULL";
				// 	$publication = "NULL";
				// 	//echo "<script>alert('$presentedby')</script>";
				// }
			}


			// if ($applicablefdc == 'Yes') {
			// 	$fdc = validateFormData($_POST["fdc"]);
			// 	$fdc = 'Yes';
			// } else if ($applicablefdc == 'No') {
			// 	$fdc = 'Not applicable';
			// }
			// if ($awards != "") {
			// 	$awards = validateFormData($awards);
			// 	$awards = "$awards";
			// } else {
			// 	$awards = "NA";
			// }

			//checking if there was an error or not
			$query = "SELECT Fac_ID from facultydetails where Email='" . $_SESSION['loggedInEmail'] . "';";
			$result = mysqli_query($conn, $query);
			if ($result) {
				$row = mysqli_fetch_assoc($result);
				$author = $row['Fac_ID'];
			}

			if (isset($_POST['applicable'])) {
				// console.log($_POST['applicable']);
				if ($_POST['applicable'] == 2) {
					$paperpath = 'NULL';
					$success = 1;
				}
				if ($_POST['applicable'] == 3) {
					$paperpath = 'not_applicable';
					$success = 1;
				}
				if ($_POST['applicable'] == 1) {
					if (isset($_FILES['paper']) && $_FILES['paper']['name'] != NULL && $_FILES['paper']['name'] != "") {
						echo "Name : " . $_FILES['paper']['name'] . "\t\tDone";
						$errors = array();
						$fileName = $_FILES['paper']['name'];
						$fileSize = $_FILES['paper']['size'];
						$fileTmp = $_FILES['paper']['tmp_name'];
						$fileType = $_FILES['paper']['type'];
						$temp = explode('.', $fileName);
						$fileExt = strtolower(end($temp));
						date_default_timezone_set('Asia/Kolkata');
						$targetName = $datapath . "papers/" . $_SESSION['F_NAME'] . "_papers_" . date("d-m-Y H-i-s", time()) . "." . $fileExt;

						if (empty($errors) == true) {
							if (file_exists($targetName)) {
								unlink($targetName);
							}
							$moved = move_uploaded_file($fileTmp, "$targetName");
							if ($moved == true) {
								$paperpath = $targetName;
								$success = 1;
							} else {
								//not successful
								//header("location:error.php");
								// echo "<h1> $targetName </h1>";
							}
						} else {
							print_r($errors);
							//header("location:else.php");
						}
					} else {
						$s = 0;
						$error1 = "No file selected";
					}
				}
			}

			if (isset($_POST['applicable1'])) {
				if ($_POST['applicable1'] == 2) {
					$certipath = 'NULL';
					$success = 1;
				}
				if ($_POST['applicable1'] == 3) {
					$certipath = 'not_applicable';
					$success = 1;
				}
				if ($_POST['applicable1'] == 1) {
					if (isset($_FILES['certificate']) && $_FILES['certificate']['name'] != NULL && $_FILES['certificate']['name'] != "") {
						$errors = array();
						$fileName = $_FILES['certificate']['name'];
						$fileSize = $_FILES['certificate']['size'];
						$fileTmp = $_FILES['certificate']['tmp_name'];
						$fileType = $_FILES['certificate']['type'];
						$temp = explode('.', $fileName);
						$fileExt = strtolower(end($temp));
						date_default_timezone_set('Asia/Kolkata');
						$targetName = $datapath . "certificates/" . $_SESSION['F_NAME'] . "_certificates_" . date("d-m-Y H-i-s", time()) . "." . $fileExt;

						if (empty($errors) == true) {
							if (file_exists($targetName)) {
								unlink($targetName);
							}
							$moved = move_uploaded_file($fileTmp, "$targetName");
							if ($moved == true) {
								$certipath = $targetName;
								$success = 1;
							}
							//  else{
							// 	echo "<h1> $targetName </h1>";
							//  }
						} else {
							print_r($errors);
						}
					} else {
						$s = 0;
						$error2 = "No file selected";
					}
				}
			}
			if (isset($_POST['applicable2'])) {
				if ($_POST['applicable2'] == 2) {
					$reportpath = 'NULL';
					$success = 1;
				}
				if ($_POST['applicable2'] == 3) {
					$reportpath = 'not_applicable';
					$success = 1;
				}
				if ($_POST['applicable2'] == 1) {
					if (isset($_FILES['report']) && $_FILES['report']['name'] != NULL && $_FILES['report']['name'] != "") {
						$errors = array();
						$fileName = $_FILES['report']['name'];
						$fileSize = $_FILES['report']['size'];
						$fileTmp = $_FILES['report']['tmp_name'];
						$fileType = $_FILES['report']['type'];
						$temp = explode('.', $fileName);
						$fileExt = strtolower(end($temp));
						date_default_timezone_set('Asia/Kolkata');
						$targetName = $datapath . "reports/" . $_SESSION['F_NAME'] . "_reports_" . date("d-m-Y H-i-s", time()) . "." . $fileExt;

						if (empty($errors) == true) {
							if (file_exists($targetName)) {
								unlink($targetName);
							}
							$moved = move_uploaded_file($fileTmp, "$targetName");
							if ($moved == true) {
								$reportpath = $targetName;
								$success = 1;
							}
							//  else{
							// 	echo "<h1> $targetName </h1>";
							//  }
						} else {
							print_r($errors);
						}
					} else {
						$s = 0;
						$error3 = "No file selected";
					}
				}
			}

			// if ($index == "N") {
			// 	$index = "NA";
			// }
			// if ($publication == "") {
			// 	$publication = 'NA';
			// }
			// if ($presentedby == "") {
			// 	$presentedby = 'NA';
			// }

			if (!isset($_POST['co_name']) && $s != 0) {
				$coauthorname = "NA";
			}
			$coauthorname = "";
			$coautharray = array();
			if (isset($_POST["co_name"]) && $s != 0) {
				for ($count2 = 0; $count2 < count($_POST["co_name"]); $count2++) {
					$co_name = $_POST["co_name"][$count2];
					array_push($coautharray, $co_name);
				}
				$coauthorname = implode(',', $coautharray);
			}
			if ((isset($_POST["co_authf"]) || isset($_POST["co_authl"])) && $s != 0) {
				for ($count1 = 0; $count1 < count($_POST["co_authf"]); $count1++) {
					$a = " ";
					$co_authf = $_POST["co_authf"][$count1];
					$co_authl = $_POST["co_authl"][$count1];
					$co_auth = $co_authf . $a . $co_authl;
					array_push($coautharray, $co_auth);
				}
				$coauthorname = implode(',', $coautharray);
			}

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
				$sql = "INSERT INTO faculty(Fac_ID,Paper_co_authors,ConfJour,jour_name,Paper_title,Location,PeerRev, presentation_status,Date_from,Date_to,noofdays,noofweeks,month,year,scopus, scopusindex,h_index, impact, SCI,`SCI link`, paper_path,certificate_path,report_path,author) VALUES ('$author','$coauthorname','$confjour','$jour_name','$paperTitle','$location','$peerev','$presentationStatus','$startDate','$endDate','$noofdays','$noofweeks','$month','$year','$scopus','$scopus_index',$hindex,$impact,'$sci','$sci_index','" . $paperpath . "','" . $certipath . "','" . $reportpath . "','$authorname')";
				echo $sql;
				if ($conn->query($sql) === TRUE) {
					$success = 1;
					$p_id = $conn->insert_id;
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}

			// function alert ($msg) {
			// 	echo "<script type='text/javascript'>alert('$msg');</script>";
			// }

			// if (!isset($_POST['co_authf']) && !isset($_POST['co_name']) && $s != 0) {
			// 	$coauthorname = "NA";
			// 	$sqlquery = "UPDATE faculty SET Paper_co_authors='$coauthorname' where P_ID=$p_id";
			// 	$conn->query($sqlquery);
			// }
			// $coauthorname = "";
			// if (isset($_POST["co_name"]) && $s != 0) {
			// 	for ($count2 = 0; $count2 < count($_POST["co_name"]); $count2++) {
			// 		$co_name = $_POST["co_name"][$count2];
			// 		$query = "SELECT Fac_ID from facultydetails WHERE F_NAME = '$co_name'";
			// 		$result = mysqli_query($conn, $query);
			// 		$row = mysqli_fetch_assoc($result);
			// 		$val = $row['Fac_ID'];

			// 		$query = "INSERT INTO co_author (c_id,c_name,p_id) VALUES ($val,'$co_name',$p_id)";
			// 		$conn->query($query);
			// 		if ($coauthorname == "") {
			// 			$coauthorname = $co_name;
			// 		} else {
			// 			$coauthorname = $coauthorname . ", " . $co_name;
			// 		}
			// 	}
			// 	if (!isset($_POST['co_authf'])) {
			// 		if ($coauthorname == "") {
			// 			$coauthorname = "NA";
			// 		}
			// 	}
			// 	$sqlquery = "UPDATE faculty SET Paper_co_authors='$coauthorname' where P_ID=$p_id";
			// 	$conn->query($sqlquery);
			// }
			// if (isset($_POST["co_authf"]) && $s != 0) {
			// 	for ($count1 = 0; $count1 < count($_POST["co_authf"]); $count1++) {
			// 		$a = " ";
			// 		$value = "";
			// 		$co_authf = $_POST["co_authf"][$count1];
			// 		$co_authl = $_POST["co_authl"][$count1];
			// 		$co_auth = $co_authf . $a . $co_authl;
			// 		$query = "SELECT c_id from co_author WHERE c_name = '$co_auth'";
			// 		$result = mysqli_query($conn, $query);
			// 		$row = mysqli_fetch_assoc($result);
			// 		$value = $row['c_id'];
			// 		if ($value != NULL) {
			// 			$query = "INSERT INTO co_author (c_id,c_name,p_id) VALUES ($value,'$co_auth',$p_id)";
			// 			$conn->query($query);
			// 		} else {
			// 			$query = "INSERT INTO co_author (c_name,p_id) VALUES ('$co_auth',$p_id)";
			// 			$conn->query($query);
			// 		}
			// 		if ($coauthorname == "") {
			// 			$coauthorname = $co_auth;
			// 		} else {
			// 			$coauthorname = $coauthorname . ", " . $co_auth;
			// 		}
			// 	}
			// 	if ($coauthorname == "") {
			// 		$coauthorname = "NA";
			// 	}
			// 	$sqlquery = "UPDATE faculty SET Paper_co_authors='$coauthorname' where P_ID=$p_id";
			// 	$conn->query($sqlquery);
			// }
		}
	} //end of for
	if ($success == 1 && $s != 0) {
		header("location:2_dashboard.php?alert=success");
	} else if ($s != 0) {
		header("location:2_dashboard.php?alert=error");
	}
	else if ($s==0) {
		echo "<script>alert('$error')</script>";
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
			<div class="col-md-8 " id="form">
				<!-- general form elements -->
				<br /><br /><br />

				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="icon">
							<i style="font-size:20px" class="fa fa-edit"></i>
							<h3 class="box-title"><b>Faculty Publication Form</b></h3>
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
							$replace_str = array('"', "'", '', '');
							if (isset($_POST['conf']))
								$conf = str_replace($replace_str, "", $conf);
							else
								$conf  = '';

							if ($volume != "") {
								$replace_str = array('"', "'", '', '');
								$volume = str_replace($replace_str, "", $volume);
							} else {
								$volume = "NULL";
							}

							if ($awards != "") {
								$replace_str = array('"', "'", '', '');
								$awards = str_replace($replace_str, "", $awards);
							} else {
								$awards = "NA";
							}

							if ($publication != "") {
								$replace_str = array('"', "'", '', '');
								$publication = str_replace($replace_str, "", $publication);
							} else {
								$publication = "NA";
							}
							?>

							<div class="form-group col-md-6">
								<label for="department_name">Department</label>
								<input required type="text" class="form-control input-lg" id="dept" name="dept" value="<?php echo strtoupper($_SESSION['Dept']); ?>" readonly>
							</div>

							<div class="form-group col-md-6">
								<label for="faculty-name">Faculty Name</label>
								<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
							</div>

							<!-- <div class="form-group col-md-6">
								<label for="faculty-name">Author</label>
								<input required type="text" class="form-control input-lg" id="author" name="author">
							</div> -->
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

							<!-- <div class="form-group col-md-6">

								<label for="c_name">Author</label>
								<div class="table-repsonsive">
									<span id="error"></span>
									<table class="table table-bordered" id="c_name">
										<tr>
											<th>Click to select </th>
											<th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
										</tr>
									</table>
								</div>
							</div> -->

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

							<!-- <div class="form-group col-md-6">
                         <label for="paper-title">Title </label><span class="colour"><b> *</b></span>
                        <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle">
					  <input required  type="text" class="form-control input-lg"  name="paperTitle[]" value = '<?php if (isset($_POST['paperTitle'])) echo $paperTitle; ?>'>
                     </div>

					

                     <div class="form-group col-md-6">
                         <label for="paper-type">Paper Type</label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg" id="paper-type" name="paperType[]">
                             <option <?php if (isset($_POST['paperType'])) if ($paperType == 'conference') echo "selected = 'selected'" ?> value = "conference">Conference</option>
                             <option <?php if (isset($_POST['paperType'])) if ($paperType == 'journal') echo "selected = 'selected'" ?> value = "journal">Journal</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-level">Paper Level</label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg" id="paper-level" name="paperLevel[]">
                             <option <?php if (isset($_POST['paperLevel'])) if ($paperLevel == "national") echo "selected = 'selected'" ?> value = "national">National</option>
                             <option <?php if (isset($_POST['paperLevel'])) if ($paperLevel == "international") echo "selected = 'selected'" ?> value = "international">International</option>
                         </select>
                     </div>
				 
					  <div class="form-group col-md-6">
                         <label for="conf">Conference/Journal Name </label><span class="colour"><b> *</b></span>
                         <input type="text" required class="form-control input-lg" id="conf" name="conf[]" value = '<?php if (isset($_POST['conf'])) echo $conf; ?>'>
                     </div>
					 
                     <div class="form-group col-md-6">
                         <label for="paper-category">Paper Category </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg" id="paper-category" name="paperCategory[]">
                             <option <?php if ($paperCategory == "peer reviewed") echo "selected = 'selected'" ?> value = "peer reviewed">Peer Reviewed</option>
                             <option <?php if ($paperCategory == "non peer reviewed") echo "selected = 'selected'" ?> value = "non peer reviewed">Non Peer Reviewed</option>
                         </select>
                     </div> -->



							<div class="form-group col-md-6">
								<label for="start-date">Start Date </label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['startDate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate[]" placeholder="03:10:10">
							</div>

							<div class="form-group col-md-6">
								<label for="end-date">End Date </label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['endDate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate[]" placeholder="03:10:10">
							</div>

							<!-- <div class="form-group col-md-6">
								<label for="days/weeks/hours">Days/Weeks/Hours </label>
								<span class="colour"><b> *</b></span>
								<select required name="status_activities[]" id="status_activities" class="form-control input-lg">
									<option value="" disabled selected>Select your option:</option>
									<option name="hours" value="hours">Hours</option>
									<option name="days" value="days">Days</option>
									<option name="weeks" value="weeks">Weeks</option>
								</select>
							</div>

							<div class="form-group col-md-6">
								<label for="no_of_hours">Equivalent duration</label>
								<span class="colour"><b> *</b></span>
								<input class="form-control input-lg" type="text" name="no_of_hours[]" id="no_of_hours" placeholder="" value="">
							</div> -->


							<!-- <div class="form-group col-md-6">
								<label for="Month">Month</label>
								<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
							</div>

							<div class="form-group col-md-6">
								<label for="Year">Year</label>
								<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
							</div> -->

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
								<input required type="number" step="0.0001" class="form-control input-lg" id="h_index" name="h_index[]">
							</div>

							<div class="form-group col-md-6">
								<label for="impact">Impact Factor </label>
								<span class="colour"><b> *</b></span>
								<input required type="number" step="0.0001" class="form-control input-lg" id="impact" name="impact[]">
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

							<!-- <div class="form-group col-md-6">
					 
					 <label for="c_name">Co-Author (Faculty)</label>
							    <div class="table-repsonsive">
							     <span id="error"></span>
							     <table class="table table-bordered" id="c_name">
							      <tr>
							       <th>Click to select </th>
							       <th><button  type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
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
                         <label for="volume">Volume/Issue/ISSN </label>
                         <input type="text" class="form-control input-lg" id="volume" name="volume[]">
                     </div>		
					 
					<div class="form-group col-md-6">
                         <label for="Index">Index </label><br/>
						  <input type="radio" name="index[]" <?php if (isset($_POST['index'])) echo ($index == "scopus") ? 'checked' : '' ?> value="scopus" > Scopus<br>
							<input type="radio" name="index[]" <?php if (isset($_POST['index'])) echo ($index == "sci") ? 'checked' : '' ?> value="sci"> SCI<br>
						<input type="radio" name="index[]" <?php if (isset($_POST['index'])) echo ($index == "both") ? 'checked' : '' ?> value="both"> Both
					</div>

					<div class="form-group col-md-6">
                         <label for="scopus">Provide Scopus/Sci/both if applicable</label>
                         <input <?php if (isset($_POST['scopus'])) echo "value = $scopus"; ?>
						 type="text" class="form-control input-lg" id="scopus" name="scopus[]" value="">
                     </div>						
					
					 <div class="form-group col-md-6">
                         <label for="H-Index">H-Index</label>
                         <input <?php if (isset($_POST['hindex'])) echo "value = $hindex"; ?>
						 type="text" class="form-control input-lg" id="hindex" name="hindex[]">
                     </div>	
					 
				 <div class="form-group col-md-6">
                         <label for="citation">Citations</label>
                         <input  <?php if (isset($_POST['citation'])) echo "value = $citation"; ?>
						 type="text" class="form-control input-lg" id="citation" name="citation[]" value="">
                     </div>	
					 
					 <div class="form-group col-md-6">
                         <label for="presentation-status">Presentation status </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg presentation-status" id="presentation-status" name="presentationStatus[]">
                             <option value ="Not Presented">Not Presented</option>
                             <option value ="Presented">Presented</option>
                         </select>
                     </div>
			
					 <div class="form-group col-md-6">
                         <label for="awards">Awards, if any </label>
                         <input type="text" class="form-control input-lg" id="awards" name="awards[]" >
                     </div>
					 
					 <div id="presented-by" style="display:none" class="form-group col-md-6">
                        <label for="presented-by">Presented by </label><span class="colour"><b> *</b></span>
						<input  <?php if (isset($_POST['presentedby'])) echo "value = '$F_NAME'";
								else echo "value = '$presentedby'"; ?>
						type="text" class="form-control input-lg"  name="presentedby[]">
                     </div>
					 <div id="publication" style="display:none" class="form-group col-md-6">
                         <label for="publication">Link of Online Publication</label>
                         <input type="text" class="form-control input-lg" id="publication" name="publication[]" >
                     </div>
					  
					 
					 	 <div class="form-group col-md-6">
                         <label for="applicable-fdc">Is FDC applicable? </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg applicable-fdc" id="applicable-fdc" name="applicablefdc[]">
                             <option <?php if (isset($_POST['fdc'])) if ($fdc == "Not applicable") echo "selected = 'selected'" ?> value ="No">No</option>
                             <option <?php if (isset($_POST['fdc'])) if ($fdc == 'no' || $fdc == 'yes' || $fdc == 'No' || $fdc == 'Yes') echo "selected = 'selected'" ?> value ="Yes">Yes</option>
                         </select>
                     </div>

					 <div class="form-group col-md-6" style="display:none">
                         <label for="fdc">Applied for FDC ? </label><span class="colour"><b> *</b></span>
                         <select  class="form-control input-lg" id="fdc" name="fdc[]">
                             <option <?php if (isset($_POST['fdc'])) if ($fdc == "yes") echo "selected = 'selected'" ?> value = "yes">Yes</option>
                             <option <?php if (isset($_POST['fdc'])) if ($fdc == "no") echo "selected = 'selected'" ?> value = "no">No</option>
                         </select>
                     </div> -->
							<div class="form-group col-md-6 col-md-offset-1"></div>

							<div class="form-group col-md-6">
								<div>

									&nbsp;<label for="course">Upload paper: Applicable?<br></label>
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
									&nbsp;<label for="course">Upload certificate: Applicable?<br></label><span class="colour"><b> *</b></span>
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

									&nbsp;<label for="course">Upload report: Applicable?<br></label><span class="colour"><b> *</b></span>
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
			html += '<td><select required name="auth_name[]" class="form-control item_unit" id="search"><option value="">Select Author</option><?php echo fill_unit_select_box($connect); ?></select></td>';
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

		$(document).on('click', '.remove', function() {
			$(this).closest('tr').remove();
		});

	});
</script>



<?php include_once('footer.php'); ?>