<?php
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
//connect to database
include("includes/connection.php");
include_once("includes/functions.php");
$fid = $_SESSION['Fac_ID'];

$ans = 'yes';

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

$sql2 = "SELECT * from fdc_online_course";

				$result2 = mysqli_query($conn,$sql2);
				
				
				
				if(mysqli_num_rows($result2)>0  ){
					$sql = "SELECT * from online_course_attended where FDC_Y_N = 'yes' && Fac_ID = '$fid'";

					$result1 = mysqli_query($conn,$sql);

					if(mysqli_num_rows($result1)>0  ){
								//we have data to display 
								while($row =mysqli_fetch_assoc($result1)){
									
									
									$oaid[] = $row['OC_A_ID'];
									
										
									
								}
						while($row =mysqli_fetch_assoc($result2)){
						$oaid_fdc[] = $row['OC_A_ID'];
						
						
					}
					
					for ($i = 0; $i < count($oaid) ;  $i++)
					{
						$equal = 0;
						for ($j = 0 ; $j < count($oaid_fdc); $j++)
						{
							if($oaid[$i] == $oaid_fdc[$j])
							{
								$equal = 1;
								break;
											
							}
														
						}
						if($equal == 0)
							add_fdc($oaid[$i]);
					}		
								
					}
					else
					{
						$query = "SELECT * FROM fdc_online_course where Fac_ID ='".$_SESSION['Fac_ID']."';";
						$result = mysqli_query($conn,$query);
					}
			
					
				}
				else if(mysqli_num_rows($result2) == 0  )
				{
					$rows = mysqli_num_rows($result2);
					$sql = "SELECT * from online_course_attended where FDC_Y_N = 'yes' && Fac_ID = '$fid' ";

					$result1 = mysqli_query($conn,$sql);

					if(mysqli_num_rows($result1)>0  ){
								
								while($row =mysqli_fetch_assoc($result1)){
									
									$Fac_ID[] = $row['Fac_ID'];
									$oaid[] = $row['OC_A_ID'];
									$course_name[] = $row['Course_Name'];

										
									
								}
								if(count($oaid) != 0)		
								{	
								for ($i = 0; $i < count($oaid) ;  $i++)
								{
									
									add($Fac_ID[$i],$oaid[$i],$course_name[$i] );

									
								}
								}
					}
					else
					{
						 $query = "SELECT * FROM fdc_online_course where Fac_ID ='".$_SESSION['Fac_ID']."';";
						$result = mysqli_query($conn,$query);

					}
					
					
					//$_SESSION['oaid'] = count($oaid);
					
				}
				
function add_fdc($oaid)
	{
		include("includes/connection.php");

		$sql_fdc = "select * from online_course_attended WHERE OC_A_ID = $oaid";
		$result_fdc = mysqli_query($conn,$sql_fdc);
		
		if(mysqli_num_rows($result_fdc)>0  )
		{
								
			while($row =mysqli_fetch_assoc($result_fdc)){
									
									
				$Fac_ID = $row['Fac_ID'];
				$Course_Name = $row['Course_Name'];
							
									
			}
		}
			
		$sql="INSERT INTO fdc_online_course(Fac_ID, OC_A_ID, Course_Name) VALUES ('$Fac_ID','$oaid','$Course_Name')";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	}
	function add($Fac_ID, $oaid, $Course_Name)
	{
		include("includes/connection.php");

		$sql="INSERT INTO fdc_online_course(Fac_ID, OC_A_ID, Course_Name) VALUES ('$Fac_ID','$oaid','$Course_Name')";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	}

$fid = $_SESSION['Fac_ID'];

/*$query1 = "SELECT * FROM online_course_attended where FDC_Y_N = 'no'";
		$result1 = mysqli_query($conn,$query1);
		if(mysqli_num_rows($result1)>0 ){
					//we have data to display 
					while($row =mysqli_fetch_assoc($result1)){
						$paperTitle = $row['Course_Name'];
						
						$query2 = "delete * FROM fdc_online_course where Fac_ID = $fid and Course_Name = '$paperTitle'";
						$result2 = mysqli_query($conn,$query2);
						
					}
		}*/
//query and result




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

              <div class="box">
                <div class="box-header">
                  <h2 class="box-title">FDC details for Online/Offline Courses</h2>
                </div><!-- /.box-header -->
				<div style="text-align:right">
				<a href="menu.php?menu=5 " style="text-align:right"> <u>Back to Online Course Activity Menu</u></a> 
                </div>
                <div class="box-body">
				<div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <thead>
            <tr>
                <th>Activity</th>
                <!-- <th>Activity ID</th> -->
                <th>Minutes No.</th>
                <th>Serial No.</th>
                <th>Period</th>
                <th>OD approved</th>
                <th>OD availed</th>
                <th>Fee Sanctioned</th>
                <th>Fee availed</th>
                <th>Approval Status</th>
                <th>Edit</th>
                <!-- <th>Delete</th> -->

            </tr>
        </thead>
        <?php
		$query = "SELECT * FROM fdc_online_course where Fac_ID ='".$_SESSION['Fac_ID']."';";
		$result = mysqli_query($conn,$query);
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
                echo "<td>".$row['Course_Name']."</td>";
				// echo "<td>".$row['A_ID']."</td>";

                echo "<td>".$row['min_no']."</td>";
                echo "<td>".$row['serial_no']."</td>";
                echo "<td>".$ans1."</td>";
                echo "<td>".$row['od_approv']."</td>";
                echo "<td>".$row['od_avail']."</td>";
                echo "<td>".$row['fee_sac']."</td>";
                echo "<td>".$row['fee_avail']."</td>";
                echo "<td>".$row['FDC_approved_disapproved']."</td>";

				$_SESSION['FDC_ID'] = $row['FDC_ID'];

				
               if($row['FDC_approved_disapproved']==='disapproved'){
				
					echo "<td>
						<form action = '5_fdc_edit.php' method = 'POST'>
							<input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
							<button type = 'submit' class = 'btn btn-primary btn-sm'>
								<span class='glyphicon glyphicon-edit'></span>
							</button>
						</form>
					</td>";
				}
				else if($row['FDC_approved_disapproved']==='approved')
				{
					echo "<td>
                    <form action = '5_fdc_edit_attend.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm' disabled='disabled'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                // echo "<td>
                //     <form action = '5_fdc_delete_attend.php' method = 'POST'>
                //         <input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
                //         <button type = 'submit' class = 'btn btn-primary btn-sm' disabled='disabled'>
                //             <span class='glyphicon glyphicon-trash'></span>
                //         </button>
                //     </form>
                // </td>";
                echo"</tr>";
               }
			   else{
                echo "<td>
                    <form action = '5_fdc_edit_attend.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                // echo "<td>
                //     <form action = '5_fdc_delete_attend.php' method = 'POST'>
                //         <input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
                //         <button type = 'submit' class = 'btn btn-primary btn-sm'>
                //             <span class='glyphicon glyphicon-trash'></span>
                //         </button>
                //     </form>
                // </td>";
                echo"</tr>";
               }	
            }
        }
        else{
            //if ther are no entries
            echo "<div class='alert alert-warning'>You have no fdc</div>";
        }
		
        ?>
        
    </table>
	
       
	</div>
        <a href="2_dashboard_online_attended.php" type="button" class="btn btn-primary">Skip</a>
    </div>
    
	
              </div>
             </div>
            </div>
          </section>
    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
