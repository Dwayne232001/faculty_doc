<?php
ob_start();
session_start();

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}

 include_once('head.php'); 
  include_once('header.php'); 

  
 //connect ot database
 include_once("includes/connection.php");

 //check if user has logged in or not

 if(!isset($_SESSION['loggedInUser'])){
     //send the iser to login page
     header("location:index.php");
 }
 
 if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod' && $_SESSION['type'] != 'cod' && $_SESSION['type']!='com'){
	//if not hod then send the user to login page
	session_destroy();
	header("location:index.php");
  }
  }  
  
  $fid=$_SESSION['Fac_ID'];
  
  $queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
  $resultrun = mysqli_query($conn, $queryrun);
  while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
  }

$_SESSION['currentTab']="research";


 //include custom functions files 
 include_once("includes/functions.php");
 include_once("includes/scripting.php");
 include_once("includes/config.php");

 $fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid ";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}

 $researchTitleError=$facultyNameError=$submittedToError=$amountError=$reportpath="";
 $radioApproval="";
 $principleInvestigator=$coInvestigator="";
 $flag1=$flag2=$flag3=$flag4=$flag5=$flag6=1;
 $s = 1;
$p_id = 0;
$error1 = "";

 //$currentTimestamp;
 $success = 0;
 $proposedAmount=$sanctionedAmount=0;
 $Fac_IDx = 0;
 $nameError = "";					
					
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	if(isset($_POST['add']))
	{
		//print_r($_POST);
        /*$conn = mysqli_connect("localhost","root","","department");
		if (mysqli_connect_errno())
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();*/


		function cleanseTheData($data) 
		{
  			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}




		$researchTitle_array = $_POST['researchTitle'];
		$startDate_array = $_POST['startDate'];
		$endDate_array = $_POST['endDate'];
		$submittedTo_array = $_POST['submittedTo'];
		$principleInvestigator_array = $_POST['principleInvestigator'];
		$coInvestigator_array = $_POST['coInvestigator'];
		$awardsWon_array = $_POST['awardsWon'];
		$sanctionedAmount_array = $_POST['sanctionedAmount'];
		$proposedAmount_array = $_POST['proposedAmount'];
		$radioApproval_array = $_POST['radioApproval'];
		$facultyName_array = $_POST['facultyName'];

		for($i=0;$i<1;$i++)
		{
			$researchTitle = mysqli_real_escape_string($conn,$researchTitle_array[$i]);
			//$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
			//$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
			$submittedTo = mysqli_real_escape_string($conn,$submittedTo_array[$i]);
			$principleInvestigator = mysqli_real_escape_string($conn,$principleInvestigator_array[$i]);
			$coInvestigator = mysqli_real_escape_string($conn,$coInvestigator_array[$i]);
			$awardsWon = mysqli_real_escape_string($conn,$awardsWon_array[$i]);
			//$sanctionedAmount = mysqli_real_escape_string($conn,$sanctionedAmount_array[$i]);
			//$proposedAmount = mysqli_real_escape_string($conn,$proposedAmount_array[$i]);
			$radioApproval = mysqli_real_escape_string($conn,$radioApproval_array[$i]);
			$facultyName = mysqli_real_escape_string($conn,$facultyName_array[$i]);
			$_SESSION['F_NAME'] = $facultyName ;


			if(empty($researchTitle))
			{
				$researchTitleError = "Enter a valid Research Title!";
				$flag1 = 0;
			}
			else
			{
				$researchTitle = cleanseTheData($researchTitle);
				//echo $researchTitle."<br>";
			}
			if(empty($startDate_array[$i]))
				$flag2 = 0;
			else
				$startDate = $startDate_array[$i];
			$endDate = $endDate_array[$i]; //No need to clean here bcoz its date.
			//$endDate = $_POST['endDate']; //No need to clean here bcoz its date.
			if(empty($endDate_array[$i]))
			{
				$date=date_create_from_format('Y-m-d',$startDate);
				date_add($date,date_interval_create_from_date_string("5 years"));
				$endDate = date_format($date,'Y-m-d');
			}
			
			if ($startDate > $endDate)		
			{
				$nameError=$nameError."Start Date cannot be greater than end date<br>";
				$flag2 = 0;
			}
			
			$time=time();
			$start = new DateTime(date($startDate,$time));
			$end = new DateTime(date($endDate,$time));
			$days = date_diff($start,$end);
			$noofdays = $days->format('%d');

			//echo $endDate."<br>";
			if(empty($submittedTo))
			{
				$submittedToError = "Enter the authority to whom research was submitted!";
				$flag3 = 0;
			}
			else
			{
				$submittedTo = cleanseTheData($submittedTo);
				//echo $submittedTo."<br>";
			}
			if(!empty($principleInvestigator))
			{
				$principleInvestigator = cleanseTheData($principleInvestigator);
				//echo $principleInvestigator."<br>";
			}
			if(!empty($coInvestigator))
			{
				$coInvestigator = cleanseTheData($coInvestigator);
				//echo $coInvestigator."<br>";
			}
			if(!empty($proposedAmount_array[$i]))
			{
				$proposedAmount = cleanseTheData($proposedAmount_array[$i]);
				//echo $proposedAmount."<br>";
			}
			else
			{
				$flag5 = 0;
			}
			if(!empty($_POST['$radioApproval[]']))
			{
				$radioApproval = cleanseTheData($radioApproval_array[$i]);
			}
			if(!empty($sanctionedAmount_array[$i]))
			{
				$sanctionedAmount = cleanseTheData($sanctionedAmount_array[$i]);
				//echo $sanctionedAmount."<br>";
			}
			if(!empty($awardsWon))
			{
				$awardsWon = cleanseTheData($awardsWon_array[$i]);
				//echo $awardsWon."<br>";
			}
			$radioApprovalAnswer = "";

			if($radioApproval == "yes")
				$radioApprovalAnswer = "yes";
			if($radioApproval == "no"){
				$radioApprovalAnswer = "no";
				$sanctionedAmount=0;
			}
			//echo $radioApprovalAnswer."<br>";
			if(  ($proposedAmount<0 && $sanctionedAmount<0) )
			{
				$amountError = "Proposed amount should not be less than zero";
				$flag4 = 0;
			}
			/*echo $flag1;
				echo $flag2;
				echo $flag3;
				echo $flag4;
				echo "<br>";*/
			if(!empty($_POST['facultyName[]']))
			{
				$facultyName = cleanseTheData($facultyName);
				// echo $facultyName;
				// echo "<br>";
				$flag6 = 1;
			}
	if(isset($_POST['applicable']))
    {
        if($_POST['applicable'] == 2)
        {
            $reportpath='NULL';
            $success=1;                      
        }
        else if($_POST['applicable'] == 3)
        {
            $reportpath='not_applicable';
            $success=1;
        }
        else if($_POST['applicable'] == 1)
        {
			echo "Hello";
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
                //    else{
                  //      echo "<h1> $targetName </h1>";
                    //}
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


			if($flag1==1 && $flag2==1 && $flag3==1 && $flag4==1 && $flag5==1 && $flag6==1 && $s != 0)
			{

				$queryx = "SELECT Fac_ID from facultydetails where F_NAME= '$facultyName'";
		        $result=mysqli_query($conn,$queryx);
		       	if($result)
		       	{
		            $row = mysqli_fetch_assoc($result);
		            $Fac_IDx = $row['Fac_ID'];
		            /*echo $Fac_IDx;
		            echo "inside fac_id query...";
		            echo "<br>";*/
			   	}
			   	//$Fac_IDx = $_SESSION['Fac_ID'];
			   	if($principleInvestigator==""){
			   		$principleInvestigator="NA";
			   	}
			   	if($coInvestigator==""){
			   		$coInvestigator="NA";
			   	}
			   	if($awardsWon==""){
			   		$awardsWon="NA";
			   	}
				print_r($success);
				$query = "INSERT INTO researchdetails (Fac_ID,researchTitle,submittedTo,fromDate,toDate,proposedAmount,radioApproval,amountSanctioned,facultyName,principleInvestigator,coInvestigator,awardsWon,reportPath,noofdays) VALUES ('$Fac_IDx','$researchTitle','$submittedTo','$startDate', '$endDate','$proposedAmount','$radioApprovalAnswer','$sanctionedAmount','$facultyName','$principleInvestigator','$coInvestigator','$awardsWon','".$reportpath."',$noofdays)";
				if (mysqli_query($conn, $query))
				{
					header("location:researchViewHOD.php?alert=success");
				}
				else if($s != 0)
				{
					header("location:researchViewHOD.php?alert=error");
				}
					
			}
		}
		mysqli_close($conn);
	}
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
					<h3 class="box-title"><b>Research Details Form</b></h3>
					<br>
				</div> 
                </div><!-- /.box-header -->
				
                <!-- form start -->
	
				
				<?php
			
					//for($k=0; $k<$_SESSION['count'] ; $k++)
					//{

				?> 
				<?php
			
					for($k=0; $k<1 ; $k++)
					{

				?>
			<form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data">
 <?php
			if($flag1!=1 || $flag2!=1 || $flag3!=1 || $flag4!=1 || $flag5!=1)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
			?>
					<div class="form-group col-md-6">
	                    <label for="facultyName">Faculty *</label>
						<?php
						include("includes/connection.php");
						$queryz="SELECT * from facultydetails WHERE Dept='".$_SESSION['Dept']."' AND type='faculty' ORDER BY F_NAME ";
						$result=mysqli_query($conn,$queryz);
						// echo "<br><input type='text' class='form-control input-lg' name='facultyName' id='facultyName' readOnly";
						echo "<br><select class='form-control input-lg' name='facultyName[]' id='facultyName'>";
						while ($row =mysqli_fetch_assoc($result)) 
						{
							echo "<option value='" . $row['F_NAME'] . "'>" . $row['F_NAME'] . "</option>";
						}
						// $facultyName = $row['F_NAME'];
						echo "</select>";
						?>
					</div>
                    
                     <div class="form-group col-md-6">
                         <label for="research-title">Research Title *</label>
					  <input  type="text" id="research-title" class="form-control input-lg"  name="researchTitle[]" required
					 value = '<?php if(isset($_POST['researchTitle'])) echo $researchTitle; ?>' >
                     </div>
                
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input <?php if(isset($_POST['startDate'])) echo "value = $startDate"; ?> type="date" class="form-control input-lg" id="start-date" name="startDate[]" required>
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date </label>
                         <input  <?php if(isset($_POST['endDate'])) echo "value = $endDate"; ?> type="date" class="form-control input-lg" id="end-date" name="endDate[]">
                     </div>
					 
					 <div class="form-group col-md-6">
                         <label for="submittedTo">Submitted to *</label>
                         <input <?php if(isset($_POST['submittedTo'])) echo "value = $submittedTo"; ?> required type="text" class="form-control input-lg" id="submittedTo" name="submittedTo[]">
                     </div>
					 
                     <div class="form-group col-md-6">
                         <label for="principleInvestigator">Principle Investigator </label>
					  <input  type="text" id="principleInvestigator"  class="form-control input-lg"  name="principleInvestigator[]"
					  value = '<?php if(isset($_POST['principleInvestigator'])) echo $principleInvestigator; ?>' >
                     </div>
                     <div class="form-group col-md-6">
                         <label for="coInvestigator">Co Investigator </label>
					  <input  type="text" id="coInvestigator" class="form-control input-lg"  name="coInvestigator[]"
					  value = '<?php if(isset($_POST['coInvestigator'])) echo $coInvestigator; ?>'>
                     </div>
					<div class="form-group col-md-6">
                         <label for="proposedAmount">Proposed Amount (Number only) *</label>
                         <input <?php if(isset($_POST['proposedAmount'])) echo "value = $proposedAmount"; ?> type="number" class="form-control input-lg" id="proposedAmount" name="proposedAmount[]" required>
                     </div>
					 <div class="form-group col-md-6">
                         <label for="radioApproval">Approved? *</label>
                         <select required class="form-control input-lg radioApproval" id="radioApproval" name="radioApproval[]">
                             <option <?php if($radioApproval == "yes") echo "selected = 'selected'" ?> value = "yes">Yes</option>
                             <option <?php if($radioApproval == "no") echo "selected = 'selected'" ?> value = "no">No</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6" id="sanctionedAmountDiv">
                         <label for="sanctionedAmount">Sanctioned Amount (Number only) </label>
                         <input  <?php if(isset($_POST['sanctionedAmount'])) echo "value = $sanctionedAmount"; ?> type="text" class="form-control input-lg" id="sanctionedAmount" name="sanctionedAmount[]">
                     </div>
                     <div class="form-group col-md-6">
                         <label for="awardsWon">Awards Won, if any?</label>
                         <input type="text"  class="form-control input-lg" id="awardsWon" name="awardsWon[]">
                     </div>
					 
					 <div class="form-group col-md-6 col-md-offset-1"></div>
	<div class="form-group col-md-6">
                    <div >
                    <label for="course">Upload report : Applicable ?<br></label><span class="colour"><b> *</b></span>
					<span class="error" style = "border : none;"> <?php echo $error1 ?> </span>
						<br>&nbsp;<input required type='radio' name='applicable' class='non-vac' value='1' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'checked':'' ?>> Yes <br>
						&nbsp;<input type='radio' name='applicable' class='vac' value='2' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="2")?'checked':'' ?>> Applicable, but not yet available <br>			
						&nbsp;<input type='radio' name='applicable' class='vac' value='3' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="3")?'checked':'' ?>> No <br>
					</div>
					<br>
					<div class='second-reveal' <?php if(isset($_POST['applicable'])) echo($_POST['applicable'] =="1")?'style = "display : block" ':'' ?>>

						<div >
                    	    <label for="card-image">Report </label><span class="colour"><b> *</b></span>
		        	        <input  type="file"   class="form-control input-lg" id="card-image" name="report">
	        	        </div> 
					</div>
	</div>

                     <script>
					 
					 $('.radioApproval').each(function(){
						 $('.radioApproval').on('change',myfunction);
					 });
					 
					 
					 
						function myfunction(){
						var x = this.value;
					
						if(x=='yes')
						{
				
							$(this).parent().next()[0].style.display = "block";
						}
						else if(x=='no')
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
                         

                         <button name="add" type="submit" class="btn pull-right btn-success btn-lg">Submit</button>
                         <a href="list_of_activities_user.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
                    </div>
                </div>
                </form>
                </div>
              </div>
           </div>      
        </section>

    
</div>
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

	

<?php include_once('footer.php'); ?>