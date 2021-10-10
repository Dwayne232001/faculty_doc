<?php 
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    //header("location:index.php");
}
include("includes/connection.php");
	$count = 0;
	$fromDate = $toDate = "";	
	function fetchData()
	{
		$output = "";
		$count = 0;
		$fromDate = $_SESSION['fromDate'] ;
		$toDate = $_SESSION['toDate'] ;
		$Fac_ID = $_SESSION['Fac_ID'] ;
		$query = " SELECT * FROM researchdetails WHERE toDate >= '$fromDate' AND fromDate <= '$toDate' AND Fac_ID = $Fac_ID ";
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$output .= '<tr>
								<td>'.$row["researchTitle"].'</td>
								<td>'.$row["fromDate"].'</td>
								<td>'.$row["toDate"].'</td>
								<td>'.$row["submittedTo"].'</td>
								<td>'.$row["principleInvestigator"].'</td>
								<td>'.$row["coInvestigator"].'</td>
								<td>'.$row["proposedAmount"].'</td>
								<td>'.$row["radioApproval"].'</td>
								<td>'.$row["amountSanctioned"].'</td>
								<td>'.$row["awardsWon"].'</td>

						</tr>';
			$count++;
		}
		$output .= '<br><br>';
		$output .= $count;
		$output .= " entries.";
		return $output;
	}

	//if($_SESSION['generate'] == 'generate')
	{
		require_once('tcpdf/tcpdf.php');
		$pdf = new TCPDF('L',PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle("Faculty Research Analysis");
		$pdf->SetHeaderData('','',PDF_HEADER_TITLE,PDF_HEADER_STRING);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_DATA,'',PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont('helvetica');
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetMargins(PDF_MARGIN_LEFT,'10',PDF_MARGIN_RIGHT);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetAutoPageBreak(true,10);
		$pdf->SetFont('helvetica','',11);
		$pdf->AddPage();
		$content = "";
		$content .= '
						<h1 align="center"><strong>K.J.Somaiya College of Engineering</strong></h1><br>
						<h3 align="center">(Autonomous College affiliated to University of Mumbai)</h3><br>
						<table border="1" cellspacing="0" cellpadding="3">
						<tr>  
			                <th width="13%"><strong>Research Title</strong></th>
							<th width="9%"><strong>Start Date</strong></th>  
			                <th width="9%"><strong>End Date</strong></th>
			                <th width="10%"><strong>Submitted to</strong></th>
							<th width="10%"><strong>Principle Investigator</strong></th>
			                <th width="10%"><strong>Co Investigator</strong></th>
			                <th width="9%"><strong>Proposed Amount</strong></th>
			                <th width="8%"><strong>Approved?</strong></th>
			                <th width="9%"><strong>Sanctioned Amount</strong></th>
							<th width="13%"><strong>Awards Won</strong></th>
		           		</tr>
	      			';  
	      $content .= fetchData();  
	      $content .= '</table>';
	      $pdf->writeHTML($content);
	      $pdf->Output('file.pdf', 'I');
    }
?>