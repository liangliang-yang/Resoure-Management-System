<?php

//* connect to database */	
$connect = mysqli_connect("localhost", "erms", "123456","erms_team26");

if (mysqli_connect_errno()) {
 die( "Unable to select database: ") . mysqli_connect_error() ." (" . mysqli_connect_errno() . ")";
 }

session_start();
if (!isset($_SESSION['UserName'])) {
	header('Location: login.php');
	exit();
}

$query = "SELECT E.ESFID, E.ESF_D, COALESCE(C.Total_cnt,0) as total_cnt, COALESCE(C.in_use_cnt,0) as in_use_cnt ". 
	"FROM ESFs AS E LEFT JOIN 
		( SELECT Primary_ESFID, COUNT(*) AS Total_cnt, (CASE WHEN Resource_Status = 'InUse' THEN 1 ELSE 0 END) AS in_use_cnt 
		FROM Resource WHERE UserName='{$_SESSION['UserName']}' GROUP BY Primary_ESFID ) C 
		on E.ESFID=C.Primary_ESFID ORDER BY E.ESFID ASC ";

$result = mysqli_query($connect, $query);

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Resource Report</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	
	<body>

		<div id="main_container">
			
			<div id="header">
				<div class="logo"><img src="images/erms_logo.gif" border="0" alt="" title="" style="width:px;height:80px;" /></div>       
			</div>
			
			<div class="menu">
				<ul>                                                                         
					<li><a href="menu.php">Main Menu</a></li>
					<li><a href="add_resource.php">Add Resource</a></li>
					<li><a href="add_incident.php">Add Emergency Incident</a></li>
					<li><a href="search_resources.php">Search Resources</a></li>
					<li><a href="resource_status.php">Resource Status</a></li>
					<li class="selected"><a href="resource_report.php">Resource Report</a></li>
					<li><a href="exit.php">Exit</a></li>              
				</ul>
			</div>
			

			<div class="center_content">
			
					
					 <div class="title_name"><?php print "Resource Report"; ?></div>          
					
										
					<div class="features">   
						
						<div class="profile_section">
							
						<div class="subtitle">Resource Report by Primary Emergency Support Function</div> 

							<style type="text/css">
							.tg  {border-collapse:collapse;border-spacing:0;}
							.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
							.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
							.tg .tg-yw4l{vertical-align:top}
							</style>
							<table class="tg" width="180%">
							  <tr>
							    <th class="tg-yw4l">#</th>
							    <th class="tg-yw4l">Primary Emergency Support Function</th>
							    <th class="tg-yw4l">Total Resources</th>
							    <th class="tg-yw4l">Resources in Use</th>
							    
							  </tr>
							  <?php 
							   	$sum_total = 0;
							   	$sum_inUse = 0;
							   	while ($row = mysqli_fetch_array($result)){
							   		$sum_total += $row['total_cnt'];
							   		$sum_inUse += $row['in_use_cnt'];
								  	echo "<tr>";
								  		echo "<td>{$row['ESFID']}</td>";
								    	echo "<td>{$row['ESF_D']}</td>";
									    echo "<td>{$row['total_cnt']}</td>";
									    echo "<td>{$row['in_use_cnt']}</td>";

								  	echo "</tr>";
							  	}

							  	echo "<tr>";
							  		echo "<td></td>";
							    	echo "<td><b>TOTALS</b></td>";
								    echo "<td><b>$sum_total</b></td>";
								    echo "<td><b>$sum_inUse</b></td>";
							  	echo "</tr>";
								
							  ?>

							</table>
						</div>
					</div>



				<div id="footer">                                              
					     
				</div>

			
		 	
		</div>

	</body>
</html>
