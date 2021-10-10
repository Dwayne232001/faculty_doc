<?php 
ob_start();
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
if(isset($_SESSION['type'])){
    if($_SESSION['type'] != 'hod'){
    //if not hod then send the user to login page
    session_destroy();
    header("location:index.php");
  }
}
$_SESSION['currentTab'] = "sttp";

//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];

$type=$_SESSION['type'];
$activity=$_SESSION['activity'];
$count=0;
$sql1="SELECT * from $type where Act_type='$activity'";
$result1=mysqli_query($conn,$sql1);
while($row = $result1->fetch_assoc()) 
{
  $count = $count + 1;
}

if ($count>0) {
  # code...
  $query2 = "SELECT MIN(Date_from) from $type where Act_type='$activity'";
  $result2 = mysqli_query($conn,$query2);
  while($row =mysqli_fetch_assoc($result2)){
      $minStartDate = $row['MIN(Date_from)'];
  }

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
    $query = "SELECT COUNT(*) FROM $type WHERE Act_type='$activity' && Date_from >= '$date1' AND Date_to <= '$date2'";
    $result = mysqli_query($conn,$query);
    while($row = mysqli_fetch_assoc($result)){
        $temp_count = $row['COUNT(*)'];
        array_push($year, $startYear.'-'.$temp_year);
        array_push($count, $temp_count);  
    }
    $startYear = $startYear + '1';
}
}


  ?>

<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php 
if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
  {
      include_once('sidebar_hod.php');

  }
  else
    include_once('sidebar.php');
 ?>

<div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div style="text-align:right">
                <a href="charts.php" style="margin-right: 20px;" class="btn btn-primary">Go Back To Charts Page</a>
            </div>
            <br>
            <?php
              if ($count>0) {?>
            <div class="col-md-6">
              <!-- CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">No. Of <?php echo "$activity $type" ?> activities</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="Chart" style="height:250px"></canvas>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>

            <div class="col-md-6">
              <!-- TABLE -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">No. Of <?php echo "$activity $type" ?> activities</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <table class="table table-condensed">
                    <tr>
                      <th>Academic Year</th>
                      <th>Count</th>
                    </tr>
                    <?php
                      $j = count($year);
                      for($i = 0;$i < $j; $i++)
                      {
                        echo "<tr>";
                        echo "<td>$year[$i]</td>";
                        echo "<td>$count[$i]</td>";
                        echo "</tr>";
                      }
                    ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>

          </div>
        </section>
</div>
<?php 
              }else{
                echo "<div class='box'><div class='box-body'><h4><strong>No activities recorded yet!</strong></h4></div></div></div></section></div>";}
            ?>

<script src = "plugins/chartjs/Chart.min.js"></script>
<script src="jquery/jquery-3.2.1.min.js"></script>
<script>

  var ChartCanvas = $("#Chart").get(0).getContext("2d");;
  var Chart = new Chart(ChartCanvas);

  var academic_year = new Array();
  <?php foreach($year as $key => $val){ ?>
        academic_year.push('<?php echo $val; ?>');
    <?php } ?>

    var numOfAttended = new Array();
    <?php foreach($count as $key => $val){ ?>
        numOfAttended.push('<?php echo $val; ?>');
    <?php } ?>

    var ChartData = {
      labels:academic_year,
      datasets:[
        {
          label:'No. Of <?php echo "$activity $type" ?> activities',
                fillColor: "rgba(60,141,188,0.9)",
                strokeColor: "rgba(60,141,188,0.8)",
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
          data: numOfAttended
        }
      ]
    };

    var ChartOptions = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: true,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
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

        Chart.Bar(ChartData, ChartOptions);

</script>

<?php include_once('footer.php'); ?>