<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Departmental Details</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      
      <!-- search form -->
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
            <li class="header"><h4 style="color:white;" ><b>MAIN NAVIGATION</b></h4></li>
      
    <?php if($_SESSION['currentTab'] == "list")
    {
    ?>
    
            <li class="treeview">
              <a href="#">
                <i class="fa fa-dashboard"></i> <span>Paper Publication</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="2_dashboard_hod.php"><i class="fa fa-circle-o"></i> View Activity</a></li>
                <li><a href="count_all.php"><i class="fa fa-circle-o"></i> Analysis</a></li>
        <li><a href="researchChart_hod.php"><i class="fa fa-circle-o"></i>Charts</a></li>
    
              </ul>
            </li>
      
            <li class="treeview">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Paper Reviewer </span>
        <i class="fa fa-angle-left pull-right"></i>
                
              </a>
              <ul class="active treeview-menu">
                 <li><a href="2_dashboard_hod_review.php"><i class="fa fa-circle-o"></i>View Activity</a></li>

            <li><a href="count_all_review.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li><a href="technicalreview_hod.php"><i class="fa fa-circle-o"></i>Charts</a></li>
      
              </ul>
            </li>
      
       <li class="treeview">
              <a href="#">
                <i class="fa fa-files-o"></i> <span>Research Details</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="researchViewHOD.php"><i class="fa fa-circle-o"></i> View Research</a></li>
                <li><a href="researchAnalysisHOD.php"><i class="fa fa-circle-o"></i> Analysis</a></li>
                <li><a href="researchChartHOD.php"><i class="fa fa-circle-o"></i> Chart</a></li>
              </ul>
            </li>
      
       <li class="treeview">

              <a href="#">
                <i class="fa fa-folder"></i> 
        <span>Faculty Interaction</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
        <li><a href="view_invited_hod_lec.php"><i class="fa fa-circle-o"></i>View Activity</a></li>
        <li><a href="analysis_f.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li><a href="faculty_chart.php"><i class="fa fa-circle-o"></i>Chart</a></li>

              </ul>
            </li>
         
            <li class="treeview">

              <a href="#">        

                <i class="fa fa-pie-chart"></i>
                <span>STTP/Workshop/FDP/QIP/<br>SEMINAR <br>Attended/Organised</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
          <li><a href="2_dashboard_attend_hod.php"><i class="fa fa-circle-o"></i>View Activity attended</a></li>
          <li><a href="2_dashboard_organised_hod.php"><i class="fa fa-circle-o"></i>View Activity organised</a></li>
          <li><a href="count_all_attend.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
             <li><a href="chart_sttp_hod.php"><i class="fa fa-circle-o"></i>Chart</a></li>

      </ul>
            </li>
      
            <li class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i> 
        <span> Organized <br>Guest Lecture/Expert Talk</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
        <li class="2"><a href="view_organised_hod_lec.php"><i class="fa fa-circle-o"></i>View Orgainsed</a></li>
        <li class="3"><a href="analysis_h_i.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li class="4"><a href="guest_organized_chart.php"><i class="fa fa-circle-o"></i>Charts</a></li>

              </ul>
            </li>       

            <li class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Online/Offline Course <br>Attended/Organised</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="2_dashboard_hod_online_attended.php"><i class="fa fa-circle-o"></i>View Activity Attended</a></li>
                <li><a href="2_dashboard_hod_online_organised.php"><i class="fa fa-circle-o"></i>View Activity Orgainsed</a></li>

          <li><a href="count_all_online.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li><a href="chart_online_hod.php"><i class="fa fa-circle-o"></i>Chart</a></li>

          </ul>
            </li> 
      
            <li class="treeview">
              <a href="#">
                <i class="fa fa-edit"></i> 
        <span>Industrial Visit</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
              <li class="2" ><a href='2_dashboard_iv_hod.php'><i class="fa fa-circle-o"></i>View Activity</a></li>
              <li class="3" ><a href='count_all_iv.php'><i class="fa fa-circle-o"></i>Analysis</a></li>
				      <li class="4" ><a href='ivchart.php'><i class="fa fa-circle-o"></i>Chart</a></li>
  
      </ul>
            </li>
                
            <li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i> 
        <span>Co-curricular Activity</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="2_dashboard_hod_cocurricular.php"><i class="fa fa-circle-o"></i>View Activity</a></li>

                <li><a href="count_all_cocurricular.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_cocurricular_hod.php"><i class="fa fa-circle-o"></i>Chart</a></li>

              </ul>
            </li> 
      
            <li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i> 
        <span>Extra-curricular Activity</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="2_dashboard_hod_excurricular.php"><i class="fa fa-circle-o"></i>View Activity</a></li>

                <li><a href="count_all_excurricular.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_excurricular_hod.php"><i class="fa fa-circle-o"></i>Chart</a></li>

              </ul>
            </li>
           
            <li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i> 
        <span>Any Other Activity</span>
            <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
        <li><a href="2_dashboard_hod_anyother.php"><i class="fa fa-circle-o"></i>View Activity</a></li>
        <li><a href="count_all_anyother.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li><a href="chart_anycurricular_hod.php"><i class="fa fa-circle-o"></i>Chart</a></li>

              </ul>
            </li>
            
           
           
    <?php }
      else
      {
        
    ?>
        <li <?php if($_SESSION['currentTab']=="paper"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>
              <a href="#">
                <i class="fa fa-dashboard"></i> <span>Paper Publication</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="2_dashboard_hod.php"><i class="fa fa-circle-o"></i> View Activity</a></li>
                <li><a href="count_all.php"><i class="fa fa-circle-o"></i> Analysis</a></li>
          <li><a href="researchChart_hod.php"><i class="fa fa-circle-o"></i>Charts</a></li>
 
        </ul>
            </li>
      
       <li <?php if($_SESSION['currentTab']=="technical_review"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Paper Reviewer </span>
        <i class="fa fa-angle-left pull-right"></i>
                
              </a>
              <ul class="active treeview-menu">
                 <li><a href="2_dashboard_hod_review.php"><i class="fa fa-circle-o"></i>View Activity</a></li>

            <li><a href="count_all_review.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li><a href="technicalreview_hod.php"><i class="fa fa-circle-o"></i>Charts</a></li>
 
 </ul>
            </li>
      
      <li <?php if($_SESSION['currentTab']=="research"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>
              <a href="#">
                <i class="fa fa-files-o"></i> <span>Research Details</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="researchViewHOD.php"><i class="fa fa-circle-o"></i> View Research</a></li>
                <li><a href="researchAnalysisHOD.php"><i class="fa fa-circle-o"></i> Analysis</a></li>
                <li><a href="researchChartHOD.php"><i class="fa fa-circle-o"></i> Chart</a></li>
              </ul>
            </li>
      
      
       <li <?php if($_SESSION['currentTab']=="faculty"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>

              <a href="#">
                <i class="fa fa-folder"></i> 
        <span>Faculty Interaction</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
          <li><a href="view_invited_hod_lec.php"><i class="fa fa-circle-o"></i>View Activity</a></li>
        <li><a href="analysis_f.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li><a href="faculty_chart.php"><i class="fa fa-circle-o"></i>Chart</a></li>

              </ul>
            </li>
             
        <li 

        <?php if($_SESSION['currentTab']=="sttp"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>
              <a href="#">

                <i class="fa fa-pie-chart"></i>
                <span>STTP/Workshop/FDP/QIP/<br>SEMINAR <br>Attended/Organised</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
          <li><a href="2_dashboard_attend_hod.php"><i class="fa fa-circle-o"></i>View Activity attended</a></li>
          <li><a href="2_dashboard_organised_hod.php"><i class="fa fa-circle-o"></i>View Activity organised</a></li>


            <li><a href="count_all_attend.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
               <li><a href="chart_sttp_hod.php"><i class="fa fa-circle-o"></i>Chart</a></li>

      </ul>
            </li>
      
        
             <li <?php if($_SESSION['currentTab']=="organised_guest"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>
              <a href="#">
                <i class="fa fa-folder"></i> 
        <span> Organized <br>Guest Lecture/Expert Talk</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
        <li class="2"><a href="view_organised_hod_lec.php"><i class="fa fa-circle-o"></i>View Orgainsed</a></li>
        <li class="3"><a href="analysis_h_i.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li class="4"><a href="guest_organized_chart.php"><i class="fa fa-circle-o"></i>Charts</a></li>

              </ul>
            </li> 
      
       <li <?php if($_SESSION['currentTab']=="Online"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Online/Offline Course</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
          <li><a href="2_dashboard_hod_online_attended.php"><i class="fa fa-circle-o"></i>View Activity Attended</a></li>
          <li><a href="2_dashboard_hod_online_organised.php"><i class="fa fa-circle-o"></i>View Activity Orgainsed</a></li>         
          <li><a href="count_all_online.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li><a href="chart_online_hod.php"><i class="fa fa-circle-o"></i>Chart</a></li>
 
 </ul>
            </li>
      
      <li <?php if($_SESSION['currentTab']=="iv"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>
              <a href="#">
                <i class="fa fa-edit"></i> 
        <span>Industrial Visit</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
              <li class="2" ><a href='2_dashboard_iv_hod.php'><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
         <li class="3" ><a href='count_all_iv.php'><i class="fa fa-circle-o"></i>Analysis</a></li>
				 <li class="4" ><a href='ivchart.php'><i class="fa fa-circle-o"></i>Chart</a></li>

      </ul>
            </li>
      
             <li <?php if($_SESSION['currentTab']=="co"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>
              <a href="#">
                <i class="fa fa-table"></i> 
        <span>Co-curricular Activity</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
        <li><a href="2_dashboard_hod_cocurricular.php"><i class="fa fa-circle-o"></i>View Activity</a></li>

        <li><a href="count_all_cocurricular.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li><a href="chart_cocurricular_hod.php"><i class="fa fa-circle-o"></i>Chart</a></li>

              </ul>
            </li>
        <li <?php if($_SESSION['currentTab']=="ex"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>
              <a href="#">
                <i class="fa fa-table"></i> 
        <span>Extra-curricular Activity</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
        <li><a href="2_dashboard_hod_excurricular.php"><i class="fa fa-circle-o"></i>View Activity</a></li>
  
        <li><a href="count_all_excurricular.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li><a href="chart_excurricular_hod.php"><i class="fa fa-circle-o"></i>Chart</a></li>

              </ul>
            </li>
           
           <li <?php if($_SESSION['currentTab']=="anyOther"){echo'class="active treeview"';}else{echo'class="treeview"';}?>>
              <a href="#">
                <i class="fa fa-table"></i> 
        <span>Any Other Activity</span>
            <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
        <li><a href="2_dashboard_hod_anyother.php"><i class="fa fa-circle-o"></i>View Activity</a></li>
    
        <li><a href="count_all_anyother.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
        <li><a href="chart_anycurricular_hod.php"><i class="fa fa-circle-o"></i>Chart</a></li>

              </ul>
            </li>
       
      <?php } ?>
      
          </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  </div>
  </body>
  </html>