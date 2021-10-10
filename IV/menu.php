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

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-6">
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Activity Menu</h3>

                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="" method="POST">
                  

                  <div class="box-body">
              
                  </div>
				  <?php
				  $username = $_SESSION['username'];
				  $menu = 0;
				  $menu = $_GET['menu'];
					if($_GET['menu'] != 0){
					
						switch ($menu) {
						case 1:
						  if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
							  <li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Paper Publication</strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									  <ul class="" style="list-style: none;">
										<li class=""><a href="actcount.php"><i class="fa fa-circle-o"></i> Add Paper</a></li>
										<li><a href="2_dashboard_hod.php"><i class="fa fa-circle-o"></i> View/Edit Activity</a></li>
										<li><a href="5_fdc_dashboard_hod.php"><i class="fa fa-circle-o"></i> FDC details</a></li>
										<li><a href="count_all.php"><i class="fa fa-circle-o"></i> Analysis</a></li>
									  </ul>
									</li>
						<?php  }
						  else
						  {
						
						?>
						
						

									<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Paper Publication</strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									  <ul class="" style="list-style: none;">
										<li class=""><a href="actcount.php"><i class="fa fa-circle-o"></i> Add Paper</a></li>
										<li><a href="2_dashboard.php"><i class="fa fa-circle-o"></i> View/Edit Activity</a></li>
										<li><a href="5_fdc_dashboard.php"><i class="fa fa-circle-o"></i> FDC details</a></li>
										<li><a href="count_your.php"><i class="fa fa-circle-o"></i> Analysis</a></li>
									  </ul>
									</li>
							
													

						<?php
						  }
						break;
						
						
					}
						
						
						
				   } else {
				   echo "failed";
				   }
					?>
                </form>
                
                </div>
              </div>
           </div>      
        </section>               
  </div><!-- /.content-wrapper -->        






    
    
<?php include_once('footer.php'); ?>
   
   
