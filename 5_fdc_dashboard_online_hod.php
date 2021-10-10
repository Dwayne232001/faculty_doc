<?php
ob_start();
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
if(isset($_SESSION['type'])){
    if($_SESSION['type'] != 'hod'){
    //if not hod then send the user to login page
    session_destroy();
    header("location:index.php");
}
}
 //$_SESSION['active'] = 1;
$_SESSION['currentTab']="Online";

//connect to database
include("includes/connection.php");
include_once("includes/functions.php");

				$sql2 = "SELECT * from fdc_online_course";

				$result2 = mysqli_query($conn,$sql2);
				
				
				
				if(mysqli_num_rows($result2)>0  ){
					
					$sql = "SELECT * from online_course_attended where FDC_Y_N = 'yes' ";

					$result1 = mysqli_query($conn,$sql);

					if(mysqli_num_rows($result1)>0  ){
								//we have data to display 
								while($row =mysqli_fetch_assoc($result1)){
									
									
									$ocaid[] = $row['OC_A_ID'];
									
										
									
								}
						while($row =mysqli_fetch_assoc($result2)){
						$ocaid_fdc[] = $row['OC_A_ID'];
						
						
					}
					
					
					for ($i = 0; $i < count($ocaid) ;  $i++)
					{
						$equal = 0;
						for ($j = 0 ; $j < count($ocaid_fdc); $j++)
						{
							if($ocaid[$i] == $ocaid_fdc[$j])
							{
								$equal = 1;
								break;
											
							}
														
						}
						if($equal == 0)
							add_fdc($ocaid[$i]);
					}		
					}
					else
					{
						//$query = "SELECT * FROM fdc where Fac_ID ='".$_SESSION['Fac_ID']."';";
						$query = "SELECT * FROM fdc_online_course ";

						$result = mysqli_query($conn,$query);
					}
			
					
					
				}
				else if(mysqli_num_rows($result2) == 0  )
				{
					$rows = mysqli_num_rows($result2);
					$sql = "SELECT * from online_course_attended where FDC_Y_N = 'yes' ";

					$result1 = mysqli_query($conn,$sql);

					if(mysqli_num_rows($result1)>0  ){
								
								while($row =mysqli_fetch_assoc($result1)){
									
									$Fac_ID[] = $row['Fac_ID'];
									$ocaid[] = $row['OC_A_ID'];
									$Course_Name[] = $row['Course_Name'];

										
									
								}
						if(count($ocaid) != 0)		
						{						
							for ($i = 0; $i < count($ocaid) ;  $i++)
							{
								
								add($Fac_ID[$i],$ocaid[$i],$Course_Name[$i] );

								
							}
						}		
								
					}	
					else
					{
						 $query = "SELECT * FROM fdc_online_course ";
						$result = mysqli_query($conn,$query);
					}

					
				//	$_SESSION['pid'] = count($pid);
					
				}

				
	function add($Fac_ID, $ocaid, $Course_Name)
	{
		include("includes/connection.php");

		$sql="INSERT INTO fdc_online_course(Fac_ID, OC_A_ID, Course_Name) VALUES ('$Fac_ID','$ocaid','$Course_Name')";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	}
	
	function add_fdc($ocaid)
	{
		include("includes/connection.php");

		$sql_fdc = "select * from online_course_attended WHERE OC_A_ID = $ocaid";
		$result_fdc = mysqli_query($conn,$sql_fdc);
		
		if(mysqli_num_rows($result_fdc)>0  )
		{
								
			while($row =mysqli_fetch_assoc($result_fdc)){
									
									
				$Fac_ID = $row['Fac_ID'];
				$Course_Name = $row['Course_Name'];
							
									
			}
		}
		
		
		
		$sql="INSERT INTO fdc_online_course(Fac_ID, OC_A_ID, Course_Name) VALUES ('$Fac_ID','$ocaid','$Course_Name')";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	}

	$query = "SELECT * from fdc_online_course inner join facultydetails on fdc_online_course.Fac_ID = facultydetails.Fac_ID ";

$result = mysqli_query($conn,$query);



$successMessage="";
if(isset($_GET['alert'])){
    if($_GET['alert']=="success"){
        $successMessage='<div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record added successfully</strong>
        </div>';  

    } 
    elseif($_GET['alert']=="update"){
        $successMessage='<div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record updated successfully</strong>
        </div>';  

    }
    elseif($_GET['alert']=="delete"){
        $successMessage='<div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record deleted successfully</strong>
        </div>';  

    }
}
?>





<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php 
if($_SESSION['type'] == 'hod')
{
      include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
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


</style>



<div class="content-wrapper">
    <?php 
        {
        echo $successMessage;
    }
    ?>
    <section class="content">
          <div class="row">
            <div class="col-xs-12">
						  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header">
				<div class="icon">
					<i style="font-size:18px" class="fa fa-table"></i>
					<h2 class="box-title"><b>FDC details for Online/Offline Course Attended</b></h2>
					<br>
					<b><a href="menu.php?menu=5 " style="font-size:15px;">Online/Offline Course Attended</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;FDC Details</a></b>	
				</div>
                </div><!-- /.box-header -->
					
				<div style="text-align:right">
		<!--		<a href="menu.php?menu=1 " style="text-align:right"> <u>Back to Paper Publication/Presentation Menu</u></a>&nbsp;&nbsp; -->
                </div>
                <div class="box-body">
				<div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <thead>
            <tr>
                <th>Faculty</th>

                <th>Course</th>
				
                <th>Minutes No.</th>
                <th>Serial No.</th>
                <th>Period</th>
                <th>OD approved</th>
                <th>OD availed</th>
                <th>Fee Sanctioned</th>
                <th>Fee availed</th>
   				<th>Last Updated</th>
				
 				<th>FDC Approval Status</th>
              
                <th>Edit</th>
            <!--    <th>Delete</th> -->

            </tr>
        </thead>
        <?php
        if(mysqli_num_rows($result)>0 ){
            //we have data to display 
            while($row =mysqli_fetch_assoc($result)){
				
				
					
				
				
				if ($row['period'] == 0)
				{
					$ans1 = "vacational";
					
				}
				else if ($row['period'] == 1)
					$ans1 = "non vacational";
				
                echo "<tr>";
				echo "<td>".$row['F_NAME']."</td>";

                echo "<td>".$row['Course_Name']."</td>";
				
                echo "<td>".$row['min_no']."</td>";
                echo "<td>".$row['serial_no']."</td>";
                echo "<td>".$ans1."</td>";
                echo "<td>".$row['od_approv']."</td>";
                echo "<td>".$row['od_avail']."</td>";
                echo "<td>".$row['fee_sac']."</td>";
                echo "<td>".$row['fee_avail']."</td>";
	            echo "<td>".$row['last_added']."</td>";
			
                echo "<td>".$row['FDC_approved_disapproved']."</td>";


				$_SESSION['FDC_ID'] = $row['FDC_ID'];

				
               
				
                echo "<td>
                    <form action = '5_fdc_edit_onlineattend_hod.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
               /* echo "<td>
                    <form action = '5_fdc_delete.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";*/
                echo"</tr>";
				
            }
        }
        else{
            //if ther are no entries
            echo "<div class='alert alert-warning'>You have no fdc</div>";
        }
		
        ?>
        
    </table>
	
       
	</div>
                <a href="2_dashboard_hod_online_attended.php" type="button" class="btn btn-primary">Skip</a>
 

    </div>
	
              </div>
             </div>
            </div>
          </section>
    
</div>
   
    
      
    
<?php include_once('footer.php'); ?>
   
   