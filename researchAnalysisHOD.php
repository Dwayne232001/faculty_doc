<?php 
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

  $_SESSION['fromDate']="";
  $_SESSION['toDate']="";
include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php $_SESSION['currentTab']="research"?>
<?php 

if($_SESSION['type'] == 'hod')
{
	  include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com') {
		include_once('sidebar_cod.php');
}
else{
	include_once('sidebar.php');
}
 ?>
 <style>
div.scroll
{
overflow:scroll;
}

.error
{
	color:red;
	border:1px solid red;
	background-color:#ebcbd2;
	border-radius:10px;
	margin:5px;
	padding:5px;
	font-family:Arial, Helvetica, sans-serif;
	width:510px;
}


</style>
<!--<div class="content-wrapper">
	<form action = "researchAnalysisHOD.php" method="post">
		<div>
			<br>Date from : <input type="date" id="fromDate" name="fromDate">
		</div>
		<div>
			<br>Date to : <input type="date" id="toDate" name="toDate">
		</div>
		<div>
			<br>Faculty Name : <input type="text" name="facultyName" id="facultyName">
		</div>
		<div>
			<input type="submit" id="getDetails" name="getDetails" value="Count Research Details">
		</div>
	</form>
</div>-->

<!--<?php ?>-->

 <div class="content-wrapper">
    <!--<?php 
    /*  {
        	echo $successMessage;
    	}
    */?>-->
    <section class="content">
          <div class="row">
            <div class="col-xs-12">
						  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
					<div class="icon">
					<i style="font-size:18px" class="fa fa-signal"></i>
					<h3 class="box-title"><b>Research Analysis</b></h3>
					<br>
					</div>    
				
				</div><!-- /.box-header -->
				<div style="text-align:right">
			<!--	<a href="menu.php?menu=11 .php" style="text-align:right"> <u>Back to Research Menu</u></a> -->
                </div>
                <form action = "researchAnalysisHOD.php" method="post">
				<div class="box-body">
				<div class="form-group">
				<div class="form-group col-md-8">
                        <label for="InputName">Select Faculty Name :</label><br>
                        <select id='search' name='facultyName' class="form-control" style="width: 220px;">
                          <option value=""></option>
                        <?php
                          $sql= " SELECT * FROM facultydetails WHERE facultydetails.Dept='".$_SESSION['Dept']."' AND type='faculty' ORDER BY F_NAME ";
                          $result= mysqli_query($conn,$sql);
                          while($row=mysqli_fetch_array($result))
                          {
                            echo"<option>".$row['F_NAME']."</option>";
                          }
                        ?>
                        </select>
                    </div>					
                    <div class="form-group col-md-8">
                        <label for="InputDateFrom">Date from :</label>
						<input type="date" name="fromDate" class="form-control " style="width:220px;"></div>
					<div class="form-group col-md-8">

 						<label for="InputDateTo">Date To :</label>
						<input type="date" name="toDate" class="form-control " style="width:220px;"></p>
                    </div> 
					</div>
				</div>
					<div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="getDetails" value = "Count Research Details"></input>
                    <a href="researchViewHOD.php" type="button" class="btn btn-primary">Back to View Mode </a>

                  </div>
				</form>
 
				
        <!-- <thead>
            <tr>
            	<th>Faculty Name</th>
                <th>Research Title</th>
				<th>Start Date</th>
                <th>End Date</th>
                <th>Submitted To</th>
                <th>Principle Investigator</th>
				<th>Co Investigator</th>
                <th>Proposed Amount</th>
                <th>Approved ?</th>
                <th>Amount Sanctioned</th>
				<th>Awards Won</th>
			</tr>
        </thead> -->
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		$_SESSION['facultyName']  = $_POST['facultyName'];
				
		if(isset($_POST['getDetails']))
		{
			$Fac_ID = $_SESSION['Fac_ID'];
			$dateIsSet = 0;
			$dateSetError = 0;
			$fromDate = "";
			$toDate = "";
			$successMessage = "";
			$result = "";
			$type1 = $type2 = $type3 = 0;
			if(isset($_POST['getDetails']))
			{
				
				
				if (!empty($_POST['fromDate']) && !empty($_POST['toDate']) && empty($_POST['facultyName']))
				{
					$dateIsSet = 1;
					$type1 = 1;
					$_SESSION['type3'] = 0;
					$_SESSION['type2'] = 0;
					$_SESSION['type1'] = 1;
					if((strtotime($_POST['fromDate']))>(strtotime($_POST['toDate'])))
					{
						/*echo $_POST['fromDate']; echo "<br>";
						echo $_POST['toDate']; echo "<br>";
						echo "Date is set wrongly!"; echo "<br>";*/
						$dateSetError = 1;
						$successMessage = "Enter proper date";
					}
					else
					{
						$_SESSION['fromDate'] = $_POST['fromDate'];
						$_SESSION['toDate'] = $_POST['toDate'];
						$fromDate =  $_SESSION['fromDate'] ;
						$toDate = $_SESSION['toDate'] ;

						/*$conn = mysqli_connect("localhost","root","","department");
						if (mysqli_connect_errno())
		    				echo "Failed to connect to MySQL: " . mysqli_connect_error();*/

						$query = " SELECT * FROM researchdetails WHERE toDate >= '$fromDate' AND fromDate <= '$toDate' /*AND toDate >= '$fromDate' AND toDate <= '$toDate' AND facultyName IN ( SELECT F_NAME FROM facultydetails WHERE Fac_ID = $Fac_ID )*/";
						$result = mysqli_query($conn,$query);
						if(mysqli_num_rows($result)>0)
						{
							$count = mysqli_num_rows($result);
							//we have data to display
							
	echo "<div class='scroll'>
			<table  class='table table-stripped table-bordered ' id = 'example1'> 	
							
		<thead>
            <tr>
            	<th>Faculty Name</th>
                <th>Research Title</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Number of days</th>
                <th>Submitted To</th>
                <th>Principle Investigator</th>
				<th>Co Investigator</th>
                <th>Proposed Amount</th>
                <th>Approved ?</th>
                <th>Amount Sanctioned</th>
				<th>Awards Won</th>
			</tr>
        </thead>";
							while($row =mysqli_fetch_assoc($result))
							{
								echo "<tr>";
								echo "<td>".$row['facultyName']."</td>";
								echo "<td>".$row['researchTitle']."</td>";
								echo "<td>".$row['fromDate']."</td>";
								echo "<td>".$row['toDate']."</td>";
								echo "<td>".$row['noofdays']."</td>";
								echo "<td>".$row['submittedTo']."</td>";
								echo "<td>".$row['principleInvestigator']."</td>";
								echo "<td>".$row['coInvestigator']."</td>";
								echo "<td>".$row['proposedAmount']."</td>";
								echo "<td>".$row['radioApproval']."</td>";
								echo "<td>".$row['amountSanctioned']."</td>";
								echo "<td>".$row['awardsWon']."</td>";

								$_SESSION['researchId'] = $row['researchId'];
								echo"</tr>";
							}
							echo "</table></div>";	
							if($count > 0)
								{ ?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
								<a href="researchAnalysisHOD-ExportToExcel.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a>
							
							<?php	}
						}
						else
						{
							//if ther are no entries
							echo "<div class='error'>No Records to Display</div>";
						}
					}
				}
				else if(!empty($_POST['facultyName']) && empty($_POST['fromDate']) && empty($_POST['toDate']) )
				{
					$dateIsSet = 1;
					$type2 = 1;
					$_SESSION['type3'] = 0;
					$_SESSION['type2'] = 1;
					$_SESSION['type1'] = 0;
					$to_date = date("Y/m/d");
					$prevyear=date("Y")-1;
					$from_date=$prevyear.'/06/01';
					//$_SESSION['facultyNameForExcel'] = 
					/*if((strtotime($_POST['fromDate']))>(strtotime($_POST['toDate'])))
					{
						echo $_POST['fromDate']; echo "<br>";
						echo $_POST['toDate']; echo "<br>";
						echo "Date is set wrongly!"; echo "<br>";
						$dateSetError = 1;
						$successMessage = "Enter proper date";
					}*/
					//else
					//{
						/*$_SESSION['fromDate'] = $_POST['fromDate'];
						$_SESSION['toDate'] = $_POST['toDate'];
						$fromDate =  $_SESSION['fromDate'] ;
						$toDate = $_SESSION['toDate'] ;*/

						$facultyName = $_POST['facultyName'];
						$_SESSION['facultyNameForExcel'] = $facultyName;

						/*$conn = mysqli_connect("localhost","root","","department");
						if (mysqli_connect_errno())
		    				echo "Failed to connect to MySQL: " . mysqli_connect_error();*/

						$query = "SELECT * from researchdetails inner join facultydetails on researchdetails.fac_id = facultydetails.Fac_ID and facultydetails.F_NAME like '%$facultyName%' and researchdetails.fromDate>= '$from_date' and researchdetails.toDate <= '$to_date'";
						$result = mysqli_query($conn,$query);
						if(mysqli_num_rows($result)>0)
						{
							$count = mysqli_num_rows($result);
							//we have data to display
							echo "<div class='scroll'>
			<table  class='table table-stripped table-bordered ' id = 'example1'> 
			<thead>
            <tr>
            	<th>Faculty Name</th>
                <th>Research Title</th>
				<th>Start Date</th>
                <th>End Date</th>
                <th>Submitted To</th>
                <th>Approved ?</th>
			</tr>
        </thead>";
							while($row =mysqli_fetch_assoc($result))
							{
								echo "<tr>";
								echo "<td>".$row['facultyName']."</td>";
								echo "<td>".$row['researchTitle']."</td>";
								echo "<td>".$row['fromDate']."</td>";
								echo "<td>".$row['toDate']."</td>";
								echo "<td>".$row['submittedTo']."</td>";
								echo "<td>".$row['radioApproval']."</td>";

								$_SESSION['researchId'] = $row['researchId'];
								echo"</tr>";
							}
							echo "</table></div>";	
							if($count > 0)
								{ ?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
								<a href="researchAnalysisHOD-ExportToExcel.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a>
							
							<?php	}
						}
						else
						{
							//if ther are no entries
							echo "<div class='error'>No records to display</div>";
						}
					//}
				}
				else if (!empty($_POST['fromDate']) && !empty($_POST['toDate']) && !empty($_POST['facultyName']))
				{
					$type3 = 1;
					$_SESSION['type3'] = 1;
					$_SESSION['type2'] = 0;
					$_SESSION['type1'] = 0;
					if((strtotime($_POST['fromDate']))>(strtotime($_POST['toDate'])))
					{
						/*echo $_POST['fromDate']; echo "<br>";
						echo $_POST['toDate']; echo "<br>";
						echo "Date is set wrongly!"; echo "<br>";*/
						$dateSetError = 1;
						$successMessage = "Enter proper date";
					}
					else
					{
						$_SESSION['fromDate'] = $_POST['fromDate'];
						$_SESSION['toDate'] = $_POST['toDate'];
						$fromDate =  $_SESSION['fromDate'];
						$toDate = $_SESSION['toDate'];
						$facultyName = $_POST['facultyName'];
						$_SESSION['facultyNameForExcel'] = $facultyName;

						/*$conn = mysqli_connect("localhost","root","","department");
						if (mysqli_connect_errno())
		    				echo "Failed to connect to MySQL: " . mysqli_connect_error();*/

						$query = " SELECT * FROM researchdetails WHERE toDate >= '$fromDate' AND fromDate <= '$toDate' AND facultyName LIKE '%$facultyName%' ;";
						$result = mysqli_query($conn,$query);
						if(mysqli_num_rows($result)>0)
						{
							$count = mysqli_num_rows($result);
							//we have data to display
							echo "<div class='scroll'>
			<table  class='table table-stripped table-bordered ' id = 'example1'> 
			<thead>
            <tr>
            	<th>Faculty Name</th>
                <th>Research Title</th>
				<th>Start Date</th>
                <th>End Date</th>
                <th>Submitted To</th>
                <th>Approved ?</th>
			</tr>
        </thead>";
							while($row =mysqli_fetch_assoc($result))
							{
								echo "<tr>";
								echo "<td>".$row['facultyName']."</td>";
								echo "<td>".$row['researchTitle']."</td>";
								echo "<td>".$row['fromDate']."</td>";
								echo "<td>".$row['toDate']."</td>";
								echo "<td>".$row['submittedTo']."</td>";
								echo "<td>".$row['radioApproval']."</td>";

								$_SESSION['researchId'] = $row['researchId'];
								echo"</tr>";
							}
							echo "</table></div>";	
							if($count > 0)
								{ ?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="print" onclick="window.print();">Print</button>
								<a href="researchAnalysisHOD-ExportToExcel.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a>
							
							<?php	}
						}
						else
						{
							//if ther are no entries
							echo "<div class='error'>No Records to Display</div>";
						}
					}
				}
			}
		}
	}
 ?>
	
	</div>
		</div>
             </div>
            </div>
          </section>
    
</div>



	<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
  $("#search").chosen();
</script>
</head>    

<?php include_once('footer.php'); ?>