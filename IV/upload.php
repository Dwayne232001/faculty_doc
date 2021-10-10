<?php
if(session_status() == PHP_SESSION_NONE)
	{
    //session has not started
     session_start();
    }
//check if user has logged in or not
 if(!isset($_SESSION['username'])){
    //send the iser to login page
    header("location:index.php");
    }

//setting error variables
$error="";
$conn=connection();
//for which id=id, type=attended or organized and for the fine="permission/report or other"
  
  $type = $_SESSION['type'] ; 
  $id   = $_SESSION['id']   ; 
  $file = $_SESSION['file'] ; 

//check if the insert was pressed
if(isset($_POST['insert-image']))
  {
	$success =0;
	//$_SESSION['applicable'] = $_POST['applicable'];
	
	if(isset($_POST['applicable']))
	{
		if($_POST['applicable'] == 2)
		{
            	//$query = "UPDATE $type SET $file='NULL' where id='$id'"; 
				//mysqli_query($conn,$query);
             	$iv=mysqli_fetch_object(IV($file,$type,$id,"select"));
             	if(strcmp($iv->$file,"NULL")!=0 && strcmp($iv->$file,"")!=0 &&strcmp($iv->$file,"not_applicable")!=0)//check if already has image uploaded
             	{
             		unlink($iv->$file);
             	}
             	$val=array("NULL",$id);
             	IV($file,$type,$val,"upload");
         		$success =1;
			 
		}
		else if($_POST['applicable'] == 3)
		{
            	//$query = "UPDATE $type SET $file='not_applicable' where id='$id'";
				//mysqli_query($conn,$query);
				$iv=mysqli_fetch_object(IV($file,$type,$id,"select"));
             	if(strcmp($iv->$file,"NULL")!=0 && strcmp($iv->$file,"")!=0 &&strcmp($iv->$file,"not_applicable")!=0)//check if already has image uploaded
             	{
             		unlink($iv->$file);
             	}
				$val=array("not_applicable",$id);
             	IV($file,$type,$val,"upload");
           		$success =1;

			
		}
		else if($_POST['applicable'] == 1)
		{
			if(isset($_FILES['image']))
			{
			  $errors= array();
			  $fileName = "$id".$_FILES['image']['name'];
			  $fileSize = $_FILES['image']['size'];
			  $fileTmp = $_FILES['image']['tmp_name'];
			  $fileType = $_FILES['image']['type'];
			  //$fileExt=strtolower(end(explode('.',$_FILES['image']['name'])));
			  $targetName="IV/uploads/".$type."/".$file."/".$fileName;  
			  $targetPath="IV/uploads/".$type."/".$file."/";
			  
			  if(empty($errors)==true) {
				if (file_exists($targetName)) {   
					unlink($targetName);
				}      
				 $moved = move_uploaded_file($fileTmp,$targetName);
				 if($moved == true)
				 {
					 //successful
						//$query = "UPDATE $type SET $file='".$targetName."' where id='$id'"; 
					 	//mysqli_query($conn,$query);
				 		$iv=mysqli_fetch_object(IV($file,$type,$id,"select"));
             			if(strcmp($iv->$file,"NULL")!=0 && strcmp($iv->$file,"")!=0 &&strcmp($iv->$file,"not_applicable")!=0)//check if already has image uploaded
             			{
             				unlink($iv->$file);
             			}	
				 		$val=array($targetName,$id);
             			IV($file,$type,$val,"upload");
					 			 $success =1;

					
					 //echo "<h1> $targetName </h1>";
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
	unset($_SESSION['type']);
	unset($_SESSION['file']);
	unset($_SESSION['id']);
	
	if(strcmp($type,"IV_attended")==0)			
	 header("location:IV.php?x=IV/select_menu/edit_menu.php&alert=update&type=attended");	
	else 
	 header("location:IV.php?x=IV/select_menu/edit_menu.php&alert=update&type=organized");	
}
	


if(isset($_POST['cancel'])){
	if(strcmp($type,"IV_attended")==0)
		header("location:IV.php?x=IV/select_menu/edit_menu.php&type=attended");
	else
		header("location:IV.php?x=IV/select_menu/edit_menu.php&type=organized");
}
?>






<?php 
include('scripting.php');
 ?>




 			<div class="col-md-6">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Upload Paper</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form action="" method="POST" enctype="multipart/form-data" class="row">

                    <input type ='hidden' name = 'id' value = '<?php echo $cardId;?>'>
                      <div class="form-group col-md-6">

						<label for="course">Applicable ?<br></label>
						<br>	
							<input type='radio' name='applicable' class='non-vac' value='1' > Yes <br>
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
		 
                         <div class="pull-right"> 
						 	 <button name="insert-image" type="submit" class="btn btn-success  btn-lg">Insert</button>
                             <button name="cancel" type="submit" class="btn btn-success  btn-lg">Cancel</button>
                         </div>
                    </div> 
                 </form>
                
                </div>
            </div>
  
   

   
   