<?php
/* connect to database */
$dbhost = "localhost";
$dbuser = "erms";
$dbpass = "123456";
$dbname = "erms_team26";
$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$connect) {
	die("Failed to connect to database");
}
session_start();
if (!isset($_SESSION['UserName'])) {
	header('Location: login.php');
	exit();
}
$query_resourceID = "SELECT MAX(ResourceID) " . "FROM Resource";
$result_resourceID = mysqli_query($connect, $query_resourceID);
$row_resourceID = mysqli_fetch_array($result_resourceID);
$new_resourceID = $row_resourceID['MAX(ResourceID)'] +1;
/* if form was submitted, then save/inser new data */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	/* validate form */
	
	$ResourceName = mysqli_real_escape_string($connect, $_POST['ResourceName']);
	$Model = mysqli_real_escape_string($connect, $_POST['Model']);
	$Latitude  = mysqli_real_escape_string($connect, $_POST['Latitude']);
	$Longitude   = mysqli_real_escape_string($connect, $_POST['Longitude']);
	$CostValue   = mysqli_real_escape_string($connect, $_POST['costvalue']);
	$CostPer   = mysqli_real_escape_string($connect, $_POST['costper']);
	$Primary_ESFID = mysqli_real_escape_string($connect, $_POST['esf']);

	$Additional_ESFID = isset($_POST['additional_esf']) ? $_POST['additional_esf'] : '';
	$capability = isset($_POST['capability']) ? $_POST['capability'] : '';


	$capabilityArray = explode("\n", $capability);
	


	$date = new DateTime();
	$now = $date->format('Y-m-d-H-i-s');
	if (!empty($ResourceName) && !empty($Latitude) && !empty($Longitude) && !empty($CostValue)){
		if(abs(floatval($Latitude)) <= 90 && abs(floatval($Longitude)) <= 180 && floatval($CostValue)>0) {
			$query_insert_resource = "INSERT INTO Resource (UserName, ResourceName, Model, costvalue, cost_per, Primary_ESFID, Latitude, Longitude, Resource_Status, NextAvailableDate)" .
					 "VALUES('{$_SESSION['UserName']}', '$ResourceName', '$Model', '$CostValue', '$CostPer', $Primary_ESFID, '$Latitude', '$Longitude', 'Available', '$now')";
			if (!mysqli_query($connect, $query_insert_resource)) {
				$errorMsg_resource = "Error: Failed to add resource.";
			}
			else{

				if(isset($_POST['additional_esf'])){
					foreach ($Additional_ESFID as $a)
						{
						$query_insert_additional_esfs = "INSERT INTO AdditionalESFs (ResourceID, AdditionalESFID)" .
								 "VALUES('$new_resourceID', '$a')";
						if (!mysqli_query($connect, $query_insert_additional_esfs)) {
							$errorMsg_esf = "Error: Failed to add additional ESF.";
						}
					}
				}

				if(!empty($_POST['capability'])){
					foreach ($capabilityArray as $c)
						{
						$query_insert_capability = "INSERT INTO Capabilities (ResourceID, Capability)" .
								 "VALUES('$new_resourceID', '$c')";
						if (!mysqli_query($connect, $query_insert_capability)) {
							$errorMsg_capability = "Error: Failed to add capability.";
						}
					}
				}
			}
		}
		else
		{
			$errorMsg_valid = "Error: Latitude: -90 to 90, Longitude: -180 to 180";
		}
	}
	else{
		$errorMsg_empty = "Error: Please fill in all the required fields.";
	}

}

$query_resourceID = "SELECT MAX(ResourceID) " . "FROM Resource";
$result_resourceID = mysqli_query($connect, $query_resourceID);
$row_resourceID = mysqli_fetch_array($result_resourceID);
$new_resourceID = $row_resourceID['MAX(ResourceID)'] +1;

$query_user = "SELECT User.Name " .
		 "FROM User " .
		 "WHERE User.UserName = '{$_SESSION['UserName']}'";
