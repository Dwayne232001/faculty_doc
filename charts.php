<?php
ob_start();
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp";

include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];

include_once('head.php');
include_once('header.php'); 

if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
  {
      include_once('sidebar_hod.php');

  }
  else
    include_once('sidebar.php');
 ?>
<?php 
if (isset($_POST['submit'])){
  $type=$_POST['type'];
  $activity=$_POST['activity'];

  $_SESSION['type']=$type;
  $_SESSION['activity']=$activity;


  if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu')
  {
    header("location:charts_display_hod.php");
  }
  else
    header("location:charts_display.php");      
}

?>
<div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
          <div class="row">

            <div class="col-md-8">
              <!-- CHART -->
			  			  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><strong style="margin-left: 10px">Charts</strong></h3>
                  <div style="margin-left: 10px"><h5><a href="list_of_activities_user.php">Dashboard</a> >> <a href="menu.php?menu=3">STTP/Workshop/FDP Activities</a> >> <a href="charts.php">Charts</a></h5></div>
                </div>
                <!-- <div style="text-align:right">
                    <a href="menu.php?menu=3 "> <u>Back to STTP/Workshop/FDP Attended/Organised Menu</u></a>
                </div> -->
                <!-- <br> -->
                <form method="POST" action="" role="form">
                  <div class="box-body">
                    <div class="form-group col-md-6">
                      <label for="activity">Activity :</label>
                      <select required name="activity" class="form-control" id="activity">
                        <option disabled selected>Select your option:</option>
                        <option value="STTP">STTP</option>
                        <option value="Workshop">Workshop</option>
                        <option value="FDP">FDP</option>
                      </select>
                    </div>

                    <div class="form-group col-md-6">
                      <label for="type">Activity type :</label>
                      <select required name="type" class="form-control" id="type">
                        <option disabled selected>Select your option:</option>
                        <option value="attended">Attended</option>
                        <option value="organised">Organised</option>
                      </select>
                    </div>

                    <button class="btn btn-primary" name="submit" style="margin-left: 15px;">Display Chart</button>
                  </div>
                </form>
              </div>
             </div>
            </div>
           </section>
          </div>    


 <?php include_once('footer.php'); ?>