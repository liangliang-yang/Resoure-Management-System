<?php


/* connect to database */	
$connect = mysqli_connect("localhost", "erms", "123456","erms_team26");
if (mysqli_connect_errno()) {
 die( "Unable to select database: ") . mysqli_connect_error() ." (" . mysqli_connect_errno() . ")";
 }

session_start();
if (!isset($_SESSION['UserName'])) {
	header('Location: login.php');
	exit();
}

unset($result);


//* retrieve information from search resource* and validate fields//
	
if (isset($_GET['id'])){
	$ResID = $_GET['id'];
	$IncID = $_GET['incID'];

	$query = "SELECT ResourceID, ResourceName FROM Resource WHERE ResourceID = '$ResID'";
	$result=mysqli_query($connect, $query);
	 
	if (!$result) {
	print '<p class="error">Error: ' . mysql_error() . '</p>';
	exit();
	}
	$row = mysqli_fetch_assoc($result);
	if (!$row) {
	print '<p class="error">Error: Cannot find resource with ID: ' . $id . '</p>';
	exit();
	}	 

	$ResourceName = $row['ResourceName'];

	$query = "SELECT IncidentID, Description FROM Incident WHERE IncidentID = '$IncID'";
	$result=mysqli_query($connect, $query);
	$row = mysqli_fetch_assoc($result);
	$incident = $row['Description'];
	
	$updated = FALSE;
}	

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$errorMsg = "";
	$ResID = $_POST['ResID'];
	$ResourceName = $_POST['ResourceName'];
	$ExpectedReturnDate = $_POST['ExpectedReturnDate'];
	$IncID = $_POST['IncID'];
	$incident = $_POST['incident'];
	$now = date('Y-m-d');
	$updated = TRUE;

	if (empty($ExpectedReturnDate)) {
		$errorMsg = "Error: You must provide a Expected Return Date.";
	}
	
	else if ($ExpectedReturnDate<$now) {
		$errorMsg = "Error: Expected return date must be greater than next abvailable date.";
	}

	else {
		$query1 = "INSERT INTO Request(ResourceID, IncidentID, RequestTime, RequestStatus, ExpectedReturnDate, DeployDate) 
			VALUES ('$ResID', '$IncID', '$now', 'Accepted', '$ExpectedReturnDate', '$now')";

		$result1 = mysqli_query($connect,$query1);
		if (!$result1) {
			$errorMsg = "Error: Failed to request resource, you may already requested this resource for this incident.";
		} else {

			$query2 = "UPDATE Resource SET Resource_Status='InUse', NextAvailableDate='$ExpectedReturnDate' WHERE ResourceID = '$ResID'";

			$result2 = mysqli_query($connect,$query2);
			if (!$result2) {
				$errorMsg = "Error: Failed to update resource.";
			}
		}
	}

}
// print '<p class="error">Final query1 = ' . $query1 . '</p>';	
//print '<p class="error">Final query2= ' . $query2 . '</p>';	


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>ERMS Deploy Resource</title>
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
					<li class="selected"><a href="search_resources.php">Search Resources</a></li>
					<li><a href="resource_status.php">Resource Status</a></li>
					<li><a href="resource_report.php">Resource Report</a></li>
					<li><a href="exit.php">Exit</a></li>               
				</ul>
			</div>
			
			<div class="center_content">
			
				<div class="center_left">
					<div class="title_name"><font size="2">Click Save to Deploy!</font></div>          
					
					<div class="features">   
						
						<div class="profile_section">
							
							<form name="requestform" action="deploy.php" method="post">
								<table width="80%">								
									<tr>
										<td class="item_label">ResourceID: </td>
										<td><?php echo $ResID; ?></td>
										
									</tr>
									<tr>
										<td class="item_label">Resource Name: </td>
										<td><?php echo $ResourceName; ?></td>
									</tr>

									<tr>
										<td class="item_label">Incident: </td>
										<td><?php echo $incident; ?></td>
									</tr>

									
									<?php

									if ($updated){
										echo "<tr> 
												<td class='item_label'>Return Date:</td>
												<td>$ExpectedReturnDate</td>
											</tr>";

									} else {
										
										echo "<tr>
											<td class='item_label'>Expected Return Date: </td>
											<td><input type='date' id='ExpectedReturnDate' name='ExpectedReturnDate' /></td>
										</tr> ";
					
									}
									?>
									<tr><td></td></tr>

									
								</table>

							<input type="hidden" name="ResID" value="<?php print $ResID; ?>" />
							<input type="hidden" name="ResourceName" value="<?php print $ResourceName; ?>" />
							
							<input type="hidden" name="IncID" value="<?php print $IncID; ?>" />
							<input type="hidden" name="incident" value="<?php print $incident; ?>" />
							<input type="submit" value="Save" name="save"/>
				   			<button><a href=search_resources.php>Return</a></button>
							</form>
																				
						</div>
						
						<?php
							if (!empty($errorMsg)) {
							print "<div class='profile_section' style='color:red'>$errorMsg</div>";
						}
						?>    

					</div>
					</div>
					
					
					<div id="footer"> </div>
					</div>
					</div>
					
			</div>

	</body>
</html>
