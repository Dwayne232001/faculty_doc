
<?php
ob_start();
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}

if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod')
    //if not hod then send the user to login page
    header("location:index.php");
}

//$_SESSION['currentTab'] = "paper";
 include_once('head.php'); 
include_once('header.php'); 

if($_SESSION['type'] == 'hod')
{
      include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
        include_once('sidebar_cod.php');
}
else{
    include_once('sidebar.php');
}


//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']='faculty';
}

//query and result
/*$query = "SELECT P_ID, Fac_ID,Paper_title,Paper_type,Paper_N_I,Paper_category,Date_from,Date_to
,Location,paper_path,certificate_path,report_path,Paper_co_authors,volume FROM faculty";*/
$query = "SELECT * from facultydetails WHERE type='".$_SESSION['type']."' ORDER BY F_NAME";
$result = mysqli_query($conn,$query);



$successMessage="";
if(isset($_GET['alert'])){
    if($_GET['alert']=="success"){
        $successMessage='<br/><br/><br/><div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record added successfully</strong>
        </div>';  

    }
    elseif($_GET['alert']=="update"){
        $successMessage='<br/><br/><br/><div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record updated successfully</strong>
        </div>';  

    }
    elseif($_GET['alert']=="delete"){
        $successMessage='<br/><br/><br/><div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record deleted successfully</strong>
        </div>';  

    }
}
?>

<style>
div.scroll
{
overflow:scroll;

}


</style>
<script type="text/javascript">
	function preventBack() { window.history.forward(); 
	
	}
	setTimeout("preventBack()",0);
	
	window.onunload = function() {null};
</script>


	
<div class="content-wrapper">
   <?php 
        {
        echo $successMessage;
    }
	$display = 0;
	if($_SESSION['type'] == 'hod')
	{
		$display = 1;
	}
	else if($_SESSION['type'] == 'cod' ||$_SESSION['type'] == 'com' )
	{
		$display = 2;
	}
    ?>
    <section class="content">
          <div class="row">
            <div class="col-xs-12">
<?php if(!isset($_GET['alert'])){ ?>
           <br/><br/><br/>
			<?php } ?>
              <div class="box box-primary">
                <div class="box-header with-border">
					<div class="icon">
					<i style="font-size:18px" class="fa fa-table"></i>
					<h2 class="box-title"><b>Faculty Details</b></h2>
					<br>
					</div>
                </div><!-- /.box-header -->
				<div style="text-align:right">
                </div>
                <div class="box-body">
				<div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <thead>
            <tr>
			    <th>Faculty ID</th>
				<th>Faculty Name</th>
                <th>Alternate Faculty Name</th>
                <th>Faculty Department</th>
                <th>Email ID</th>
                <th>Mobile Number</th>
                <th>Type</th>
               
			<?php 
				if($_SESSION['type'] == 'hod')
				{
			?>			
                <th>Edit</th>
                <th>Delete</th>
		<?php }?>
            </tr>
        </thead>
        <?php
				$_SESSION['rows'] = mysqli_num_rows($result);

        if(mysqli_num_rows($result)>0){
            //we have data to display 
            while($row =mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['Fac_ID']."</td>";
				echo "<td>".$row['F_NAME']."</td>";
				echo "<td>".$row['alt_name']."</td>";
                echo "<td>".$row['Dept']."</td>";
                echo "<td>".$row['Email']."</td>";
                echo "<td>".$row['Mobile']."</td>";
                echo "<td>".$row['type']."</td>";
				
					echo "<td>
						<form action = 'editdetails.php' method = 'POST'>
							<input type = 'hidden' name = 'id' value = '".$row['Fac_ID']."'>
							<button type = 'submit' class = 'btn btn-primary btn-sm'>
								<span class='glyphicon glyphicon-edit'></span>
							</button>
						</form>
					</td>";
				
					
					echo "<td>
							<form action = 'deletedetails.php' method = 'POST'>
								<input type = 'hidden' name = 'id' value = '".$row['Fac_ID']."'>
								
								<button type = 'submit' class = 'btn btn-primary btn-sm' >
									<span class='glyphicon glyphicon-trash'></span>
								</button>
							</form>
						</td>";

					echo"</tr>";
            }
        }
        else{
            //if ther are no entries
            echo "<div class='alert alert-warning'>You have no papers</div>";
        }
				
        ?>
        
    </table>
	
       
	</div>
		<?php if ($_SESSION['rows'] > 0) {?>

	            <div class="text-left"><a href="adddetails.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Details</span></a>
	          
			   <a href="export_to_excel_facultydetails.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 
	<?php }else {?>

	            <div class="text-left"><a href="adddetails.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Details</span></a>

		<?php } ?>

    </div>
	
	
	
              </div>
             </div>
            </div>
          </section>
    
</div>
  
    
<?php include_once('footer.php'); ?>
   
   