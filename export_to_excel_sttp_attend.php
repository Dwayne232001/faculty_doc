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
$query = "SELECT * FROM attended Where Fac_ID=$fid ";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Title</th>  
                         <th>Type</th>
                         <th>Organised By</th>  
                         <th>Date from</th>
                         <th>Date to</th>
                         <th>Status of Activity</th>
                         <th>Equivalent Duration</th>
                         <th>Awards</th>
                         <th>Location</th>
                         <th>FDC Status</th>
                         <th>Approval Status</th>
                         <th>Permission Letter</th>
                         <th>Certificate</th>
                         <th>Report</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["Act_title"].'</td>
                         <td>'.$row["Act_type"].'</td>
                         <td>'.$row["Organized_by"].'</td>
                         <td>'.$row["Date_from"].'</td>    
                         <td>'.$row["Date_to"].'</td>
                         <td>'.$row["Status_Of_Activity"].'</td>
                         <td>'.$row["Equivalent_Duration"].'</td>
                         <td>'.$row["Awards"].'</td>    
                         <td>'.$row["Location"].'</td>
                         <td>'.$row["FDC_Y_N"].'</td>
                         <td>'.$row["ApprovalStatus"].'</td>
                         <td>'.$row["Permission_path"].'</td>
                         <td>'.$row["Certificate_path"].'</td>    
                         <td>'.$row["Report_path"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=sttp_attend.xls');
  echo $output;
 }
?>