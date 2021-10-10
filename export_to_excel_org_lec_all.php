<?php
include_once('includes/connection.php');

//export.php
ob_start();
session_start();
$output = '';
 $query = "SELECT * FROM guestlec inner join facultydetails on guestlec.Fac_ID = facultydetails.Fac_ID ";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Faculty</th>
                         <th>Topic</th>  
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Resource person Name</th>
                         <th>Designation</th>
                         <th>Organization</th>
                         <th>Target Audience</th>
                         <th>Attendence Record</th>
                         <th>Permission Record</th>
                         <th>Certificate</th>
                         <th>Report</th>
                         </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["F_NAME"].'</td>
                         <td>'.$row["topic"].'</td>
                         <td>'.$row["durationf"].'</td>
                         <td>'.$row["durationt"].'</td>  
                         <td>'.$row["name"].'</td> 
                         <td>'.$row["designation"].'</td>  
                         <td>'.$row["organisation"].'</td>
                         <td>'.$row["targetaudience"].'</td>
                         <td>'.$row["attendance_path"].'</td>  
                         <td>'.$row["permission_path"].'</td> 
                         <td>'.$row["certificate1_path"].'</td>  
                         <td>'.$row["report_path"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=guest_organised.xls');
  echo $output;
 }
?>