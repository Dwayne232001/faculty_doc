<?php
ob_start();
session_start();
$_SESSION['currentTab']="technical_review";

include 'includes/connection.php';
$flag_count = $_SESSION['flag_count'];		
$query="";
		if($flag_count === 1)
		{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;	
			$query = "SELECT * from paper_review inner join facultydetails on paper_review.Fac_ID = facultydetails.Fac_ID where paper_review.Date_from >= '$from_date' and paper_review.Date_from <= '$to_date' ";

		}
		else if($flag_count === 2)
		{
               $to_date = date("Y/m/d");
               $prevyear=date("Y")-1;
               $from_date=$prevyear.'/06/01';
			$sname = $_SESSION['sname'] ;
			$query = "SELECT * from paper_review inner join facultydetails on paper_review.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' where paper_review.Date_from >= '$from_date' and paper_review.Date_from <= '$to_date' ";
			
		}
		else if($flag_count === 3)
		{
					$from_date =  $_SESSION['from_date'] ;
					$to_date = $_SESSION['to_date'] ;
					$sname = $_SESSION['sname'] ;
					$query = "SELECT *  from paper_review inner join facultydetails on paper_review.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and paper_review.Date_from >= '$from_date' and paper_review.Date_from <= '$to_date'";
		}
$output="";

$result = mysqli_query($conn,$query) or die("Couldn't execute query:<br>". mysqli_errno()); 
if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table style="border:1px 1px">  
                    <tr>   
                         <th>Faculty Name</th>
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
                         <td>'.$row['F_NAME'].'</td>
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