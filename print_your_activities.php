<?php
session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp";

				include_once 'dompdf/dompdf_config.inc.php';
				include_once("includes/connection.php");

				   
				$Fac_ID = $_SESSION['Fac_ID'];	
				//$set = $_SESSION['set'];
				$type = $_SESSION['type'];
				
				$from_date =  $_SESSION['from_date'] ;
				$to_date = $_SESSION['to_date'] ;
				
				//$dateset = $_SESSION['dateset'];
				$count= $_SESSION['count1'];
				$type= $_SESSION['type'];
				$activities= $_SESSION['activities'];
				
				
					if($count > 0)
					{
				
						$sql = "select * from $type where Date_from >= '$from_date' and Date_to <= '$to_date' and Fac_ID = $Fac_ID and Act_type = '$activities'" ;
					
						
						if($res1 = mysqli_query($conn,$sql))
						{
							if(mysqli_num_rows($res1) > 0)
							{

								while($row = $res1->fetch_assoc()) 
								{
										$acttitle1[] = $row['Act_title'];
										$organized1[] = $row['Organized_by'];
										$from_date1[]=  $row['Date_from'];
										$to_date1[]= $row['Date_to'];
										$loc1[] = $row['Location'];
								}
							}
						}
						for($i = 0; $i < $count; $i++)
						{
							$rowall1 .= "-".$acttitle1[$i]."<br>";
							$orgall1 .= "-".$organized1[$i]."<br>";
							$datefrom .="-".$from_date1[$i]."<br>";
							$dateto .="-".$to_date1[$i]."<br>";
							$loc .="-".$loc1[$i]."<br>";
						}
					}
					// else
					// {
					// 	$rowall1 = "NIL";
					// 	$orgall1 = "NIL";
					// }

			$op = "<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Activities $type</p><hr>"."<table  border='1' class='table table-stripped table-bordered' id = 'example1'>
				 
				<tr> 
							
 							<td><strong>Total Activity Count</strong></td>
							<td colspan='5'><strong>"."$count"."</strong></td>

				</tr>

				<tr>
					<td><strong>Activity Title</strong></td>
					<td><strong>Activity Type</strong></td>
					<td><strong>Organized By</strong></td>
					<td><strong>From Date</strong></td>
					<td><strong>To date<strong></td>
					<td><strong>Location</strong></td>

				</tr>

				<tr> 		
					<td>"."$rowall1"."</td>
					<td>"."$activities"."</td>
					<td>"."$orgall1"."</td>
					<td>"."$datefrom"."</td>
					<td>"."$dateto"."</td>
					<td>"."$loc"."</td>
				</tr>	
				
				
						
				</table>";

				$dompdf = new DOMPDF();
				$dompdf->load_html($op);
				$dompdf->set_paper('a4', 'portrait');
				$dompdf->render();

				$dompdf->stream('hi',array('Attachment'=>0));
				
?>