<?php 
ob_start();
  session_start();
  if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
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
                 
					<div class="icon">
						<i style="font-size:20px" class="fa fa-edit"></i>
						<h3 class="box-title"><b>Provide no. of form responses for applying STTP/Workshop/FDP activities</b></h3>
						<br>
						<b><a href="menu.php?menu=3" style="font-size:15px;">STTP/Workshop/FDP Activities</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount_attend.php" style="font-size:15px;">&nbsp;No. of activity attended</a></b>	
					</div>
				
				
				</div><!-- /.box-header -->
				<!-- <div style="text-align:right">
					<a href="menu.php?menu=3 "> <u>Back to STTP/Workshop/FDP Attended/Organised Menu</u></a>
				</div>
				<br>
 -->                <!-- form start -->
                <form role="form" action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
						<label for="activity">Select activity: *</label>
	                    
						<select required name="activities">
							<option value="" disabled selected>Select your option</option>
							<option name="STTP" value="STTP">STTP</option>
							<option name="Workshop" value="Workshop">Workshop</option>
							<option name="FDP" value="FDP">FDP</option>
							<option name="QIP" value="QIP">QIP</option>
							<option name="SEMINAR" value="SEMINAR">SEMINAR</option>
							<option name="WEBINAR" value="WEBINAR">WEBINAR</option>
							<option name="REFRESHER" value="REFRESHER_PROGRAM">INDUCTION/REFRESHER PROGRAM</option>
						</select>
							
							<br><br>
	                    <label for="activity">Number of activities to be entered: *</label>
	                    <input type="number"  value="1" id="count" name="count" min="1" required />

	                    

                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" name="submit_count" id="submit" value="" class="btn btn-primary">Log Activity!</button>
                    <!-- <button type="submit" name="cancel" id="cancel" value="" class="btn btn-primary">Cancel</button> -->
                    <a href="menu.php?menu=3" name="cancel" id="cancel" value="" class="btn btn-primary" >Cancel</a>
					

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

					    // new added
					    // $status_activities=$_POST['status_activities'];
					    // $_SESSION['status_activities']=$status_activities;

					    // $no_of_hours=$_POST['no_of_hours'];
					    // $_SESSION['no_of_hours']=$no_of_hours;
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
					if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
					{
						header("location:1_add_paper_multiple_attend_hod.php");

					}
					else
						header("location:1_add_paper_multiple_attend.php");
					}
					}
						 
					if(isset($_POST['cancel']))
					  {
						  if($username == 'hodextc@somaiya.edu')
						{
							header("location:menu.php?menu=3");

						}
						else
							header("location:menu.php?menu=3");
						
					  }
						
					?>
                </form>
                
                </div>
              </div>
           </div>      
        </section>               
  </div><!-- /.content-wrapper -->        






    
    
<?php include_once('footer.php'); ?>
   
   
