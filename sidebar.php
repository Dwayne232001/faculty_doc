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
          <li class="header">
            <h4 style="color:white;"><b>MAIN NAVIGATION</b></h4>
          </li>

          <?php if ($_SESSION['currentTab'] == "list") {
          ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-files-o"></i> <span>Faculty Publications</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="1_add_paper_multiple.php"><i class="fa fa-circle-o"></i> Add Paper</a></li>
                <li><a href="2_dashboard.php"><i class="fa fa-circle-o"></i> View/Edit Activity</a></li>
                <li><a href="count_your.php"><i class="fa fa-circle-o"></i> Analysis</a></li>
                <li><a href="chart_paper_publication.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <!-- <li class="treeview">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Paper Reviewer </span>
                <i class="fa fa-angle-left pull-right"></i>

              </a>
              <ul class="active treeview-menu">
                <li><a href="1_add_paper_multiple_review.php"><i class="fa fa-circle-o"></i> Add Paper Reviewed details</a></li>
                <li><a href="2_dashboard_review.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_review.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_paper_review.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li> -->

            <li class="treeview">
              <a href="#">
                <i class="fa fa-search"></i> <span>Research Proposal/Projects<br>/Consultancy Projects</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="researchForm.php"><i class="fa fa-circle-o"></i> Add Research Details</a></li>
                <li><a href="researchView.php"><i class="fa fa-circle-o"></i> View/Edit Activity</a></li>
                <li><a href="researchAnalysis.php"><i class="fa fa-circle-o"></i> Analysis</a></li>
                <li><a href="chart_research.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>


            <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Faculty Interaction <br>With Outside World</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="guest2.php"><i class="fa fa-circle-o"></i>Add Faculty Interaction Details</a></li>
                <li><a href="view_invited_lec.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="faculty_interaction_analysis.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_faculty_interaction.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>STTP/WS/FDP/QIP/TR<br>S/IN Attended</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="1_add_paper_multiple_attend.php"><i class="fa fa-circle-o"></i>Add Activity attended</a></li>
                <li><a href="2_dashboard_attend.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_attend.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_sttp_faculty.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>STTP/WS/FDP/QIP/TR<br>S/IN Organised</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="1_add_paper_multiple_organised.php"><i class="fa fa-circle-o"></i>Add Activity organised</a></li>
                <li><a href="2_dashboard_organised.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_attend.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_sttp_faculty.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>


            <!-- <li class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i>
                <span> Organized <br>Guest Lecture/Expert Talk</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="1"><a href="organised_guest.php"><i class="fa fa-circle-o"></i>Add Guest Lecture Organised</a></li>
                <li class="2"><a href="view_organised_lec.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li class="3"><a href="analysis_i.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_guest_lec_organised.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li> -->

            <li class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Online Courses Completed</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="1_add_course_attended.php"><i class="fa fa-circle-o"></i>Add Course Completed</a></li>
                <!-- <li class=""><a href="1_add_course_organised.php"><i class="fa fa-circle-o"></i>Add Organised Course</a></li> -->
                <li><a href="2_dashboard_online_attended.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <!-- <li><a href="2_dashboard_online_organised.php"><i class="fa fa-circle-o"></i>View/Edit Activity Orgainsed</a></li> -->
                <li><a href="count_your_online.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_online_course.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-university"></i>
                <span>Books/Chapter Published</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="1_add_books_published.php"><i class="fa fa-circle-o"></i>Add Books/Chapter Published</a></li>
                <li><a href="2_dashboard_books"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-sticky-note-o"></i>
                <span>Patents/IPR/Copyrights</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="1_add_patents.php"><i class="fa fa-circle-o"></i>Patents/IPR/Copyrights</a></li>
                <li><a href="2_dashboard_patent"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-briefcase"></i>
                <span>Industrial Visit Organised</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href='industrialvisit.php'><i class="fa fa-circle-o"></i>Add Activity</a></li>
                <li><a href='2_dashboard_iv.php'><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href='ivchart_fac.php'><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_iv.php"><i class="fa fa-circle-o"></i> Charts</a></li>

              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-trophy"></i>
                <span>Faculty Awards/Prizes<br>/Recognition Won</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="1_add_activity_multiple_cocurricular.php"><i class="fa fa-circle-o"></i>Add Activity</a></li>
                <li><a href="2_dashboard_cocurricular.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_cocurricular.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_cocurricular.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i>
                <span>Extra-Curricular Activity</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="1_add_activity_multiple_excurricular.php"><i class="fa fa-circle-o"></i>Add Activity</a></li>
                <li><a href="2_dashboard_excurricular.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_excurricular.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_excurricular.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>


            <li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i>
                <span>Any Other Activity</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="1_add_activity_multiple_anyother.php"><i class="fa fa-circle-o"></i>Add Activity</a></li>
                <li><a href="2_dashboard_anyother.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_anyother.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_anyother.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>


          <?php } else {

          ?>
            <li <?php if ($_SESSION['currentTab'] == "paper") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-files-o"></i> <span>Faculty Publication</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="1_add_paper_multiple.php"><i class="fa fa-circle-o"></i> Add Paper</a></li>
                <li><a href="2_dashboard.php"><i class="fa fa-circle-o"></i> View/Edit Activity</a></li>
                <li><a href="count_your.php"><i class="fa fa-circle-o"></i> Analysis</a></li>
                <li><a href="chart_paper_publication.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <!-- <li <?php if ($_SESSION['currentTab'] == "technical_review") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Paper Reviewer </span>
                <i class="fa fa-angle-left pull-right"></i>

              </a>
              <ul class="active treeview-menu">
                <li><a href="1_add_paper_multiple_review.php"><i class="fa fa-circle-o"></i> Add Paper Reviewed details</a></li>
                <li><a href="2_dashboard_review.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_review.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_paper_review.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li> -->

            <li <?php if ($_SESSION['currentTab'] == "research") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-search"></i> <span>Research Proposal/Projects<br>/Consultancy Projects</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="researchForm.php"><i class="fa fa-circle-o"></i> Add Research Details</a></li>
                <li><a href="researchView.php"><i class="fa fa-circle-o"></i> View/Edit Activity</a></li>
                <li><a href="researchAnalysis.php"><i class="fa fa-circle-o"></i> Analysis</a></li>
                <li><a href="chart_research.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <li <?php if ($_SESSION['currentTab'] == "faculty") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>

              <a href="#">
                <i class="fa fa-users"></i>
                <span>Faculty Interaction <br>With Outside World</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="guest2.php"><i class="fa fa-circle-o"></i>Add Faculty Interaction Details</a></li>
                <li><a href="view_invited_lec.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="faculty_interaction_analysis.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_faculty_interaction.php"><i class="fa fa-circle-o"></i> Charts</a></li>

              </ul>
            </li>


            <li <?php if ($_SESSION['currentTab'] == "sttp") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>STTP/WS/FDP/QIP/TR<br>S/IN Attended</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="1_add_paper_multiple_attend.php"><i class="fa fa-circle-o"></i>Add Activity attended</a></li>
                <li><a href="2_dashboard_attend.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_attend.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_sttp_faculty.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <li <?php if ($_SESSION['currentTab'] == "sttp1") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>STTP/WS/FDP/QIP/TR<br>S/IN Organised</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="1_add_paper_multiple_organised.php"><i class="fa fa-circle-o"></i>Add Activity organised</a></li>
                <li><a href="2_dashboard_organised.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_attend.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_sttp_faculty.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <!-- <li <?php if ($_SESSION['currentTab'] == "organised_guest") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-folder"></i>
                <span> Organized <br>Guest Lecture/Expert Talk</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="1"><a href="organised_guest.php"><i class="fa fa-circle-o"></i>Add Guest Lecture Organised</a></li>
                <li class="2"><a href="view_organised_lec.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li class="3"><a href="analysis_i.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_guest_lec_organised.php"><i class="fa fa-circle-o"></i> Charts</a></li>

              </ul>
            </li> -->

            <li <?php if ($_SESSION['currentTab'] == "Online") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Online Courses Completed</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="1_add_course_attended.php"><i class="fa fa-circle-o"></i>Add Course Completed</a></li>
                <!-- <li class=""><a href="1_add_course_organised.php"><i class="fa fa-circle-o"></i>Add Organised Course</a></li> -->
                <li><a href="2_dashboard_online_attended.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <!-- <li><a href="2_dashboard_online_organised.php"><i class="fa fa-circle-o"></i>View/Edit Activity Orgainsed</a></li> -->
                <li><a href="count_your_online.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_online_course.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <li <?php if ($_SESSION['currentTab'] == "books") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-university"></i>
                <span>Books/Chapter Published</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="1_add_books_published.php"><i class="fa fa-circle-o"></i>Add Books/Chapter Published</a></li>
                <li><a href="2_dashboard_books.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
              </ul>
            </li>

            <li <?php if ($_SESSION['currentTab'] == "patents") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-sticky-note-o"></i>
                <span>Patents/IPR/Copyrights</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="1_add_patents.php"><i class="fa fa-circle-o"></i>Patents/IPR/Copyrights</a></li>
                <li><a href="2_dashboard_patent.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
              </ul>
            </li>

            <li <?php if ($_SESSION['currentTab'] == "iv") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-briefcase"></i>
                <span>Industrial Visit Organised</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="1"><a href='industrialvisit.php'><i class="fa fa-circle-o"></i>Add Activity</a></li>
                <li class="2"><a href='2_dashboard_iv.php'><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li class="3"><a href='ivchart_fac.php'><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_iv.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <li <?php if ($_SESSION['currentTab'] == "co") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-trophy"></i>
                <span>Faculty Awards/Prizes<br>/Recognition Won</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="1_add_activity_multiple_cocurricular.php"><i class="fa fa-circle-o"></i>Add Activity</a></li>
                <li><a href="2_dashboard_cocurricular.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_cocurricular.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_cocurricular.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>

            <li <?php if ($_SESSION['currentTab'] == "ex") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-table"></i>
                <span>Extra-curricular Activity</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="1_add_activity_multiple_excurricular.php"><i class="fa fa-circle-o"></i>Add Activity</a></li>
                <li><a href="2_dashboard_excurricular.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_excurricular.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_excurricular.php"><i class="fa fa-circle-o"></i> Charts</a></li>
              </ul>
            </li>
            <li <?php if ($_SESSION['currentTab'] == "anyOther") {
                  echo 'class="active treeview"';
                } else {
                  echo 'class="treeview"';
                } ?>>
              <a href="#">
                <i class="fa fa-table"></i>
                <span>Any Other Activity</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="1_add_activity_multiple_anyother.php"><i class="fa fa-circle-o"></i>Add Activity</a></li>
                <li><a href="2_dashboard_anyother.php"><i class="fa fa-circle-o"></i>View/Edit Activity</a></li>
                <li><a href="count_your_anyother.php"><i class="fa fa-circle-o"></i>Analysis</a></li>
                <li><a href="chart_anyother.php"><i class="fa fa-circle-o"></i> Charts</a></li>
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