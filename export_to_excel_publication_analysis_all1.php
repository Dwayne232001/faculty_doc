<?php
ob_start();

session_start();
include 'includes/connection.php';
$output="";
$flag_count = $_SESSION['flag_count'];		
		if($flag_count === 1)
		{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;	
			$query = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID where faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date' ";

		}
		else if($flag_count === 2)
		{
               $to_date = date("Y/m/d");
               $prevyear=date("Y")-1;
               $from_date=$prevyear.'/06/01';
			$sname = $_SESSION['sname'] ;
			$query = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' where faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date'";
		}
		else if($flag_count === 3)
		{
					$from_date =  $_SESSION['from_date'] ;
					$to_date = $_SESSION['to_date'] ;
					$sname = $_SESSION['sname'] ;
					$query = "SELECT *  from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date'";
		}
	$result = mysqli_query($conn, $query);
$output="";
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr> 
                         <th>Faculty</th>  
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
                         <td>'.$row["F_NAME"].'</td>  
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
