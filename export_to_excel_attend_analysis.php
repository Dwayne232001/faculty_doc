<?php  
include_once('includes/connection.php');
ob_start();
session_start();
$_SESSION['currentTab'] = "sttp_attend";
$output = '';
 $query = "SELECT * from attended where Fac_ID ='".$_SESSION['Fac_ID']."' ";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Title</th>  
                         <th>Type</th>  
                         <th>Organised By</th>
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Status of Activity</th>
                         <th>Equivalent Duration</th>
                         <th>Awards</th>
                         <th>Location</th>
                         <th>Last Updated</th>
                         <th>Permission</th>
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
                         <td>'.$row["LastUpdated"].'</td>  
                         <td>'.$row["Permission_path"].'</td> 
                         <td>'.$row["Certificate_path"].'</td>  
                         <td>'.$row["Report_path"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=sttp_addend.xls');
  echo $output;
 }
?>