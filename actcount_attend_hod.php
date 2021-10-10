<?php 
ob_start();
  session_start();
  if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
if(isset($_SESSION['type'])){
    if($_SESSION['type'] != 'hod'){
    //if not hod then send the user to login page
    session_destroy();
    header("location:index.php");
}
}
$_SESSION['currentTab'] = "sttp";

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
  
  /*$_SESSION["Username"] = 'test';
  $user = $_SESSION["Username"];
  echo $user;*/
  
 

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
			  			  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Form for applying activities</h3>

                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
					<label for="activity">Select activity:</label>
                    
					<select required name="activities">
						<option value="" disabled selected>Select your option</option>
						<option name="STTP" value="STTP">STTP</option>
						<option name="Workshop" value="Workshop">Workshop</option>
						<option name="FDP" value="FDP">FDP</option>
						</select>
						
						<br><br>
                    <label for="activity">Number of activities to be entered</label>
                    <input type="number" id="count" name="count"/>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" name="submit_count" id="submit" value="" class="btn btn-primary">Log Activity!</button>
                    <button type="submit" name="cancel" id="cancel" value="" class="btn btn-primary">Cancel</button>
					

                  </div>
				  <?php
				if(isset($_POST['submit_count'])){
					
				
				}
				?>
				  <?php
				  $username = $_SESSION['username'];
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
					 if(isset($_POST['submit_count']))
					  {
						$count = $_REQUEST["count"];
					$act = $_POST['activities'];
					$_SESSION['act_name']=$act;
					  }
					  else{
					  $count = 0;
					  }
					$_SESSION['count'] = $count;

					if($count <=0 )
					{
						$result="Don't enter zero or negative value<br>";
						echo '<div class="error">'.$result.'</div>';

					}
					else{
					if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu')
					{
						header("location:1_add_paper_multiple_attend_hod.php?alert=success");

					}
					else
						header("location:1_add_paper_multiple_attend_hod.php?alert=success");
					}
					}
						 
					if(isset($_POST['cancel']))
					  {
						  if($username == 'hodextc@somaiya.edu')
						{
							header("location:2_dashboard_attend_hod.php");

						}
						else
							header("location:2_dashboard_attend.php");
						
					  }
						
					?>
                </form>
                
                </div>
              </div>
           </div>      
        </section>               
  </div><!-- /.content-wrapper -->        






    
    
<?php include_once('footer.php'); ?>
   
   
