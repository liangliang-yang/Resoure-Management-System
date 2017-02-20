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


if (isset($_GET['id'])){
	$id = $_GET['id'];
	$type = $_GET['type'];

	if ($type == 'RIU'){
		$date = new DateTime();
		$now = $date->format('Y-m-d-H-i-s');
		$query = "UPDATE Resource SET Resource_Status='Available', NextAvailableDate='$now' WHERE ResourceID=$id";
		mysqli_query($connect, $query);
	}

	if ($type == 'RR'){
		$incID = $_GET['incID'];
		$query = "DELETE FROM Request WHERE ResourceID=$id AND IncidentID=$incID";
		mysqli_query($connect, $query);
	}

	if ($type == 'DP'){
		$incID = $_GET['incID'];
		$curDate = date('Y-m-d');
		$expRD = $_GET['expRD'];
		echo $expRD;
		$query = "UPDATE Resource SET Resource_Status='InUse', NextAvailableDate='$expRD' WHERE ResourceID=$id";
		mysqli_query($connect, $query);
		$query = "UPDATE Request SET RequestStatus='Accepted', DeployDate='$curDate' WHERE ResourceID=$id AND IncidentID=$incID";
		mysqli_query($connect, $query);

	}

	if ($type == 'RJ'){
		$incID = $_GET['incID'];
		$query = "UPDATE Request SET RequestStatus='Rejected' WHERE ResourceID=$id AND IncidentID=$incID";
		mysqli_query($connect, $query);
		
	}
	
	if ($type == 'RP'){
		$query = "DELETE FROM Repair WHERE ResourceID=$id";
		mysqli_query($connect, $query);
	}

}

$query_ResInUse = "SELECT R.ResourceID, R.ResourceName, I.Description, U.Name, RE.DeployDate, RE.ExpectedReturnDate,'Return' as Action ". 
	"FROM Request AS RE 
	INNER JOIN Resource AS R ON RE.ResourceID = R.ResourceID
	INNER JOIN Incident AS I ON RE.IncidentID = I.IncidentID
	INNER JOIN User AS U on U.UserName=R.UserName ".
	"WHERE I.UserName='{$_SESSION['UserName']}' AND R.Resource_Status='InUse' AND RE.RequestStatus='Accepted' AND RE.ExpectedReturnDate > CURDATE()";

$result_ResInUse = mysqli_query($connect, $query_ResInUse);
// $row_ResInUse = mysqli_fetch_array($result_ResInUse);
// echo "{$row_ResInUse['ResourceID']}";


$query_ResReqested = "SELECT R.ResourceID, R.ResourceName, I.Description, I.IncidentID, U.Name as Owner, RE.ExpectedReturnDate, 'Cancel' as Action ".
	"FROM Request AS RE
	INNER JOIN Resource AS R ON RE.ResourceID = R.ResourceID
	INNER JOIN Incident AS I ON RE.IncidentID = I.IncidentID
	INNER JOIN User AS U on U.UserName=R.UserName ".
	"WHERE I.UserName='{$_SESSION['UserName']}' AND RE.RequestStatus='Pending' AND RE.ExpectedReturnDate > CURDATE() ";

$result_ResReqested = mysqli_query($connect, $query_ResReqested);
// $row_ResReqested = mysqli_fetch_array($result_ResReqested);


$query_ResReceived = "SELECT R.ResourceID, R.ResourceName, I.Description, I.IncidentID, U.Name as Request_by, RE.ExpectedReturnDate,
	(CASE WHEN R.Resource_Status='Available' THEN 'Deploy, Reject' WHEN R.Resource_Status='InUse' then 'Reject' END) as Action ".
	"FROM Request AS RE
	INNER JOIN Resource AS R ON RE.ResourceID = R.ResourceID
	INNER JOIN Incident AS I ON RE.IncidentID = I.IncidentID
	INNER JOIN User AS U on U.UserName=I.UserName ".
	"WHERE R.UserName='{$_SESSION['UserName']}' AND RE.RequestStatus='Pending' AND RE.ExpectedReturnDate > CURDATE()";

$result_ResReceived = mysqli_query($connect, $query_ResReceived);
// $row_ResReceived = mysqli_fetch_array($result_ResReceived);


$query_Repair = "SELECT R.ResourceID, R.ResourceName, REP.StartDate, REP.ReturnDate, R.Resource_Status ".
	"FROM Repair AS REP 
	INNER JOIN Resource AS R ON REP.ResourceID=R.ResourceID ".
	"WHERE R.UserName='{$_SESSION['UserName']}' AND REP.ReturnDate > CURDATE()";

