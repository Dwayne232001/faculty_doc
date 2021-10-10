<?php
ob_start();
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}


if(isset($_SESSION['type'])){
  if($_SESSION['type'] != 'hod')
    //if not hod then send the user to login page
    header("location:index.php");
}

$_SESSION['currentTab']="paper";

//connect to database
include("includes/connection.php");

/*$query1 = "SELECT MAX(toDate) from researchdetails WHERE Fac_ID ='".$_SESSION['Fac_ID']."'; ";
$result1 = mysqli_query($conn,$query1);
while($row =mysqli_fetch_assoc($result1)){
    $maxEndDate = $row['MAX(toDate)'];
}*/

$national=$peer=$nonpeer=$Presented=$international=$notpresented=$journal=$conference=0;
if(isset($_POST['name'])){
      $Facname=$_POST['name'];
      $Facname_arr=explode(',', $Facname);
      $fname=$Facname_arr[0];
      $query = "SELECT Fac_ID FROM facultydetails WHERE F_NAME='".$fname."'";
      $result = $conn->query($query); 
      while($row = mysqli_fetch_array($result))  
      {  
        $fid=$row['Fac_ID'];
      }
  $query2 = "SELECT * from faculty where Fac_ID=$fid";
  $result2 = mysqli_query($conn,$query2);
  while($row = mysqli_fetch_assoc($result2))
  {
    if($row['Paper_N_I']=="national"){
      $national++;
    }
    else if($row['Paper_N_I']=="international"){
      $international ++;
    }
    if($row['presentation_status']=="Presented"){
      $Presented++;
    }
    else if($row['presentation_status']=="Not Presented"){
      $notpresented ++;
    }
    if($row['paper_category']=="peer reviewed"){
      $peer++;
    }
    else if($row['paper_category']=="non peer reviewed"){
      $nonpeer ++;
    }
    if($row['Paper_type']=="journal"){
      $journal++;
    }
    else if($row['Paper_type']=="conference"){
      $conference++;
    }
  }
}


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
            <i style="font-size:20px" class="fa fa-bar-chart"></i>
            <h1 class="box-title"><b>Charts</b></h1>
            <br>
            <b><a href="menu.php?menu=1 " style="font-size:15px;">Paper Publication</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="" style="font-size:15px;">&nbsp;Charts</a></b> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" style="width:100px; height:40px" id="print" onclick="window.print();">Print Charts</button>
            <a href="faculty_analysis_hod.php" class="btn btn-primary" style="height: 40px">Choose another Faculty </a>
          </div>
        </div>
      </div>
    </div>
    </div>
          <div class="row">

      <div class="col-md-6">
              <!-- CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><b>Bar Chart</b></h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <div id="piechart1" style="height:350px"></div>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
      </div>
      <div class="col-md-6">
              <!-- CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><b>Bar Chart</b></h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <div id="piechart2" style="height:350px"></div>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
      </div>
      <div class="col-md-6">
              <!-- CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><b>Bar Chart</b></h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <div id="piechart3" style="height:350px"></div>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
      </div>
      <div class="col-md-6">
              <!-- CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><b>Bar Chart</b></h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <div id="piechart4" style="height:350px"></div>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
      </div>
          </div>
        </section>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src = "plugins/chartjs/Chart.min.js"></script>
<script src="jquery/jquery-3.2.1.min.js"></script>
<script>
  google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart1);
      google.charts.setOnLoadCallback(drawChart2);
      google.charts.setOnLoadCallback(drawChart3);
      google.charts.setOnLoadCallback(drawChart4);

      function drawChart1() {

        var data = google.visualization.arrayToDataTable([
          ['National Paper', 'International Paper',{ role: 'annotation' },{role: 'style'}],
          ['national' , <?php echo $national; ?>,'Count: <?php echo $national ; ?>','color:#965fff'],
          ['international' ,<?php echo $international ; ?>,'Count: <?php echo $international ; ?>','color:#ff1955']
        ]);

        var options = {
          title: 'National Paper v/s International Paper',
          width: 600,
          height: 350,
          bar: {groupWidth: "50%"},
          /*vAxis: {
            minValue: 0,
          },
          hAxis: {
            minValue: 0,
          },*/
          legend: { position: "none" }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('piechart1'));

        chart.draw(data, options);
      }
      function drawChart2() {

        var data = google.visualization.arrayToDataTable([
          ['Journal', 'Conference',{ role: 'annotation' },{role: 'style'}],
          ['Journal' , <?php echo $journal; ?>,'Count: <?php echo $journal; ?>','color:#965fff'],
          ['Conference' ,<?php echo $conference; ?>,'Count: <?php echo $conference; ?>','color:#ff1955']
        ]);

        var options = {
          title: 'Journal v/s Conference',
          width: 600,
          height: 350,
          bar: {groupWidth: "50%"},
          legend: { position: "none" }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('piechart2'));

        chart.draw(data, options);
      }
      function drawChart3() {

        var data = google.visualization.arrayToDataTable([
          ['Presented', 'Not Presented',{ role: 'annotation' },{role: 'style'}],
          ['Presented' , <?php echo $Presented; ?>,'Count: <?php echo $Presented; ?>','color:#965fff'],
          ['Not Presented' ,<?php echo $notpresented; ?>,'Count: <?php echo $notpresented; ?>','color:#ff1955']
        ]);

        var options = {
          title: 'Presentation Status : Presented v/s Not Presented',
          width: 600,
          height: 350,
          bar: {groupWidth: "50%"},
          legend: { position: "none" }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('piechart3'));

        chart.draw(data, options);
      }

      function drawChart4() {

        var data = google.visualization.arrayToDataTable([
          ['Peer Reviewed', 'Non Peer Reviewed',{ role: 'annotation' },{role: 'style'}],
          ['Peer Reviewed' , <?php echo $peer; ?>,'Count: <?php echo $peer; ?>','color:#965fff'],
          ['Non Peer Reviewed' ,<?php echo $nonpeer; ?>,'Count: <?php echo $nonpeer; ?>','color:#ff1955']
        ]);

        var options = {
          title: 'Peer Reviewd v/s Non Peer Reviewd',
          width: 600,
          height: 350,
          bar: {groupWidth: "50%"},
          legend: { position: "none" }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('piechart4'));

        chart.draw(data, options);
      } 
                
</script>




 <?php include_once('footer.php'); ?>