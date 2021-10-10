<?php
ob_start();
session_start();
$_SESSION['currentTab']="technical_review";

include 'includes/connection.php';
$from_date = $_SESSION['from_date'];
$to_date = $_SESSION['to_date'];
$Fac_ID = $_SESSION['Fac_ID'];
$sql = "select * from paper_review where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID";
$table = "paper_review"; 
$filename = "paper_reviewed_analysis"; 

$result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error(). "<br>" . mysqli_errno()); 
$output="";
if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table style="border:1px 1px">  
                    <tr>   
                         <th>Paper Title</th>  
                         <th>Journal/Conference</th>  
                         <th>National/Interntional</th>
                         <th>Paper category</th>  
                         <th>conf_journal_name</th>
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
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=technical_review_analysis.xls');
  echo $output;
 }
?>