$result_Repair = mysqli_query($connect, $query_Repair);
// $row_Repair = mysqli_fetch_array($result_Repair);

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Resource Status</title>
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
					<li class="selected"><a href="resource_status.php">Resource Status</a></li>
					<li><a href="resource_report.php">Resource Report</a></li>
					<li><a href="exit.php">Exit</a></li>              
				</ul>
			</div>
			

			<div class="center_content">
			
					
					 <div class="title_name"><?php print "Resource Status"; ?></div>          
					
										
					<div class="features">   
						
						<div class="profile_section">
							
							<div class="subtitle">Resource in use</div>   

							<style type="text/css">
							.tg  {border-collapse:collapse;border-spacing:0;}
							.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
							.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
							.tg .tg-yw4l{vertical-align:top}
							</style>
							<table class="tg" width="180%">
							  <tr>
							    <th class="tg-yw4l">Id</th>
							    <th class="tg-yw4l">Resource Name</th>
							    <th class="tg-yw4l">Incident</th>
							    <th class="tg-yw4l">Owner</th>
							    <th class="tg-yw4l">Start Date</th>
							    <th class="tg-yw4l">Return by</th>
							    <th class="tg-yw4l">Action</th>
							  </tr>

							   <?php 
							   
							   	while ($row_ResInUse = mysqli_fetch_array($result_ResInUse)){

							  	echo "<tr>";
							  		echo "<td>{$row_ResInUse['ResourceID']}</td>";
							    	echo "<td>{$row_ResInUse['ResourceName']}</td>";
								    echo "<td>{$row_ResInUse['Description']}</td>";
								    echo "<td>{$row_ResInUse['Name']}</td>";
								    echo "<td>{$row_ResInUse['DeployDate']}</td>";
								    echo "<td>{$row_ResInUse['ExpectedReturnDate']}</td>";
								    echo "<td><button><a href=resource_status.php?id={$row_ResInUse['ResourceID']}&type=RIU>Return</a></button></td>";
							  	echo "</tr>";
							  	}
								
							  ?>

							</table></br>

							<div class="subtitle">Resource Requested by me</div>   

							<table class="tg" width="180%">
							  <tr>
							    <th class="tg-yw4l">Id</th>
							    <th class="tg-yw4l">Resource Name</th>
							    <th class="tg-yw4l">Incident</th>
							    <th class="tg-yw4l">Owner</th>
							    <th class="tg-yw4l">Return by</th>
							    <th class="tg-yw4l">Action</th>
							  </tr>

							  <?php 
							   
							   	while ($row_ResReqested = mysqli_fetch_array($result_ResReqested)){
							  	echo "<tr>";
							  		echo "<td>{$row_ResReqested['ResourceID']}</td>";
							    	echo "<td>{$row_ResReqested['ResourceName']}</td>";
								    echo "<td>{$row_ResReqested['Description']}</td>";
								    echo "<td>{$row_ResReqested['Owner']}</td>";
								    echo "<td>{$row_ResReqested['ExpectedReturnDate']}</td>";
								    echo "<td><button><a href=resource_status.php?id={$row_ResReqested['ResourceID']}&type=RR&incID={$row_ResReqested['IncidentID']}>Cancel</a></button></td>";
							  	echo "</tr>";
							  	}
								
							  ?>
							</table></br>

							<div class="subtitle">Resource Requests received by me</div>   

							<table class="tg" width="180%">
							  <tr>
							    <th class="tg-yw4l">Id</th>
							    <th class="tg-yw4l">Resource Name</th>
							    <th class="tg-yw4l">Incident</th>
							    <th class="tg-yw4l">Requested by</th>
							    <th class="tg-yw4l">Return by</th>
							    <th class="tg-yw4l">Action</th>
							  </tr>
							  <?php 
							   
							   	while ($row_ResReceived = mysqli_fetch_array($result_ResReceived)){
							  	echo "<tr>";
							  		echo "<td>{$row_ResReceived['ResourceID']}</td>";
							    	echo "<td>{$row_ResReceived['ResourceName']}</td>";
								    echo "<td>{$row_ResReceived['Description']}</td>";
								    echo "<td>{$row_ResReceived['Request_by']}</td>";
								    echo "<td>{$row_ResReceived['ExpectedReturnDate']}</td>";
								    
								    if ($row_ResReceived['Action'] == 'Reject'){
								    	echo "<td><button><a href=resource_status.php?id={$row_ResReceived['ResourceID']}&type=RJ&incID={$row_ResReceived['IncidentID']}>Reject</a></button></td>";
								    } else if ($row_ResReceived['Action'] == 'Deploy, Reject'){
								    	echo "<td><button><a href=resource_status.php?id={$row_ResReceived['ResourceID']}&type=DP&incID={$row_ResReceived['IncidentID']}&expRD={$row_ResReceived['ExpectedReturnDate']}>Deploy</a></button><button><a href=resource_status.php?id={$row_ResReceived['ResourceID']}&type=RJ&incID={$row_ResReceived['IncidentID']}>Reject</a></button></td>";
								    } else {
								    	echo "<td></td>";
								    }
								    
							  	echo "</tr>";
							  	}
								
							  ?>
							</table></br>


							<div class="subtitle">Repairs Scheduled / In-progress</div>   

							<table class="tg" width="180%">
							  <tr>
							    <th class="tg-yw4l">Id</th>
							    <th class="tg-yw4l">Resource Name</th>
							    <th class="tg-yw4l">Start on</th>
							    <th class="tg-yw4l">Ready by</th>
							    <th class="tg-yw4l">Action</th>
							  </tr>
							  <?php 
							   
							   	while ($row_Repair = mysqli_fetch_array($result_Repair)){
							  	echo "<tr>";
							  		echo "<td>{$row_Repair['ResourceID']}</td>";
							    	echo "<td>{$row_Repair['ResourceName']}</td>";
								    echo "<td>{$row_Repair['StartDate']}</td>";
								    echo "<td>{$row_Repair['ReturnDate']}</td>";
								    if ($row_Repair['Resource_Status'] == 'InRepair'){
								    	echo "<td></td>";
								    } else {
								    	echo "<td><button><a href=resource_status.php?id={$row_Repair['ResourceID']}&type=RP>Cancel</a></button></td>";
									}

							  	echo "</tr>";
							  	}
								
							  ?>
							</table></br>
					</div>
				</div>



				<div id="footer">                                              
					     
				</div>
			
		 	</div>
		</div>

	</body>
</html>
