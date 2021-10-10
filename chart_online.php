<?php
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab']="online";

	include_once("includes/connection.php");
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
 
	//$sqlses = $_SESSION['sql'] ;
	//$mysqli=mysqli_query($conn,$sqlses);
	
 
?>

<!DOCTYPE HTML>
<html>
<head>


<section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-xs-12">
              <!-- general form elements -->
						  <br/><br/><br/>
  
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h1 class="box-title">Charts</h1>
                </div><!-- /.box-header -->
				<div style="text-align:right">
								<a href="menu.php?menu=5 "> <u>Back to Online Course Attended Activities Menu</u></a>
				</div><br>
					<?php
					$date = idate("Y");
					$date = (int)$date;
					if($_SESSION["type"] === 'Oragnise' ){
?>                   <?php 
$mysqli = $conn;
	$result_start;
	for ($i=2014; $i<=$date ; $i++) { 
		$j=$i + 1;
		$sql1="SELECT * FROM online_course_organised WHERE Date_From>='$i-07-1' && Date_To<='$j-6-30'";
		$label = "$i" . "-" . "$j";
		if($res = $mysqli->query($sql1)) {
			if($i == 2014){
				$result_start = $res->num_rows;
			}
			$res1=$res->num_rows;
			$label = "$i" . "-" . "$j";
			$temp[$i]= array("y" => $res1, "label" => $label );
		
		}
	}
$dataPoints = array(array("y" => $result_start, "label" => "2014-15" ));
for ($i=2014; $i<=$date ; $i++)
	{
	   array_push ($dataPoints,$temp[$i]);	
	} 
?>


                <br><br><br>
				<h2 style="margin-left: 25%; "> Courses Organised</h2>
				<br>
				<div id="chartContainer" style="height: 350px; width: 70%; margin-left: 300px;"></div>
				<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: " "
	},
	axisY: {
		title: "Count of organised courses"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## Courses(s)",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
chart = {};

}
</script>
				<!--<div class="box box-success"> -->
                <div class="box-header with-border">
                <!--  <h3 class="box-title">Bar Chart</h3>-->
                  <div class="box-tools pull-right">
                  </div>
                </div>
				<div class="box-body chart-responsive">
                  <div class="chart" id="bar-chart" style="height: 100px;"></div>
                <!-- /.box-body -->
              <!-- </div><!-- /.box -->

</div> 
						<?php } 
						else if($_SESSION["type"] === 'Attended'){ ?>
<?php
 ob_start();
 $types = $_GET['types'];
//session_start();
/*if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
	include_once("includes/connection.php");
	include_once('head.php'); 
	include_once('header.php'); 
	if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
  {
	    include_once('sidebar_hod.php');

  }
  else
	  include_once('sidebar.php');*/
 
	//$sqlses = $_SESSION['sql'] ;
	//$mysqli=mysqli_query($conn,$sqlses);
	$mysqli = $conn;
	
	

$result_start;
	for ($i=2014; $i<=$date ; $i++) { 
		$j=$i + 1;
		$sql1="SELECT * FROM online_course_attended WHERE Date_From>='$i-07-1' && Date_To<='$j-6-30'&& type_of_course='$types'";
		$label = "$i" . "-" . "$j";
		if($res = $mysqli->query($sql1)) {
			if($i == 2014){
				$result_start = $res->num_rows;
			}
			$res1=$res->num_rows;
			$label = "$i" . "-" . "$j";
			$temp[$i]= array("y" => $res1, "label" => $label );
		
		}
	}
$dataPoints = array(array("y" => $result_start, "label" => "2014-15" ));
for ($i=2014; $i<=$date ; $i++)
	{
	   array_push ($dataPoints,$temp[$i]);	
	} 
?>

<br><br><br>
				<h2 style="margin-left: 25%; "> Courses Attended - <?php echo $types?> </h2>
				<br>
				<div id="chartContainer" style="height: 350px; width: 70%; margin-left: 300px;"></div>
				<!--<div class="box box-success"> -->
				<script>
window.onload = function() {
	var chart1 = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: " "
	},
	axisY: {
		title: "Count of attended Courses"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## Courses(s)",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart1.render();
}
</script>
                <div class="box-header with-border" >
                <!--  <h3 class="box-title">Bar Chart</h3>-->
                  <div class="box-tools pull-right">
                  </div>
                </div>
				<div class="box-body chart-responsive">
                  <div class="chart" id="bar-chart" style="height: 100px;"></div>
                <!-- /.box-body -->
              <!-- </div><!-- /.box -->
					<?php }?>

</div>
</div>
</form>
</div>
</div>
</div>
</section>

</head>
<body>
<script src=jquery/canvasjs.min.js></script>
</body>
<?php include_once('footer.php'); ?> 
</html>