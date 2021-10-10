<?php
session_start();
include 'includes/connection.php';
$output='';
$display = 0;   
        
$sql = $_SESSION['sql'];

$result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error(). "<br>" . mysqli_errno()); 
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Faculty</th>
                         <th>Course Name</th>  
                         <th>Date from</th>
                         <th>Date to</th>
                         <th>Organized By</th>
                         <th>Purpose</th>
                         <th>Type of Course</th>
                         <th>Status</th>
                         <th>Duration</th>
                         <th>Credit/Audit</th>
                         </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["F_NAME"].'</td>
                         <td>'.$row["Course_Name"].'</td>
                         <td>'.$row["Date_From"].'</td>
                         <td>'.$row["Date_To"].'</td>  
                         <td>'.$row["Organised_by"].'</td> 
                         <td>'.$row["Purpose"].'</td>
                         <td>'.$row["type_of_course"].'</td>
                         <td>'.$row["status_of_activity"].'</td>
                         <td>'.$row["duration"].'</td>
                         <td>'.$row["credit_audit"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=online_analysis.xls');
  echo $output;
 }
?>