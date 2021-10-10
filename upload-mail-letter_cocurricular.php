<?php
session_start();
//check if user has logged in or not
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab']="co";

include_once("includes/functions.php");
include_once("includes/connection.php");
//include config file
include_once("includes/config.php");
$cardId = validateFormData($_POST['id']);
//setting error variables
$error="";

//check if the insert was pressed

if(isset($_POST['insert-image'])){
	$success =0;
	//$_SESSION['applicable'] = $_POST['applicable'];
	
	if(isset($_POST['applicable']))
	{
		if($_POST['applicable'] == 2)
		{
			$query = "Update paper_review set mail_letter_path ='NULL'  where paper_review_ID = $cardId";
             mysqli_query($conn,$query);
			 $success =1;
			 
		}
		else if($_POST['applicable'] == 3)
		{
			$query = "Update paper_review set mail_letter_path ='not_applicable'  where paper_review_ID = $cardId";
             mysqli_query($conn,$query);
			 			 $success =1;

			
		}
		else if($_POST['applicable'] == 1)
		{
			if(isset($_FILES['image']))
			{
			  $errors= array();
			  $fileName = $_FILES['image']['name'];
			  $fileSize = $_FILES['image']['size'];
			  $fileTmp = $_FILES['image']['tmp_name'];
			  $fileType = $_FILES['image']['type'];
			  $fileExt=strtolower(end(explode('.',$_FILES['image']['name'])));
			  date_default_timezone_set('Asia/Kolkata');
			  $targetName=$datapath."mails_letters/".$_POST['id']."_mail_letters_".date("d-m-Y H-i-s", time()).".".$fileExt;  
			  
			  if(empty($errors)==true) {
				if (file_exists($targetName)) {   
					unlink($targetName);
				}      
				 $moved = move_uploaded_file($fileTmp,$targetName);
				 if($moved == true){
					 //successful
					 $query = "Update paper_review set mail_letter_path =' ".$targetName."'  where paper_review_ID = $cardId";
					 mysqli_query($conn,$query);
					 			 $success =1;

					
					 echo "<h1> $targetName </h1>";
				 }
				 else{
					 //not successful
					 //header("location:error.php");
					 
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
						   header("location:2_dashboard_hod_review.php?alert=update");

						}
						else
						{
							header("location:2_dashboard_review.php?alert=update");

						}
}
else if($success == 0)
				echo "<script> alert('Error!') </script>";

}
	


if(isset($_POST['cancel'])){
	if($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com')
				{
	               header("location:2_dashboard_hod_review.php");

				}
				else
				{
					header("location:2_dashboard_review.php");

				}
	
}
?>





<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php if($_SESSION['type'] == 'hod')
{
      include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
        include_once('sidebar_cod.php');
}
else{
    include_once('sidebar.php');
}
include('includes/scripting.php');
 ?>





<div class="content-wrapper">
    
    <section class="content" style = "margin:10px;">
          <div class="row">
            <!-- left column -->
            <div class="col-md-6">
              <!-- general form elements -->
			  			  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Upload Paper</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form action="" method="POST" enctype="multipart/form-data" class="row">

                    <input type ='hidden' name = 'id' value = '<?php echo $cardId;?>'>
                      <div class="form-group col-md-6">

						<label for="course">Applicable ?<br></label>
					<br>	<input type='radio' name='applicable' class='non-vac' value='1' > Yes <br>
						<input type='radio' name='applicable' class='vac' value='2' > Applicable, but not yet available <br>
						 
						<input type='radio' name='applicable' class='vac' value='3' > No <br>
					</div>
					<div class='second-reveal'>
					 <div class="form-group col-md-6">
					 
                         <label for="card-image">Paper * </label>
                         <input  type="file"   class="form-control input-lg" id="card-image" name="image">
                    </div> 
					</div>
                    <div class="form-group col-md-12">
                <!--       <button name="cancel" type="submit" class="btn btn-warning btn-lg">Cancel</button> -->
<?php 
if($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com')
{ ?>
        <a href="2_dashboard_hod_review.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
<?php
}
else
{
?>
      <a href="2_dashboard_review.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
<?php
}
?>			 
                         <div class="pull-right"> 
						 
                             <button name="insert-image" type="submit" class="btn btn-success  btn-lg">Insert</button>
                         </div>
                    </div> 
                 </form>
                
                </div>
              </div>
           </div>      
        </section>
    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   