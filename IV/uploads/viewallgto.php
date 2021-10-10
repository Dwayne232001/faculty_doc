<?php
$conn=mysqli_connect('localhost:3308','root','','preyash');
$sql="SELECT * FROM goingtoorganize";
$records=mysqli_query($conn,$sql);
?>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="table-responsive">          
  <table class="table">
	<tr>
		<th>Faculty name</th>
		<th>I.V Details</th>
		<th>Purpose</th>
		<th>Target Audience</th>
		<th>Staff</th>
		<th>Date</th>
		<th>From</th>
		<th>to</th>
		<!--<th>permission</th>-->
	</tr>
<?php
while($iv=mysqli_fetch_assoc($records))
{
	echo"<tr>";
	echo"<td>".$iv['f_name']."</td>";
	echo"<td>".$iv['ind'].",".$iv['city']."</td>";
	echo"<td>".$iv['purpose']."</td>";
	echo"<td>".$iv['tAudience']."</td>";
	echo"<td>".$iv['s_name']."</td>";
	echo"<td>".$iv['date']."</td>";
	echo"<td>".$iv['frm']."</td>";
	echo"<td>".$iv['tu']."</td>";
	//echo"<td>".$iv['permission']."</td>";
	//echo"<td><img src=".$iv['permission']." alt='permission' height='42' width='42'>";
	echo"</tr>";

}
?>
</table>
</div><br>
<form action=editdbgto.php method="POST">
<input type="submit" class="btn btn-primary" value="Edit">
</form>
</body>
</html>