<?php
include_once('includes/connection.php');

//export.php
ob_start();
session_start();

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


  
  $queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
  $resultrun = mysqli_query($conn, $queryrun);
  while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
  }

$_SESSION['currentTab'] = "co_curricular";
$output = '';
 $query = "SELECT * FROM co_curricular inner join facultydetails on co_curricular.Fac_ID=facultydetails.Fac_ID";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Faculty</th>
                         <th>Activity Name</th>  
                         <th>Organized By</th>  
                         <th>Purpose</th>
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Last Edited</th>
                         <th>Permission</th>
                         <th>Certificate</th>
                         <th>Report</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row['F_NAME'].'</td>
                         <td>'.$row["activity_name"].'</td>
                         <td>'.$row["organized_by"].'</td>
                         <td>'.$row["purpose_of_activity"].'</td>    
                         <td>'.$row["Date_from"].'</td>
                         <td>'.$row["Date_to"].'</td>
                         <td>'.$row["currentTimestamp"].'</td>  
                         <td>'.$row["permission_path"].'</td> 
                         <td>'.$row["certificate_path"].'</td>  
                         <td>'.$row["report_path"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=co_curricular_all.xls');
  echo $output;
 }
?>