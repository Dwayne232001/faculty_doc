<?php 
ob_start();
session_start();

 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
include_once("includes/connection.php");
$fid=$_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}
;

include_once('head.php');
$_SESSION['currentTab']="Online";

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

?>

<?php include_once('header.php'); ?>

<?php 
if($_SESSION['type'] == 'hod')
{
	  include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
		include_once('sidebar_cod.php');
}
else{
	include_once('sidebar.php');
}

$year = [];
$count = [];
 ?>

<style>
div.scroll
{
  overflow:scroll;
}

</style>

<div class="content-wrapper">

<!-- Main content -->
    <section class="content">
      <div class="row">
            <!-- left column -->
        <div class="col-xs-12">
              <!-- general form elements -->
			  			  <br/><br/><br/>

             <div class="box box-primary">
                <div class="box-header with-border">
					<div class="icon">
					<i style="font-size:18px" class="fa fa-signal"></i>
					<h3 class="box-title"><b>Online Course Chart</b></h3>
					<br>	
					</div> 
                </div><!-- /.box-header -->
			<div style="text-align:right">
		</div>
                <!-- form start -->
            <form role="form" action = "chart_online_course.php" method="post">
               <div class="box-body">
                    <div class="form-group col-md-2">
						<label for="type">Select Type:</label>
						<select name='type_online' id='type' style="width:150px;">
							<option value="Attended">Attended</option>
							<option value="Organised">Organised</option>
						</select>
					</div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="submit_chart" value="View Chart">
                  </div>
            </form>
