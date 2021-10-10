<?php
ob_start();
session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp";

				include_once 'dompdf/dompdf_config.inc.php';
				include_once("includes/connection.php");
	
	$type = $_SESSION['type'];
$sql = $_SESSION['sql'];
$display = $_SESSION['display'];
$from_date = $_SESSION['from_date'] ;
$to_date = $_SESSION['to_date'] ;
				 
					if($display == 1)
					{
						if($res1 = mysqli_query($conn,$sql)){
						if(mysqli_num_rows($res1) > 0){

							while($row = $res1->fetch_assoc()) 
							{
								
										$count = $count + 1;
									
										$Act_title[] = $row['Act_title'];
										$Act_type[] = $row['Act_type'];
									
									
									
							}
							for($i = 0; $i < $count; $i++)
							{
								$rowall1 .= "-".$Act_type[$i]."<br>";
								$rowall2 .= "-".$Act_title[$i]."<br>";
								
							}
						}
						}
					
						
						
					
					$op = "<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>STTP/Workshop/FDP $type analysis</p>"."<table  border='1' class='table table-stripped table-bordered' id = 'example1'>
				 
					
					<tr> 
							
 							<td><strong>Total Activities $type</strong></td>

							<td colspan='3'><strong>"."$count"."</strong></td>
							

					</tr> 
					<tr> 
							
 							<td><strong>Date from</strong></td>
							<td><strong>"."$from_date"."</strong></td>
					</tr>
					<tr>	
							<td><strong>Date to</strong></td>
							<td><strong>"."$to_date"."</strong></td>
							

					</tr> 
					<tr> 
							
 							<td><strong>Activities Type</strong></td>
							<td><strong>"."$Act_type[0]"."</strong></td>
							<td><strong>Activities Title</strong></td>
							<td><strong>"."$rowall2"."</strong></td>
							

					</tr> 
					
					
					</table>";
					
				execute($op);
	
				
				}
				else
				{
					$sname = $_SESSION['faculty_name'];
					if($res1 = mysqli_query($conn,$sql)){
						if(mysqli_num_rows($res1) > 0){

							while($row = $res1->fetch_assoc()) 
							{
								
										$count = $count + 1;
									
										$Act_title[] = $row['Act_title'];
										$Act_type[] = $row['Act_type'];
										$fromdate[] = $row['Date_from'];
										$todate[] = $row['Date_to'];

									
									
									
							}
							for($i = 0; $i < $count; $i++)
							{
								$rowall1 .= "-".$Act_type[$i]."<br>";
								$rowall2 .= "-".$Act_title[$i]."<br>";
								$rowall3 .= "-".$fromdate[$i]."<br>";
								$rowall4 .= "-".$todate[$i]."<br>";

							}
						}
						}
					
						
						
					
					$op = "<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>STTP/Workshop/FDP $type analysis</p>"."<table  border='1' class='table table-stripped table-bordered' id = 'example1'>
				 
					<tr> 
							<td><strong>Faculty Name</strong></td>
							<td><strong>"."$sname"."</strong></td>
					</tr>
					<tr> 
							
 							<td><strong>Total Activities $type </strong></td>

							<td><strong>"."$count"."</strong></td>
							

					</tr> 
						<tr>
						<td><strong>From Date</strong></td>
						<td><strong>To Date</strong></td>
						<td><strong>Activities Type</strong></td>
						<td><strong>Activities Title</strong></td>
						</tr>
					<tr> 
							
							<td>"."$rowall3"."</td>
							
							<td>"."$rowall4"."</td>
 							
							<td>"."$Act_type[0]"."</td>
							
							<td>"."$rowall2"."</td>
							

					</tr> 
					
					
					</table>";
					
				execute($op);
				}
				
	function execute($op)
	{
		$dompdf = new DOMPDF();
				$dompdf->load_html($op);

				$dompdf->set_paper('a4', 'landscape');
				$dompdf->render();

				$dompdf->stream('hi',array('Attachment'=>0));
	}	
				
?>