$result_user = mysqli_query($connect, $query_user);
if (!$result_user) {
	print "<p class='error'>User Error: " . mysql_error() . "</p>";
	exit();
}
$row_user = mysqli_fetch_array($result_user);
if (!$row_user) {
	print "<p>Error: No data returned from database.</p>";
	exit();
}
// query of ESFs
$query_esf = "SELECT ESFID, ESF_D FROM ESFs";
$result_esf = mysqli_query($connect, $query_esf);
if (!$result_esf) {
	print "<p class='error'>ESF Error: " . mysql_error() . "</p>";
	exit();
}
// query of ESFs again
$query_esf2 = "SELECT ESFID, ESF_D FROM ESFs";
$result_esf2 = mysqli_query($connect, $query_esf2);
if (!$result_esf2) {
	print "<p class='error'>ESF Error: " . mysql_error() . "</p>";
	exit();
}
// query of costper
$query_costper = "SELECT Unit FROM CostPer";
$result_costper = mysqli_query($connect, $query_costper);
if (!$result_costper) {
	print "<p class='error'>CostPer Error: " . mysql_error() . "</p>";
	exit();
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!-- <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  		<script class="jsbin" src="http://ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script> -->
  		<meta charset=utf-8 />
		<title>Add new resource</title>
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
						<li class="selected"><a href="add_resource.php">Add Resource</a></li>
						<li><a href="add_incident.php">Add Emergency Incident</a></li>
						<li><a href="search_resources.php">Search Resources</a></li>
						<li><a href="resource_status.php">Resource Status</a></li>
						<li><a href="resource_report.php">Resource Report</a></li>
						<li><a href="exit.php">Exit</a></li>
					</ul>
				</div>

			<div class="center_content">

					<div class="title_name">New Resource Info</div>

					<div class="features">

					<div class="profile_section">


					<form id="submitform" action="add_resource.php" method="post">

					<table cellpadding="6" width="200%">
						<?php
						$currentdate = date('m/d/Y');
						echo "<tr>
							<td class='item_label'>( * ) is the required field </td>
							</tr>";
						echo "<tr>
							<td class='item_label'>The Current Date is: </td>
							<td>$currentdate</td>
							</tr>";
						echo "<tr><td class='item_label'>Resource ID: </td><td> $new_resourceID</td></tr>";
						echo "<tr><td class='item_label'>Owner: </td><td> {$row_user['Name']} </td></tr>";
						echo "<tr><td class='item_label'>Resource Name ( * ): </td>
							<td><input type='text' name='ResourceName'></td></tr>";
						?>



						<tr>
							<td class='item_label'>Primary ESF ( * ):</td>

							<td>
							<?php
							echo "<select name='esf'>";
				    			while ($row_esf = mysqli_fetch_array($result_esf)) {
				                  		echo "<option value=' " .$row_esf['ESFID'] .  " '>". $row_esf['ESF_D'] ."</option>";
							}
				    			echo "</select>";
				    			?>
				    		</td>
				    	</tr>


					<tr>
						<td class='item_label'>Additional ESFs: </td>

						<td>
						<?php
						echo "<select name='additional_esf[ ]' multiple>";
			    			while ($row_esf2 = mysqli_fetch_array($result_esf2)) {
			                  		echo "<option value=' " .$row_esf2['ESFID'] .  " '>". $row_esf2['ESF_D'] ."</option>";
						}
			    			echo "</select>";
			    			?>
			    		</td>
			    	</tr>



					<tr><td class='item_label'>Model: </td><td><input type="text" name="Model"></td></tr>

					<tr><td class='item_label'>Capabilities: </td>
					<td><div id="display" ></div>

						<input id="capa" type="text" name="capa"  />
				    	<input type="button" value="Add" onclick="insert()" />
			    	</td>
			    	</tr>

					<tr>
						<td class='item_label'>Home Location ( * ) </td>
						<td>
							<table>
								<tr><td>Latitude ( * ): </td><td>Longitude ( * ):</td></tr>
								<tr>
									<td><input type="text" name="Latitude"></td>
									<td><input type="text" name="Longitude"></td>
								</tr>
							</table>
					</tr>

					<tr>
						<td class='item_label'>Cost ( * ) </td>
						<td>
						<table>
						<tr>
							<td>
							$: <input type="text" name="costvalue">
							</td>

							<td>Per
							<?php
							echo "<select name='costper'>";
				    			while ($row_costper = mysqli_fetch_array($result_costper)) {
				                  		echo "<option value='{$row_costper['Unit']}'>".$row_costper['Unit']."</option>";
							}
				    			echo "</select>";
				    			?>
				    		</td>
			    		</tr>
			    		</table>
			    		</td>
			    	</tr>


					</table>

			    	<input type="submit" value="Save">
			    	<input type="reset" value="Cancel">

			    	<?php
					if (!empty($errorMsg_resource)) {
					print "<div class='profile_section' style='color:red'>$errorMsg_resource</div>";
					}
				?>

				<?php
					if (!empty($errorMsg_esf)) {
					print "<div class='profile_section' style='color:red'>$errorMsg_esf</div>";
					}
				?>

				<?php
					if (!empty($errorMsg_capability)) {
					print "<div class='profile_section' style='color:red'>$errorMsg_capability</div>";
					}
				?>

				<?php
					if (!empty($errorMsg_valid)) {
					print "<div class='profile_section' style='color:red'>$errorMsg_valid</div>";
					}
				?>

				<?php
					if (!empty($errorMsg_empty)) {
					print "<div class='profile_section' style='color:red'>$errorMsg_empty</div>";
					}
				?>
					<!-- <a href="javascript:profileform.submit();" class="fancy_button">save</a> -->
					</form>
					</div>
					</div>

					<div id="footer"></div>
				</div>
			</div>


			<script type="text/javascript">
	  			var capas  = [];
				var capaInput  = document.getElementById("capa");
				var messageBox  = document.getElementById("display");
				messageBox.innerHTML = "<textarea name ='capability' rows='3'></textarea>";
				function insert ( ) {
					if (capas.indexOf(capaInput.value) == -1 &&  capaInput.value !="") {
	 				capas.push( capaInput.value );
	 				}
					clearAndShow();
				}
				function clearAndShow () {
	  			// Clear our fields
	  				capaInput.value = "";
	  			// Show our output
	  				messageBox.innerHTML = "";
					messageBox.innerHTML +=  "<textarea name ='capability' rows='3'>" + capas.join("\n") + "</textarea>";
				}
	  		</script>




	</body>
</html>