<?php
if(isset($_POST['submit_chart'])){
  if(isset($_POST['type_online']))
     $_SESSION['type_online'] = $_POST['type_online'];
  if($_SESSION['type_online'] == 'Attended')	
        $query="SELECT * from online_course_attended WHERE Fac_ID=$fid";
  else if($_SESSION['type_online'] == 'Organised')
        $query="SELECT * from online_course_organised WHERE Fac_ID=$fid";
$sql=mysqli_query($conn,$query);
if(mysqli_num_rows($sql)>0)
{
    if($_SESSION['type_online'] == 'Attended')
		  $query2 = "SELECT MIN(Date_From) from online_course_attended WHERE Fac_ID=$fid";
    else if($_SESSION['type_online'] == 'Organised')
		  $query2 = "SELECT MIN(Date_From) from online_course_organised WHERE Fac_ID=$fid";
	$result2 = mysqli_query($conn,$query2);
	while($row = mysqli_fetch_assoc($result2))
	{
		$minStartDate = $row['MIN(Date_From)'];
	}
}
else
{
	$minStartDate = '2016-07-01';
}
//print ".$minStartDate.";
//echo "<script>alert('$minStartDate')</script>";
$todaysDate=date('Y-m-d');

$maxYear = substr($todaysDate, 0, 4);
$minYear = substr($minStartDate, 0, 4);

if(substr($todaysDate,5,2) <= '06'){
  $endYear = $maxYear;
}
else{
  $endYear = $maxYear + '1';
}
$startYear = $minYear - '1';

$year = [];
$count = [];

while($startYear != $endYear) {
	$temp_year = $startYear + '1';
	$date1 = $startYear.'-07-01';
	$date2 = $temp_year.'-06-30';

    if($_SESSION['type_online'] == 'Attended'){
      $query = "SELECT COUNT(*) FROM online_course_attended WHERE Fac_ID=$fid AND Date_To >= '$date1' AND Date_From <= '$date2'";
      $query1="SELECT type_of_course from online_course_attended WHERE Fac_ID=$fid";
    }
    else if($_SESSION['type_online'] == 'Organised'){
      $query = "SELECT COUNT(*) FROM online_course_organised WHERE Fac_ID=$fid AND Date_To >= '$date1' AND Date_From <= '$date2'";
      $query1="SELECT type_of_course from online_course_organised WHERE Fac_ID=$fid";
    }

	$result = mysqli_query($conn,$query);
	while($row = mysqli_fetch_assoc($result)){
    	$temp_count = $row['COUNT(*)'];
    	array_push($year, $startYear.'-'.substr($temp_year,2,4));
		array_push($count, $temp_count);	
	}
	$startYear = $startYear + '1';
}
$offline=$online=0;
$result1 = mysqli_query($conn,$query1);
  while($row = mysqli_fetch_assoc($result1)){
    if($row['type_of_course']=='online'){
      $online++;
    }
    if($row['type_of_course']=='offline'){
      $offline++;
    }
  }

//print_r($year);
//print_r($count);

?>

<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php 
if($_SESSION['type'] == 'hod')
{
    include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
    include_once('sidebar_cod.php');
}
else{
  include_once('sidebar.php');
}

 ?>
        <!-- left column -->
        <div class="col-xs-12">
              <!-- general form elements -->
				  <br/>
		  
			<div class="box box-primary">
                <div class="box-header with-border">
					<div class="icon">
						<i style="font-size:20px" class="fa fa-bar-chart"></i>
						<h1 class="box-title"><b>Charts</b></h1>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" style="width:100px; height:40px" id="print" onclick="window.print();">Print Chart</button>
			</div>
		</div>
		</div>
          <div class="row">

			<div class="col-md-6">
              <!-- CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><b>Bar Chart - Online Course <?php if(isset($_SESSION['type_online'])) echo $_SESSION['type_online']; ?></b></h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="researchChart" style="height:250px"></canvas>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>

            <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><b><?php if(isset($_SESSION['type_sttp'])) echo $_SESSION['type_sttp']; ?> - Type </b></h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="sttpType" style="height:250px"></canvas>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>

            <div class="col-md-6">
              <!-- TABLE -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><b>Statistics</b></h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <table class="table table-condensed">
                    <tr>
                      <th>Academic Year</th>
                      <th>Count</th>
                    </tr>
                    <?php
					if($year != 0){
                      $j = count($year);
                      for($i = 0;$i < $j; $i++)
                      {
                        echo "<tr>";
                        echo "<td>$year[$i]</td>";
                        echo "<td>$count[$i]</td>";
                        echo "</tr>";
                      }
					}
                    ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              <!--<a href="researchChart-GeneratePDF.php" type="button" class="btn btn-primary" target="_blank">Generate PDF</a>-->
            </div>
          </div>
        </section>
</div>


<script src = "plugins/chartjs/Chart.min.js"></script>
<script src="jquery/jquery-3.2.1.min.js"></script>
<script>
	var researchChartCanvas = $("#researchChart").get(0).getContext("2d");;
	var researchChart = new Chart(researchChartCanvas);

	var academic_year = new Array();
	<?php foreach($year as $key => $val){ ?>
        academic_year.push('<?php echo $val; ?>');
    <?php } ?>

    var numOfResearch = new Array();
    <?php foreach($count as $key => $val){ ?>
        numOfResearch.push('<?php echo $val; ?>');
    <?php } ?>

    var researchChartData = {
    	labels:academic_year,
    	datasets:[
    		{
    			label:'No. of Ongoing Research',
              	fillColor: "rgba(66, 165, 245,0.9)",
				strokeColor: "rgba(69, 39, 160,0.8)",
              	//strokeColor: "rgba(60,141,188,0.8)",
              	pointColor: "#3b8bba",
              	pointStrokeColor: "rgba(60,141,188,1)",
              	pointHighlightFill: "#fff",
              	pointHighlightStroke: "rgba(60,141,188,1)",
    			data: numOfResearch
    		}
    	]
    };

    var researchChartOptions = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: true,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.03)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - If there is a stroke on each bar
          barShowStroke: true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth: 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing: 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing: 1,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to make the chart responsive
          responsive: true,
          maintainAspectRatio: true
        };

        researchChart.Bar(researchChartData, researchChartOptions);

      var TypeCanvas=$("#sttpType").get(0).getContext("2d");;
  var sttpType=new Chart(TypeCanvas);

  var type=new Array();
  type.push('online');
  type.push('offline');

  var numOf_type=new Array();
  numOf_type.push('<?php echo $online; ?>');
  numOf_type.push('<?php echo $offline; ?>');

  var TypeData={
    labels:type,
    datasets:[
      {
        label:'Count of each Type',
                fillColor: "rgba(60,141,188,0.9)",
                strokeColor: "rgba(60,141,188,0.8)",
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
          data: numOf_type
      }
    ]
  }
        sttpType.Bar(TypeData, researchChartOptions);

</script>

<?php
}
?>


 <?php include_once('footer.php'); ?>