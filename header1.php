<head>
<style>

.sidebar-collapse .logo {
	width: 50px;
}

a.logo.departmental {
    width: 230px;
}

.caret {
    font-weight:50px;
}
li a.demo{
	width: 150px; !important
}

</style>
</head>
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">

  <header class="main-header">
  
    <!-- Logo -->
    <a href="" class="logo departmental">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D</b>D</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Departmental </b>Details
	   
	  </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-expand-md navbar-static-top">
		  <ul class="nav navbar-nav">
	<!--<div style="font-size: 20px;"><b>K.J.Somaiya College of Engineering</b></div>-->
      <!-- Sidebar toggle button-->
	  <li>
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">&nbsp;
		<b><?php echo "Logged in as ". $_SESSION['username'];?> </b>
	</a>
	</li>
	<li class="">
	<a  class="logo" href="list_of_activities_user.php"  style="width:150px;">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><i class="fa fa-home"></i></span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><i class="fa fa-home"></i>&nbsp;<b>Home</b></span>
    </a>
	</li>
	<li class="dropdown">
 <a href="#" class="logo dropdown-toggle" data-toggle="dropdown" style="width:150px;">
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"><i class="glyphicon glyphicon-user"></i>&nbsp;<span class="caret"></span></span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><i class="glyphicon glyphicon-user"></i>&nbsp;<b>Profile</b>&nbsp;<span class="caret"></span></span>
			</a>
			<ul class="dropdown-menu">
            <li><a href="updatedetails.php"><b>Update Details</b></a></li>
			<li role="separator" class="divider"></li>
            <li><a href="changepassword.php"><b>Reset Password</b></a></li>
          </ul>
	</li>
	<ul class="nav navbar-nav navbar-right">
		<a class="" style="float:right">
			<img src="images/somaiya1.png" style="margin-right:; margin-left:" height="50"/>
			<img src="images/trust.png" style="margin-right:; margin-left:5px;" height="45"/>
		</a>
		<li class="" style="margin-left:">
		<a href="logout.php" class="logo" style="float:right; width:150px;">
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"><i class="glyphicon glyphicon-log-out"></i></span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><i class="glyphicon glyphicon-log-out"></i>&nbsp;<b>Logout</b></span>
		</a>
		</li>
	
		<!--a href="list_of_activities_user.php" class="logo" style="float:left;" ><b>Home</b></a></span-->
		<!--a href="logout.php" class="logo" style="float:right;"><b>Logout</b></a></span-->
    </ul>   
	   

     
		 	  
		

	  </ul>
    </nav>
  </header>