<?php
ob_start();
session_start();
include("includes/connection.php");

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}

$fid=$_SESSION['Fac_ID'];

$output = '';
$query = "SELECT * FROM organised Where Fac_ID=$fid ";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Title</th>  
                         <th>Type</th>
                         <th>Organised By</th>
                         <th>Resourse Person</th>  
                         <th>Date from</th>
                         <th>Date to</th>
                         <th>Co-ordinated By</th>
                         <th>Role Of Faculty</th>
                         <th>Full time/Part time</th>
                         <th>Number of participants</th>
                         <th>Duration</th>
                         <th>Status of Activity</th>
                         <th>Sponsors</th>
                         <th>Sponsorship Details</th>
                         <th>Approval Details</th>
                         <th>Permission Letter</th>
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
                         <td>'.$row["Role_Of_Faculty"].'</td>
                         <td>'.$row["Time_Activities"].'</td>
                         <td>'.$row["No_Of_Participants"].'</td>
                         <td>'.$row["Equivalent_Duration"].'</td>
                         <td>'.$row["Status_Of_Activity"].'</td>
                         <td>'.$row["Sponsorship"].'</td>
                         <td>'.$row["Sponsor_Details"].'</td>    
                         <td>'.$row["Approval_Details"].'</td>
                         <td>'.$row["Permission_path"].'</td>
                         <td>'.$row["Certificate_path"].'</td>    
                         <td>'.$row["Report_path"].'</td>
                         <td>'.$row["Brochure_path"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=sttp_orgainsed.xls');
  echo $output;
 }
?>