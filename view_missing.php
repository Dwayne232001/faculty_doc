<?php
ob_start();
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}

if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod')
    //if not hod then send the user to login page
    header("location:index.php");
}

$_SESSION['currentTab'] = "missing";
 include_once('head.php'); 
include_once('header.php'); 
 
if($_SESSION['type'] == 'hod')
{
      include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
        include_once('sidebar_cod.php');
}
else{
    include_once('sidebar.php');
}


//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
//query and result
/*$query = "SELECT P_ID, Fac_ID,Paper_title,Paper_type,Paper_N_I,Paper_category,Date_from,Date_to
,Location,paper_path,certificate_path,report_path,Paper_co_authors,volume FROM faculty";*/
$query = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID ";
$result = mysqli_query($conn,$query);


$successMessage="";
if(isset($_GET['alert'])){
    if($_GET['alert']=="success"){
        $successMessage='<br/><br/><br/><div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Operation successful</strong>
        </div>';  

	}
	elseif($_GET['alert']=="fail"){
        $successMessage='<br/><br/><br/><div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Operation failed</strong>
        </div>';  

    }
}
?>


<style>
div.scroll
{
overflow:scroll;

}


</style>
<script type="text/javascript">

	function getExportAction(checkBox, selectBox){
		var checkbox = document.getElementById(checkBox);
		var submit = document.getElementById("export");
		if(checkbox.checked){
			submit.formAction = 'proofmissing.php';
		}
		else{
			submit.formAction = 'export_missing.php';
		}

	}

	function selectAll(selectBox){
		var checkbox = document.getElementById('select-all');
		if (typeof selectBox == "string") { 
        selectBox = document.getElementById(selectBox);
    	}
  		var selectAll = checkbox.checked;
		for (var i = 0; i < selectBox.options.length; i++) { 
            selectBox.options[i].selected = selectAll; 
        } 
	}

	function preventBack() { window.history.forward(); 
	
	}
	setTimeout("preventBack()",0);
	
	window.onunload = function() {null};
