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

$_SESSION['currentTab']="technical_review";

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");
include_once("includes/config.php");

$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}

//setting error variables
$nameError="";
$emailError="";
$paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coauthors = $volume =$paperpath=$certipath=$reportpath= "";
$flag= 1 ;
$success = 0;
$s = 1;
$p_id = 0;
$error1 = $error2 = $error3 = "";
	
        $faculty_name= $_SESSION['loggedInUser'];


//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    //the form was submitted
    $fname_array = $_POST['fname'];

	$paperTitle_array = $_POST['paperTitle'];
	$paperType_array = $_POST['paperType'];
	$paperLevel_array = $_POST['paperLevel'];
	$conf_array = $_POST['conf'];

	$paperCategory_array = $_POST['paperCategory'];

	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$organized_array = $_POST['organized'];
	$details_array = $_POST['details'];
	$volume_array = $_POST['volume'];

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
$organized = mysqli_real_escape_string($conn,$organized_array[$i]);
$details = mysqli_real_escape_string($conn,$details_array[$i]);
$volume = mysqli_real_escape_string($conn,$volume_array[$i]);

$time=time();
$start = new DateTime(date($startDate,$time));
$end = new DateTime(date($endDate,$time));
$days = date_diff($start,$end);
$noofdays = $days->format('%d');
 
        $paperTitle=validateFormData($paperTitle);
        $paperTitle = "'".$paperTitle."'";
   
	
        $paperType=validateFormData($paperType);
        $paperType = "'".$paperType."'";
  
   
        $paperLevel=validateFormData($paperLevel);
        $paperLevel = "'".$paperLevel."'";
   
   
        $paperCategory=validateFormData($paperCategory);
        $paperCategory = "'".$paperCategory."'";
   
        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
	
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
   
	
		if ($startDate > $endDate)
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}
	
        $organized=validateFormData($organized);
        $organized = "'".$organized."'";
    
	
        $conf=validateFormData($conf);
        $conf = "'".$conf."'";
    	 
	  //following are not required so we can directly take them as it is

	 $details=validateFormData($details);
    $details = "'".$details."'";
	
	$fname=validateFormData($fname);
    $fname = "'".$fname."'";
	
	        $volume=validateFormData($volume);
        $volume = "'".$volume."'";
	
			
	  //checking if there was an error or not
        $query = "SELECT Fac_ID from facultydetails where F_NAME= $fname";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
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
					  $targetName=$datapath."mails_letters/".$_SESSION['F_NAME']."_mail_letters_".date("d-m-Y H-i-s", time()).".".$fileExt;  
					  
					  if(empty($errors)==true) {
						if (file_exists($targetName)) {   
							unlink($targetName);
						}      
						 $moved = move_uploaded_file($fileTmp,"$targetName");
						 if($moved == true){
						 	$paperpath=$targetName;
						 	$success=1;
						 }
						// else{
							 //not successful
							 //header("location:error.php");
						//	 			 echo "<h1> $targetName </h1>";
						// }
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
					//	 else{
					//		 	echo "<h1> $targetName </h1>";
					//	 }
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

			if($volume=="''"){
				$volume="'NA'";
			}
			
		if($flag==1 && $s != 0)
	    {
        $sql="INSERT INTO paper_review(Fac_ID,Paper_title,Paper_type,Paper_N_I,conf_journal_name,paper_category,Date_from,Date_to, organised_by,details,volume,mail_letter_path,certificate_path,report_path,noofdays) VALUES ($author,$paperTitle,$paperType,$paperLevel,$conf,$paperCategory,$startDate,$endDate,$organized,$details,$volume,'".$paperpath."','".$certipath."','".$reportpath."',$noofdays)";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
				header("location:2_dashboard_hod_review.php?alert=success");
			} else if($s != 0){
				header("location:2_dashboard_hod_review.php?alert=error");
			}
		}
						
		
 
}//end of for
			

			        
}

}


//close the connection
mysqli_close($conn);
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
					<h3 class="box-title"><b>Technical Paper Reviewed Form</b></h3>
					<br>
					</div>
				 
                </div><!-- /.box-header -->
                <!-- form start -->
	
				
	<?php
			
					for($k=0; $k<1; $k++)
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
if(isset($_POST['conf']))
$conf = str_replace($replace_str, "", $conf);
else
	$conf  = '';

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['details']))
	$details = str_replace($replace_str, "", $details);
else
	$details  = '';

