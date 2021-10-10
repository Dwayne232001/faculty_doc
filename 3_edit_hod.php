<?php
ob_start();
session_start();

//connect ot database
include_once("includes/connection.php");

//check if user has logged in or not

if (!isset($_SESSION['loggedInUser'])) {
	//send the iser to login page
	header("location:index.php");
}

$fid = $_SESSION['Fac_ID'];

$queryrun = "SELECT * FROM facultydetails where Fac_ID=$fid";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
	$_SESSION['Dept'] = $row['Dept'];
	$_SESSION['type'] = $row['type'];
}

$_SESSION['currentTab'] = "paper";

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/config.php");


//setting error variables
$nameError = "";
$emailError = "";
$flag = 1;
$paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coAuthors = "";
$presentationStatus =  $publication = $awards = $presentedby = $Udate = "";

date_default_timezone_set("Asia/Kolkata");
if (isset($_POST['id'])) {
	$_SESSION['id'] = $_POST['id'];
	$id = $_POST['id'];
}

$id = $_SESSION['id'];
$query = "SELECT * from faculty where P_ID = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$Fac_ID = $row['Fac_ID'];
$paperTitle = $row['Paper_title'];
$startDate = $row['Date_from'];
$endDate = $row['Date_to'];
$paperType_db = $row['Paper_type'];
$paperLevel_db = $row['Paper_N_I'];
$conf = $row['conf_journal_name'];
$paperCategory_db = $row['PeerRev'];
$location = $row['Location'];
$coAuthors = $row['Paper_co_authors'];
// $volume = $row['volume'];
// $index_db = $row['scopusindex'];
$scopus = $row['scopus'];
$sci = $row['SCI'];
$impact = $row['impact'];
$hindex = $row['h_index'];
// $citation = $row['citations'];
// $fdc_db = $row['FDC_Y_N'];
$presentationStatus_db = $row['presentation_status'];
// $publication = $row['Link_publication'];
// $awards = $row['Paper_awards'];
// $presentedby_db = $row['presented_by'];
$Udate = $row['Udate'];
$paperpath = $row['paper_path'];
$certipath = $row['certificate_path'];
$reportpath = $row['report_path'];

$coordiarray = explode(',', $coAuthors);

$query2 = "SELECT * from facultydetails where Fac_ID = $Fac_ID";
$result2 = mysqli_query($conn, $query2);
if ($result2) {
	$row = mysqli_fetch_assoc($result2);
	$F_NAME = $row['F_NAME'];
}
$_SESSION['F_NAME'] = $F_NAME;

