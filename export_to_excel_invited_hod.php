<?php  
include_once('includes/connection.php');

//export.php
ob_start();
session_start();

if(!isset($_SESSION['loggedInUser'])){
     //send the iser to login page
     header("location:index.php");
 }

include("includes/connection.php");

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

$_SESSION['currentTab'] = "facultyIneraction";
$output = '';
 $query = "SELECT * FROM invitedlec inner join facultydetails on invitedlec.Fac_ID = facultydetails.Fac_ID ";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Faculty Name</th>
                         <th>organized By</th>  
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Award</th>
                         <th>Invited For</th>
                         <th>Topic of Lecture</th>
                         <th>Details if any other Activity</th>
                         <th>Last Updated on</th>
                         <th>Invitation</th>
                         <th>Certificate</th>
                         <th>Nn Of Days<th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["F_NAME"].'</td>
                         <td>'.$row["organized"].'</td>
                         <td>'.$row["durationf"].'</td> 
                         <td>'.$row["durationt"].'</td>  
                         <td>'.$row["award"].'</td>
                         <td>'.$row["res_type"].'</td>
                         <td>'.$row["topic"].'</td>  
                         <td>'.$row["details"].'</td> 
                         <td>'.$row["tdate"].'</td>
                         <td>'.$row["invitation_path"].'</td>
                         <td>'.$row["certificate_path"].'</td>  
                         <td>'.$row["noofdays"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=FacultyIneractionHOD.xls');
  echo $output;
 }
?>