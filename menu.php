<?php 
ob_start();
  session_start();
  if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
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
<head>
<style>
.menu {
    width: 22px;
    height: 3px;
    background-color: black;
    margin: 4px 0;
}
</style>
</head>

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
					<i style="font-size:17px" class="fa fa-bars"></i>
					&nbsp;<h3 class="box-title"><b>Activity Menu</b></h3>
				</div>
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
									<div class="icon"> 
										<i class="fa fa-file-text-o"></i><span><strong>Paper Publication</strong></span>
									</div>
									 <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount.php"><h4>Add Paper</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="2_dashboard_hod.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="5_fdc_dashboard_hod.php"><h4>FDC Dashboard</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="count_all.php"><h4>Analysis</h4></a></li>
										</ul>
									</li>
						<?php  }
						  else
						  {
						
						?>
								<li class="" style="list-style: none; font-size: 14px;">
									 
										&nbsp;&nbsp;<i class="fa fa-file-text-o" ></i> <span><strong>Paper Publication <br></strong></span>
									
									
									  <br>
									  <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount.php"><h4>Add Paper</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="2_dashboard.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="5_fdc_dashboard.php"><h4>FDC Dashboard</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="count_your.php"><h4>Analysis</h4></a></li>
										</ul>
									</li>
							
													

						<?php
						  }
						break;
						case 2:
						 if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
							 <li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Technical Papers Reviewed</strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									
									   <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount_review.php"><h4>Add Paper</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="2_dashboard_hod_review.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="count_all_review.php"><h4>Analysis</h4></a></li>
										</ul>
									  
									</li> 
					<?php	  }
						  else
						  {
						?>

									<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Technical Papers Reviewed <br></strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									 
									  <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount_review.php"><h4>Add Paper</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="2_dashboard_review.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="count_your_review.php"><h4>Analysis</h4></a></li>
										</ul>
									</li>
							
													

						<?php
						  }
						break;
						case 3:
						
						 if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
							  <li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>STTP/Workshop/FDP Attended/Organised</strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									  							  
									  <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount_attend.php"><h4>Add Activity attended</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="actcount_organised.php"><h4>Add Activity organised</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="2_dashboard_attend_hod.php"><h4>View/Edit Activity attended</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="2_dashboard_organised_hod.php"><h4>View/Edit Activity organised</h4></a></li>
										  <li class="list-group-item list-group-item-success"><a href="5_fdc_dashboard_attend_hod.php"><h4>FDC Dashboard</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="count_all_attend.php"><h4>Analysis</h4></a></li>
										</ul>
									  
									  
									</li>
							
					<?php	  }
						  else
						  {
						?>

									<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>STTP/Workshop/FDP Attended/Organised <br><br></strong></span> <i class="fa fa-angle-left pull-right"></i>
									
																		  
									   <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount_attend.php"><h4>Add Activity attended</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="actcount_organised.php"><h4>Add Activity organised</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="2_dashboard_attend.php"><h4>View/Edit Activity attended</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="2_dashboard_organised.php"><h4>View/Edit Activity organised</h4></a></li>
										  <li class="list-group-item list-group-item-success"><a href="5_fdc_dashboard_attend.php"><h4>FDC Dashboard</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="count_your_attend.php"><h4>Analysis</h4></a></li>
										</ul>
									  
									</li>
							
													

						<?php
						  }
						break;
						case 4:
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
							  <li class="" style="list-style: none; font-size: 14px;">
							<i class="fa " ></i> <span><strong>Guest Lecture Organised <br><br></strong></span> <i class="fa fa-angle-left pull-right"></i>
							 
									<!--	<i class="fa " ></i> <span><strong>Invited for/Organised Guest Lecture </strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									  <ul class="" style="list-style: none;">
										<li><a href="guestlec.php"><i class="fa fa-circle-o"></i>Invited for Guest Lecture</a></li>
										<li><a href="orglec.php"><i class="fa fa-circle-o"></i>Guest Lecture Organised</a></li>
										<li><a href="view_invited_hod_lec.php"><i class="fa fa-circle-o"></i>View/Edit Invited</a></li>
										<li><a href="view_organised_hod_lec.php"><i class="fa fa-circle-o"></i>View/Edit Organised</a></li>

										<li><a href="analysis_h_i.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
									  </ul> -->
									 <ul class="treeview-menu">

									   <li class="list-group-item list-group-item-success"><a href="orglec.php"><h4>Add Guest Lecture Organised</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="view_organised_hod_lec.php"><h4>View/Edit Guest Lecture Orgainsed</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="analysis_h_i.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="guest_organized_chart.php"><h4>Charts</h4></a></li>
									  </ul>

									 
									</li>
						<?php   }
						  else
						  {
						?>

									<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Guest Lecture Organised <br><br></strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									 	<ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="orglec.php"><h4>Guest Lecture Organised</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="view_organised_lec.php"><h4>View/Edit Guest Lecture Orgainsed</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="analysis_i.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="guest_organized_chart.php"><h4>Charts</h4></a></li>
										  
										</ul>		  
										  
									</li>
							
													

						<?php
						  }
						break;
						case 5:
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
						<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Online/Offline Course Attended/Organised <br><br></strong></span> <i class="fa fa-angle-left pull-right"></i>
									
								 <ul class="treeview-menu">

									   <li class="list-group-item list-group-item-success"><a href="actcount_course_attended.php"><h4>Add Attended Course</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="actcount_course_organised.php"><h4>Add Organised Course</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="2_dashboard_hod_online_attended.php"><h4>View/Edit Activity Attended</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="2_dashboard_hod_online_organised.php"><h4>View/Edit Activity Orgainsed</h4></a></li>
										<li class="list-group-item list-group-item-success"><a href="5_fdc_dashboard_online_attended_hod.php"><h4>FDC details</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="count_all_online.php"><h4>Analysis</h4></a></li>
											 <li class="list-group-item list-group-item-danger"><a href="chart_online_hod.php"><h4>Charts</h4></a></li>
									</ul>
	
														
									
									</li>
					<?php	  }
						  else
						  {
						?>

								<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Online/Offline Course Attended/Organised <br><br></strong></span> <i class="fa fa-angle-left pull-right"></i>
									 <ul class="treeview-menu">

									   <li class="list-group-item list-group-item-success"><a href="actcount_course_attended.php"><h4>Add Attended Course</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="actcount_course_organised.php"><h4>Add Organised Course</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="2_dashboard_online_attended.php"><h4>View/Edit Activity Attended</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="2_dashboard_online_organised.php"><h4>View/Edit Activity Orgainsed</h4></a></li>
										<li class="list-group-item list-group-item-success"><a href="5_fdc_dashboard_online_attended.php"><h4>FDC details</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="count_your_online.php"><h4>Analysis</h4></a></li>
										  
									</ul>

									
									</li>
							
													

						<?php
						  }
						break;
						case 6:
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
							  <li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Co-curricular Activity </strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									   <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount_cocurricular.php"><h4>Add Activity</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="2_dashboard_hod_cocurricular.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="count_all_cocurricular.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="chart_cocurricular_hod.php"><h4>Charts</h4></a></li>
										  
										</ul>	
									  
									</li>
							
					<?php	  }
						  else
						  {
							  
						  
						?>

									<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Co-curricular Activity </strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									 
									    <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount_cocurricular.php"><h4>Add Activity</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="2_dashboard_cocurricular.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="count_your_cocurricular.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="chart_cocurricular_hod.php"><h4>Charts</h4></a></li>
										  
										</ul>	
									</li>
							
													

						<?php
						}
						break;
						case 7:
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
							  <li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Extra-curricular Activity  </strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									 
									    <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount_excurricular.php"><h4>Add Activity</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="2_dashboard_hod_excurricular.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="count_all_excurricular.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="chart_excurricular_hod.php"><h4>Charts</h4></a></li>
										  
										</ul>	
									</li>
						<?php  }
						  else
						  {
							  
						  
						?>

									<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Extra-curricular Activity </strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									 
									   <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount_excurricular.php"><h4>Add Activity</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="2_dashboard_excurricular.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="count_your_excurricular.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="chart_excurricular_hod.php"><h4>Charts</h4></a></li>
										  
										</ul>	
									</li>
							
													

						<?php
						}
						break;
						
						case 8:
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
							  <li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Any Other Activity  </strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									
									  
									  <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount_anyother.php"><h4>Add Activity</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="2_dashboard_hod_anyother.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="count_all_anyother.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="chart_anycurricular_hod.php"><h4>Charts</h4></a></li>
										  
										</ul>	
									</li>
						<?php  }
						  else
						  {
							  
						  
						?>

									<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Any Other Activity </strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									  
									  <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="actcount_anyother.php"><h4>Add Activity</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="2_dashboard_anyother.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="count_your_anyother.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="chart_anycurricular_hod.php"><h4>Charts</h4></a></li>
										  
										</ul>	
									  
									</li>
							
													

						<?php
						}
						break;
						
						case 9:
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
							  <li class="" style="list-style: none; font-size: 14px;">
									<div class="icon"> 
										<i class="fa fa-file-text-o"></i><span><strong>Research Details</strong></span>
									</div>
										 										  
										 <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="researchFormCount.php"><h4>Add Research Details</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="researchViewHOD.php"><h4>View/Edit Research</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="researchAnalysisHOD.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="researchChartHOD.php"><h4>Chart</h4></a></li>
										</ul>
										  
									</li>
						<?php  }
						  else
						  {
							  
						  
						?>

								<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Research Proposal Activity</strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									 <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="researchFormCount.php"><h4>Add Research Details</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="researchView.php"><h4>View/Edit Research</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="researchAnalysis.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="researchChart.php"><h4>Chart</h4></a></li>
										</ul>
									</li>
							
							
													

						<?php
						}
						break;
						case 10:
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
							  <li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Industrial Visit Attended/Organised </strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									
								 <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="/dd/IV.php?x=IV/select_menu/addcount.php"><h4>Add Activity</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="/dd/IV.php?x=IV/select_menu/edit_menu.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="/dd/IV.php?x=IV/select_menu/view_menu.php"><h4>Analysis</h4></a></li>
									<!--	  <li class="list-group-item list-group-item-danger"><a href=""><h4>Charts</h4></a></li> -->
										</ul>	  
									</li>
						<?php  }
						  else
						  {
							  
						  
						?>

									<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Industrial Visit Attended/Organised </strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									 <ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="/dd/IV.php?x=IV/select_menu/addcount.php"><h4>Add Activity</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="/dd/IV.php?x=IV/select_menu/edit_menu.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="/dd/IV.php?x=IV/select_menu/view_menu.php"><h4>Analysis</h4></a></li>
									<!--	  <li class="list-group-item list-group-item-danger"><a href=""><h4>Charts</h4></a></li> -->
										</ul>	  
									</li>
							
													

						<?php
						}
						break;
						
						
						
						case 11:
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
						  {?>
							  <li class="" style="list-style: none; font-size: 14px;">
									<div class="icon"> 
										<i class="fa fa-file-text-o"></i><span><strong>Faculty Interaction</strong></span>
									</div>
								<ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="guestlec.php"><h4>Add Faculty Interaction Details</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="view_invited_hod_lec.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="analysis_f.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="faculty_chart.php"><h4>Chart</h4></a></li>
								</ul>		 										  
										
									</li>
						<?php  }
						  else
						  {
							  
						  
						?>

								<li class="" style="list-style: none; font-size: 14px;">
									 
										<i class="fa " ></i> <span><strong>Faculty Interaction</strong></span> <i class="fa fa-angle-left pull-right"></i>
									
									<ul class="list-group">
										  <li class="list-group-item list-group-item-success"><a href="guestlec.php"><h4>Add Faculty Interaction Details</h4></a></li>
										  <li class="list-group-item list-group-item-info"><a href="view_invited_lec.php"><h4>View/Edit Activity</h4></a></li>
										  <li class="list-group-item list-group-item-warning"><a href="analysis_f.php"><h4>Analysis</h4></a></li>
										  <li class="list-group-item list-group-item-danger"><a href="faculty_chart.php"><h4>Chart</h4></a></li>
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
   
   