$replace_str = array('"', "'",'' ,'');
$volume = str_replace($replace_str, "", $volume);
?>						 										
			
					 <div class="form-group col-md-6">
                    <label for="fname">Faculty *</label>

					<?php
					include("includes/connection.php");

					$query="SELECT * from facultydetails WHERE Dept='".$_SESSION['Dept']."' AND type='faculty' ORDER BY F_NAME ";
					$result=mysqli_query($conn,$query);
					echo "<select name='fname[]' id='fname' class='form-control input-lg'>";
					while ($row =mysqli_fetch_assoc($result)) {
						echo "<option value='" . $row['F_NAME'] ."'>" . $row['F_NAME'] ."</option>";
					}
					echo "</select>";
					?>
					</div>
				
                     <div class="form-group col-md-6">
                         <label for="paper-title">Title </label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input <?php if(isset($_POST['paperTitle'])) echo "value = $paperTitle"; ?> type="text" class="form-control input-lg"  name="paperTitle[]" >
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-type">Paper Type *</label>
                        <select required class="form-control input-lg" id="paper-type" name="paperType[]">
                             <option <?php if(isset($_POST['paperType'])) if($paperType == 'conference') echo "selected = 'selected'" ?> value = "conference">Conference</option>
                             <option <?php if(isset($_POST['paperType'])) if($paperType == 'journal') echo "selected = 'selected'" ?> value = "journal">Journal</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-level">Paper Level *</label>
                         <select required class="form-control input-lg" id="paper-level" name="paperLevel[]">
                             <option <?php if(isset($_POST['paperLevel'])) if($paperLevel == "national") echo "selected = 'selected'" ?> value = "national">National</option>
                             <option <?php if(isset($_POST['paperLevel'])) if($paperLevel == "international") echo "selected = 'selected'" ?> value = "international">International</option>
                         </select>
                     </div>
					 <div class="form-group col-md-6">
                         <label for="conf">Conference/Journal Name *</label>
						<input type="text" class="form-control input-lg" id="conf" name="conf[]" >      
						 </div>
                     <div class="form-group col-md-6">
                         <label for="paper-category">Paper Category *</label>
                         <select required class="form-control input-lg" id="paper-category" name="paperCategory[]">
                             <option <?php if($paperCategory == "peer reviewed") echo "selected = 'selected'" ?> value = "peer reviewed">Peer Reviewed</option>
                             <option <?php if($paperCategory == "non peer reviewed") echo "selected = 'selected'" ?> value = "non peer reviewed">Non Peer Reviewed</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input <?php if(isset($_POST['startDate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate[]"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input <?php if(isset($_POST['endDate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate[]"
                         placeholder="03:10:10">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="location">Organized by *</label>
                         <input <?php if(isset($_POST['organized'])) echo "value = $organized"; ?> required type="text" class="form-control input-lg" id="location" name="organized[]">
                     </div>

                     <div class="form-group col-md-6">
                         <label for="details">Details of Program/Your Role * </label>
						<input type="text"  required class="form-control input-lg" id="details" name="details[]" >                
						 </div>
                       <div class="form-group col-md-6">
                         <label for="volume">Volume/Issue/ISSN </label>
                         <input type="text" class="form-control input-lg" id="volume" name="volume[]" >
                     </div>		
					 
					 <div class="form-group col-md-6 col-md-offset-1"></div>
					 
	<div class="form-group col-md-6 ">		 
                     
					 <div>
						&nbsp;<label for="course">Upload Mail/Letter : Applicable ?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "border : none;"> <?php echo $error3 ?> </span>
						<br>&nbsp;<input required type='radio' name='applicable' class='non-vac' value='1' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'checked':'' ?>> Yes <br>
						&nbsp;<input type='radio' name='applicable' class='vac' value='2' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="2")?'checked':'' ?>> Applicable, but not yet available <br>		 
						&nbsp;<input type='radio' name='applicable' class='vac' value='3' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="3")?'checked':'' ?>> No <br>
					</div>
					<br>
					<div class='second-reveal' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'style = "display : block" ':'' ?>>
						 <div >
							 
                    	     <label for="card-image"> Mail/Letter </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="paper">
	        	        </div> 
					</div>
                     <div >

						&nbsp;<label for="course">Upload certificate : Applicable ?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "border : none;"> <?php echo $error1 ?> </span>
						<br>	&nbsp;<input required type='radio' name='applicable1' class='non-vac1' value='1' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'checked':'' ?>> Yes <br>
						&nbsp;<input type='radio' name='applicable1' class='vac1' value='2' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="2")?'checked':'' ?>> Applicable, but not yet available <br>
										 
						&nbsp;<input type='radio' name='applicable1' class='vac1' value='3'<?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="3")?'checked':'' ?> > No <br>
					</div>
					<br>
					<div class='second-reveal1' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'style = "display : block" ':'' ?>>
						 <div > 
                    	     <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="certificate">
	        	        </div> 
					</div>
                     <div >

						&nbsp;<label for="course">Upload report : Applicable ?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "border : none;"> <?php echo $error2 ?> </span>
						<br>	&nbsp;<input required type='radio' name='applicable2' class='non-vac2' value='1'  <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="1")?'checked':'' ?>> Yes <br>
						&nbsp;<input type='radio' name='applicable2' class='vac2' value='2' <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="2")?'checked':'' ?> > Applicable, but not yet available <br>
										 
						&nbsp;<input type='radio' name='applicable2' class='vac2' value='3' <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="3")?'checked':'' ?> > No <br>
					</div>
					<br>
					<div class='second-reveal2'<?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="1")?'style = "display : block" ':'' ?>>
						 <div>
							 
                    	     <label for="card-image">Report </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="report">
	        	        </div> 
					</div>
	</div>			
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                         <a href="list_of_activities_user.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

                         <button name="add"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                    </div>
                </form>
                </div>
				
				
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   