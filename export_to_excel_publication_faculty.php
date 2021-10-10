<?php  
include_once('includes/connection.php');

//export.php
ob_start();
session_start();
$_SESSION['currentTab'] = "paper";
$output = '';
 $query = "SELECT * FROM faculty where Fac_ID ='".$_SESSION['Fac_ID']."' ;";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Paper Name</th>  
                         <th>Journal/Conference</th>  
                         <th>National/Interntional</th>
                         <th>Paper category</th>  
                         <th>conf_journal_name</th>
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Location</th>
                         <th>Paper_copy</th>
                         <th>Certificate_copy</th>
                         <th>Report_copy</th>
                         <th>Paper_co_authors</th>
                         <th>volume</th>
                         <th>scopus_index</th>
                         <th>scopus</th>
                         <th>h_index</th>
                         <th>citations</th>
                         <th>FDC_Y_N</th>
                         <th>Presentation_status</th>
                         <th>Presented_by</th>
                         <th>Link_publication</th>
                         <th>Paper_awards</th>
                         <th>fdc_app_disapp</th>
                         <th>Adate</th>
                         <th>Udate</th>
                         <th>No_of_days</th>
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
                         <td>'.$row["Date_from"].'</td>
                         <td>'.$row["Date_to"].'</td>
                         <td>'.$row["Location"].'</td>  
                         <td>'.$row["paper_path"].'</td> 
                         <td>'.$row["certificate_path"].'</td>  
                         <td>'.$row["report_path"].'</td>
                         <td>'.$row["Paper_co_authors"].'</td>
                         <td>'.$row["volume"].'</td>  
                         <td>'.$row["scopusindex"].'</td> 
                         <td>'.$row["scopus"].'</td>  
                         <td>'.$row["h_index"].'</td>
                         <td>'.$row["citations"].'</td>
                         <td>'.$row["FDC_Y_N"].'</td>  
                         <td>'.$row["presentation_status"].'</td> 
                         <td>'.$row["presented_by"].'</td>  
                         <td>'.$row["Link_publication"].'</td>
                         <td>'.$row["Paper_awards"].'</td>
                         <td>'.$row["FDC_approved_disapproved"].'</td>  
                         <td>'.$row["Adate"].'</td> 
                         <td>'.$row["Udate"].'</td>  
                         <td>'.$row["noofdays"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 }
?>