    
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
  $faculty_name = $row['F_NAME'];
	$deptName = $row['Dept'];
}

$_SESSION['currentTab']="books";

//setting error variables
$nameError="";
$emailError="";
$course = $startDate = $endDate = $organised = $purpose = "";
$flag= 1;
$success = 0;
$s = 1;
$p_id = 0;
$error1 = $error2 = "";
$faculty_name= $_SESSION['loggedInUser'];

//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    //the form was submitted
    $fname_array = $_POST['fname'];
	$course_array = $_POST['course'];
	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$organised_array = $_POST['organised'];
    $purpose_array = $_POST['purpose'];
	$applicablefdc_array = $_POST['applicablefdc'];
	
	$fdc_array = $_POST['applicablefdc'];
    $type_of_course_array = $_POST['type'];
    $status_of_activity_array = $_POST['status'];
    $duration_array = $_POST['duration'];
    $credit_audit_array = $_POST['creau'];

	/*	$min_no_array=$_POST['min_no'];
		$serial_no_array=$_POST['serial_no'];
				$period_array = $_POST['period'];

		$od_approv_array=$_POST['od_approv'];
		$od_avail_array=$_POST['od_avail'];
		$fee_sac_array=$_POST['fee_sac'];
		$fee_avail_array=$_POST['fee_avail'];*/
	
	
    //check for any blank input which are required
    		
for($i=0; $i<1;$i++)
{
    $fname = mysqli_real_escape_string($conn,$fname_array[$i]);
$course = mysqli_real_escape_string($conn,$course_array[$i]);

$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$organised = mysqli_real_escape_string($conn,$organised_array[$i]);
$purpose = mysqli_real_escape_string($conn,$purpose_array[$i]);
$type_of_course = mysqli_real_escape_string($conn,$type_of_course_array[$i]);
$status_of_activity = mysqli_real_escape_string($conn,$status_of_activity_array[$i]);
$duration = mysqli_real_escape_string($conn,$duration_array[$i]);
$credit_audit = mysqli_real_escape_string($conn,$credit_audit_array[$i]);
$applicablefdc = mysqli_real_escape_string($conn,$applicablefdc_array[$i]);

$time=time();
$start = new DateTime(date($startDate,$time));
$end = new DateTime(date($endDate,$time));
$days = date_diff($start,$end);
$noofdays = $days->format('%d');

$fdc = mysqli_real_escape_string($conn,$fdc_array[$i]);
$_SESSION['fdc'] = $fdc;
$_SESSION['F_NAME'] = $fname;
 
    $course=validateFormData($course);
        $course = "'".$course."'";
		
        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
		
   
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
		
	
		if ($startDate > $endDate)		
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}
		
	if($purpose!=""){
       $purpose=validateFormData($purpose);
        $purpose = "$purpose";
  }else{
    $purpose='NA';
  }
		
		$type_of_course=validateFormData($type_of_course);
        $type_of_course = "'".$type_of_course."'";		
   
        $status_of_activity=validateFormData($status_of_activity);
        $status_of_activity = "'".$status_of_activity."'";
    
        $organised=validateFormData($organised);
        $organised = "'".$organised."'";
        
		$duration=validateFormData($duration);
        $duration = "'".$duration."'";
		
		$credit_audit=validateFormData($credit_audit);
        $credit_audit = "'".$credit_audit."'";
		
		

  
        if($applicablefdc == 'Yes')
        {
          $fdc='Yes';
          $fdc = "'".$fdc."'";		}
        else if($applicablefdc == 'No')
        {
          $fdc = 'Not applicable';
          $fdc = "'".$fdc."'";				
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
          $fileExt=strtolower(end($temp));            date_default_timezone_set('Asia/Kolkata');
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
         //  else{
           //        echo "<h1> $targetName </h1>";
          // }
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
         //  else{
           //        echo "<h1> $targetName </h1>";
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
		
	
	  //checking if there was an error or not
       $query = "SELECT Fac_ID from facultydetails where F_NAME= '$fname'";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
       }
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['purpose']) && $_POST['purpose']!="")
  $purpose = str_replace($replace_str, "", $purpose);
else
	$purpose  = 'NA';

	   if($flag!=0 && $s != 0)
	   {
        $sql="INSERT INTO online_course_attended(Fac_ID,Course_Name, Date_from, Date_to,Organised_by, Purpose, FDC_Y_N,type_of_course,status_of_activity,duration,credit_audit,certificate_path,report_path,noofdays) VALUES ('$author',$course,$startDate,$endDate,$organised,'$purpose',$fdc,$type_of_course,$status_of_activity,$duration,$credit_audit,'".$certipath."','".$reportpath."',$noofdays)";
			if ($conn->query($sql) === TRUE) {
        $success = 1;
        header("location:2_dashboard_hod_online_attended.php?alert=success");
			} else if($s != 0) {
				header("location:2_dashboard_hod_online_attended.php?alert=error");
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
					<h3 class="box-title"><b>Books/Chapter Published Form</b></h3>
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
                        <label for="department_name">Department Name</label>
                        <input required type="text" class="form-control input-lg" id="department_name" name="department_name[]" value="<?php echo strtoupper($_SESSION['Dept']); ?>" readonly>
                    </div>
			
					<div class="form-group col-md-6">
                         <label for="faculty-name">Faculty Name</label>
                         <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName[]" value="<?php echo $faculty_name; ?>" readonly>
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

                    <div class="form-group col-md-3">
                        <label for="fn_co-author">Co-Author (First Name)</label>
						<span class="colour"><b> *</b></span>
                        <input   class="form-control input-lg" type="text" name="first_name[]" id="fn_co-author" placeholder="First Name" value="">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="ln_co-author">Co-Author (Last Name)</label>
						<span class="colour"><b> *</b></span>
                        <input   class="form-control input-lg" type="text" name="last_name[]" id="ln_co-author" placeholder="Last Name" value="">
                    </div>

					<div class="form-group col-md-6">
                        <label for="book_type">Book Type</label>
						<span class="colour"><b> *</b></span>
                        <select required name="book_type" id="book_type" class="form-control input-lg" >
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
                         <input  <?php if(isset($_POST['url'])) echo "value = $url"; ?> required type="url" class="form-control input-lg" id="location" name="url[]">
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
  html += '<td><select name="co_name[]" class="form-control item_unit" id="search"><option value="">Select Co-author</option><?php echo fill_unit_select_box($connect); ?></select></td>';
  html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
  $('#c_name').append(html);
 });
 
 $(document).on('click', '.remove', function(){
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
 </script>    -->
    
    
<?php include_once('footer.php'); ?>