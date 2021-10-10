<?php
ob_start();
session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
				include_once 'dompdf/dompdf_config.inc.php';
				include_once("includes/connection.php");

				
				$Fac_ID = $_SESSION['Fac_ID'];	
				
					$from_date =  $_SESSION['from_date'] ;
					$to_date = $_SESSION['to_date'] ;
					
				
							$sql = "select * from ex_curricular where Date_from >= '$from_date' and Date_from <= '$to_date' and Fac_ID = $Fac_ID" ;
							$result=$conn->query($sql);
						   $num= $result->num_rows;
						   
						   ob_start();
						   echo "<div class='box-body'>
						   Total number of Activities : $num
						   <table align='' border='1' class='table table-stripped table-bordered' id = 'example1'>
				           
						   <tr id='tr1'>
				           <th>Activity Name</th>
				           <th>Date from</th>
				           <th>Date to</th>
				           </tr>";
						   for($i=1;$i<=$num;$i++)
						   {
							  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
							  echo '
							  <tr>
							  <td>'.$row["activity_name"].'</td>
							  <td>'.$row["Date_from"].'</td>
							  <td>'.$row["Date_to"].'</td>
							  </tr>';
							}
							echo "</table></div>";
					   $op = "<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Extra-curricular Activity</p>"."<table  border='1' class='table table-stripped table-bordered' id = 'example1'>";
				 
				$html = ob_get_clean();

				$dompdf = new DOMPDF();
				$dompdf->load_html($op."<br>".$html);
				
				$dompdf->set_paper('a4', 'portrait');
				$dompdf->render();
                $dompdf->stream('hi',array('Attachment'=>0));
				
?>