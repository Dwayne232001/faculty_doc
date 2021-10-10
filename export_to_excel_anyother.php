<?php
include_once('includes/connection.php');

//export.php
ob_start();
session_start();
$_SESSION['currentTab'] = "any_other_activity";
$output = '';
 $query = "SELECT * FROM any_other_activity where Fac_ID ='".$_SESSION['Fac_ID']."' ;";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
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
  header('Content-Disposition: attachment; filename=any_other_activity.xls');
  echo $output;
 }
?>