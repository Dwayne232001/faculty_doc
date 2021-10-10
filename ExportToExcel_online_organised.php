<?php
include_once('includes/connection.php');

//export.php
ob_start();
session_start();
$_SESSION['currentTab'] = "Online";
$output = '';
 $query = "SELECT * from online_course_organised where Fac_ID ='".$_SESSION['Fac_ID']."' ";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Name</th>
                         <th>Type of Course</th>
                         <th>Organized By</th>  
                         <th>Purpose</th>
                         <th>Target Audience</th>
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Faculty Role</th>  
                         <th>Full/Part Time</th>  
                         <th>Participants</th>
                         <th>Status</th>
                         <th>Duration</th>
                         <th>Sponsored</th>
                         <th>Name of sponsor</th>
                         <th>Approved</th>
                         <th>Attendance</th>
                         <th>Certificate</th>
                         <th>Report</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["Course_Name"].'</td>
                         <td>'.$row["type_of_course"].'</td>
                         <td>'.$row["Organised_By"].'</td>
                         <td>'.$row["Purpose"].'</td> 
                         <td>'.$row["Target_Audience"].'</td>
                         <td>'.$row["Date_From"].'</td>
                         <td>'.$row["Date_To"].'</td>
                         <td>'.$row["faculty_role"].'</td>
                         <td>'.$row["full_part_time"].'</td>
                         <td>'.$row["no_of_part"].'</td>
                         <td>'.$row["status"].'</td>
                         <td>'.$row["duration"].'</td>   
                         <td>'.$row["sponsored"].'</td>
                         <td>'.$row["name_of_sponsor"].'</td>
                         <td>'.$row["is_approved"].'</td>
                         <td>'.$row["attendence_path"].'</td> 
                         <td>'.$row["certificate_path"].'</td>  
                         <td>'.$row["report_path"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=online_course_organised.xls');
  echo $output;
 }
?>