</script>


	
<div class="content-wrapper">
   <?php 
        {
        echo $successMessage;
    }
	$display = 0;
	if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
	{
		$display = 1;
	}
	else if($_SESSION['username'] == 'member@somaiya.edu')
	{
		$display = 2;
	}
    ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
			<?php if(!isset($_GET['alert'])){ ?>
           		<br/><br/><br/>
			<?php } ?>
              <div class="box box-primary">
                <div class="box-header with-border">
					<div class="icon">
					<i style="font-size:18px" class="fa fa-table"></i>
					<h2 class="box-title"><b>Missing Attachments</b></h2>
					<br>
					</div>
                </div><!-- /.box-header -->
                <div class="box-body">
					<div class="col-sm-6" >
					<?php 
					$q1 = "SELECT DISTINCT F_NAME, Email, facultydetails.Fac_ID FROM facultydetails inner join faculty on faculty.Fac_ID=facultydetails.Fac_ID where (paper_path = 'NULL' OR paper_path = '') and  (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') ";
					$q2 = "SELECT DISTINCT F_NAME, Email, facultydetails.Fac_ID FROM facultydetails inner join paper_review on paper_review.Fac_ID = facultydetails.Fac_ID where (mail_letter_path = 'NULL' OR mail_letter_path = '') and  (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') ";
					$q3 = "SELECT DISTINCT F_NAME, Email, facultydetails.Fac_ID FROM facultydetails inner join attended on attended.Fac_ID = facultydetails.Fac_ID where (Permission_path = 'NULL' OR Permission_path = '') and  (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') ";
					$q4 = "SELECT DISTINCT F_NAME, Email, facultydetails.Fac_ID FROM facultydetails inner join organised on organised.Fac_ID=facultydetails.Fac_ID where (Brochure_path = 'NULL' OR Brochure_path = '') and (Permission_path = 'NULL' OR Permission_path = '') and  (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') ";
					$q5 = "SELECT DISTINCT researchdetails.facultyName, facultydetails.Email, facultydetails.Fac_ID FROM facultydetails inner join researchdetails on researchdetails.Fac_ID=facultydetails.Fac_ID where reportPath = '' or reportPath = 'NULL'";
					$q6 = "SELECT DISTINCT F_NAME, Email, facultydetails.Fac_ID FROM facultydetails inner join invitedlec on invitedlec.Fac_ID=facultydetails.Fac_ID where (invitation_path = 'NULL' OR invitation_path = '') and  (certificate_path = 'NULL' OR certificate_path = '') ";
					$q7 = "SELECT DISTINCT F_NAME,Email, facultydetails.Fac_ID FROM facultydetails inner join guestlec on guestlec.Fac_ID=facultydetails.Fac_ID where (attendance_path = 'NULL' OR attendance_path = '') and (permission_path = 'NULL' OR permission_path = '') and (certificate1_path = 'NULL' OR certificate1_path = '') and (report_path = 'NULL' OR report_path = '')";
					$q8 = "SELECT DISTINCT F_NAME,Email, facultydetails.Fac_ID FROM facultydetails inner join online_course_attended on online_course_attended.Fac_ID=facultydetails.Fac_ID where (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') ";
					$q9 = "SELECT DISTINCT F_NAME,Email, facultydetails.Fac_ID FROM facultydetails inner join online_course_organised on online_course_organised.Fac_ID=facultydetails.Fac_ID where (attendence_path = 'NULL' OR attendence_path = '') and  (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') ";
					$q10 = "SELECT DISTINCT F_NAME,Email, facultydetails.Fac_ID FROM co_curricular inner join facultydetails on co_curricular.Fac_ID = facultydetails.Fac_ID where (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') and (permission_path = 'NULL' OR permission_path = '')";
					$q11 = "SELECT DISTINCT F_NAME,Email, facultydetails.Fac_ID FROM ex_curricular inner join facultydetails on ex_curricular.Fac_ID = facultydetails.Fac_ID where (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') and (permission_path = 'NULL' OR permission_path = '')";
					$q12 = "SELECT DISTINCT F_NAME,Email, facultydetails.Fac_ID FROM any_other_activity inner join facultydetails on any_other_activity.Fac_ID = facultydetails.Fac_ID where (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') and (permission_path = 'NULL' OR permission_path = '')";
				
					$q13 = $q1 . " UNION " . $q2 . " UNION " . $q3 . " UNION " . $q4 . " UNION " . $q5 . " UNION " . $q6 . " UNION " . $q7 . " UNION " . $q8 ." UNION " . $q9 ." UNION " . $q10 ." UNION " . $q11 . " UNION " . $q12 . "ORDER BY F_NAME ASC";
					$r1 = mysqli_query($conn,$q13);
					?>
						<form method="POST" action="" id="facultyForm"> 
							<label>Select faculty:</label>
							<!-- <span style="display:inline-block; width: 330px;"></span> -->
							<label style="float:right"><input type="checkbox" name="Select all" id="select-all" onchange="selectAll('sel1')" /> Select all </label>
							<br/>
							<select multiple required style="height:40vh" name="email[]" aria-controls="example1" class="form-control input-sm" id="sel1" >
								<?php
								if(mysqli_num_rows($r1)>0){
									// echo "<option selected value=\"\">--No faculty selected-- </option>";
									while($row1 = mysqli_fetch_assoc($r1)){
										echo "<option id=${row1['Fac_ID']} value=${row1['Fac_ID']}>". $row1['F_NAME'] . " (" . $row1['Email'] .") </option>";
									}
								}
								?>
							</select>
							<div style="margin: 15px 0px;">
								<input type="submit" class="btn btn-warning btn-lg" value = "Export Faculty Data" id = "export" onclick = "getExportAction('select-all','sel1')">
								<input type="submit" class="btn btn-warning btn-lg" value = "Mail Faculty" id = "mail" formaction="export_to_excel_mail.php">
							</div>
						</form>
					 </div>
        		 </div>
    		</div>
		</div>
    </div>
</section>  
</div> 
     
<?php include_once('footer.php'); ?>
   
   