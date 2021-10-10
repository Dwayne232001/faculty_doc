<?php
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab']="technical_review";
//connect ot database
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
include_once("includes/config.php");

$Fac_ID=$_SESSION['Fac_ID'];
//setting error variables
$nameError="";
$emailError="";
$paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coAuthors = "";
$mail_letter_path=$reportpath=$certipath="";
$flag = 1;

if(isset($_POST['rid'])){
    $id = $_POST['rid'];
    $_SESSION['id']=$_POST['rid'];
}
    $id = $_SESSION['id'];
    $query = "SELECT * from paper_review where paper_review_ID = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['Fac_ID'];
    $currentTimestamp = $row['last_added'];
    $paperTitle = $row['Paper_title'];
    $startDate = $row['Date_from'];
    $endDate = $row['Date_to'];
    $paperType_db = $row['Paper_type'];
    $paperLevel_db = $row['Paper_N_I'];
	$conf = $row['conf_journal_name'];
    $paperCategory_db = $row['paper_category'];
    $organized = $row['organised_by'];
    $details = $row['details'];
    $volume = $row['volume'];
    $mail_letter_path=$row['mail_letter_path'];
	$certipath=$row['certificate_path'];
    $reportpath=$row['report_path'];
    
	$query2 = "SELECT * from facultydetails where Fac_ID = $Fac_ID";
	$result2 = mysqli_query($conn,$query2);
	if($result2)
	{
        $row = mysqli_fetch_assoc($result2);
    	$F_NAME = $row['F_NAME'];
    }
    $_SESSION['F_NAME'] = $F_NAME ;
	   
//check if the form was submitted
if(isset($_POST['update'])){
    //the form was submitted
    $clientName=$clientEmail=$clientPhone=$clientAddress=$clientCompany=$clientNotes="";
    
    //check for any blank input which are required
  
        $paperTitle=validateFormData($_POST['paperTitle']);
        $paperTitle = "'".$paperTitle."'";
   
		if ((strtotime($_POST['startDate'])) > (strtotime($_POST['endDate'])))
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
        }	
        $endDate=validateFormData($_POST['endDate']);
        $startDate=validateFormData($_POST['startDate']);

        $time=time();
        $start = new DateTime(date($startDate,$time));
        $end = new DateTime(date($endDate,$time));
        $days = date_diff($start,$end);
        $noofdays = $days->format('%d');

        $startDate = "'".$startDate."'";
        $endDate = "'".$endDate."'";
  
        $paperType=validateFormData($_POST['paperType']);
        $paperType = "'".$paperType."'";
   
        $paperLevel=validateFormData($_POST['paperLevel']);
        $paperLevel = "'".$paperLevel."'";
   
        $paperCategory=validateFormData($_POST['paperCategory']);
        $paperCategory = "'".$paperCategory."'";
  
        $organized=validateFormData($_POST['organized']);
        $organized = "'".$organized."'";
 
        $conf=validateFormData($_POST['conf']);
        $conf = "'".$conf."'";
   
    //following are not required so we can directly take them as it is
    
    $details=validateFormData($_POST["details"]);
    $details = "'".$details."'";
	
	
	        $volume=validateFormData($_POST["volume"]);
        $volume = "'".$volume."'";
		
	
        if(isset($_POST['applicable']))
        {
            // console.log($_POST['applicable']);
            if($_POST['applicable'] == 2)
            {
                $mail_letter_path='NULL';
                $success=1;
                         
            }
            else if($_POST['applicable'] == 3)
            {
                $mail_letter_path='not_applicable';
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
                    $targetName=$datapath."mails_letters/".$_SESSION['F_NAME']."_mail_letters_".date("d-m-Y H-i-s", time()).".".$fileExt;  
                          
                    if(empty($errors)==true) 
                    {
                        if (file_exists($targetName)) 
                        {   
                            unlink($targetName);
                        }      
                        $moved = move_uploaded_file($fileTmp,"$targetName");
                        if($moved == true){
                            $mail_letter_path=$targetName;
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
                        else{
                            echo "<h1> $targetName </h1>";
                        }
                    }else{
                        print_r($errors);
                    }
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
                if(isset($_FILES['report']))
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
if($flag == 1)
{
	$sql = "UPDATE paper_review SET Paper_title = $paperTitle,
                               Paper_type = $paperType,
							   Paper_N_I = $paperLevel,
   							   conf_journal_name = $conf,
                               last_added = CURRENT_TIMESTAMP,
							   paper_category = $paperCategory,
							   Date_from = $startDate,
							   Date_to = $endDate, 
							   organised_by = $organized,
							   details =$details,
                               volume = $volume,
                               mail_letter_path = '".$mail_letter_path."',
							   certificate_path ='".$certipath."',
							   report_path = '".$reportpath."',
                               noofdays = $noofdays
							   WHERE paper_review_ID ='".$_SESSION['id']."'";

			if ($conn->query($sql) === TRUE) 
			{
				$success = 1;	
            } 
            else 
            {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
			if($success ==1 )
			{
                if($_SESSION['type'] == 'hod')
                {
                    header("location:2_dashboard_hod_review.php?alert=update");
                }
                else
                {
                    header("location:2_dashboard_review.php?alert=update");
                }
			}else{
                if($_SESSION['type'] == 'hod')
                {
                    header("location:2_dashboard_hod_review.php?alert=error");
                }
                else
                {
                    header("location:2_dashboard_review.php?alert=error");
                }
            }
    }
}

//close the connection
mysqli_close($conn);
?>





<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php include_once("includes/scripting.php"); ?>

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
					<h3 class="box-title"><b>Technical Paper Reviewed Edit Form</b></h3>
					<br>
				</div>
                </div><!-- /.box-header -->
				<div style="text-align:left">
			<!--	<p style="color:#428bca;"><b>&nbsp;&nbsp;&nbsp;<u>Last Edit was made on <?php //echo $Udate?></u></b></u></p>  -->
				</div>
				<div style="text-align:right">
			<!--		<a href="menu.php?menu=2 "> <u>Back to Technical Papers Reviewed Menu</u></a> -->
				</div>
                
                <!-- form start -->
                <form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data">
 <?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
					//echo '<script type="text/javascript">alert("INFO:  '.$nameError.'");</script>';				
				}	
			?>		
<?php 
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['conf']))
$conf = str_replace($replace_str, "", $conf);


