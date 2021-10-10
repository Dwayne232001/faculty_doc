<?php
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
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

$_SESSION['currentTab'] = "organised_guest";
//setting error variables
$emailError="";
$flag = 1;
$nameError=$attendancepath=$certipath=$paperpath=$reportpath="";
$Fac_ID=0;

if(isset($_POST['rid'])){
    $id = $_POST['rid'];
    $_SESSION['id']=$_POST['rid'];
}
    $id = $_SESSION['id'];
    $query = "SELECT * from guestlec where p_id = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['fac_id'];
    $topic = $row['topic'];
    $startDate = $row['durationf'];
    $endDate = $row['durationt'];
    $name = $row['name'];
	$designation= $row['designation'];
	$organisation= $row['organisation'];
	$targetaudience = $row['targetaudience'];
    $paperpath=$row['permission_path'];
    $certipath=$row['certificate1_path'];
    $reportpath=$row['report_path'];
    $attendancepath=$row['attendance_path'];		
			$query2 = "SELECT * from facultydetails where Fac_ID = $Fac_ID";
			$result2 = mysqli_query($conn,$query2);
			if($result2)
			{
	            $row = mysqli_fetch_assoc($result2);
				$F_NAME = $row['F_NAME'];
            }
            $_SESSION['F_NAME'] = $F_NAME ;
            
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if(isset($_POST['update'])){
    //check for any blank input which are required
 
        $topic1=validateFormData($_POST['topic']);
        $topic1 = "'".$topic1."'";
    
		// if ((strtotime($_POST['startDate'])) > (strtotime($_POST['endDate'])))
		// {
		// 	$nameError=$nameError."Start Date cannot be greater than end date<br>";
		// 	$flag = 0;
		// }	
        $startDate1=validateFormData($_POST['startDate']);
        $endDate1=validateFormData($_POST['endDate']);

        $time=time();
        $start = new DateTime(date($startDate1,$time));
        $end = new DateTime(date($endDate1,$time));
        $days = date_diff($start,$end);
        $noofdays = $days->format('%d');

        $startDate1 = "'".$startDate1."'";
        $endDate1 = "'".$endDate1."'";
   
        $name1=validateFormData($_POST['name']);
        $name1 = "'".$name1."'";
    
        $designation1=validateFormData($_POST['designation']);
        $designation1 = "'".$designation1."'";
  
        $organisation1=validateFormData($_POST['organisation']);
        $organisation1 = "'".$organisation1."'";
    
        $targetaudience1=validateFormData($_POST['targetaudience']);
        $targetaudience1 = "'".$targetaudience1."'";

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
                $targetName=$datapath."permissions/".$_SESSION['F_NAME']."_permissions_".date("d-m-Y H-i-s", time()).".".$fileExt;  
                      
                if(empty($errors)==true) 
                {
                    if (file_exists($targetName)) 
                    {   
                        unlink($targetName);
                    }      
                    $moved = move_uploaded_file($fileTmp,"$targetName");
                    if($moved == true){
                        $paperpath=$targetName;
                        $success=1;
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
                        // echo "<h1> $targetName </h1>";
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
                echo "hello";
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
                        // echo "<h1> $targetName </h1>";
                    }
                }else{
                    print_r($errors);
                }
            }
        }
    }
    if(isset($_POST['applicable3']))
    {
        if($_POST['applicable3'] == 2)
        {
            $attendancepath='NULL';
            $success=1;                      
        }
        else if($_POST['applicable3'] == 3)
        {
            $attendancepath='not_applicable';
            $success=1;
        }
        else if($_POST['applicable3'] == 1)
        {
            if(isset($_FILES['attendance']))
            {
                $errors= array();
                $fileName = $_FILES['attendance']['name'];
                $fileSize = $_FILES['attendance']['size'];
                $fileTmp = $_FILES['attendance']['tmp_name'];
                $fileType = $_FILES['attendance']['type'];
                $temp=explode('.',$fileName);
                $fileExt=strtolower(end($temp));
                date_default_timezone_set('Asia/Kolkata');
                $targetName=$datapath."attendance/".$_SESSION['F_NAME']."_attendance_".date("d-m-Y H-i-s", time()).".".$fileExt;  
                if(empty($errors)==true) {
                    if (file_exists($targetName)) {   
                        unlink($targetName);
                    }      
                    $moved = move_uploaded_file($fileTmp,"$targetName");
                    if($moved == true){
                        $attendancepath=$targetName;
                        $success=1;     
                    }
                    else{
                        // echo "<h1> $targetName </h1>";
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
				
	if($flag!=0)
	{		

	$sql = "update guestlec set topic = $topic1 ,targetaudience=$targetaudience1,organisation=$organisation1,designation=$designation1,name=$name1,durationf=$startDate1,durationt=$endDate1,permission_path='".$paperpath."',certificate1_path='".$certipath."',report_path='".$reportpath."',attendance_path='".$attendancepath."',noofdays = $noofdays WHERE p_id = '".$_SESSION['id']."' ";
							

			if ($conn->query($sql) === TRUE) 
			{
                if($_SESSION['type'] == 'hod')
                {
                    header("location:view_organised_hod_lec.php?alert=update");
                }
                else
                {
                    header("location:view_organised_lec.php?alert=update");
                }
			}									
			else {
				if($_SESSION['type'] == 'hod')
                {
                    header("location:view_organised_hod_lec.php?alert=error");
                }
                else
                {
                    header("location:view_organised_lec.php?alert=error");
                }
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
                <br>
                <br>
                <br>
              <!-- general form elements -->
              <div class="box box-primary">

                <div class="box-header with-border">
                <div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Guest Lecture Organised Edit Form</b></h3>
					<br>
				</div>
				
				</div><!-- /.box-header -->
				<!-- form start -->
                <form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data">
				
				<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
					//echo '<script type="text/javascript">alert("INFO:  '.$nameError.'");</script>';				
				}	
			?>		
                    <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
                    <div class="form-group col-md-6">
                         <label for="faculty-name">Faculty Name</label>
                         <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                     </div>
					
                     <div class="form-group col-md-6">
                         <label for="paper-title">TOPIC *</label>
                         <input required type="text" class="form-control input-lg" id="topic" name="topic" value='<?php echo $topic ?>' >
                     </div>
                     
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label> <?php $value = date("Y-m-d\TH:i:s", strtotime($startDate));  ?>
                         <input type="datetime-local" required class="form-control input-lg" id="start-date" name="startDate" placeholder="" value="<?php echo $value; ?>" >
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label><?php  $value = date("Y-m-d\TH:i:s", strtotime($endDate)); ?>
                         <input type="datetime-local" required class="form-control input-lg" id="end-date" name="endDate" placeholder="" value="<?php echo $value; ?>">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="location">Resource Person Name *</label>
                         <input required type="text" class="form-control input-lg" id="name" name="name" value='<?php echo $name ?>'>
                     </div>

                  <div class="form-group col-md-6">
                         <label for="paper-title">Designation *</label>
                         <input required type="text" class="form-control input-lg" id="designation" name="designation" value='<?php echo $designation ?>' >
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-title">Organisation *</label>
                         <input   required type="text" class="form-control input-lg" id="organisation" name="organisation"  value='<?php echo $organisation ?>'>
                     </div>
                  <div class="form-group col-md-6">
                         <label for="paper-title">Target Audience</label>
                         <input type="text" class="form-control input-lg" id="targetaudience" name="targetaudience" value='<?php echo $targetaudience ?>' >
                     </div>

<div class="form-group col-md-6">   
                     <div>
                         <label for="Index">Permission Letter : </label><br/>
                          <input type="radio" id="r1" name="applicable" value="1" class="non-vac" <?php  echo($paperpath!=NULL)?'checked':'' ?>>Yes
                          <br>
                        <input type="radio" name="applicable" value="2" class="vac" <?php echo ($paperpath=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
                        <input type="radio" name="applicable" value="3" class="vac" <?php echo ($paperpath=='not_applicable')?'checked':'' ?>> No
                    </div> 
                    <br>
                    <div class='second-reveal' id='f1'>
                         <div >
                             
                             <label for="card-image">Permission Letter </label><span class="colour"><b> *</b></span>
                             <input  type="file"   class="form-control input-lg" id="card-image" name="paper">
                              <a <?php 
                              $f=0;
                              if($paperpath!="not_applicable" && $paperpath!="NULL" && $paperpath!='no status' && $paperpath!=""){
                                echo "href='$paperpath'";
                                $f=1;
                              }else{
                                echo "style='display:none'";
                              }
                              ?> target="_blank"><h4><?php if($f==1){echo "View Existing permission letter";} ?><h4></a>
                        </div> 
                    </div>

                    <div>
                         <label for="Index">Certificate :  </label><br/>
                          <input type="radio" name="applicable1" id="r2" value="1" class="non-vac1" <?php  echo($certipath!=NULL)?'checked':'' ?>>Yes<br>
                            <input type="radio" name="applicable1" value="2" class="vac1" <?php echo ($certipath=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
                        <input type="radio" name="applicable1" value="3" class="vac1" <?php echo ($certipath=='not_applicable')?'checked':'' ?>> No          
                    </div> 
                    <br>
                    <div class='second-reveal1'id='f2'>
                         <div>
                             <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
                             <input  type="file" class="form-control input-lg" id="card-image" name="certificate">
                             <a <?php 
                              $f1=0;
                              if($certipath!="not_applicable" && $certipath!="NULL" && $certipath!='no status' && $certipath!=""){
                                echo "href='$certipath'";
                                $f1=1;
                              }else{
                                echo "style='display:none'";
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
                         <div>
                             
                             <label for="card-image">Report </label><span class="colour"><b> *</b></span>
                             <input  type="file"   class="form-control input-lg" id="card-image" name="report">
                             <a <?php 
                              $f2=0;
                              if($reportpath!="not_applicable" && $reportpath!="NULL" && $reportpath!='no status' && $reportpath!=""){
                                echo "href='$reportpath'";
                                $f2=1;
                              }else{
                                echo "style='display:none'";
                              }
                              ?> target="_blank"><h4><?php if($f2==1){echo "View Existing Report";} ?><h4></a>
                        </div> 
                    </div>

                    <div >
                         <label for="Index">Attendance : </label><br/>
                          <input type="radio" name="applicable3" id="r4" value="1" class="non-vac4" <?php  echo($attendancepath!=NULL)?'checked':'' ?>>Yes<br>
                            <input type="radio" name="applicable3" value="2" class="vac4" <?php echo ($attendancepath=='NULL')?'checked':'' ?>>Applicable, but not yet available <br>
                        <input type="radio" name="applicable3" value="3" class="vac4" <?php echo ($attendancepath=='not_applicable')?'checked':'' ?>> No
                    </div> 
                    <br>
                    <div class='second-reveal4' id='f4'>
                         <div>
                             
                             <label for="card-image">Attendance </label><span class="colour"><b> *</b></span>
                             <input  type="file"   class="form-control input-lg" id="card-image" name="attendance">
                             <a <?php 
                              $f2=0;
                              if($attendancepath!="not_applicable" && $attendancepath!="NULL" && $attendancepath!='no status' && $attendancepath!=""){
                                echo "href='$attendancepath'";
                                $f2=1;
                              }else{
                                echo "style='display:none'";
                              }
                              ?> target="_blank"><h4><?php if($f2==1){echo "View Existing Attendance";} ?><h4></a>
                        </div> 
                    </div>
                </div>
                    <script>
                     
                     window.onload = function() {
                        mycheck4();
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
                    function mycheck4(){
                        var radio4 = document.getElementById("r4");
                        var file4 = document.getElementById("f4");
                        if(radio4.checked==true){
                            file4.style.display = "block";
                        }else{
                            file4.style.display= "none";
                        }
                    }

                     </script>  
<br/>
<div class="form-group col-md-12">
                    <?php
						if($_SESSION['type'] == 'hod')
						{
						   echo '<a href="view_organised_hod_lec.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
						}
						else
						{
							echo '<a href="view_organised_lec.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
						}
                    ?> 
                    <button name="update"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                    </div>
                </form>  
                </div>
              </div>
           </div>      
        </section>
</div>
<?php include_once('footer.php'); ?>