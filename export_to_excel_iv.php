<?php
include_once('includes/connection.php');

//export.php
ob_start();
session_start();
$_SESSION['currentTab'] = "iv";
$fid=$_SESSION['Fac_ID'];
$output = '';
$query = "SELECT * FROM iv_organized where f_id=$fid ";
$result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Industry Name</th>  
                         <th>City</th> 
                         <th>Audience</th>
                         <th>no. Of Participants</th> 
                         <th>Staff</th>
                         <th>Purpose</th>
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>IV Type</th>
                         <th>Details</th>
                         <th>Permisiion</th>
                         <th>Certificate</th>
                         <th>Report</th>
                         <th>Attendance</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["ind"].'</td>
                         <td>'.$row["city"].'</td>
                         <td>'.$row["t_audience"].'</td>
                         <td>'.$row["part"].'</td>
                         <td>'.$row["staff"].'</td>
                         <td>'.$row["purpose"].'</td>    
                         <td>'.$row["t_from"].'</td>
                         <td>'.$row["t_to"].'</td>
                         <td>'.$row["ivtype"].'</td>
                         <td>'.$row["details"].'</td>
                         <td>'.$row["permission"].'</td>    
                         <td>'.$row["certificate"].'</td>
                         <td>'.$row["report"].'</td>
                         <td>'.$row["attendance"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=iv_faculty.xls');
  echo $output;
 }
?>