$replace_str = array('"', "'",'' ,'');
if(isset($_POST['details']))
	$details = str_replace($replace_str, "", $details);


$replace_str = array('"', "'",'' ,'');
$volume = str_replace($replace_str, "", $volume);
?>							
				<div class="form-group col-md-6">

                <label for="faculty-name">Faculty Name</label>
                <input type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                </div>
                    <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
                     <div class="form-group col-md-6">
                         <label for="paper-title">Title *</label>
                          <input required type="text" type="text" class="form-control input-lg" id="paper-title" name="paperTitle" value="<?php echo "$paperTitle"; ?>">
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-type">Paper Type *</label>
                         <select required class="form-control input-lg" id="paper-type" name="paperType">
                             <option <?php if($paperType_db == "conference") echo "selected = 'selected'" ?>  value = "conference">Conference</option>
                             <option <?php if($paperType_db == "journal") echo "selected = 'selected'" ?> value = "journal">Journal</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-level">Paper Level *</label>
                         <select required class="form-control input-lg" id="paper-level" name="paperLevel">
                             <option <?php if($paperLevel_db == "national") echo "selected = 'selected'" ?> value = "national">National</option>
                             <option  <?php if($paperLevel_db == "international") echo "selected = 'selected'" ?> value = "international">International</option>
                         </select>
                     </div>
					  <div class="form-group col-md-6">
                         <label for="conf">Conference/Journal Name </label>
                         <input type="text"  class="form-control input-lg" id="conf" name="conf" rows="2" value="<?php echo $conf; ?>">
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-category">Paper Category *</label>
                         <select required class="form-control input-lg" id="paper-category" name="paperCategory">
                             <option  <?php if($paperCategory_db == "peer reviewed") echo "selected = 'selected'" ?> value = "peer reviewed">Peer Reviewed</option>
                             <option <?php if($paperCategory_db == "non peer reviewed") echo "selected = 'selected'" ?> value = "non peer reviewed">Non Peer Reviewed</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input 
                             <?php echo "value = $startDate"; ?>
                           required type="date" class="form-control input-lg" id="start-date" name="startDate"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input
                             <?php echo "value = $endDate"; ?>
                           required type="date" class="form-control input-lg" id="end-date" name="endDate"
                         placeholder="03:10:10">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="organized">Organized by*</label>
                         <input
              
                           required type="text" class="form-control input-lg" id="organized" name="organized" value='<?php echo $organized ?>'>
                     </div>

                     <div class="form-group col-md-6">
                         <label for="details">Details of Program/Your Role *</label>
                         <input type="text" required class="form-control input-lg" id="details" name="details" rows="2" value="<?php echo $details; ?>">                     
                    </div>
					 
					  <div class="form-group col-md-6">
                         <label for="volume">Volume/Issue/ISSN </label>
                         <input type="text" class="form-control input-lg" id="volume" name="volume" rows="2" value="<?php echo $volume; ?>">                     
                    </div>
                    
                         <div class="form-group col-md-6 col-md-offset-1"></div>
     <div class="form-group col-md-6">				
					 <div >
                         <label for="Index">Mail/Letter : </label><br/>
						  <input type="radio" id="r1" name="applicable" value="1" class="non-vac" <?php  echo($mail_letter_path!=NULL)?'checked':'' ?>>Yes
						  <br>
						<input type="radio" name="applicable" value="2" class="vac" <?php echo ($mail_letter_path=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
						<input type="radio" name="applicable" value="3" class="vac" <?php echo ($mail_letter_path=='not_applicable')?'checked':'' ?>> No
					</div> 
                    <br>
					<div class='second-reveal' id='f1'>
						 <div >
							 
                    	     <label for="card-image">Mail/Letter </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="paper">
		        	          <a <?php 
		        	          $f=0;
		        	          if($mail_letter_path!="not_applicable" && $mail_letter_path!="NULL" && $mail_letter_path!='no status' && $mail_letter_path!=""){
		        	          	echo "href='$mail_letter_path'";
		        	          	$f=1;
		        	          }else{
		        	          	echo "";
		        	          }
		        	          ?> target="_blank"><h4><?php if($f==1){echo "View Existing paper";} ?><h4></a>
	        	        </div> 
					</div>


					<div >
                         <label for="Index">Certificate :  </label><br/>
						  <input type="radio" name="applicable1" id="r2" value="1" class="non-vac1" <?php  echo($certipath!=NULL)?'checked':'' ?>>Yes<br>
							<input type="radio" name="applicable1" value="2" class="vac1" <?php echo ($certipath=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
						<input type="radio" name="applicable1" value="3" class="vac1" <?php echo ($certipath=='not_applicable')?'checked':'' ?>> No
						 
					</div> 
                    <br>
					<div class='second-reveal1'id='f2'>
						 <div >
							 
                    	     <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="certificate">
		        	         <a <?php 
		        	          $f1=0;
		        	          if($certipath!="not_applicable" && $certipath!="NULL" && $certipath!='no status' && $certipath!=""){
		        	          	echo "href='$certipath'";
		        	          	$f1=1;
		        	          }else{
		        	          	echo "";
		        	          }
		        	          ?> target="_blank"><h4><?php if($f1==1){echo "View Existing Certificate";} ?><h4></a>
	        	        </div> 
					</div>
					

					<div>
                         <label for="Index">Report : </label><br/>
						  <input type="radio" name="applicable2" id="r3" value="1" class="non-vac2" <?php  echo($reportpath!=NULL)?'checked':'' ?>>Yes<br>
							<input type="radio" name="applicable2" value="2" class="vac2" <?php echo ($reportpath=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
						<input type="radio" name="applicable2" value="3" class="vac2" <?php echo ($reportpath=='not_applicable')?'checked':'' ?>> No
					</div> 
					<br>
					<div class='second-reveal2' id='f3'>
						 <div >
							 
                    	     <label for="card-image">Report </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="report">
		        	         <a <?php 
		        	          $f2=0;
		        	          if($reportpath!="not_applicable" && $reportpath!="NULL" && $reportpath!='no status' && $reportpath!=""){
		        	          	echo "href='$reportpath'";
		        	          	$f2=1;
		        	          }else{
		        	          	echo "";
		        	          }
		        	          ?> target="_blank"><h4><?php if($f2==1){echo "View Existing Report";} ?><h4></a>
	        	        </div> 
					</div>
     </div>
					

                    <div class="form-group col-md-12">
                    <?php
						if($_SESSION['type'] == 'hod')
						{
						   echo '<a href="2_dashboard_hod_review.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
						}
						else
						{
							echo '<a href="2_dashboard_review.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
						}
                        ?>
                         

                         <button name="update"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>

                    </div>
                    <script>
					 
					 window.onload = function() {
						mycheck1();
						mycheck2();
						mycheck3();
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
					function mycheck3(){
						var radio3 = document.getElementById("r3");
						var file3 = document.getElementById("f3");
						if(radio3.checked==true){
							file3.style.display = "block";
						}else{
							file3.style.display= "none";
						}
					}
					 
					 </script>


                </form>
                
                </div>
              </div>
           </div>      
        </section>
</div>    
<?php include_once('footer.php'); ?>