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
	$IncidID = $_GET['incid'];
	// $NextAvailable = $_GET['nextavail'];
	$query = "SELECT ResourceName, UserName, Resource_Status, NextAvailableDate
		FROM Resource WHERE ResourceID = '$ResID'";

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
	$ResOwner = $row['UserName'];
	$ResStatus = $row['Resource_Status'];
	$NextAvailable = $row['NextAvailableDate'];

	$query = "SELECT Description 
		FROM Incident WHERE IncidentID='$IncidID'";

	$result=mysqli_query($connect, $query);
	$row = mysqli_fetch_assoc($result);
	
	$incident = $row['Description'];
	$updated = FALSE;

}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$errorMsg = "";
	$ExpectedReturnDate = mysqli_real_escape_string($connect,$_POST['ExpectedReturnDate']);
	$ResID = $_POST['ResID'];
	$IncidID = $_POST['IncidID'];
	$NextAvailable = $_POST['NextAvailable'];
	$ResourceName = $_POST['ResourceName'];
	$ResStatus = $_POST['ResStatus'];
	$ResOwner = $_POST['ResOwner'];
	$incident = $_POST['incident'];
	$updated = TRUE;

	/* validate form */
	if (empty($ExpectedReturnDate)) {
		$errorMsg = "Error: You must provide a Expected Return Date.";
	}
	
	else if ($ExpectedReturnDate<$NextAvailable) {
		$errorMsg = "Error: Expected return date must be greater than next abvailable date.";
	}
	
	else {
		$query1 = "INSERT INTO Request (IncidentID, ResourceID, RequestTime, RequestStatus, ExpectedReturnDate) " .
				 "VALUES ($IncidID, $ResID,CURRENT_TIMESTAMP,'Pending','$ExpectedReturnDate')";
		$result1 = mysqli_query($connect,$query1);
		if (!$result1) {
			
			$errorMsg = "You have already requested the resource!";
		}
		
	}
	
}
//print '<p class="error">Final query = ' . $query . '</p>';	



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>ERMS Request Resource</title>
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
					<div class="title_name">Request Resource</div>          
					
					<div class="features">   
						
						<div class="profile_section">
							
							<form name="requestform" action="request.php" method="post">
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

								<tr>
									<td class="item_label">Resource Owner: </td>
									<td><?php echo $ResOwner; ?></td>
								</tr>

								<tr>
									<td class="item_label">Resource Status: </td>
									<td><?php echo $ResStatus; ?></td>
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
								
								
							
							</table>
							<input type="hidden" name="ResID" value="<?php print $ResID; ?>" />
							<input type="hidden" name="IncidID" value="<?php print $IncidID; ?>" />
							<input type="hidden" name="NextAvailable" value="<?php print $NextAvailable; ?>" />
							<input type="hidden" name="ResStatus" value="<?php print $ResStatus; ?>" />
							<input type="hidden" name="ResOwner" value="<?php print $ResOwner; ?>" />
							<input type="hidden" name="incident" value="<?php print $incident; ?>" />
							<input type="hidden" name="ResourceName" value="<?php print $ResourceName; ?>" />
							<input type="submit" value="Save" name="save"/>
				   			<input type="reset" value="Reset" name="reset"/>
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
				
				<div class="clear"></div> 
			
			</div>    

		
			<div id="footer">                                              
				<div class="right_footer"><a href="http://csstemplatesmarket.com"  target="_blank">http://csstemplatesmarket.com</a></div>       
			</div>
			
		 
		</div>

	</body>
</html>
