<?php 
ob_start();
  session_start();
    if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab']="technical_review";

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

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
<style>
.error
{
	color:red;
	border:1px solid red;
	background-color:#ebcbd2;
	border-radius:10px;
	margin:5px;
	margin-left:5%;
	padding:5px;
	font-family:Arial, Helvetica, sans-serif;
	width:510px;
}

</style>
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
				<i style="font-size:20px" class="fa fa-edit"></i>

					<h3 class="box-title"><b>Provide no. of form responses for Paper Publication/Presentation activities</b></h3>
					<br><b><a href="menu.php?menu=2" style="font-size:15px;">Technical Paper Reviewed</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount_review.php" style="font-size:15px;">&nbsp;No. of Papers</a></b>	

                </div><!-- /.box-header -->
				<div style="text-align:right">
			<!--		<a href="menu.php?menu=2 "> <u>Back to Technical Papers Reviewed Menu</u></a> -->
				</div>
                <!-- form start -->
				<?php //echo $_SESSION['username'];?>
                <form role="form" action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                    <label for="activity">Number of technical papers reviewed to be entered</label>
                    <input type="number"   value="1" id="count" name="count"/>
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
					if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{
						header("location:1_add_paper_multiple_review_hod.php?alert=success");

					}
					else
						header("location:1_add_paper_multiple_review.php?alert=success");
					}
					}
						 
					if(isset($_POST['cancel']))
					  {
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						{
							header("location:2_dashboard_hod_review.php");

						}
						else
							header("location:2_dashboard_review.php");
						
					  }
						
					?>
                </form>
                
                </div>
              </div>
           </div>      
        </section>               
  </div><!-- /.content-wrapper -->        






    
    
<?php include_once('footer.php'); ?>
   
   
