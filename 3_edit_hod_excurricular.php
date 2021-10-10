<?php
ob_start();
session_start();

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

$_SESSION['currentTab']="ex";


//include custom functions files 
include_once("includes/functions.php");

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



//setting error variables
$nameError="";
$emailError="";
$flag = 1;
$activityname = $startDate = $endDate = $purposeofactivity  = $organizedby = "";
$id = $F_NAME =  "";

if(isset($_POST['id'])){
    $_SESSION['id'] = $_POST['id'];
}
	$id = $_SESSION['id'];
    $query = "SELECT * from ex_curricular where ex_curricular_ID = '$id'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['Fac_ID'];

    
	
	$activityname = $row['activity_name'];
	$organizedby=$row['organized_by'];
    $purposeofactivity=$row['purpose_of_activity'];
    $startDate = $row['Date_from'];
	$endDate= $row['Date_to'];
	$currentTimestamp = $row['currentTimestamp'];


			
			$query2 = "SELECT * from facultydetails where Fac_ID = '$Fac_ID'";
			$result2 = mysqli_query($conn,$query2);
			if($result2)
			{
	            $row = mysqli_fetch_assoc($result2);
				$F_NAME = $row['F_NAME'];

			}
	   

//check if the form was submitted
if(isset($_POST['update'])){
	
    //the form was submitted
    
    $activityname = $_POST['activityname'];
	$organizedby=$_POST['organizedby'];
    $purposeofactivity=$_POST['purposeofactivity'];
    $startDate = $_POST['startdate'];
	$endDate = $_POST['enddate'];
	
	 $activityname=validateFormData($_POST['activityname']);
        $activityname = "'".$activityname."'";
  
		if ((strtotime($_POST['startdate'])) > (strtotime($_POST['enddate'])))
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}	
  
        $startdate=validateFormData($_POST['startdate']);
        $startdate = "'".$startdate."'";
    
        $endDate=validateFormData($_POST['enddate']);
        $endDate = "'".$endDate."'";
	
        $organizedby=validateFormData($organizedby);
        $organizedby = "'".$organizedby."'";
		
        $purposeofactivity=validateFormData($purposeofactivity);
        $purposeofactivity = "'".$purposeofactivity."'";
	
	if($flag==1)
	{					 
		
    
   	$sql="UPDATE ex_curricular set activity_name= $activityname, Date_from=$startdate, Date_to=$endDate, organized_by=$organizedby,purpose_of_activity=$purposeofactivity, currentTimestamp = CURRENT_TIMESTAMP WHERE ex_curricular_ID = '$id'";

			
			if ($conn->query($sql) === TRUE)
			{
					if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{
					   header("location:2_dashboard_hod_excurricular.php?alert=update");

					}
					/*else
					{
						header("location:2_dashboard_excurricular.php?alert=update");

					}*/
			}
	}
			/*else 
			   header("location:2_dashboard_hod_excurricular.php");*/

}
//close the connection
mysqli_close($conn);
?>






<style>
#p1
{
	font-size:20px;
}
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
					<h3 class="box-title"><b>Extra-Curricular Activity</b></h3>
					<br>	
				</div>
				</div><!-- /.box-header -->
				<div style="text-align:right">
				<!--	<a href="menu.php?menu=7 "> <u>Back to Extra-curricular Menu</u></a> -->
				</div>
                <!-- form start -->
                <form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
         	<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
			?>	
<?php 


$replace_str = array('"', "'",'' ,'');
$organizedby = str_replace($replace_str, "", $organizedby);	

$replace_str = array('"', "'",'' ,'');
$purposeofactivity = str_replace($replace_str, "", $purposeofactivity);	

?>				
					<input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
					<div class="form-group col-md-6">
					 <label for="activity-name">Faculty Name</label>
						<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
					</div>
				 <div class="form-group col-md-6">
					 <label for="activity-name">Activity Name *</label>
                      <input required   type="text" class="form-control input-lg"  name="activityname" value='<?php echo $activityname; ?>' >
					  </div><br><br>
                     
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input required  <?php echo "value = $startDate"; ?> type="date" class="form-control input-lg" id="start-date" name="startdate"
                         placeholder="03:10:10">
                     </div><br><br>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input required  <?php echo "value = $endDate"; ?> type="date" class="form-control input-lg" id="end-date" name="enddate"
                         placeholder="03:10:10" >
                     </div><br><br>
                    
                   <div class="form-group col-md-6">
                         <label for="organized-by">Organized By *</label>
                         <textarea required class="form-control input-lg" id="organizedby" name="organizedby" rows="2"><?php echo $organizedby; ?></textarea>
                     </div>
					 
					  <div class="form-group col-md-6">
                         <label for="purpose-of-activity">Purpose of Activity *</label>
                         <textarea required class="form-control input-lg" id="purposeofactivity" name="purposeofactivity" rows="2"><?php echo $purposeofactivity; ?></textarea>
                     </div>
                    

                    <div class="form-group col-md-12">
                         <a href="2_dashboard_hod_excurricular.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
						
                       <button name="update"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>

                    </div>
                </form>
                
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   