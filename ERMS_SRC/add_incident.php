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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errorMsg = "";
	$date = mysqli_real_escape_string($connect, $_POST['date']);
	$description = mysqli_real_escape_string($connect, $_POST['description']);
	$lat = mysqli_real_escape_string($connect, $_POST['latitude']);
	$lng = mysqli_real_escape_string($connect, $_POST['longitude']);


	if (!empty($date) && !empty($description) && !empty($lat) && !empty($lng)){
		if (abs(floatval($lat)) <= 90 && abs(floatval($lng)) <= 180){
			$query_incident = "INSERT INTO Incident (Username, DateAdd, Description, Latitude, Longitude)" . "VALUES ('{$_SESSION['UserName']}', '$date', '$description', '$lat', '$lng')";

			if (!mysqli_query($connect, $query_incident)) {
				$errorMsg = "Error: Failed to add incident.";
			}
		} else {
			$errorMsg = "Error: latitude must between -90 and +90, longitude must between -180 and +180.";
		}
	} else {
		$errorMsg = "Error: All values must be entered.";
	}

}

$query_incidentID = "SELECT MAX(IncidentID) " . "FROM Incident";
$result_incidentID = mysqli_query($connect, $query_incidentID);
$row_incidentID = mysqli_fetch_array($result_incidentID);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Add Incident</title>
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
					<li class="selected"><a href="add_incident.php">Add Emergency Incident</a></li>
					<li><a href="search_resources.php">Search Resources</a></li>
					<li><a href="resource_status.php">Resource Status</a></li>
					<li><a href="resource_report.php">Resource Report</a></li>
					<li><a href="exit.php">Exit</a></li>              
				</ul>
			</div>
			
			<div class="center_content">
			
			  
				<div class="title_name"><?php echo "New Incident Info" ; ?></div>          
					
				<div class="center_left">	

				<div class="features">  

				<form method="post" action="add_incident.php">
					<table cellpadding="8" width="80%">								
						<tr>
							<td class="item_label">IncidentID: </td>
							<td><?php echo $row_incidentID['MAX(IncidentID)'] + 1; ?></td>
						</tr>
						<tr>
							<td class="item_label">Date: </td>
							<td><input type="date" id="date" name="date" /></td>
						</tr>
						
						
						<tr>
							<td class="item_label">Description: </td>
							<td><input type="text" id="description" name="description" /></td>
						</tr>
						
						<tr>
							<td class="item_label">Location: </td>
						
							<td>
								<table>
								<tr>
								<td class="item_label">Lat: </td>
								<td><input type="text" id="latitude" name="latitude" /></td>
								</tr>
								<tr>
								<td class="item_label">Lng: </td>
								<td><input type="text" id="longitude" name="longitude" /></td>
								</tr>
								</table>
							</td>
						</tr>
						<tr><td></td></tr>
						
					</table>

					<input type="submit" value="Save" name="save"/>
	   				<input type="reset" value="Cancel" name="cancel"/>

	   				<?php
						if (!empty($errorMsg)) {
						print "<div class='profile_section' style='color:red'>$errorMsg</div>";
						}
					?> 


				</form>  
				</div>
				</div>

				<div id="footer">                                              
					     
				</div>
			
		 	
			</div>

	</body>
</html>
