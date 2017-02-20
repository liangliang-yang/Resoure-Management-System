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

// use format: href=repair.php?id=$id

if (isset($_GET['id'])){
	$id = $_GET['id'];
	$query = "SELECT ResourceID, ResourceName, Resource_Status, NextAvailableDate 
		FROM Resource WHERE ResourceID = $id ";
	$result = mysqli_query($connect, $query);
	$row = mysqli_fetch_array($result);
	$ResourceID = $row['ResourceID'];
	$ResourceName = $row['ResourceName'];
	$ResourceStatus = $row['Resource_Status'];
	$NextAvailableDate = $row['NextAvailableDate'];
	$updated = FALSE;

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errorMsg = "";
	$days = mysqli_real_escape_string($connect, $_POST['ndays']);
	$ResourceID = $_POST['id'];
	$NextAvailableDate = $_POST['NextAD'];
	$ResourceName = $_POST['ResName'];
	$ResStatus = $_POST['ResStatus'];
	if ($ResStatus == 'Available'){
		$ResourceStatus = 'InRepair';
	} else {
		$ResourceStatus = 'InUse';
	}
	$updated = TRUE;

	if (empty($days)) {
		$errorMsg = "Error: You must provide a Expected Return Date.";
	} else if (intval($days) < 0){
		$errorMsg = "Error: You must provide a valid Expected Return Date.";
	} else {

	if ($ResStatus == 'InUse'){
		$returnDate = DateTime::createFromFormat('Y-m-d H:i:s', $NextAvailableDate);
		$nextAD = $returnDate->format('Y-m-d');

		$returnDate->add(new DateInterval('P'.$days.'D'));

		$returnDate = $returnDate->format('Y-m-d');
		// $ResourceStatus = 'InRepair';
		// $updated = TRUE;

		$query = "INSERT INTO Repair (ResourceID, StartDate, ReturnDate)
		VALUES ($ResourceID, '$nextAD', '$returnDate') " ;
	
		if (!mysqli_query($connect, $query)) {
			$errorMsg = "Error: Failed to request repair, you may already requested repair.";
		
		}
	}

	if ($ResStatus == 'Available'){
		$date = new DateTime();
		$now = $date->format('Y-m-d');
		$date->add(new DateInterval('P'.$days.'D'));
		$returnDate = $date->format('Y-m-d');
		$date = $date->format('Y-m-d H:i:s');
		// $ResourceStatus = 'InUse';
		// $updated = TRUE;

		$query = "INSERT INTO Repair (ResourceID, StartDate, ReturnDate)
 			VALUES ($ResourceID, '$now', '$returnDate') ";
 		if (!mysqli_query($connect, $query)) {
			$errorMsg = "Error: Failed to request repair, you may already requested repair.";
		}

   		$query = "UPDATE Resource SET Resource_Status='InRepair', NextAvailableDate='$date' WHERE ResourceID=$ResourceID ";
   		if (!mysqli_query($connect, $query)) {
			$errorMsg = "Error: Failed to request repair, you may already requested repair.";

		}
	}
	}
}

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>ERMS MENU</title>
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
			
					
					 <div class="title_name">Repair</div>          
					
										
					<div class="features">   
						
						<div class="profile_section">
							
							<div class="subtitle">Enter repair info</div>

							<form method="post" action="repair.php">
								<table width="80%">								
									<tr>
										<td class="item_label">ResourceID: </td>
										<td><?php echo $ResourceID; ?></td>
										
									</tr>
									<tr>
										<td class="item_label">Resource Name: </td>
										<td><?php echo $ResourceName; ?></td>
									</tr>


									<tr>
										<td class="item_label">Resource Status: </td>
										<td><?php echo $ResourceStatus; ?></td>
									</tr>
									
									<?php

									if ($updated){
										echo "<tr> 
												<td class='item_label'>Return Date:</td>
												<td>$returnDate</td>
											</tr>";
									} else {
										
										if ($row['Resource_Status'] == 'InUse'){
										echo "<tr>
											<td class='item_label'>For n days after return: </td>
											<td><input type='text' id='ndays' name='ndays' /></td>
										</tr> ";
										}
										if ($row['Resource_Status'] == 'Available'){
										echo "<tr>
											<td class='item_label'>For next n days: </td>
											<td><input type='text' id='ndays' name='ndays' /></td>
											
										</tr> ";
										}
									
									}
									?>
									<tr><td></td></tr>

									
								</table>

								<input type="hidden" name="id" value="<?php echo $ResourceID; ?>">
								<input type="hidden" name="ResName" value="<?php echo $ResourceName; ?>">
								<input type="hidden" name="ResStatus" value="<?php echo $ResourceStatus; ?>">
								<input type="hidden" name="NextAD" value="<?php echo $NextAvailableDate; ?>">
								<input type="submit" value="Save" name="save"/>
				   				<input type="reset" value="Reset" name="reset"/>
				   				<button><a href=search_resources.php>Return</a></button>

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
		</div>

	</body>
</html>
