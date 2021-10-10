<?php
ob_start();
session_start();
//check if user has logged in or not
 include_once('head.php'); 
 include_once('header.php'); 
  $_SESSION ["currentTab"] = "faculty"; 
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
$topic=$award=$organized=$details="";
$durationf=$durationt="";
$invitation=$invitation2 = "";
$flag= 1;
$success = 0;
		$fid = $_SESSION['Fac_ID'];
	$count1 = $_SESSION['count'];
	
        $faculty_name= $_SESSION['loggedInUser'];

$query="SELECT * from faculty where Fac_ID = $fid ";
    $result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
		
	}
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){
    //the form was submitted
     $fname_array = $_POST['fname'];
   
	$topic_array = $_POST['topic'];
	$durationf_array= $_POST['durationf'];
	$durationt_array = $_POST['durationt'];
		$organized_array = $_POST['organized'];
		$details_array = $_POST['details'];

	$resource_array = $_POST ['resource'];
	$award_array = $_POST['award'];
	


    		
for($i=0; $i<count($topic_array);$i++)
{
	$fname = mysqli_real_escape_string($conn,$fname_array[$i]);

$topic = mysqli_real_escape_string($conn,$topic_array[$i]);

$durationf = mysqli_real_escape_string($conn,$durationf_array[$i]);
$details = mysqli_real_escape_string($conn,$details_array[$i]);
$durationt = mysqli_real_escape_string($conn,$durationt_array[$i]);
$organized = mysqli_real_escape_string($conn,$organized_array[$i]);
$resource = mysqli_real_escape_string($conn,$resource_array[$i]);
$award = mysqli_real_escape_string($conn,$award_array[$i]);


        $topic=validateFormData($topic);
        $topic = "'".$topic."'";
		
        $organized=validateFormData($organized);
        $organized = "'".$organized."'";
		
        $durationf=validateFormData($durationf);
        $durationf = "'".$durationf."'";
		
        $durationt=validateFormData($durationt);
        $durationt = "'".$durationt."'";
		
	if ($durationf > $durationt)		
	{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
	}
	
	
        $resource=validateFormData($resource);
        $resource = "'".$resource."'";
		
        $award=validateFormData($award);
        $award = "'".$award."'";
		
        $details=validateFormData($details);
        $details = "'".$details."'";
		
	
        $query = "SELECT Fac_ID from facultydetails where F_NAME = $fname";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['award']))
$award = str_replace($replace_str, "", $award);
else
	$award  = '';

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['details']))
{
$details = str_replace($replace_str, "", $details);
$details = str_replace("rn",'', $details);

}
else
	$details  = '';



$replace_str = array('"', "'",'' ,'');
if(isset($_POST['topic']))
{
$topic = str_replace($replace_str, "", $topic);
$topic = str_replace("rn",'', $topic);

}
else
	$topic  = '';

	   
        $sql="INSERT INTO invitedlec(Fac_ID,organized,durationf,durationt, res_type, award,topic,details) VALUES ('$author',$organized,$durationf,$durationt, $resource, '$award','$topic','$details')";

			if ($conn->query($sql) === TRUE) {
			header("location:view_invited_hod_lec.php?alert=success");

					


			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
 
}//end of for
		
			        
}

}


//close the connection
//mysqli_close($conn);
?>
<script>

