<?php
ob_start();
session_start();
$_SESSION['currentTab'] = "sttp";
include 'includes/connection.php';
$output = '';
 $query = "SELECT * from organised where Fac_ID ='".$_SESSION['Fac_ID']."'";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Title</th>  
                         <th>Type</th>  
                         <th>Orgnised By</th>
                         <th>Resourse Person</th>  
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Co-ordinated By</th>
                         <th>Location</th>
                         <th>Role Of Faculty</th>
                         <th>Full Time/Part Time</th>
                         <th>No Of Participants</th>
                         <th>Duration</th>
                         <th>Status Of Activity</th>
                         <th>Sponsors</th>
                         <th>Sponsorship Details</th>
                         <th>Approval Details</th>
                         <th>Last Updated</th>
                         <th>Permission</th>
                         <th>Certificate</th>
                         <th>Report</th>
                         <th>Brochure</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["Act_title"].'</td>
                         <td>'.$row["Act_type"].'</td>
                         <td>'.$row["Organized_by"].'</td>  
                         <td>'.$row["Resource"].'</td>   
                         <td>'.$row["Date_from"].'</td>
                         <td>'.$row["Date_to"].'</td>
                         <td>'.$row["Coordinated_by"].'</td> 
                         <td>'.$row["Location"].'</td>  
                         <td>'.$row["Role_Of_Faculty"].'</td>  
                         <td>'.$row["Time_Activities"].'</td>
                         <td>'.$row["No_Of_Participants"].'</td>
                         <td>'.$row["Equivalent_Duration"].'</td>  
                         <td>'.$row["Status_Of_Activity"].'</td> 
                         <td>'.$row["Sponsorship"].'</td>  
                         <td>'.$row["Sponsor_Details"].'</td>
                         <td>'.$row["Approval_Details"].'</td>
                         <td>'.$row["LastUpdated"].'</td>
                         <td>'.$row["Permission_path"].'</td>  
                         <td>'.$row["Certificate_path"].'</td>
                         <td>'.$row["Report_path"].'</td>
                         <td>'.$row["Brochure_path"].'</td>  
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=sttp_organised.xls');
  echo $output;
 }
?>
