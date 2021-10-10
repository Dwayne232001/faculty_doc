<?php


ob_start();
  session_start();
  if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "paper";

 
  
if($_SESSION['type'] == 'hod')
{
	  include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
		include_once('sidebar_cod.php');
}
else{
	include_once('sidebar.php');
}



include_once("includes/functions.php");
include_once("includes/connection.php");
//include config file
include_once("includes/config.php");
include('includes/scripting.php');

$cardId = validateFormData($_POST['id']);

$query = "SELECT * FROM faculty WHERE P_ID=$cardId";
$result = mysqli_query($conn,$query);
while($row=mysqli_fetch_assoc($result)){
	$fid=$row['Fac_ID'];
}
$query1= "SELECT * FROM facultydetails WHERE Fac_ID= $fid ";
$result1=mysqli_query($conn,$query1);
while($row=mysqli_fetch_assoc($result1)){
	$fname=$row['F_NAME'];
	$_SESSION['F_NAME'] = $fname ;
}

//setting error variables
$papererror="";

//check if the insert was pressed

if(isset($_POST['paper'])){
	$success =0;
	//$_SESSION['applicable'] = $_POST['applicable'];
	
	if(isset($_POST['applicable']))
	{
		if($_POST['applicable'] == 2)
		{
			$query = "Update faculty set paper_path ='NULL'  where P_ID = $cardId";
             mysqli_query($conn,$query);
			 $success =1;
			 
		}
		else if($_POST['applicable'] == 3)
		{
			$query = "Update faculty set paper_path ='not_applicable'  where P_ID = $cardId";
             mysqli_query($conn,$query);
			 			 $success =1;

			
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
			  $fileExt=strtolower(end(explode('.',$fileName)));
			  date_default_timezone_set('Asia/Kolkata');
			  $targetName=$datapath."papers/".$_SESSION['F_NAME']."_papers_".date("d-m-Y H-i-s", time()).".".$fileExt;  
			  
			  if(empty($errors)==true) {
				if (file_exists($targetName)) {   
					unlink($targetName);
				}      
				 $moved = move_uploaded_file($fileTmp,"$targetName");
				 if($moved == true){
					 //successful
					 $query = "Update faculty set paper_path =' ".$targetName."'  where P_ID = $cardId";
					 mysqli_query($conn,$query);
					 			 $success =1;
				 }
				 else{
					 //not successful
					 //header("location:error.php");
					 			 echo "<h1> $targetName </h1>";
				 }
			  }
				else{
				 print_r($errors);
				//header("location:else.php");
			  }
			}
		}
	
}
if($success == 1)
{
	 if($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com')
						{
						   header("location:2_dashboard_hod.php?alert=update");

						}
						else
						{
							header("location:2_dashboard.php?alert=update");

						}
}
else if($success == 0)
				echo "<script> alert('Error!') </script>";
	
}


if(isset($_POST['cancel'])){
	if($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com')
				{
	               header("location:2_dashboard_hod.php");

				}
				else
				{
					header("location:2_dashboard.php");

				}
	
}
?>




<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>

<?php 
include('includes/scripting.php');
 ?>







<div class="content-wrapper">
    
    <section class="content" >
          <div class="row">
            <!-- left column -->
            <div class="col-md-6">
              <!-- general form elements -->
			  			  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
				  <i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Upload Paper</b></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form action="" method="POST" enctype="multipart/form-data" class="row">

                    <input type ='hidden' name = 'id' value = '<?php echo $cardId;?>'>
                      <div class="form-group col-md-6">

						&nbsp;<label for="course">Applicable ?<br></label>
					<br>	&nbsp;<input type='radio' name='applicable' class='non-vac' value='1' > Yes <br>
						&nbsp;<input type='radio' name='applicable' class='vac' value='2' > Applicable, but not yet available <br>
						 
						&nbsp;<input type='radio' name='applicable' class='vac' value='3' > No <br>
					</div>
					<div class='second-reveal'>
					 <div class="form-group col-md-6">
					 
                         <label for="card-image">Paper </label><span class="colour"><b> *</b></span>
                         <input  type="file"   class="form-control input-lg" id="card-image" name="paper">
                    </div> 
					</div>
                    <div class="form-group col-md-12">
                <!--       <button name="cancel" type="submit" class="btn btn-warning btn-lg">Cancel</button> -->
<?php 
if($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com')
{ ?>
        <a href="2_dashboard_hod.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
<?php
}
else
{
?>
      <a href="2_dashboard.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
<?php
}
?>			 
                         <div class="pull-right"> 
						 
                             <button name="paper" type="submit" class="btn btn-success  btn-lg">Insert</button>
                         </div>
                    </div> 
                 </form>
                
                </div>
              </div>
           </div>      
        </section>
    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   