function yesnoCheck() {
    if (document.getElementById('lec').checked) {
        document.getElementById('ifYesLec').style.visibility = 'visible';
		document.getElementById('ifYesOther').style.visibility = 'hidden';
    }
    else if (document.getElementById('other').checked) {
		document.getElementById('ifYesLec').style.visibility = 'hidden';
		document.getElementById('ifYesOther').style.visibility = 'visible';
	}

}
</script>
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
					<h3 class="box-title"><b>Faculty Interaction Details Form</b></h3>
					<br>
					<b><a href="menu.php?menu=11 " style="font-size:15px;">Faculty Interaction</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="guestlec.php" style="font-size:15px;">&nbsp;No. of Faculty Interactions</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Add Faculty Interaction</a></b>	
					</div>				 <br> <h2 class="box-title" align="center"> Last updated on: 
				  <?php
				    $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
					
                    $result=mysqli_query($conn,$query);
					
                    if($result){
                    $row = mysqli_fetch_assoc($result);
                    $author = $row['Fac_ID'];
	                }
					
				    $sql= "SELECT tdate from invitedlec where fac_id = '".$author."' ORDER BY p_id DESC LIMIT 1;";
					
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
					mysqli_close($conn);
					?>
				   </h2>	
				 
                </div><!-- /.box-header -->
                <!-- form start -->
	
			  <div class="form-group col-md-6">
                    <label for="fname">Faculty </label><span class="colour"><b> *</b></span>

					<?php
					include("includes/connection.php");

					$query="SELECT * from facultydetails";
					$result=mysqli_query($conn,$query);
					echo "<select name='fname[]' id='fname' class='form-control input-lg'>";
					while ($row =mysqli_fetch_assoc($result)) {
						echo "<option value='" . $row['F_NAME'] ."'>" . $row['F_NAME'] ."</option>";
					}
					echo "</select>";
					?>
					</div><br /><br /><br /><br />
	<?php
			
					for($k=1; $k<=$_SESSION['count'] ; $k++)
					{

				?>
            <p>   &nbsp&nbsp&nbsp&nbsp&nbsp************************************************************************************
              <h4 style="padding-left: 30px; padding-bottom: 10px;color: #2961bc"><strong><em>FORM <?php echo $k ?> :</em></strong></h4>

			<form role="form" method="POST" class="row" action ="guest2.php" style= "margin:10px;" >
					
<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
			?>						
                     <div class="form-group col-md-6">
					 
                         <label for="organized">Organized By *</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input required type="text" class="form-control input-lg"  name="organized[]" id="organized" <?php echo "value = $organized"; ?> ><br></div>
					  
					  
						
						<div class="form-group col-md-6">
                                <label for="durationf">Duration From *</label>
                         <input <?php if(isset($_POST['durationf'])) echo "value = $durationf"; ?> required type="date" class="form-control input-lg" id="durationf" name="durationf[]" value=""><br> </div>
						
						<br>
                    <div class="form-group col-md-6">
   						
                         <label for="durationt"> Duration To *</label>
                         <input <?php if(isset($_POST['durationt'])) echo "value = $durationt"; ?> required type="date" class="form-control input-lg" id="durationt" name="durationt[]" value=""><br></div>
						 <div class="form-group col-md-6">
						
					     <label for="award">Awards, If Any </label>
                         <textarea class="form-control input-lg" id="award" name="award[]" rows="2">
							 <?php if(isset($_POST['award'])) echo $award; ?>
					 
						 </textarea> </div>
				
                    <br>
					 <div class="form-group col-md-6">
					
					     <label for="resource">Invited As A Resource Person For *</label><br>
                         
						 <input required type = "radio" name = "resource[]" value="lecture" id= "lec" onClick= "javascript:yesnoCheck();">
						<label for= "lec">Lecture/Talk/Workshop/Conference etc. </label><br>
						<input type = "radio" name = "resource[]" value="any_other" id= "other" onClick= "javascript:yesnoCheck();">
						<label for= "other">Any Other </label></div><br />
						 
						 <div id="ifYesLec" style="visibility:hidden" class="form-group col-md-6">
                    <label> Topic Of Lecture * </label><br>
					<textarea class="form-control input-lg" id= "topic" name="topic[]" rows="2" >
							 <?php if(isset($_POST['topic'])) echo $topic; ?>
				
					
					</textarea>

						
                    </div>
					 
					<div id="ifYesOther" style="visibility:hidden" class= "form-group col-md-6">
                    <label> Details Of The Activity * </label>
					<textarea class="form-control input-lg" id= "details" name="details[]" rows="2" >
							 <?php if(isset($_POST['details'])) echo $details; ?>
				
					</textarea>
						
                    </div>
					
                     
                    
                    
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                        

                         <input type="submit" name="add" class="btn btn-success btn-lg" value = "Submit" />
						 
						 <a href="menu.php?menu=11" type="button" class="btn btn-warning pull-right btn-lg">Cancel</a>
                    </div>
                </form>
				</div>
                </div>
              </div>
            
        </section>
   </div>
    
    
    
<?php include_once('footer.php'); ?>