<?php
session_start();
include 'includes/connection.php';
$output='';
$display = 0;	
		
		if($_SESSION['display'] == 1)
		{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;
			$sql1 = "SELECT * from co_curricular inner join facultydetails on co_curricular.Fac_ID=facultydetails.Fac_ID where Date_from >= '$from_date' and Date_from <= '$to_date' and co_curricular.Fac_ID = facultydetails.Fac_ID ";
			$display = 1;
		}
		else if ($_SESSION['display'] == 2)
		{
               $to_date = date("Y/m/d");
               $prevyear=date("Y")-1;
               $from_date=$prevyear.'/06/01';
			$sname = $_SESSION['sname'] ;
			$sql1 = "SELECT * from co_curricular inner join facultydetails on co_curricular.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' where Date_from >= '$from_date' and Date_from <= '$to_date' and co_curricular.Fac_ID = facultydetails.Fac_ID ";
			$display = 2;
		}
		else if($_SESSION['display'] == 3)
		{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;
			$sname = $_SESSION['sname'] ;
			$sql1 = "SELECT F_NAME,activity_name, Date_from, Date_to, organized_by, purpose_of_activity, currentTimestamp  from co_curricular inner join facultydetails on co_curricular.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and co_curricular.Date_from >= '$from_date' and co_curricular.Date_from <= '$to_date'";
		     $_SESSION['sql'] = $sql1;
			$display = 3;
		}
	$result = mysqli_query($conn, $sql1);
 if(mysqli_num_rows($result) > 0)
 {
 $output.='
  <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Faculty</th>
                         <th>Activity Name</th>  
                         <th>Organized By</th>  
                         <th>Purpose</th>
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Number of days</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row['F_NAME'].'</td>
                         <td>'.$row["activity_name"].'</td>
                         <td>'.$row["organized_by"].'</td>
                         <td>'.$row["purpose_of_activity"].'</td>    
                         <td>'.$row["Date_from"].'</td>
                         <td>'.$row["Date_to"].'</td>
                         <td>'.$row["noofdays"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=co_curricular_analysis.xls');
  echo $output;
 }
?>