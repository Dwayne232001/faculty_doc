<?php
ob_start();
 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ?>
<script>
$(document).ready(function(){
  $(".1").attr("class","active");
});
</script>	
<?php 

function change()
{
	echo "SELECTED";
}

$_SESSION['currentTab']="iv";


?>

              <div class='box box-primary'>
			  			  			  <br/><br/><br/>

                <div class='box-header with-border'>
                 <i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Provide no. of form responses for Industrial Visit activities</b></h3>
					<br/>	<b><a href="menu.php?menu=10" style="font-size:15px;">Industrial Visit</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="IV.php?x=IV/select_menu/addcount.php" style="font-size:15px;">&nbsp;No. of Visits</a></b>	
				
				</div><!-- /.box-header -->
                <!-- form start -->
				<?php 
				//echo "&emsp;Logged In User: <b>".$_SESSION['loggedInUser']."</b>";
				?>
				<div style="text-align:center">
				</div>
				
                <form role='form' action='' method='POST'>
                  <div class='box-body'>
                    <div class='form-group'>
                    <label for='activity'>Number of Activity</label>
                    <input type='number' id='count' name='count'/>
                    &emsp;&emsp;

                 		<!-- ===== Select Activity==== -->
                 	<b>Select Activity:</b> 	
                    <select required name="activity">
  					<option value="attended" <?php if(isset($_GET['type'])){ if(strcmp($_GET['type'],"Attended")==0){change();}}?>>Attended</option>
  					<option value="organized"<?php if(isset($_GET['type'])){ if(strcmp($_GET['type'],"Organized")==0){change();}}?>>Organized</option>
  					</select>

                    
                  </div>
                  </div><!-- /.box-body -->

                  <div class='box-footer'>
                    <button type='submit' name='submit_count' id='submit' value='' class='btn btn-primary'>Log Activity!</button>
                    <button type='submit' name='cancel' id='cancel' value='' class='btn btn-primary'>Cancel</button>
                  </div>
				  
                </form>
                
                </div>
                <?php

				  	$username = $_SESSION['username'];
					if ($_SERVER["REQUEST_METHOD"] == "POST") 
						{
					 		if(isset($_POST['submit_count']))
					  		{
								$count = $_POST["count"];
					  		}
					  		else
					  		{
					  			$count = 0;
					  		}
							
							if($count <=0 )
							{
								$result="Don't enter zero or negative value<br>";
								echo '<div class="error">'.$result.'</div>';

							}
							else
							{	
								$_SESSION['activity']=$_POST['activity'];
									header("location:IV.php?x=IV/".$_SESSION['activity']."/form.php&count=".$count);
							}
						}
						 
					if(isset($_POST['cancel']))
					  	{
			
							header("location:IV.php?x=IV/select_menu/edit_menu.php");						
					  	}
						
					?>
		