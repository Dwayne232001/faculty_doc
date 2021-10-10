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
$_SESSION['currentTab']= "organised_guest";

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");
include_once("includes/config.php");


//setting error variables
$nameError= "";
$topic="";
$durationf=$durationt="";
$invited=$invitation=$invitation2 =$invitation3=$invitation4=$name=$designation=$organisation=$certificate1_path=$permissionpath=$reportpath=$attendancepath="";
$flag= 1;
$success = 0;
$s = 1;
$p_id = 0;
$error1 = $error2 = $error3 = $error4 = "";
$fid = $_SESSION['Fac_ID'];
$faculty_name= $_SESSION['loggedInUser'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
  $_SESSION['F_NAME'] = $row['F_NAME'];
}

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $query = "SELECT * from guestlec where p_id = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['fac_id'];
    $topic = $row['topic'];
    $durationf = $row['durationf'];
    $durationt = $row['durationt'];
    $name = $row['name'];
	$designation= $row['designation'];
	$organisation= $row['organisation'];
	$targetaudience = $row['targetaudience'];
    $permissionpath=$row['permission_path'];
    $certificate1_path=$row['certificate1_path'];
    $reportpath=$row['report_path'];
    $attendancepath=$row['attendance_path'];
}

$query="SELECT * from faculty where Fac_ID = $fid ";
    $result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
		
	}
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    //the form was submitted
    
	$topic_array = $_POST['topic'];
	$durationf_array= $_POST['durationf'];
	$durationt_array = $_POST['durationt'];
	$invited_array = $_POST['invited'];
	$name_array = $_POST['name'];
	$designation_array = $_POST['designation'];
	$organisation_array = $_POST['organisation'];
		


    		
for($i=0; $i<1;$i++)
{
$topic = mysqli_real_escape_string($conn,$topic_array[$i]);
$durationf = mysqli_real_escape_string($conn,$durationf_array[$i]);
$durationt = mysqli_real_escape_string($conn,$durationt_array[$i]);
$invited = mysqli_real_escape_string($conn,$invited_array[$i]);
$name = mysqli_real_escape_string($conn,$name_array[$i]);
$designation = mysqli_real_escape_string($conn,$designation_array[$i]);
$organisation = mysqli_real_escape_string($conn,$organisation_array[$i]);


$time=time();
$start = new DateTime(date($durationf,$time));
$end = new DateTime(date($durationt,$time));
$days = date_diff($start,$end);
$noofdays = $days->format('%d');
 
        $topic=validateFormData($topic);
        $topic = "'".$topic."'";
		if($invited!=""){
            $invited=validateFormData($invited);
            $invited = "$invited";
        }
        else{
            $invited='NA';
        }
		
        $durationf=validateFormData($durationf);
        $durationf = "'".$durationf."'";
		
        $durationt=validateFormData($durationt);
        $durationt = "'".$durationt."'";
		
	  
		if ($durationf > $durationt)		
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}
	
	  //checking if there was an error or not
        $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['name']))
$name = str_replace($replace_str, "", $name);
else
	$name  = '';

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['invited']))
{
	$invited = str_replace($replace_str, "", $invited);
	$invited = str_replace("rn",'', $invited);
}
else
	$invited  = 'NA';

