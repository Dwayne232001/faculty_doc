<?php
ob_start();
session_start();
$_SESSION['currentTab'] = "paper";

include 'includes/connection.php';
$from_date = $_SESSION['from_date'];
$to_date = $_SESSION['to_date'];
$Fac_ID = $_SESSION['Fac_ID'];
$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID";
$table = "faculty"; 
$filename = "paper_publication"; 
$sql = $_SESSION['sql'];

$result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error(). "<br>" . mysqli_errno()); 
$output="";
if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr> 
                         <th>Paper Name</th>  
                         <th>Journal/Conference</th>  
                         <th>National/Interntional</th>
                         <th>Paper Category</th>
                         <th>conf_journal_name</th>
                         <th>Presentation_status</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr> 
                         <td>'.$row["Paper_title"].'</td>
                         <td>'.$row["Paper_type"].'</td>
                         <td>'.$row["Paper_N_I"].'</td>  
                         <td>'.$row["paper_category"].'</td> 
                         <td>'.$row["conf_journal_name"].'</td>   
                         <td>'.$row["presentation_status"].'</td> 
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=paper_publication_analysis.xls');
  echo $output;
 }

?>

