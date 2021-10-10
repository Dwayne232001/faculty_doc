<?php 
ob_start();
  session_start();
  if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab']= "organised_guest";

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
            <div class="col-md-6">
              <!-- general form elements -->
			  			  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
					<div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Provide no. of form responses for Guest Lecture Organised</b></h3>
					<br>
					<b><a href="menu.php?menu=4" style="font-size:15px;">Guest Lecture Organised</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="orglec.php" style="font-size:15px;">&nbsp;No. of Guest Lecture Organised</a></b>	
					</div>
                </div><!-- /.box-header -->
				<div style="text-align:right">
				<!--				<a href="menu.php?menu=4 "> <u>Back to Invited for/Organised Guest Lecture Menu</u></a> -->
				</div>
                <!-- form start -->
				<?php //echo $_SESSION['username'];?>
                <form role="form" action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                    <label for="activity">Number of guest lectures to be entered *</label>
                    <input class= "required" type="number" id="count" name="count" value="1" min="1" onkeypress= "return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"/>
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
						header("location:organised_guest_hod.php?alert=success");

					}
					else
						header("location:organised_guest.php?alert=success");
					}
					}
						 
					if(isset($_POST['cancel']))
					  {
					if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu')
						{
							header("location:menu.php?menu=4");

						}
						else
							header("location:menu.php?menu=4");
						
					  }
						
					?>
                </form>
                
                </div>
              </div>
           </div>      
        </section>               
  </div><!-- /.content-wrapper -->        






    
    
<?php include_once('footer.php'); ?>
   
   