//check if the form was submitted
if (isset($_POST['update'])) {
	//the form was submitted
	$clientName = $clientEmail = $clientPhone = $clientAddress = $clientCompany = $clientNotes = "";

	//check for any blank input which are required
	$Udate = $_POST['Udate'];
	$Udate = "'" . $Udate . "'";

	$paperTitle = validateFormData($_POST['paperTitle']);
	$paperTitle = "'" . $paperTitle . "'";

	$startDate = validateFormData($_POST['startDate']);
	$endDate = validateFormData($_POST['endDate']);

	$time = time();
	$start = new DateTime(date($startDate, $time));
	$end = new DateTime(date($endDate, $time));
	$days = date_diff($start, $end);
	$noofdays = $days->format('%d');

	$startDate = "'" . $startDate . "'";
	$endDate = "'" . $endDate . "'";

	if ($startDate > $endDate) {
		$nameError = $nameError . "Start Date cannot be greater than end date<br>";
		$flag = 0;
	}

	$paperType = validateFormData($_POST['paperType']);
	$paperType = "'" . $paperType . "'";

	// $paperLevel = validateFormData($_POST['paperLevel']);
	// $paperLevel = "'" . $paperLevel . "'";

	$paperCategory = validateFormData($_POST['PeerRev']);
	$paperCategory = "'" . $paperCategory . "'";

	// $location = validateFormData($_POST['location']);
	// $location = "'" . $location . "'";


	$conf = validateFormData($_POST['aff']);
	$conf = "'" . $conf . "'";

	$presentationStatus = validateFormData($_POST['presentationStatus']);
	$presentationStatus = "'" . $presentationStatus . "'";

	// if ($_POST['presentationStatus'] == 'Presented') {
	// 	if (!$_POST['presentedby']) {
	// 		$nameError = $nameError . "Please enter by whom it is presented<br>";
	// 		$flag = 0;
	// 	} else {
	// 		if ($presentedby == "") {
	// 			$presentedby = 'NA';
	// 		} else {
	// 			$presentedby = validateFormData($_POST['presentedby']);
	// 			$presentedby = "$presentedby";
	// 		}
	// 		if ($publication == "") {
	// 			$publication = 'NA';
	// 		} else {
	// 			$publication = validateFormData($_POST["publication"]);
	// 			$publication = "$publication";
	// 		}
	// 	}
	// }

	// if ($_POST['presentationStatus'] == 'Not Presented') {
	// 	$presentedby = 'NA';
	// 	$publication = 'NA';
	// }

	//following are not required so we can directly take them as it is

	// $coAuthors = validateFormData($_POST["coauthors"]);
	// $coAuthors = "'" . $coAuthors . "'";


	// $volume = validateFormData($_POST["volume"]);
	// $volume = "'" . $volume . "'";

	// if (isset($_POST["index"])) {
	// 	$index = validateFormData($_POST["index"]);
	// 	$index = "$index";
	// } else {
	// 	$index = 'NA';
	// }

	$hindex = validateFormData($_POST["hindex"]);
	$hindex = "'" . $hindex . "'";

	$scopus = validateFormData($_POST["scopus"]);
	$scopus = "'" . $scopus . "'";

	$sci = validateFormData($_POST["sci"]);
	$sci = "'" . $sci . "'";

	$impact = validateFormData($_POST["impact"]);
	$impact = "'" . $impact . "'";

	// $citation = validateFormData($_POST["citation"]);
	// $citation = "'" . $citation . "'";



	// $awards = validateFormData($_POST["awards"]);
	// $awards = "$awards";


	// $applicablefdc = $_POST["applicablefdc"];
	// $fdc = $applicablefdc;

	// if ($applicablefdc == 'Yes') {
	// 	$fdc = validateFormData($_POST["fdc"]);
	// 	$fdc = 'Yes';
	// } else if ($applicablefdc == 'No') {
	// 	$fdc = "Not applicable";
	// }
	// if ($awards != "") {
	// 	$awards = validateFormData($awards);
	// 	$awards = "$awards";
	// } else {
	// 	$awards = 'NA';
	// }
	// $etime = ltrim($etime, $etime[0]);
	$days = date_diff($start, $end);
	$no_of_days = $days->format('%d');
	$month = $start->format('n');
	$year = $start->format('Y');
	$no_of_weeks = $no_of_days / 7;

	$coauthorname = "";
	$coautharray = array();
	if (isset($_POST["co_name"])) {
		for ($count2 = 0; $count2 < count($_POST["co_name"]); $count2++) {
			$co_name = $_POST["co_name"][$count2];
			array_push($coautharray, $co_name);
		}
		$coauthorname = implode(',', $coautharray);
	} else {
		$coauthorname = $coAuthors;
	}
	echo $coauthorname;

	if (isset($_POST['applicable'])) {
		// console.log($_POST['applicable']);
		if ($_POST['applicable'] == 2) {
			$paperpath = 'NULL';
			$success = 1;
		} else if ($_POST['applicable'] == 3) {
			$paperpath = 'not_applicable';
			$success = 1;
		} else if ($_POST['applicable'] == 1) {
			if (isset($_FILES['paper'])) {
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
					}
				} else {
					print_r($errors);
					//header("location:else.php");
				}
			}
		}
	}

	if (isset($_POST['applicable1'])) {
		if ($_POST['applicable1'] == 2) {
			$certipath = 'NULL';
			$success = 1;
		} else if ($_POST['applicable1'] == 3) {
			$certipath = 'not_applicable';
			$success = 1;
		} else if ($_POST['applicable1'] == 1) {
			if (isset($_FILES['certificate'])) {
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
					} else {
					}
				} else {
					print_r($errors);
				}
			}
		}
	}
	if (isset($_POST['applicable2'])) {
		if ($_POST['applicable2'] == 2) {
			$reportpath = 'NULL';
			$success = 1;
		} else if ($_POST['applicable2'] == 3) {
			$reportpath = 'not_applicable';
			$success = 1;
		} else if ($_POST['applicable2'] == 1) {
			if (isset($_FILES['report'])) {
				$errors = array();
				$fileName = $_FILES['report']['name'];
				$fileSize = $_FILES['report']['size'];
				$fileTmp = $_FILES['report']['tmp_name'];
				$fileType = $_FILES['report']['type'];
				$fileExt = strtolower(end(explode('.', $fileName)));
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
					} else {
					}
				} else {
					print_r($errors);
				}
			}
		}
	}
	//checking if there was an error or not
	$query = "SELECT Fac_ID from facultydetails where Email='" . $_SESSION['loggedInEmail'] . "';";
	$result = mysqli_query($conn, $query);
	if ($result) {
		$row = mysqli_fetch_assoc($result);
		$author = $row['Fac_ID'];
	}
	$succ = 0;
	$success1 = 0;
	// if ($index == "N") {
	// 	$index = 'NA';
	// }
	// if ($publication == "") {
	// 	$publication = 'NA';
	// }
	// if ($presentedby == "") {
	// 	$presentedby = 'NA';
	// }

	if ($flag == 1) {
		$sql = "UPDATE faculty set Paper_title = $paperTitle,		
                               Paper_type = $paperType,							   
							   conf_journal_name = $conf,
							   PeerRev = $paperCategory,
							   Date_from = $startDate,
							   Date_to = $endDate, 
							   Paper_co_authors ='$coauthorname',
							   scopus = $scopus,
							   SCI = $sci,		   
							   h_index = $hindex,							   
							   impact = $impact,
							   presentation_status = $presentationStatus,
							   Udate = $Udate,
							   paper_path = '" . $paperpath . "',
							   certificate_path ='" . $certipath . "',
							   report_path = '" . $reportpath . "',
							   noofdays = $no_of_days,
                               noofweeks = $no_of_weeks,
                               month = $month,
							   year = $year					   
							   WHERE P_ID = $id";

		// $sql1 = "UPDATE fdc set Paper_title = $paperTitle where P_ID = $id ";
		// $result1 = mysqli_query($conn, $sql1);
		echo $sql;
		if ($conn->query($sql) === TRUE) {
			if ($_SESSION['type'] == 'hod') {
				header("location:2_dashboard_hod.php?alert=update");
			} else {
				header("location:2_dashboard.php?alert=update");
			}
		} else {
			if ($_SESSION['type'] == 'hod') {
				header("location:2_dashboard_hod.php?alert=error");
			} else {
				header("location:2_dashboard.php?alert=error");
			}
		}
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

<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php include_once("includes/scripting.php"); ?>
<?php
if ($_SESSION['type'] == 'hod') {
	include_once('sidebar_hod.php');
} elseif ($_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
	include_once('sidebar_cod.php');
} else {
	include_once('sidebar.php');
}
?>

<div class="content-wrapper">

	<section class="content">
		<div class="row">
			<!--	  <img src="images\509536872-612x612.jpg" alt="Soamiya" align="right" width="350" height="910" style="margin-right:15px;"> -->
			<!-- left column -->
			<div class="col-md-8">
				<!-- general form elements -->
				<br /><br /><br />

				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="icon">
							<i style="font-size:20px" class="fa fa-edit"></i>
							<h3 class="box-title"><b>Paper Presentation/Publication</b></h3>
							<br>
						</div>
					</div><!-- /.box-header -->

					<!-- form start -->

					<form role="form" method="POST" class="row" action="3_edit_hod.php" style="margin:10px;" enctype="multipart/form-data">
						<?php
						if ($flag == 0) {
							echo '<div class="error">' . $nameError . '</div>';
							//echo '<script type="text/javascript">alert("INFO:  '.$nameError.'");</script>';				
						}
						?>


						<input type='hidden' name='id' value='<?php echo $id; ?>'>
						<input type="hidden" name="Udate" value="<?php echo date('Y-m-d H:i:s'); ?>" />
						<br />
						<div class="form-group col-md-6">
							<label for="department_name">Department Name</label>
							<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $_SESSION['Dept']; ?>" readonly>
						</div>
						<div class="form-group col-md-6">

							<label for="faculty-name">Faculty Name</label>
							<input type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
						</div>
						<div class="form-group col-md-6">
							<label for="paper-title">Title of Paper</label><span class="colour"><b> *</b></span>
							<input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle" value="<?php echo "$paperTitle"; ?>">
						</div>
						<div class="form-group col-md-6">
							<label for="paper-type">Paper Type </label><span class="colour"><b> *</b></span>
							<select required class="form-control input-lg" id="paper-type" name="paperType">

								<option <?php if ($paperType_db == 'national_conference') echo "selected = 'selected'" ?> value="national_conference">National Conference</option>
								<option <?php if ($paperType_db == 'national_journal') echo "selected = 'selected'" ?> value="national_journal">National Journal</option>
								<option <?php if ($paperType_db == 'international_conference') echo "selected = 'selected'" ?> value="international_conference">International Conference</option>
								<option <?php if ($paperType_db == 'international_journal') echo "selected = 'selected'" ?> value="international_journal">International Journal</option>
							</select>
						</div>
						<!-- <div class="form-group col-md-6">
							<label for="paper-level">Paper Level </label><span class="colour"><b> *</b></span>
							<select required class="form-control input-lg" id="paper-level" name="paperLevel">
								<option <?php if ($paperLevel_db == "national") echo "selected = 'selected'" ?> value="national">National</option>
								<option <?php if ($paperLevel_db == "international") echo "selected = 'selected'" ?> value="international">International</option>
							</select>
						</div> -->

						<?php


						$replace_str = array('"', "'", '', '');
						$conf = str_replace($replace_str, "", $conf);

						?>

						<div class="form-group col-md-6">
							<label for="aff">Affiliation </label><span class="colour"><b> *</b></span>
							<input type="text" required class="form-control input-lg" id="aff" name="aff" rows="2" value="<?php echo $conf; ?>">
						</div>


						<div class="form-group col-md-6">
							<label for="PeerRev">Peer Reviewed </label><span class="colour"><b> *</b></span>
							<select required class="form-control input-lg" id="PeerRev" name="PeerRev">
								<option <?php if ($paperCategory_db == "yes") echo "selected = 'selected'" ?> value="yes">Yes</option>
								<option <?php if ($paperCategory_db == "no") echo "selected = 'selected'" ?> value="no">No</option>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="start-date">Start Date </label><span class="colour"><b> *</b></span>
							<input <?php echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate" placeholder="03:10:10">
						</div>

						<div class="form-group col-md-6">
							<label for="end-date">End Date </label><span class="colour"><b> *</b></span>
							<input <?php echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate" placeholder="03:10:10">
						</div>

						<!-- <div class="form-group col-md-6">
							<label for="location">Location </label><span class="colour"><b> *</b></span>
							<input required type="text" class="form-control input-lg" id="location" name="location" value='<?php echo $location ?>'>
						</div> -->

						<!-- <div class="form-group col-md-6">
							<label for="Index">Index </label><br />
							<input type="radio" name="index" value="scopus" <?php echo ($index_db == 'scopus') ? 'checked' : '' ?>> Scopus<br>
							<input type="radio" name="index" value="sci" <?php echo ($index_db == 'sci') ? 'checked' : '' ?>> SCI<br>
							<input type="radio" name="index" value="both" <?php echo ($index_db == 'both') ? 'checked' : '' ?>> Both
						</div> -->

						<!-- <div class="form-group col-md-6">
							<label for="scopus">Provide Scopus/SCI/both if applicable</label>
							<input <?php if ($scopus == "") echo "";
									else echo "value = $scopus"; ?> type="text" class="form-control input-lg" id="scopus" name="scopus">
						</div> -->

						<div class="form-group col-md-6">
							<label for="scopus">Scopus</label><span class="colour"><b> *</b></span>
							<select required class="form-control input-lg" id="scopus" name="scopus">
								<option <?php if ($scopus == "yes") echo "selected = 'selected'" ?> value="yes">Yes</option>
								<option <?php if ($scopus == "no") echo "selected = 'selected'" ?> value="no">No</option>
							</select>
						</div>

						<div class="form-group col-md-6">
							<label for="scopus_index">Scopus Index Link </label>
							<span class="colour"><b> *</b></span>
							<input type="url" class="form-control input-lg" id="scopus_index" name="scopus_index[]">
						</div>

						<div class="form-group col-md-6">
							<label for="sci">SCI </label><span class="colour"><b> *</b></span>
							<select required class="form-control input-lg" id="sci" name="sci">
								<option <?php if ($sci == "yes") echo "selected = 'selected'" ?> value="yes">Yes</option>
								<option <?php if ($sci == "no") echo "selected = 'selected'" ?> value="no">No</option>
							</select>
						</div>

						<div class="form-group col-md-6">
							<label for="sci_index">SCI Index Link </label>
							<span class="colour"><b> *</b></span>
							<input type="url" class="form-control input-lg" id="sci_index" name="sci_index[]">
						</div>


						<div class="form-group col-md-6">
							<label for="location">H-Index</label>
							<input <?php if ($hindex == "") echo "";
									else echo "value = $hindex"; ?> type="number" class="form-control input-lg" id="hindex" name="hindex">
						</div>

						<div class="form-group col-md-6">
							<label for="impact">Impact</label>
							<input <?php if ($impact == "") echo "";
									else echo "value = $impact"; ?> type="number" class="form-control input-lg" id="impact" name="impact">
						</div>

						<!-- <div class="form-group col-md-6">
							<label for="coauthors">Co-Author </label>
							<input type="text" class="form-control input-lg" id="coauthors" name="coauthors" rows="2" value="<?php echo $coAuthors; ?>">
						</div> -->
						<div class="form-group col-md-6">

							<label for="c_name">Co-Author</label>
							<div class="table-repsonsive">
								<span id="error"></span>
								<table class="table table-bordered" id="c_name">
									<tr>
										<input type="text" required class="form-control input-lg" id="coordinated" name="coordinated" rows="2" value="<?php echo $coAuthors; ?>" disabled>
									</tr>
									<tr>
										<th>Click to Edit </th>
										<th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
									</tr>
								</table>
							</div>
						</div><br>

						<!-- <div class="form-group col-md-6">
							<label for="volume">Volume/Issue/ISSN </label>
							<input type="text" class="form-control input-lg" id="volume" name="volume" rows="1" value="<?php echo $volume; ?>">
						</div> -->


						<div class="form-group col-md-6">
							<label for="presentation-status">Attended/Presented </label><span class="colour"><b> *</b></span>
							<select required onchange="myfunction()" class="form-control input-lg" id="presentation-status" name="presentationStatus">
								<option <?php if ($presentationStatus_db == "no") echo "selected = 'selected'" ?> value="no">No</option>
								<option <?php if ($presentationStatus_db == "yes") echo "selected = 'selected'" ?> value="yes">Yes</option>
							</select>
						</div>
						<!-- <div class="form-group col-md-6">
							<label for="awards">Awards, if any</label>
							<input type="text" class="form-control input-lg" id="awards" name="awards" rows="1" value="<?php echo $awards; ?>">
						</div>
						<div id="presented-by" class="form-group col-md-6">
							<label for="presented-by">Presented By </label><span class="colour"><b> *</b></span>
							<input <?php if ($presentedby_db == "") echo "value = '$F_NAME'";
									else echo "value = '$presentedby_db'"; ?> required type="text" class="form-control input-lg" id="presented-by" name="presentedby">
						</div>
						<div id="publication" class="form-group col-md-6">
							<label for="publication">Link of Online Publication</label>
							<input type="text" class="form-control input-lg" id="publication" name="publication" rows="1" value="<?php echo $publication; ?>">
						</div>
						<div class="form-group col-md-6">
							<label for="applicable-fdc">Is FDC applicable? </label><span class="colour"><b> *</b></span>
							<select required onchange="myfunction1()" class="form-control input-lg applicable-fdc" id="applicable-fdc" name="applicablefdc">
								<option <?php if ($fdc_db == "Not applicable") echo "selected = 'selected'" ?> value="No">No</option>
								<option <?php if ($fdc_db == 'no' || $fdc_db == 'yes' || $fdc_db == 'No' || $fdc_db == 'Yes') echo "selected = 'selected'" ?> value="Yes">Yes</option>
							</select>
						</div> -->

						<!-- <div id="fdc" class="form-group col-md-6" style="display:none">
							<label for="fdc">Applied for FDC ? </label><span class="colour"><b> *</b></span>
							<select class="form-control input-lg" id="fdc" name="fdc">
								<option <?php if ($fdc_db == "yes") echo "selected = 'selected'" ?> value="yes">Yes</option>
								<option <?php if ($fdc_db == "no") echo "selected = 'selected'" ?> value="no">No</option>
							</select>
						</div> -->
						<div class="form-group col-md-6 col-md-offset-1"></div>
						<div class="form-group col-md-6">
							<div>
								<label for="Index">Paper : </label><br />
								<input type="radio" id="r1" name="applicable" value="1" class="non-vac" <?php echo ($paperpath != NULL) ? 'checked' : '' ?>>Yes
								<br>
								<input type="radio" name="applicable" value="2" class="vac" <?php echo ($paperpath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
								<input type="radio" name="applicable" value="3" class="vac" <?php echo ($paperpath == 'not_applicable') ? 'checked' : '' ?>> No
							</div>
							<br>
							<div class='second-reveal' id='f1'>
								<div>

									<label for="card-image">Paper </label><span class="colour"><b> *</b></span>
									<input type="file" class="form-control input-lg" id="card-image" name="paper">
									<a <?php
										$f = 0;
										if ($paperpath != "not_applicable" && $paperpath != "NULL" && $paperpath != 'no status' && $paperpath != "") {
											echo "href='$paperpath'";
											$f = 1;
										} else {
											echo "";
										}
										?> target="_blank">
										<h4><?php if ($f == 1) {
												echo "View Existing paper";
											} ?><h4>
									</a>
								</div>
							</div>


							<div>
								<label for="Index">Certificate : </label><br />
								<input type="radio" name="applicable1" id="r2" value="1" class="non-vac1" <?php echo ($certipath != NULL) ? 'checked' : '' ?>>Yes<br>
								<input type="radio" name="applicable1" value="2" class="vac1" <?php echo ($certipath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
								<input type="radio" name="applicable1" value="3" class="vac1" <?php echo ($certipath == 'not_applicable') ? 'checked' : '' ?>> No

							</div>
							<br>
							<div class='second-reveal1' id='f2'>
								<div>

									<label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
									<input type="file" class="form-control input-lg" id="card-image" name="certificate">
									<a <?php
										$f1 = 0;
										if ($certipath != "not_applicable" && $certipath != "NULL" && $certipath != 'no status' && $certipath != "") {
											echo "href='$certipath'";
											$f1 = 1;
										} else {
											echo "";
										}
										?> target="_blank">
										<h4><?php if ($f1 == 1) {
												echo "View Existing Certificate";
											} ?><h4>
									</a>
								</div>
							</div>


							<div>
								<label for="Index">Report : </label><br />
								<input type="radio" name="applicable2" id="r3" value="1" class="non-vac2" <?php echo ($reportpath != NULL) ? 'checked' : '' ?>>Yes<br>
								<input type="radio" name="applicable2" value="2" class="vac2" <?php echo ($reportpath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
								<input type="radio" name="applicable2" value="3" class="vac2" <?php echo ($reportpath == 'not_applicable') ? 'checked' : '' ?>> No
							</div>
							<br>

							<div class='second-reveal2' id='f3'>
								<div>

									<label for="card-image">Report </label><span class="colour"><b> *</b></span>
									<input type="file" class="form-control input-lg" id="card-image" name="report">
									<a <?php
										$f2 = 0;
										if ($reportpath != "not_applicable" && $reportpath != "NULL" && $reportpath != 'no status' && $reportpath != "") {
											echo "href='$reportpath'";
											$f2 = 1;
										} else {
											echo "";
										}
										?> target="_blank">
										<h4><?php if ($f2 == 1) {
												echo "View Existing Report";
											} ?><h4>
									</a>
								</div>
							</div>
						</div>

						<script>
							window.onload = function() {
								myfunction();
								myfunction1();
								mycheck1();
								mycheck2();
								mycheck3();
							}

							function myfunction() {
								var x = document.getElementById("presentation-status").value;

								if (x == 'Presented') {
									//document.getElementById("demo").innerHTML = "You selected: " + x;
									//console.log(document.getElementById("presented-by"));
									document.getElementById("presented-by").style.display = 'block';
									document.getElementById("publication").style.display = 'block';
								} else {
									//document.getElementById("demo").innerHTML = "You selected: " + x;
									document.getElementById("presented-by").style.display = 'none';
									document.getElementById("publication").style.display = 'none';
								}
							}

							function myfunction1() {
								var y = document.getElementById("applicable-fdc").value;

								if (y == 'Yes') {

									document.getElementById("fdc").style.display = 'block';

								} else {
									document.getElementById("fdc").style.display = 'none';
								}
							}

							function mycheck1() {
								var radio1 = document.getElementById("r1");
								var file1 = document.getElementById("f1");
								if (radio1.checked == true) {
									file1.style.display = "block";
								} else {
									file1.style.display = "none";
								}
							}

							function mycheck2() {
								var radio2 = document.getElementById("r2");
								var file2 = document.getElementById("f2");
								if (radio2.checked == true) {
									file2.style.display = "block";
								} else {
									file2.style.display = "none";
								}
							}

							function mycheck3() {
								var radio3 = document.getElementById("r3");
								var file3 = document.getElementById("f3");
								if (radio3.checked == true) {
									file3.style.display = "block";
								} else {
									file3.style.display = "none";
								}
							}
						</script>

						<div class="form-group col-md-12">
							<?php
							if ($_SESSION['type'] == 'hod') {
								echo '<a href="2_dashboard_hod.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>';
							} else {
								echo '<a href="2_dashboard.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>';
							}
							?>

							<button name="update" type="submit" class="demo btn btn-success pull-right btn-lg">Submit</button>

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
			<?php for ($x = 0; $x < sizeof($coordiarray); $x++) { ?>
				html += '<tr>';
				html += '<td><select name="co_name[]" class="form-control item_unit" id="search"><option value="<?php echo $coordiarray[$x]; ?>"><?php echo $coordiarray[$x]; ?></option><?php echo fill_unit_select_box($connect); ?></select></td>';
				html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';

			<?php } ?>
			$('#c_name').append(html);
		});

		$(document).on('click', '.remove', function() {
			$(this).closest('tr').remove();
		});
	});
</script>


<?php include_once('footer.php'); ?>