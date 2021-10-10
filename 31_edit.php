<?php
ob_start();
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the user to login page
    header("location:index.php");
}
 include_once('head.php'); 
 include_once('header.php'); 
 $_SESSION ["currentTab"] = "faculty"; 

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

//include custom functions files 
include("includes/functions.php");




//setting error variables
$nameError="";
$emailError="";


if(isset($_POST['id'])){
    $id = $_POST['id'];
	$_SESSION['id'] = $id;
    $query = "SELECT * from invitedlec where invited_id = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['Fac_ID'];
	$_SESSION['Fac_ID'] = $Fac_ID;
    
	$organized2 = $row['organized'];
    $startDate = $row['durationf'];
    $endDate = $row['durationt'];
	$awards= $row ['award'];
	$resource= $row['res_type'];
	$topic = $row['topic'];
    $details= $row['details'];
    
    
}

$a = $_SESSION['Fac_ID'];
			
			$query2 = "SELECT * from facultydetails where Fac_ID = $a";
			$result2 = mysqli_query($conn,$query2);
			if($result2)
			{
	            $row = mysqli_fetch_assoc($result2);
				$F_NAME = $row['F_NAME'];

			}
	   

//check if the form was submitted
if(isset($_POST['update'])){

    
    
    //check for any blank input which are required
    
     if(!$_POST['organized']){
        $nameError="Please enter organized by<br>";
    }
    else{
        $organized1=validateFormData($_POST['organized']);
        $organized1 = "'".$organized1."'";
    }
   if(!$_POST['startDate']){
        $nameError="Please enter a start date<br>";
    }
    else{
        $startDate1=validateFormData($_POST['startDate']);
        $startDate1 = "'".$startDate1."'";
    }
    if(!$_POST['endDate']){
        $nameError="Please enter an end date<br>";
    }
    else{
        $endDate1=validateFormData($_POST['endDate']);
        $endDate1 = "'".$endDate1."'";
    }
    
   
    
  
    if(!$_POST['award']){
        $nameError="Please enter an award<br>";
    }
  else{
        $awards1=validateFormData($_POST['award']);
        $awards1 = "'".$awards1."'";
    }
	
    
	$resource1 = $_POST['resource'];
		
		
   
 
	 if($_POST['resource'] == 'lecture')
	 {
		 $topic1=validateFormData($_POST['topic']);
        $topic1 = "'".$topic1."'";
		$details1 = "";
	 }
	 else if($_POST['resource'] == 'any other')
	 {
		 
        $topic1 = "";
		$details1=validateFormData($_POST['details']);
        $details1 = "'".$details1."'";
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

	$sql = "UPDATE invitedlec SET organized= $organized1, 
	durationf=$startDate1,
	durationt=$endDate1 , 
	award= $awards1, 
	res_type= '$resource1'	
	WHERE invited_id = $a";							

			if ($conn->query($sql) === TRUE) 
			{
				if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{
					   header("location:view_invited_hod_lec.php?alert=update");

					}
					else
					{
						header("location:view_invited_lec.php?alert=update");

					}
				
				
				}	
					
				
					
			 else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}

}

//close the connection
mysqli_close($conn);
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



<div class="content-wrapper">
    
    <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Faculty Interaction</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
                     <div class="form-group col-md-6">
                         <label for="organized">Organized By *</label>
                         <input required type="text" class="form-control input-lg" id="organized" name="organized" value = '<?php echo $organized2; ?>' >
                     </div>
                     
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input 
                             <?php echo "value = '$startDate'"; ?>
                           required type="date" class="form-control input-lg" id="start-date" name="startDate"
                         placeholder="">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input
                             <?php echo "value = '$endDate'"; ?>
                           required type="date" class="form-control input-lg" id="end-date" name="endDate"
                         placeholder="">
                     </div>
             
                         <div class="form-group col-md-6">
                        
                         <label for="award">Awards, If Any </label>
                         <textarea			
						  class="form-control input-lg" id="award" name="award" rows="2" > <?php echo $awards; ?></textarea>  </div>
                
                    <br>
                     <div class="form-group col-md-6">
                    
                         <label for="resource">Invited As A Resource Person For *</label><br>
                         
                         <input type = "radio" name = "resource" <?php if($resource == "lecture") echo 'checked'; ?> value="lecture" id= "lec" onClick= "javascript:yesnoCheck();" >
                        <label for= "lec">Lecture/Talk/Workshop/Conference </label><br>
                        <input type = "radio" name = "resource" <?php if($resource == "any other") echo 'checked'; ?> value="any other" id= "other" onClick= "javascript:yesnoCheck();">
                        <label for= "other">Any Other </label></div><br />
                         
                         <div id="ifYesLec" style="visibility:hidden" class="form-group col-md-6">
                    <label> Topic Of Lecture * </label><br>
                   
					   
					 <textarea 
					class="form-control input-lg" id= "topic" name="topic" rows="2"  > <?php echo $topic; ?>  </textarea>
                         
                    </div>
                     
                    <div id="ifYesOther" style="visibility:hidden" class= "form-group col-md-6">
                    <label> Details Of The Activity * </label>
                    <textarea 
					
					class="form-control input-lg" id= "details" name="details" rows="2"  ><?php echo $details; ?> </textarea>
                        
                    </div>
                    
                     
                    

                    <div class="form-group col-md-12">
                         <a href="view_invited_lec.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

                         <button name="update" id="update" type="submit" class="btn btn-success pull-right btn-lg">Submit</button>

                    </div>
                </form>
                
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>