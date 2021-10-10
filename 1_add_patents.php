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

$_SESSION['currentTab'] = "patents";

//connect to the database
include_once("includes/connection.php");

$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid ";
$resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
  $namefac=$row['F_NAME'];
  $deptName=$row['Dept'];
  $_SESSION['F_NAME'] = $row['F_NAME'];
  $F_NAME = $row['F_NAME'];
}
//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");

//include config file
include_once("includes/config.php");

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

//setting error variables
$nameError="";
$emailError="";
$papererror="";
// $paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coauthors = $volume = ""; 
// $presentationStatus = $index= $publication = $awards = $presentedby = $paperpath=$reportpath=$letterpath="";
$flag=1;
$success = 0;
$count1 = 1;
$s = 1;
$p_id = 0;
$faculty_name= $_SESSION['loggedInUser'];
$error1 = $error2 = $error3 = "";
	
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	if(isset($_POST['add']))
	{	
    //the form was submitted
	
	$investigatorFac_array = $_POST['investigator_fac'];
	// $investigatorFirst_array = $_POST['investigator_first'];
	// $investigatorLast_array = $_POST['investigator_last'];
	$title_array = $_POST['title'];
	$appNo_array = $_POST['app_no'];
	$dateFiling_array = $_POST['date_filing'];
	$status_array = $_POST['status'];
	$datePub_array = $_POST['date_publication'];
    		
	for($i=0; $i<1;$i++)
	{
		$investigatorFac = mysqli_real_escape_string($conn,$investigatorFac_array[$i]);
		// $investigatorFirst = mysqli_real_escape_string($conn,$investigatorFirst_array[$i]);
		// $investigatorLast = mysqli_real_escape_string($conn,$investigatorLast_array[$i]);
		$title = mysqli_real_escape_string($conn,$title_array[$i]);
		$appNo = mysqli_real_escape_string($conn,$appNo_array[$i]);
		$dateFiling = mysqli_real_escape_string($conn,$dateFiling_array[$i]);
		$status = mysqli_real_escape_string($conn,$status_array[$i]);
		$datePub = mysqli_real_escape_string($conn,$datePub_array[$i]);

		$time=time();
		$date_calc = new DateTime(date($datePub,$time));
		$month = $date_calc->format('n');
		$year = $date_calc->format('Y');
		  
        $investigatorFac=validateFormData($investigatorFac);
        $investigatorFac = "$investigatorFac";
   
        // $investigatorFirst=validateFormData($investigatorFirst);
        // $investigatorFirst = "$investigatorFirst";
    
        // $investigatorLast=validateFormData($investigatorLast);
        // $investigatorLast = "$investigatorLast";
   
        $title=validateFormData($title);
        $title = "$title";
   
		
	
		if ($dateFiling > $datePub)		
		{
			$nameError=$nameError." Date of filing cannot be greater than date of publishing <br>";
			$error = "Date of filing cannot be greater than date of publishing";
			$s = 0;
			$flag = 0;
		}
	
		$appNo=validateFormData($appNo);
		$appNo = "$appNo";
		
		$dateFiling=validateFormData($dateFiling);
		$dateFiling = "$dateFiling";
	
        $status=validateFormData($status);
        $status = "$status";

		$datePub=validateFormData($datePub);
        $datePub = "$datePub";
   		
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
        $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result)
	   {
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

			if(isset($_POST['applicable1']))
			{
				if($_POST['applicable1'] == 2)
				{
					$letterpath='NULL';		
					$success=1;		 
				}
				if($_POST['applicable1'] == 3)
				{
					$letterpath='not_applicable';
					$success=1;		
				}
				if($_POST['applicable1'] == 1)
				{
					if(isset($_FILES['letter_path']) && $_FILES['letter_path']['name'] != NULL && $_FILES['letter_path']['name'] !="")
					{
					  $errors= array();
					  $fileName = $_FILES['letter_path']['name'];
					  $fileSize = $_FILES['letter_path']['size'];
					  $fileTmp = $_FILES['letter_path']['tmp_name'];
					  $fileType = $_FILES['letter_path']['type'];
					  $temp=explode('.',$fileName);
					  $fileExt=strtolower(end($temp));					  
					  date_default_timezone_set('Asia/Kolkata');
					  $targetName=$datapath."letters/".$_SESSION['F_NAME']."_letters_".date("d-m-Y H-i-s", time()).".".$fileExt;  
					  
					  if(empty($errors)==true) {
						if (file_exists($targetName)) {   
							unlink($targetName);
						}      
						 $moved = move_uploaded_file($fileTmp,"$targetName");
						 if($moved == true){
						 	$letterpath=$targetName;
						 	$success=1;		
						 }
						//  else{
						// 	echo "<h1> $targetName </h1>";
						//  }
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
			if(isset($_POST['applicable2']))
			{
				if($_POST['applicable2'] == 2)
				{
					$reportpath='NULL';
					$success=1;						 
				}
				if($_POST['applicable2'] == 3)
				{
					$reportpath='not_applicable';
					$success=1;
				}
				if($_POST['applicable2'] == 1)
				{
					if(isset($_FILES['report_path']) && $_FILES['report_path']['name'] != NULL && $_FILES['report_path']['name'] !="" )
					{
					  $errors= array();
					  $fileName = $_FILES['report_path']['name'];
					  $fileSize = $_FILES['report_path']['size'];
					  $fileTmp = $_FILES['report_path']['tmp_name'];
					  $fileType = $_FILES['report_path']['type'];
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
						//  else{
						// 	echo "<h1> $targetName </h1>";
						//  }
					  }else{
						 print_r($errors);
					  }
					}
					else{
						$s = 0;
						$error3 = "No file selected";
					}
				}
			}

			// if($index=="N"){
			// 	$index="NA";
			// }
			// if($publication==""){
			// 	$publication='NA';
			// }
			// if($presentedby==""){
			// 	$presentedby='NA';
			// }
			
		if($flag!=0 && $s != 0)
		{	
			$sql="INSERT INTO patents(Fac_ID,investigator_fac,investigator_first,investigator_last,title,app_no,date_filing,status,date_publication,month,year,letter_path,report_path) VALUES ('$author','$investigatorFac','$investigatorFirst','$investigatorLast','$title','$appNo','$dateFiling','$status','$datePub','$month','$year','".$letterpath."','".$reportpath."')";
			// echo $sql;
			if ($conn->query($sql) === TRUE) 
			{
				$success = 1;
				// $p_id= $conn->insert_id;
			} 
			else
			{
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}

		// if(!isset($_POST['co_authf']) && !isset($_POST['co_name']) && $s != 0 ){
		// 		$coauthorname="NA";
		// 		$sqlquery="UPDATE patents SET investigator_fac='$coauthorname' where P_ID=$p_id";
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

				//   $query = "INSERT INTO co_author (c_id,c_name,p_id) VALUES ($val,'$co_name',$p_id)";
				//   $conn->query($query);
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
		// 	$sqlquery="UPDATE patents SET investigator='$coauthorname' where P_ID=$p_id";
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
		// 			// $value=$row['c_id'];
		// 			// if($value!=NULL){
		// 			// 	$query = "INSERT INTO co_author (c_id,c_name,p_id) VALUES ($value,'$co_auth',$p_id)";
		// 			// 	$conn->query($query);
		// 			// }else{
		// 		  	// 	$query = "INSERT INTO co_author (c_name,p_id) VALUES ('$co_auth',$p_id)";
		// 		  	// 	$conn->query($query);
		// 			// }
		// 		  if($coauthorname=="")
		// 		  {
		// 		  	$coauthorname=$co_auth;
		// 		  }
		// 		  else{
		// 		  $coauthorname=$coauthorname.", ".$co_auth;	 
		// 		}
		// 	}
	// 		if($coauthorname==""){
	// 			$coauthorname="NA";
	// 		}
	// 		$sqlquery="UPDATE patents SET investigator='$coauthorname' where P_ID=$p_id";
	// 		$conn->query($sqlquery);
	// 	}
	  }
	}//end of for
	if($success == 1 && $s != 0) 	
	{
		header("location:2_dashboard_patent.php?alert=success");
	}else if($s != 0){
		header("location:2_dashboard_patent.php?alert=error");
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
					<h3 class="box-title"><b>Patents/IPR/Copyrights Form</b></h3>
					<br>
					</div>
                </div><!-- /.box-header -->
				<div style="text-align:right">
			<!--	<a href="menu.php?menu=1 " style="text-align:right"> <u>Back to Paper Publication/Presentation Menu</u>&nbsp &nbsp </a> -->
                </div>
                <!-- form start -->

	<?php
			
					for($k=0; $k<1 ; $k++)
					{

				?>

			<form id="insert_form" role="form" method="POST" enctype="multipart/form-data" class="row" action ="" style= "margin:10px;" >


			
				<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
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
                        <label for="department">Department Name</label>
                        <input required type="text" class="form-control input-lg" id="department" name="department[]" value="<?php echo strtoupper($deptName); ?>" readonly>
                    </div>
			
					<div class="form-group col-md-6">
                         <label for="facultyName">Faculty Name</label>
                         <input required type="text" class="form-control input-lg" id="facultyName" name="facultyName[]" value="<?php echo $faculty_name; ?>" readonly>
                    </div>

					<div class="form-group col-md-6">					 
					 <label for="c_name">Name of Investigator (If Any)</label>
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
					 <label for="co_auth">Name of Investigator (If Not From Faculty)</label>
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
                         <label for="title">Title </label>
						 <span class="colour"><b> *</b></span>
                         <input type="text" required class="form-control input-lg" id="title" name="title[]"  <?php if(isset($_POST['title'])) echo "value = $title"; ?>>
                    </div>

					<div class="form-group col-md-6" id="application_number">
                         <label for="application_number">Application Number </label><span class="colour"><b> *</b></span>
                         <input  <?php if (isset($_POST['app_no'])) echo "value = $appNo"; ?> type="text" class="form-control input-lg" id="application_number" name="app_no[]" required>
                     </div>

					<div class="form-group col-md-6">
                         <label for="filing-grant-date">Date of Filing/Grant </label><span class="colour"><b> *</b></span>
                         <input <?php if(isset($_POST['date_filing'])) echo "value = $dateFiling"; ?> required type="date" class="form-control input-lg" id="filing-grant-date" name="date_filing[]"
                         placeholder="03:10:10">
                     </div>

					 <div class="form-group col-md-6">
                         <label for="publication-date">Date of Publication </label><span class="colour"><b> *</b></span>
                         <input <?php if(isset($_POST['date_publication'])) echo "value = $datePub"; ?> required type="date" class="form-control input-lg" id="publication-date" name="date_publication[]"
                         placeholder="03:10:10">
                     </div>

					<div class="form-group col-md-6">
                        <label for="level">Status</label>
						<span class="colour"><b> *</b></span>
                        <select required name="status[]" id="status_activities" class="form-control input-lg" >
                        <option value="" disabled selected>Select your option:</option>
                            <option name="applied[]" value="applied">Applied</option>
                            <option name="published[]" value="published">Published</option>
							<option name="granted[]" value="granted">Granted</option>
                        </select>
                    </div>
                    
					<div class="form-group col-md-6 col-md-offset-1"></div>
<!-- 
					<div class="form-group col-md-6"> 
                     <div >

						&nbsp;<label for="course">Upload paper : Applicable ?<br></label>
						<span class="colour"><b> *</b></span>
						<span class="error" style = "border : none;"> <?php echo $error1 ?> </span>						
						<br>	&nbsp;<input required type='radio' name='applicable' class='non-vac' value='1' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'checked':'' ?>> Yes <br>
						&nbsp;<input type='radio' name='applicable' class='vac' value='2' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="2")?'checked':'' ?>> Applicable, but not yet available <br>
										 
						&nbsp;<input type='radio' name='applicable' class='vac' value='3' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="3")?'checked':'' ?>> No <br>
					</div>
					<br>
					<div class='second-reveal' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'style = "display : block" ':'' ?>>
						 <div >
                    	     <label for="card-image">Paper </label><span class="colour"><b> *</b></span>
		        	         <input type="file" class="form-control input-lg" id="card-image" name="paper">
	        	        </div> 
					</div>
					<br> -->
					<div class="form-group col-md-6">  
					<div >
						&nbsp;<label for="course">Upload Grant Letter: Applicable?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "background-color: none; border : none;"> <?php echo $error2 ?> </span>
						<br>	&nbsp;<input required type='radio' name='applicable1' class='non-vac1' value='1' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'checked':'' ?>> Yes <br>
						&nbsp;<input type='radio' name='applicable1' class='vac1' value='2' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="2")?'checked':'' ?>> Applicable, but not yet available <br>
										 
						&nbsp;<input type='radio' name='applicable1' class='vac1' value='3' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="3")?'checked':'' ?>> No <br>
					</div>
					<br>
					<div class='second-reveal1' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'style = "display : block" ':'' ?>>
						 <div > 
                    	     <label for="card-image">Grant Letter</label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="letter_path" >
	        	        </div> 
					</div>
					<br>
                     <div >

						&nbsp;<label for="course">Upload Report: Applicable?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "border : none;"> <?php echo $error3 ?> </span>
						<br>	&nbsp;<input required type='radio' name='applicable2' class='non-vac2' value='1' <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="1")?'checked':'' ?>> Yes <br>
						&nbsp;<input type='radio' name='applicable2' class='vac2' value='2' <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="2")?'checked':'' ?>> Applicable, but not yet available <br>
										 
						&nbsp;<input type='radio' name='applicable2' class='vac2' value='3' <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="3")?'checked':'' ?>> No <br>
					</div>
					<br>
					<div class='second-reveal2' <?php if(isset($_POST['applicable'])) echo($_POST['applicable2'] =="1")?'style = "display : block" ':'' ?> >
						 <div >
							 
                    	     <label for="card-image">Report </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="report_path">
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
				
							$(this).parent().next()[0].style.display = "block";
						
						}
						else
						{
								$(this).parent().next()[0].style.display = "none";
						}
					}
						
				</script>		
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                         <a href="list_of_activities_user.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>

                         <button name="add"  type="submit" class="demo btn btn-success pull-right btn-lg">Submit</button>
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
  html += '<td><select name="investigator_fac[]" class="form-control item_unit" id="search"><option value="">Select Investigator</option><?php echo fill_unit_select_box($connect); ?></select></td>';
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
  html += '<td><input type="text" name="investigator_first[]" placeholder="First name" class="form-control item_name" /></td>';
  html += '<td><input type="text" name="investigator_last[]" placeholder="Last name" class="form-control item_name" /></td>';
  html += '<td><button type="button" name="remove1" class="btn btn-danger btn-sm remove1"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
  $('#co_auth').append(html);
 });
 
 $(document).on('click', '.remove1', function(){
  $(this).closest('tr').remove();
 });
 
});
 </script>   
    
    
    
<?php include_once('footer.php'); ?>
   
   