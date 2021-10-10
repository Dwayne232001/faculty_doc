<?php 
ob_start();
session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}

if(isset($_SESSION['type'])){
  if($_SESSION['type'] != 'hod' && $_SESSION['type']!='cod' && $_SESSION['type']!='com'){
    //if not hod then send the user to login page
    header("location:index.php");
  }
}

$_SESSION['currentTab'] = "faculty";

include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php
if($_SESSION['type'] == 'hod')
{
    include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ){
    include_once('sidebar_cod.php');
}
else{
  include_once('sidebar.php');
}
 ?>
<?php 
include_once("includes/functions.php");

//include custom functions files 
include_once("includes/scripting.php");
include_once("includes/connection.php");

$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}

?>

<div class="content-wrapper">

<!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-xs-12">
              <!-- general form elements -->
              <br/><br/><br/>
 
              <div class="box box-primary">
                <div class="box-header with-border" >
          <div class="icon">
          <i style="font-size:18px" class="fa fa-signal"></i>
          <h3 class="box-title"><b>Yearly Analysis</b></h3>
          <br>

          </div>
                </div><!-- /.box-header -->
        <div style="text-align:right">
      <!--  <a href="menu.php?menu=1 " style="text-align:right"> <u>Back to Paper Publication/Presentation Menu</u></a>&nbsp&nbsp -->
                </div>
        <!-- form start -->
                <form role="form" action = "facultyinteraction_chart.php" method="post">
                  <div class="box-body">
                    <div class="form-group">
                        <label for="InputName">Select Faculty Name :</label><br>
                        <select id='search' name='name' class="form-control" style="width: 220px;">
                          <option value=""></option>
                          <option>All Faculty</option>
                        <?php
                          $sql= " SELECT * FROM facultydetails WHERE facultydetails.Dept='".$_SESSION['Dept']."' AND type='faculty' ORDER BY F_NAME";
                          $result= mysqli_query($conn,$sql);
                          while($row=mysqli_fetch_array($result))
                          {
                            echo"<option>".$row['F_NAME']."</option>";
                          }
                        ?>
                        </select>
                    </div>            
                                       
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="count" value = "Count Invited Lectures"></input>
                    <a href="view_invited_hod_lec.php" type="button" class="btn btn-primary">Back to View Mode </a>

                  </div>
                </form>
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
<?php 
  include_once("footer.php");
?>