<script>
$(document).ready(function(){
    // $("select").change(function(){
    //     $(location).attr('href', 'IV.php?x=../IV/'+$(this).val()+'/edit.php');
    // });
   alert("are you sure");
});
</script>

<?php
if(session_status() == PHP_SESSION_NONE)
	{
    //session has not started
     session_start();
    }
//check if user has logged in or not
 if(!isset($_SESSION['username']))
    {
    //send the user to login page
    header("location:../index.php");
    }

 include_once('IVSql.php');
 $id   = $_SESSION['id'];
 $type = $_SESSION['type'] ;
 unset($_SESSION['id']); 
 unset($_SESSION['type']);

 $conn=connection();
 
  //$sql="SELECT permission FROM $type where f_id='$id'";
  
 $iv=mysqli_fetch_object(IV("permission",$type,$id,"select"));
 if(strcmp($iv->permission,"NULL")!=0 && strcmp($iv->permission,"not_applicable")!=0 && strcmp($iv->permission,"")!=0)
 {
 	unlink("../".$iv->permission);	
 }
 
 $iv=mysqli_fetch_object(IV("report",$type,$id,"select"));
  if(strcmp($iv->report,"NULL")!=0 && strcmp($iv->report,"")!=0 &&strcmp($iv->report,"not_applicable")!=0)
  {
 	  unlink("../".$iv->report);	
  }

// $sql="SELECT certificate FROM $type where f_id='$id'";
$iv=mysqli_fetch_object(IV("certificate",$type,$id,"select"));
  if(strcmp($iv->certificate,"NULL")!=0 && strcmp($iv->certificate,"not_applicable")!=0 && strcmp($iv->certificate,"")!=0)
 {
 	unlink("../".$iv->certificate);	
 }
 	
if(strcmp($type,"iv_organized")!=0)
{
$iv=mysqli_fetch_object(IV("attendance",$type,$id,"select"));
  if(strcmp($iv->attendance,"NULL")!=0 && strcmp($iv->attendance,"not_applicable")!=0 && strcmp($iv->attendance,"")!=0)
 {
 	unlink("../".$iv->attendance);	
 }
}

 //$sql="DELETE FROM $type WHERE f_id='$id'";
 $check = IV("where",$type,$id,"delete");
 if($check)
  {
          
  if(strcmp($type,"IV_attended")==0) 
					header("location:../IV.php?x=IV/select_menu/edit_menu.php&alert=delete&type=attended");
	else
          header("location:../IV.php?x=IV/select_menu/edit_menu.php&alert=delete&type=organized");

  }


?>