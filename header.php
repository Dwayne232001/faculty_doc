<head>
  <style>
    .sidebar-collapse .logo {
      width: 50px;
    }

    a.logo.departmental {
      width: 230px;
    }

    .caret {
      font-weight: 50px;
    }

    li a.demo {
      width: 150px;
       !important
    }

    .fa-home{
      width: 45px;
      justify-content:center;
    }

    .fa-user{
      width: 45px;
    }

    .fa-upload{
      width : 50px;
    }

    .glyphicon-log-out{
      width:60px;
    }
    
    .hidden-xs{
      width:45px;
    }

  </style>
  <meta name="google-signin-client_id" content="11867248725-qk4s7juqucll4p2qik7otuepk6hpmvd5.apps.googleusercontent.com">
  <script src="https://apis.google.com/js/platform.js" async defer></script>
</head>
<?php
//session_start();
//$_SESSION['user']=$_POST['user'];  //retrieving posted values
//$_SESSION['pic']=$_POST['pic'];

?>

<body class="hold-transition skin-purple sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>F</b>A</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Faculty </b>Activities </span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-fixed-top">
        <!--<nav class="navbar navbar-static-top"> -->
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
          &nbsp;<img src="images/somaiyalogo.png" style=" position: relative; " height="20" />

        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav list-inline d-flex justify-content-center">
            <!-- Messages: style can be found in dropdown.less-->
            <li class="">
              <a href="list_of_activities_user.php" class="" data-toggle="" style="padding-left : 5px;">
                <i class="fa fa-home">&nbsp;<b>Home</b></i>
                <span class="label label-success"></span>
              </a>
            </li>
            <?php 
              if ($_SESSION['type'] == 'hod') { 
            ?>
            <li class="">
              <a href="import_from_excel.php" class="" data-toggle="" style="padding-left : 5px;">
                <i class="fa fa-upload">&nbsp;<b>Import</b></i>
                <span class="label label-success"></span>
              </a>
            </li>
            <li class="">
              <a href="view_missing.php" class="" data-toggle="" style="padding-left : 5px;">
                <i class="fa fa-paperclip">&nbsp;<b>Missing</b></i>
                <span class="label label-success"></span>
              </a>
            </li>
              <?php } ?>
            <!-- Notifications: style can be found in dropdown.less -->
            <li class="dropdown notifications-menu" >
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left : 5px;">
                <i class="fa fa-user">&nbsp;<b>Profile</b></i>
                <span class="label label-warning"></span>
              </a>
              <ul class="dropdown-menu">
                <?php

                if ($_SESSION['type'] == 'hod') { ?>

                  <li><a href="adddetails.php"><i class="fa fa-plus text-red"></i><b><font color="red">&nbsp;Add Faculty Profile</font></b></a></li>
                  <li role="separator" class="divider"></li>

                  <li><a href="addcod.php"><i class="fa fa-plus text-red"></i><b><font color="red">&nbsp;Add Co-ordinator/Committee</font></b></a></li>
                  <li role="separator" class="divider"></li>

                  <li><a href="viewdetails.php"><i class="fa fa-eye text-red"></i><b><font color="red">&nbsp;View Faculty Profile Details</font></b></a></li>

                  <li role="separator" class="divider"></li>
                <?php } ?>
                <li><a href="updatedetails.php"><i class="fa fa-user text-red"></i><b><font color="red">&nbsp;Update Your Profile</font></b></a></li>
                <li role="separator" class="divider"></li>
                <li><a href="changepassword.php"><span class="fa-passwd-reset fa-stack">
                      <i class="fa fa-undo fa-stack-2x"></i>
                      <i class="fa fa-lock fa-stack-1x"></i></span><b><font color="red">&nbsp; Reset Password</font></b></a></li>


              </ul>
            </li>
            <!-- Tasks: style can be found in dropdown.less -->
            <li class="">
              <a href="logout.php" class="" data-toggle="" style="padding-left : 5px;">
                <i class="glyphicon glyphicon-log-out"><b>&nbsp;Logout</b></i>
                <span class="label label-danger"></span>
              </a>

            </li>
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left : 5px;">
                <img src="dist/img/user.png" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php
                                        //$google = sessionStorage.getItem('myUserEntity');
                                        //if ($google == null) {
                                        echo "Logged in as " . $_SESSION['loggedInUser'];
                                        //}
                                        //else {
                                        // echo "Logged in as ". $_SESSION['user'];
                                        //}
                                        ?> </span>


              </a>

            </li>
            <!-- Control Sidebar Toggle Button -->

          </ul>
        </div>
      </nav>
    </header>