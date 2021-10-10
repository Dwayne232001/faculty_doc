<?php
include_once('includes/connection.php');

//export.php
ob_start();
session_start();
$_SESSION['currentTab'] = "online_attend";
$Fac_ID = $_SESSION['Fac_ID'];
$output = '';
 $query = "SELECT * FROM online_course_attended WHERE Fac_ID = $Fac_ID ";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Name</th>  
                         <th>Organized By</th>  
                         <th>Purpose</th>
                         <th>Type Of Course</th>
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Status</th>
                         <th>Duration</th>
                         <th>Credit/Audit</th>
                         <th>Certificate</th>
                         <th>Report</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["Course_Name"].'</td>
                         <td>'.$row["Organised_by"].'</td>
                         <td>'.$row["Purpose"].'</td> 
                         <td>'.$row["type_of_course"].'</td>    
                         <td>'.$row["Date_From"].'</td>
                         <td>'.$row["Date_To"].'</td>
                         <td>'.$row["status_of_activity"].'</td>
                         <td>'.$row["duration"].'</td>   
                         <td>'.$row["credit_audit"].'</td> 
                         <td>'.$row["certificate_path"].'</td>  
                         <td>'.$row["report_path"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=online_course_attended.xls');
  echo $output;
 }
?>