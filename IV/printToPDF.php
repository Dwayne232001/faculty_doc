<?php 
ob_start();
 if(session_status() == PHP_SESSION_NONE)
   {
    session_start();
   }
 if(!isset($_SESSION['username']))
   {
    header("refresh:2,url=index.php");
   }

   include_once("IVsql.php");
   $type = $_SESSION['type'];
    set_include_path(get_include_path() . PATH_SEPARATOR . "/dompdf");
   include_once 'dompdf/dompdf_config.inc.php';

   $conn=mysqli_connect('localhost','root','','department');
		
   $header="<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Industrial Visit $type</p><hr>"; 

   $f_name = mysqli_fetch_assoc(getFacultyDetails($_SESSION['f_id']))['F_NAME'];


		if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
	{
		$selection="<th>Faculty Name</th>";
	}
	else{
	$selection="";	
  }

   $table=$header."";
   $sql = $_SESSION['table_query'];
  

   $result=mysqli_query($conn,$sql);  
   $dompdf = new DOMPDF();
   if(isset($_GET['flag']))
   {
    $table=$header."<table border=1 width=100% align=center>
            <tr>
            <th>Activity Name</th>
            <th>Total Number of Activity</th>
            </tr>
            <tr>
            <td align=center>$_GET[type]</td>
            <td align=center>$_GET[count]</td>
            </tr>
            </table>
            <br>
            <hr>
            <p>Faculty Name: ".$f_name."</p> 
            <p>Date :$_GET[date]</p>  
            </pre>
            <hr>
           ";
   } 

  if(mysqli_num_rows($result)>0)
                      {
                        $table =$table."<table border=1 width=100% cellspacing=0px;>
                           <thead>
                            <tr>
                            ".$selection."<th>Industry Name</th>
                            <th>City</th>
                            <th>Purpose</th>
                            <th>From</th>
                            <th>To</th>
                            </tr>" ;

                          while($employee=mysqli_fetch_assoc($result))
                          {
                            $table = $table."<tr>";

							if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
                            {
                            $table = $table."<td>".$employee['F_NAME']."</td>";
                        	  }
                            $table = $table."<td>".$employee['ind']."</td>";
                            $table = $table."<td>".$employee['city']."</td>";
                            $table = $table."<td>".$employee['purpose']."</td>";
                            $table = $table."<td>".date("d-m-Y",strtotime($employee['t_from']))."</td>";
                            $table = $table."<td>".date("d-m-Y",strtotime($employee['t_to']))."</td>";
                            $table = $table."</tr>";
                           }  
                         }                          
 ?>

</thead></table>
<?php 
   
                  $dompdf->load_html($table);
                  $dompdf->render();
                  $dompdf->set_paper('a4', 'portrait');
                  //$dompdf->stream("hello.pdf");
                  $dompdf->stream('hi',array('Attachment'=>0));

?>