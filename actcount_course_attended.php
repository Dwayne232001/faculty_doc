<?php 
ob_start();
  session_start();
  if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
  include_once('head.php'); 
  include_once('header.php'); 
  $_SESSION['currentTab']="Online";

  if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

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
					<div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Provide no. of form responses for Online/Offline Course Attended Activities</b></h3>
					<br>
					<b><a href="menu.php?menu=5" style="font-size:15px;">Online/Offline Course</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount_course_attended.php" style="font-size:15px;">&nbsp;No. of Online/Offline Courses</a></b>	
					</div>
                </div><!-- /.box-header -->
				<div style="text-align:right">
				<!-- <a href="menu.php?menu=5 "> <u>Back to Online Course Attended Activities Menu</u></a> -->
				</div>
                <!-- form start -->
                <form role="form" action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                    <label for="activity">Number of attended courses to be entered *</label>
                    <input type="number" value="1" id="count" value="1" name="count"/>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" name="submit_count" id="submit" value="" class="btn btn-primary">Log Activity!</button>
                    <button type="submit" name="cancel" id="cancel" value="" class="btn btn-primary">Cancel</button>


                  </div>
				  <?php
				  $username = $_SESSION['username'];
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
					 if(isset($_POST['submit_count']))
					  {
						$count = $_REQUEST["count"];
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
					if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu')
					{
						header("location:1_add_course_attended_hod.php?alert=success");

					}
					else
						header("location:1_add_course_attended.php?alert=success");
					}
					}
						 
					if(isset($_POST['cancel']))
					  {
						  if($username == 'hodextc@somaiya.edu')
						{
							header("location:menu.php?menu=5");

						}
						else
							header("location:menu.php?menu=5");
						
					  }
						
					?>
                </form>
                
                </div>
              </div>
           </div>      
        </section>               
  </div><!-- /.content-wrapper -->        






    
    
<?php include_once('footer.php'); ?>
   
   