if(isset($_POST['applicable']))
    {
        // console.log($_POST['applicable']);
        if($_POST['applicable'] == 2)
        {
            $attendancepath='NULL';
            $success=1;
                     
        }
        else if($_POST['applicable'] == 3)
        {
            $attendancepath='not_applicable';
            $success=1;
        }
        else if($_POST['applicable'] == 1)
        {
            if(isset($_FILES['attendance']) && $_FILES['attendance']['name'] != NULL && $_FILES['attendance']['name'] !="")
            {
                $errors= array();
                $fileName = $_FILES['attendance']['name'];
                $fileSize = $_FILES['attendance']['size'];
                $fileTmp = $_FILES['attendance']['tmp_name'];
                $fileType = $_FILES['attendance']['type'];
                $temp=explode('.',$fileName);
                $fileExt=strtolower(end($temp));
                date_default_timezone_set('Asia/Kolkata');
                $targetName=$datapath."attendance/".$_SESSION['F_NAME']."_".date("d-m-Y H-i-s", time()).".".$fileExt;  
                      
                if(empty($errors)==true) 
                {
                    if (file_exists($targetName)) 
                    {   
                        unlink($targetName);
                    }      
                    $moved = move_uploaded_file($fileTmp,"$targetName");
                    if($moved == true){
                        $attendancepath=$targetName;
                        $success=1;
                    }
               //     else{
                     //not successful
                        // header("location:error.php");
                //        echo "<h1> $targetName </h1>";
                //    }
                }else{
                    print_r($errors);
                        header("location:else.php");
                }
            }
            else{
                $s = 0;
                $error4 = "No file selected";
            }
        }
    }

    if(isset($_POST['applicable1']))
    {
        if($_POST['applicable1'] == 2)
        {
            $permissionpath='NULL';      
            $success=1;      
        }
        else if($_POST['applicable1'] == 3)
        {
            $permissionpath='not_applicable';
            $success=1;     
        }
        else if($_POST['applicable1'] == 1)
        {
            if(isset($_FILES['permission']) && $_FILES['permission']['name'] != NULL && $_FILES['permission']['name'] !="")
            {
                $errors= array();
                $fileName = $_FILES['permission']['name'];
                $fileSize = $_FILES['permission']['size'];
                $fileTmp = $_FILES['permission']['tmp_name'];
                $fileType = $_FILES['permission']['type'];
                $temp=explode('.',$fileName);
                $fileExt=strtolower(end($temp));
                date_default_timezone_set('Asia/Kolkata');
                $targetName=$datapath."permissions/".$_SESSION['F_NAME']."_".date("d-m-Y H-i-s", time()).".".$fileExt;      
                if(empty($errors)==true) {
                    if (file_exists($targetName)) {   
                        unlink($targetName);
                    }      
                    $moved = move_uploaded_file($fileTmp,"$targetName");
                    if($moved == true){
                        $permissionpath=$targetName;
                        $success=1;     
                    }
                //    else{
                    //    echo "<h1> $targetName </h1>";
                 //   }
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
    if(isset($_POST['applicable3']))
    {
        if($_POST['applicable3'] == 2)
        {
            $reportpath='NULL';
            $success=1;                      
        }
        else if($_POST['applicable3'] == 3)
        {
            $reportpath='not_applicable';
            $success=1;
        }
        else if($_POST['applicable3'] == 1)
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
                $targetName=$datapath."reports/".$_SESSION['F_NAME']."_".date("d-m-Y H-i-s", time()).".".$fileExt;  
                if(empty($errors)==true) {
                    if (file_exists($targetName)) {   
                        unlink($targetName);
                    }      
                    $moved = move_uploaded_file($fileTmp,"$targetName");
                    if($moved == true){
                        $reportpath=$targetName;
                        $success=1;     
                    }
                    //else{
                     //   echo "<h1> $targetName </h1>";
                   // }
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
    if(isset($_POST['applicable2']))
    {
    	echo "Helll Yeah!!!";
        if($_POST['applicable2'] == 2)
        {
            $certificate1_path='NULL';
            $success=1;                      
        }
        else if($_POST['applicable2'] == 3)
        {
            $certificate1_path='not_applicable';
            $success=1;
        }
        else if($_POST['applicable2'] == 1)
        {
            if(isset($_FILES['certificate'])&& $_FILES['certificate']['name'] != NULL && $_FILES['certificate']['name'] !="")
            {
                $errors= array();
                $fileName = $_FILES['certificate']['name'];
                $fileSize = $_FILES['certificate']['size'];
                $fileTmp = $_FILES['certificate']['tmp_name'];
                $fileType = $_FILES['certificate']['type'];
                $temp=explode('.',$fileName);
                $fileExt=strtolower(end($temp));
                date_default_timezone_set('Asia/Kolkata');
                $targetName=$datapath."certificates/".$_SESSION['F_NAME']."_".date("d-m-Y H-i-s", time()).".".$fileExt;  
                if(empty($errors)==true) {
                    if (file_exists($targetName)) {   
                        unlink($targetName);
                    }      
                    $moved = move_uploaded_file($fileTmp,"$targetName");
                    if($moved == true){
                        $certificate1_path=$targetName;
                        $success=1;     
                    }
                   // else{
                   //     echo "<h1> $targetName </h1>";
                   // }
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
  $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
				$succ = 0;
                $success1 = 0;
            if($invited="" || $invited="''"){
                $invited='NA';
            }
	   
	   if($flag!=0 && $s != 0)
	   {
        $sql="INSERT INTO guestlec(fac_id,topic,durationf,durationt,name,designation,organisation,targetaudience,tdate,attendance_path,permission_path,certificate1_path,report_path,noofdays) VALUES ('$author',$topic,$durationf,$durationt,'$name','$designation','$organisation','$invited',CURRENT_TIMESTAMP(),'".$attendancepath."','".$permissionpath."','".$certificate1_path."','".$reportpath."',$noofdays)";

			if ($conn->query($sql) === TRUE) {
                $success = 1;
			    header("location:view_organised_lec.php?alert=success");
			} else if($s != 0){
				header("location:view_organised_lec.php?alert=error");
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
					<h3 class="box-title"><b>Guest Lecture/Seminar Organised Form</b></h3>
					<br>
					</div>
				  
				<!--<br><br>   <h2 class="box-title" align="right"> Last updated on: 
				  -->
				  <?php
				  /*  $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
					
                    $result=mysqli_query($conn,$query);
					
                    if($result){
                    $row = mysqli_fetch_assoc($result);
                    $author = $row['Fac_ID'];
	                }
					
				    $sql= "SELECT tdate from guestlec where fac_id = '".$author."' ORDER BY p_id DESC LIMIT 1;";
					
					$result1= mysqli_query($conn,$sql);
					if ($result1)
					{
						$row1= mysqli_fetch_assoc ($result1);
						$latest= $row1['tdate'];
						echo $latest;
					}
					else
					{
						echo "No entries added yet";
					}
					mysqli_close($conn); */
					?>
				 
                </div><!-- /.box-header -->
                <!-- form start -->
	
	<?php
			
					for($k=1; $k<=1; $k++)
					{

				?>
			<form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data" >
	<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
			?>
	<?php 
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['name']))
$name = str_replace($replace_str, "", $name);
else
	$name  = '';

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['invited']))
{
$invited = str_replace($replace_str, "", $invited);
$invited = str_replace("rn",'', $invited);

}
else
	$invited  = 'NA';

?>						
				<div class="form-group col-md-6">

                    <label for="faculty-name">Faculty Name</label>
                    <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
                </div>
                     <div class="form-group col-md-6">
                         <label for="topic">Topic Of Lecture*</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input  type="text" class="form-control input-lg"  name="topic[]" <?php if($topic != '') echo "value = $topic"; ?>>
					  </div>
                    <div class="form-group col-md-6">
                                <label for="durationf">Duration From *</label>
                         <input required type="datetime-local" class="form-control input-lg" id="durationf" name="durationf[]"
                         <?php if($durationf != '') echo "value = $durationf"; ?>>
                 </div>
				 
				  <div class="form-group col-md-6">
				   <label for="durationt"> Duration To *</label>
                         <input required type="datetime-local" class="form-control input-lg" id="durationt" name="durationt[]"
                         <?php if($durationt != '') echo "value = $durationt"; ?>>
					 
					 </div>
					
				   		<div class="form-group col-md-6">
						
					     <label for="name">Resource Person Name* </label>
                         <input type="text" class="form-control input-lg" id="name" name="name[]" <?php if($name != '') echo "value = $name"; ?>>
                         </div>
                           
		<div class="form-group col-md-6">      
	  <label for="designation">Designation*</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input <?php if(isset($_POST['designation'])) echo "value = $designation"; ?> type="text" class="form-control input-lg"  name="designation[]">
			</div>		
					  
					 <div class="form-group col-md-6">  
					  <label for="organisation">Organisation*</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input <?php if(isset($_POST['organisation'])) echo "value = $organisation"; ?> type="text" class="form-control input-lg"  name="organisation[]">
					
					  </div>
                       
					
					 <div class="form-group col-md-6">
					     <label for="invited">Target Audience </label>
                         <input type="text" class="form-control input-lg" id="invited" name="invited[]" <?php if($invited != '') echo "value = $invited"; ?>>
					</div>

<div class="form-group col-md-6">
					<div >
						&nbsp;<label for="course">Upload Attendance : Applicable ?<br></label><span class="colour"><b> *</b></span>
                        <span class="error" style = "border : none;"> <?php echo $error4 ?> </span>
                    	<br>&nbsp;<input required type='radio' name='applicable' class='non-vac' value='1'  <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'checked':'' ?>	> Yes <br>
						&nbsp;<input type='radio' name='applicable' class='vac' value='2'  <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="2")?'checked':'' ?>	> Applicable, but not yet available <br>
						&nbsp;<input type='radio' name='applicable' class='vac' value='3'  <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="3")?'checked':'' ?>	> No <br>
					</div>
<br>
					<div class='second-reveal' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'style = "display : block" ':'' ?>>
						<div >
                    	    <label for="card-image">Attendance </label><span class="colour"><b> *</b></span>
		        	        <input  type="file"   class="form-control input-lg" id="card-image" name="attendance">
	        	        </div> 
					</div>

                     <div>
						&nbsp;<label for="course"> Upload Permission Record : Applicable ?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "border : none;"> <?php echo $error1 ?> </span>
                        <br>&nbsp;<input required type='radio' name='applicable1' class='non-vac1' value='1'  <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'checked':'' ?>	> Yes <br>
						&nbsp;<input type='radio' name='applicable1' class='vac1' value='2'  <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="2")?'checked':'' ?>	>Applicable, but not yet available <br>
						&nbsp;<input type='radio' name='applicable1' class='vac1' value='3'  <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="3")?'checked':'' ?>	> No <br>
					</div>
<br>
					<div class='second-reveal1' <?php if(isset($_POST['applicable1'])) echo($_POST['applicable1'] =="1")?'style = "display : block" ':'' ?>>
						<div> 
                    	    <label for="card-image">Permission Record </label><span class="colour"><b> *</b></span>
		        	        <input  type="file"   class="form-control input-lg" id="card-image" name="permission">
	        	        </div> 
					</div>

                    <div>
						&nbsp;<label for="course"> Certificate copy : Applicable ?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "border : none;"> <?php echo $error2 ?> </span>
                        <br>&nbsp;<input required type='radio' name='applicable2' class='non-vac2' value='1'  <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="1")?'checked':'' ?>> Yes <br>
						&nbsp;<input type='radio' name='applicable2' class='vac2' value='2'  <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="2")?'checked':'' ?>> Applicable, but not yet available <br>
						&nbsp;<input type='radio' name='applicable2' class='vac2' value='3'  <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="3")?'checked':'' ?>> No <br>
					</div>
<br>
					<div class='second-reveal2' <?php if(isset($_POST['applicable2'])) echo($_POST['applicable2'] =="1")?'style = "display : block" ':'' ?>>
						<div>
                    	    <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
		        	        <input  type="file"   class="form-control input-lg" id="card-image" name="certificate">
	        	        </div> 
					</div>

					<div>
						&nbsp;<label for="course"> Report copy : Applicable ?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "border : none;"> <?php echo $error3 ?> </span>
                        <br>&nbsp;<input required type='radio' name='applicable3' class='non-vac4' value='1'  <?php if(isset($_POST['applicable3'])) echo($_POST['applicable3'] =="1")?'checked':'' ?>	> Yes <br>
						&nbsp;<input type='radio' name='applicable3' class='vac4' value='2'  <?php if(isset($_POST['applicable3'])) echo($_POST['applicable3'] =="2")?'checked':'' ?>	>Applicable, but not yet available <br>
						&nbsp;<input type='radio' name='applicable3' class='vac4' value='3'  <?php if(isset($_POST['applicable3'])) echo($_POST['applicable3'] =="3")?'checked':'' ?>	> No <br>
					</div>
<br>
					<div class='second-reveal4' <?php if(isset($_POST['applicable3'])) echo($_POST['applicable3'] =="1")?'style = "display : block" ':'' ?>>
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
                        

                         <input type="submit" name="add" class="btn btn-success pull-right btn-lg" value = "Submit" />
						 
						<a href="list_of_activities_user.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>