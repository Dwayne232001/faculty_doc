<?php 
session_start();
ob_start();

session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
include("includes/connection.php");

if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod' && $_SESSION['type'] != 'cod' && $_SESSION['type']!='com'){
	//if not hod then send the user to login page
	session_destroy();
	header("location:index.php");
  }
  }  
  
  $fid=$_SESSION['Fac_ID'];
  
  $queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
  $resultrun = mysqli_query($conn, $queryrun);
  while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
  }


	$fromDate = $toDate = "";	
	function fetchData()
	{
		$count = 0;
		$output = "";
		$Fac_ID = $_SESSION['Fac_ID'] ;
		if($_SESSION['type1'] == 1)
		{
			$fromDate = $_SESSION['fromDate'] ;
		$toDate = $_SESSION['toDate'] ;
			
			$query = " SELECT * FROM researchdetails WHERE toDate >= '$fromDate' AND fromDate <= '$toDate' ";
		}
		if($_SESSION['type2'] == 1)
		{
			$facultyName = $_SESSION['facultyNameForExcel'] ;
			$query = " SELECT * FROM researchdetails WHERE facultyName LIKE '%$facultyName%' ";
		}
		if($_SESSION['type3'] == 1)
		{
			$facultyName = $_SESSION['facultyNameForExcel'] ;
			$fromDate = $_SESSION['fromDate'] ;
		$toDate = $_SESSION['toDate'] ;
			$query = " SELECT * FROM researchdetails WHERE toDate >= '$fromDate' AND fromDate <= '$toDate' AND facultyName LIKE '%$facultyName%' ";
		}
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$output .= '<tr>
								<td>'.$row["facultyName"].'</td>
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
			                <th width="9%"><strong>Faculty Name</strong></th>  
			                <th width="13%"><strong>Research Title</strong></th>
			                <th width="9%"><strong>Start Date</strong></th>
							<th width="9%"><strong>End Date</strong></th>
							<th width="9%"><strong>Submitted To</strong></th>  
			                <th width="9%"><strong>Principle Investigator</strong></th>
			                <th width="9%"><strong>Co Investigator</strong></th>
			                <th width="7%"><strong>Proposed Amount</strong></th>
			                <th width="7%"><strong>Approved?</strong></th>
			                <th width="7%"><strong>Sanctioned Amount</strong></th>
							<th width="12%"><strong>Awards Won</strong></th>
		           		</tr>
	      			';  
	      $content .= fetchData();  
	      $content .= '</table>';  
	      $pdf->writeHTML($content);  
	      $pdf->Output('file.pdf', 'I');
    }
?>