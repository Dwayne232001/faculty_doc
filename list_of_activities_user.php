<?php
ob_start();
session_start();
if (!isset($_SESSION['loggedInUser'])) {
	//send the iser to login page
	header("location:index.php");
}
$_SESSION['currentTab'] = "list";

include("includes/connection.php");

$fid = $_SESSION['Fac_ID'];

$queryrun = "SELECT * FROM facultydetails where Fac_ID=$fid ";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
	$_SESSION['Dept'] = $row['Dept'];
	$_SESSION['type'] = $row['type'];
	$_SESSION['username'] = $row['F_NAME'];
}

include_once('head.php');
include_once('header.php');

if ($_SESSION['type'] == 'hod') {
	include_once('sidebar_hod.php');
} elseif ($_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
	include_once('sidebar_cod.php');
} else {
	include_once('sidebar.php');
}


//update noofdays column in faculty
$sql = "SELECT * from faculty ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$pid = $row['P_ID'];

		$Date_from = $row['Date_from'];
		$Date_to = $row['Date_to'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update faculty set noofdays =$noofdays
            			where P_ID = $pid ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in paper_review
$sql = "SELECT * from paper_review ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$paper_review_ID = $row['paper_review_ID'];

		$Date_from = $row['Date_from'];
		$Date_to = $row['Date_to'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update paper_review set noofdays =$noofdays
            			where paper_review_ID = $paper_review_ID ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in researchdetails
$sql = "SELECT * from researchdetails ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$researchId = $row['researchId'];

		$Date_from = $row['fromDate'];
		$Date_to = $row['toDate'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update researchdetails set noofdays =$noofdays
            			where researchId = $researchId ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in faculty interaction
$sql = "SELECT * from invitedlec ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$invited_id = $row['invited_id'];

		$Date_from = $row['durationf'];
		$Date_to = $row['durationt'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update invitedlec set noofdays =$noofdays
            			where invited_id = $invited_id ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in sttp attended
$sql = "SELECT * from attended ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$A_ID = $row['A_ID'];

		$Date_from = $row['Date_from'];
		$Date_to = $row['Date_to'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update attended set noofdays =$noofdays
            			where A_ID = $A_ID ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in sttp organised
$sql = "SELECT * from organised ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$A_ID = $row['A_ID'];

		$Date_from = $row['Date_from'];
		$Date_to = $row['Date_to'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update organised set noofdays =$noofdays
            			where A_ID = $A_ID ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in guest lec organised
$sql = "SELECT * from guestlec  ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$p_id = $row['p_id'];

		$Date_from = $row['durationf'];
		$Date_to = $row['durationt'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update guestlec set noofdays =$noofdays
            			where p_id = $p_id ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in online course attended
$sql = "SELECT * from online_course_attended  ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$OC_A_ID = $row['OC_A_ID'];

		$Date_from = $row['Date_From'];
		$Date_to = $row['Date_To'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update online_course_attended set noofdays =$noofdays
            			where OC_A_ID = $OC_A_ID ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in online_course_organised
$sql = "SELECT * from online_course_organised  ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$OC_O_ID = $row['OC_O_ID'];

		$Date_from = $row['Date_From'];
		$Date_to = $row['Date_To'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update online_course_organised set noofdays =$noofdays
            			where OC_O_ID = $OC_O_ID ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in co-curricular
$sql = "SELECT * from co_curricular  ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$co_curricular_ID = $row['co_curricular_ID'];

		$Date_from = $row['Date_from'];
		$Date_to = $row['Date_to'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update co_curricular set noofdays =$noofdays
            			where co_curricular_ID = $co_curricular_ID ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in ex-curricular
$sql = "SELECT * from ex_curricular  ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$ex_curricular_ID = $row['ex_curricular_ID'];

		$Date_from = $row['Date_from'];
		$Date_to = $row['Date_to'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update ex_curricular set noofdays =$noofdays
            			where ex_curricular_ID = $ex_curricular_ID ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//update noofdays column in any other
$sql = "SELECT * from any_other_activity  ";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$any_other_ID = $row['any_other_ID'];

		$Date_from = $row['Date_from'];
		$Date_to = $row['Date_to'];

		$fromdate = strtotime($Date_from);
		$todate = strtotime($Date_to);

		$noofdays = $todate - $fromdate;
		$noofdays = round($noofdays / (60 * 60 * 24));


		$sql1 = "update any_other_activity set noofdays =$noofdays
            			where any_other_ID = $any_other_ID ";

		if ($conn->query($sql1) === TRUE) {
			$success = 1;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

?>

<style>
	.small-box:hover,
	.small-box:hover img {
		transform: scale(1.05);
	}

	.small-box,
	.small-box img {
		transition: all 300ms;
	}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- left column -->
			<div class="col-xs-12">
				<!-- general form elements -->
				<br /><br /><br />

				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Please Select Following Forms for Providing Activity Details</h3>
						<!--    <h4 class=""><strong>(Please find related submenu for each category at the left sidebar)</strong></h4> -->

					</div><!-- /.box-header -->
					<!-- form start -->
					<?php //echo $_SESSION['username'];
					?>
					<form role="form" action="" method="POST">


						<div class="box-body">
							<Section>
								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-yellow" style="height:110px;">
										<div class="inner">
											&nbsp;&nbsp;<button type="submit" name="paper[]" id="paper" value="" class="btn btn-warning btn-md"><b>Faculty&nbsp;&nbsp;<br>Publications&nbsp;&nbsp;</b>
											</button>
											<br /><br />
										</div>
										<div class="icon">
											<i class="fa fa-files-o"></i>
											<!--img src="images/download.jpg" alt="Paper" width="80" height="80"-->
										</div>
									</div>
								</div>

								<!-- <div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-aqua" style="height:110px;">
										<div class="inner">
											&nbsp;&nbsp;<button type="submit" name="techpaper[]" id="techpaper" value="" class="btn btn-info btn-md"><b>Paper&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>Reviewer</b></button>
											<br />

										</div>
										<div class="icon">
											<i class="fa fa-copy"></i>
										</div>
									</div>
								</div> -->
								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-red" style="height:110px;">
										<div class="inner">
											<button type="submit" name="research[]" id="research" value="" class="btn btn-danger btn-md"><b>Research Proposal/<br>Projects/Consultancy<br> Projects</b></button>
										</div>
										<div class="icon">
											<i class="fa fa-search"></i>
										</div>
									</div>
								</div>
								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-green" style="height:110px;">
										<div class="inner">
											<button type="submit" name="faculty[]" id="faculty" value="" class="btn btn-success btn-md"><b>Faculty Interaction <br>With Outside World</b></button>
										</div>
										<div class="icon">
											<i class="fa fa-users"></i>
										</div>
									</div>
								</div>
								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-red" style="height:110px;">
										<div class="inner">
											<button type="submit" name="sttp[]" id="sttp" value="" class="btn btn-danger btn-md"><b>STTP/WS/FDP/QIP/TR<br>S/IN Attended</b></button>
											<br />

										</div>
										<div class="icon">
											<i class="fa fa-pie-chart"></i>
										</div>
									</div>
								</div>

								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-red" style="height:110px;">
										<div class="inner">
											<button type="submit" name="sttp1[]" id="sttp1" value="" class="btn btn-danger btn-md"><b>STTP/WS/FDP/QIP/TR<br>S/IN Organised</b></button>
											<br />

										</div>
										<div class="icon">
											<i class="fa fa-pie-chart"></i>
										</div>
									</div>
								</div>

								
								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-yellow" style="height:110px;">
										<div class="inner">
											&nbsp;&nbsp;<button type="submit" name="online[]" id="online" value="" class="btn btn-warning btn-md"><b>Online Courses<br> Completed</b></button>
										</div>
										<div class="icon">
											<i class="fa fa-laptop"></i>
											<!--img src="images/download.jpg" alt="Paper" width="80" height="80"-->
										</div>
									</div>
								</div>


								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-yellow" style="height:110px;">
										<div class="inner">
											&nbsp;&nbsp;<button type="submit" name="books[]" id="books" value="" class="btn btn-warning btn-md"><b>Books/<br>Chapter<br>Published</b></button>
										</div>
										<div class="icon">
											<i class="fa fa-university"></i>
											<!--img src="images/download.jpg" alt="Paper" width="80" height="80"-->
										</div>
									</div>
								</div>

								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-green" style="height:110px;">
										<div class="inner">
											&nbsp;&nbsp;<button type="submit" name="patents[]" id="patents" value="" class="btn btn-success btn-md"><b>Patents/<br>IPR/<br>Copyrights</b></button>
										</div>
										<div class="icon">
											<i class="fa fa-sticky-note-o"></i>
											<!-- img src="images/download.jpg" alt="Paper" width="80" height="80" -->
										</div>
									</div>
								</div>
							
								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-yellow" style="height:110px;">
										<div class="inner">
											<button type="submit" name="iv[]" id="iv" value="" class="btn btn-warning btn-md"><b>Industrial Visit<br>Organised</b></button>
										</div>
										<div class="icon">
											<i class="fa fa-briefcase"></i>
										</div>
									</div>
								</div>


								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-green" style="height:110px;">
										<div class="inner">
											<button type="submit" name="co[]" id="co" value="" class="btn btn-success btn-md"><b>Faculty Awards<br>/Prizes/<br>Recognition Won</b></button>
										</div>
										<div class="icon">
											<i class="fa fa-trophy"></i>
										</div>
									</div>
								</div>
							
								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-red" style="height:110px;">
										<div class="inner">
											<button type="submit" name="ex[]" id="ex" value="" class="btn btn-danger btn-md"><b>Extra-Curricular<br>Activity</b></button>
										</div>
										<div class="icon">
											<i class="fa fa-table"></i>
										</div>
									</div>
								</div>


								<div class="form-group col-lg-3 col-xs-6">
									<div class="small-box bg-green" style="height:110px;">
										<div class="inner">
											<button type="submit" name="anyother[]" id="anyother" value="" class="btn btn-success btn-md"><b>Any Other<br>Activity</b></button>
										</div>
										<div class="icon">
											<i class="fa fa-table"></i>
										</div>
									</div>
								</div>
								</section>
								<section>
								<a href="Help_file_new.pdf" target="_blank"><img src="help.jpg" alt="Help File" style="&nbsp;&nbsp;&nbsp;width:100px;height:100px;border:0;">
								</a>
								
								
								<!-- <div class="form-group col-md-12">
     <?php if ($_SESSION['type'] == 'hod' && $_SESSION['type'] == 'cod' && $_SESSION['type'] == 'com') { ?>                    

              <a href="ExportAllToExcel_hod.php" type="button" class="btn btn-warning btn-lg">Export Entire Data</a>
	  <?php } else { ?>

             <a href="ExportAllToExcel.php" type="button" class="btn btn-warning btn-lg">Export Entire Data</a>

	 <?php  } ?>          
		</div>
					
					
		  <div class="form-group col-md-12">
                         
  <?php if ($_SESSION['type'] == 'hod' && $_SESSION['type'] == 'cod' && $_SESSION['type'] == 'com') { ?>    
             <a href="ExportAllToExcel_informat_hod.php" type="button" class="btn btn-warning btn-lg">Export Entire Data in specific format</a>
			 
			   <a href="proofmissing.php" type="button" class="btn btn-warning btn-lg" target="_blank">List of missing attachments</a>
			   <!--changed 1 line-->
								<a href="view_missing.php" type="button" class="btn btn-warning btn-lg">Mail Reminders</a>
								<!--end changed-->
							<?php } else { ?>
								<a href="ExportAllToExcel_informat.php" type="button" class="btn btn-warning btn-lg">Export Entire Data in specific format</a>

							<?php  } ?>
						</div> -->



						<!--	<div class="form-group col-lg-3 col-xs-6">
				   	<div class="small-box bg-red" style="height:110px;">
						<div class="inner">				   
						&nbsp;<button type="submit" name="ex" id="submit" value="" class="btn btn-danger btn-md"><b>Documentation</b></button>
						</div>
						<div class="icon">
						<i class="fa fa-book"></i>
						</div>
					</div>	
                </div> -->
	</section>
</div>

<?php
$username = $_SESSION['username'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['paper'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:2_dashboard_hod.php");
		} else
			header("location:1_add_paper_multiple.php");
	}
	if (isset($_POST['techpaper'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:2_dashboard_hod_review.php");
		} else
			header("location:1_add_paper_multiple_review.php");
	}

	if (isset($_POST['research'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:researchViewHOD.php");
		} else
			header("location:researchForm.php");
	}
	if (isset($_POST['faculty'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:view_invited_hod_lec.php");
		} else
			header("location:guest2.php");
	}

	if (isset($_POST['sttp'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:2_dashboard_attend_hod.php");
		} else
			header("location:1_add_paper_multiple_attend.php");
	}
	if (isset($_POST['online'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:2_dashboard_hod_online_attended.php");
		} else
			header("location:1_add_course_attended.php");
	}
	if (isset($_POST['guest'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:view_organised_hod_lec.php");
		} else
			header("location:organised_guest.php");
	}
	if (isset($_POST['iv'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:2_dashboard_iv_hod.php");
		} else
			header("location:industrialvisit.php");
	}
	if (isset($_POST['co'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:2_dashboard_hod_cocurricular.php");
		} else
			header("location:1_add_activity_multiple_cocurricular.php");
	}
	if (isset($_POST['copyrights'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:patents_copyrights.php");
		} else
			header("location:patents_copyrights.php");
	} //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['books'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:1_add_books_published.php");
		} else
			header("location:1_add_books_published.php");
	}
	if (isset($_POST['ex'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:2_dashboard_hod_excurricular.php");
		} else
			header("location:1_add_activity_multiple_excurricular.php");
	}
	if (isset($_POST['anyother'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:2_dashboard_hod_anyother.php");
		} else
			header("location:1_add_activity_multiple_anyother.php");
	}

	if (isset($_POST['cancel'])) {
		if ($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
			header("location:2_dashboard_hod.php");
		} else
			header("location:2_dashboard.php");
	}
}
?>
</form>

</div>
</div>
</div>
</section>
</div><!-- /.content-wrapper -->








<?php include_once('footer.php'); ?>