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

/* if form was submitted, then execute query to search for resources */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$keyword = mysqli_real_escape_string($connect,$_POST['keyword']);
	$location = mysqli_real_escape_string($connect,$_POST['location']);
	$displayESF = mysqli_real_escape_string($connect,$_POST['displayESF']);
	$Des = mysqli_real_escape_string($connect,$_POST['Des']);
	

 		
	$query = "SELECT DISTINCT R.ResourceID, R.ResourceName, R.UserName, U.Name as Onwer, CONCAT(R.costvalue, '/', R.cost_per) as cost, R.Resource_Status, R.NextAvailableDate, R.Latitude, R.Longitude " .
			 "FROM Resource AS R INNER JOIN User AS U " .
			 "ON U.UserName=R.UserName";
	
	$query_add="SELECT DISTINCT R.ResourceID, R.ResourceName, R.UserName, R.Onwer, R.cost, R.Resource_Status, R.NextAvailableDate, I.IncidentID, I.Description, I.UserName AS IN_Owner, @dLat:=RADIANS(I.Latitude-R.Latitude),@dLon:=RADIANS(I.Longitude-R.Longitude), " .
	"@a:=SIN(@dLat/2)*SIN(@dLat/2)+COS(RADIANS(I.Latitude))*COS(RADIANS(R.Latitude))*SIN(@dLon/2)*SIN(@dLon/2),@c:=(2*ATAN2(SQRT(@a),SQRT(1-@a)))*6371 AS Distance FROM ("; 
		
	$query1="";
	$query1="";
	if (!empty($keyword) or !empty($displayESF)  ) {
	
		if (!empty($keyword)) {
			$query = $query . " LEFT JOIN Capabilities AS C ON R.ResourceID=C.ResourceID WHERE (R.ResourceName LIKE '%$keyword%' OR R.Model LIKE '%$keyword%' OR C.Capability LIKE '%$keyword%') ";
		}
		
		if (!empty($displayESF)) {
			$query = $query . " AND ((R.Primary_ESFID = (SELECT ESFID FROM ESFS WHERE CONCAT('(#',ESFID,') ',ESF_D)='$displayESF')) OR ((SELECT ESFID FROM ESFS WHERE CONCAT('(#',ESFID,') ',ESF_D)='$displayESF') IN (SELECT AdditionalESFID FROM AdditionalESFs AS A WHERE R.ResourceID=A.ResourceID))) ";
		} 
	}
	
	if (!empty($Des)){
		
		$query=$query_add.$query.") AS R CROSS JOIN (SELECT IncidentID, Description,UserName, Latitude, Longitude from Incident  " .
	    "WHERE CONCAT('(',Incident.IncidentID,') ', Incident.Description)='$Des') AS I";
		if (!empty($location)){
			$query="SELECT ResourceID,IncidentID, Description, ResourceName, UserName, Onwer, IN_Owner, cost,Resource_Status,NextAvailableDate, Distance FROM ". "(".$query.")" ." T WHERE Distance<=$location";
			}
		$query2 = $query . " ORDER BY Distance, ResourceName";	
	}
	else 
		$query1 = $query . " ORDER BY ResourceName";
	
	//echo '<p class="error">Final query = ' . $query1 . '</p>';
	//echo '<p class="error">Final query = ' . $query2 . '</p>';
	
	 
		
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>ERMS Search Resources</title>
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
					
					 <div class="title_name"><?php print "Search Resource"; ?></div>          
					
										
					<div class="features">   
						
						<div class="profile_section">
							
							<div class="subtitle">Search Resources</div>    
							
							<form name="searchform" action="search_resources.php" method="post">
							<table width="80%">								
								<tr>
									<td class="item_label">Keyword</td>
									<td><input type="text" name="keyword" /></td>
								</tr>
								<tr>
									<td class="item_label">ESF</td>
									<td>
									<?php
										$sql = "SELECT CONCAT('(#',ESFID,') ',ESF_D) AS displayESF FROM ESFs";
										$result_sql = mysqli_query($connect, $sql);

										echo "<select name='displayESF'>";
										echo '<option value=""></option>';
										while ($row = mysqli_fetch_array($result_sql)) {
											echo "<option value='" . $row['displayESF'] . "'>" . $row['displayESF'] . "</option>";
										}
										echo "</select>";
									?>
									</td>
								</tr>
								
								
								<tr>
									<td class="item_label">Location </td>
									<td>With<style="font-size: 35px;"><input type="text" name="location" />Kilometers of incident</td>
								</tr>
								
								<tr>
									<td class="item_label">Incident</td>
									<td>
									<?php
										$sql = "SELECT CONCAT('(',IncidentID,') ', Description) AS Des FROM Incident WHERE Username='{$_SESSION['UserName']}'";
										$result_sql = mysqli_query($connect, $sql);

										echo "<select name='Des'>";
										echo '<option value=""></option>';
										while ($row = mysqli_fetch_array($result_sql)) {
											echo "<option value='" . $row['Des'] . "'>" . $row['Des'] . "</option>";
										}
										echo "</select>";
									?>
									</td>
								</tr>
								
							</table>
							
							<a href="javascript:searchform.submit();" class="fancy_button">search</a> 
							
							</form>
							
														
						</div>
						
						<?php
						if (!empty($query1)){
							$result1 = mysqli_query($connect,$query1);
						if (isset($result1)) {
													
							print "<div class='profile_section'>";
							print "<div class='subtitle'>Search Results</div>";							
							print "<table width='180%'>";
							print "<tr><td class='heading'>ID</td><td class='heading'>Name</td><td class='heading'>Owner</td><td class='heading'>Cost</td><td class='heading'>Status</td><td class='heading'>Next Available</td></tr>";
							
							
							while ($row = mysqli_fetch_array($result1)){
								
								//$UserName = urlencode($row['UserName']);
								
								print "<tr>";
								print "<td>{$row['ResourceID']}</td>";
								print "<td>{$row['ResourceName']}</td>";
								print "<td>{$row['Onwer']}</td>";	
								print "<td>{$row['cost']}</td>";
								print "<td>{$row['Resource_Status']}</td>";
								print "<td>{$row['NextAvailableDate']}</td>";
								
								
								}
								print "</tr>";
								
							}
							print "</table>";
							print "</div>";
						}	
						
						if (!empty($query2)){
							$result2 = mysqli_query($connect,$query2);
							
						if (isset($result2)) {
													
							print "<div class='profile_section'>";
							print "<div class='subtitle'>Search Results For Incident: {$Des}</div>";	
															
							print "<table width='180%'>";
							print "<tr><td class='heading'>ID</td><td class='heading'>Name</td><td class='heading'>Owner</td><td class='heading'>Cost</td><td class='heading'>Status</td><td class='heading'>Next Available</td><td class='heading'>Distance (km)</td><td class='heading'>Action</td></tr>";
							
							
							while ($row = mysqli_fetch_array($result2)){
								
								//$UserName = urlencode($row['UserName']);
								
								print "<tr>";
								print "<td>{$row['ResourceID']}</td>";
								print "<td>{$row['ResourceName']}</td>";
								print "<td>{$row['Onwer']}</td>";	
								print "<td>{$row['cost']}</td>";
								print "<td>{$row['Resource_Status']}</td>";
								print "<td>{$row['NextAvailableDate']}</td>";
								print "<td>".round($row['Distance'],3)."</td>";
								if( $_SESSION['UserName']==$row['IN_Owner']& $row['IN_Owner']!= $row['UserName']& $row['Resource_Status']!="InRepair") {
								echo "<td><button><a href=request.php?id={$row['ResourceID']}&type=RQ&incid={$row['IncidentID']}>Request</a></button></td>";
								}
								if( $_SESSION['UserName']==$row['UserName'] & ($row['Resource_Status']=="Available") & $row['UserName']!=$row['IN_Owner']) {
								echo "<td><button><a href=deploy.php?id={$row['ResourceID']}&type=DP>Deploy</a></button></td>";
								echo "<td><button><a href=repair.php?id={$row['ResourceID']}&type=RQ>Repair</a></button></td>";
								}
								if( $_SESSION['UserName']==$row['UserName'] & ($row['Resource_Status']=="Available") & $row['UserName']==$row['IN_Owner']) {
								echo "<td><button><a href=deploy.php?id={$row['ResourceID']}&type=RQ&incID={$row['IncidentID']}>Deploy</a></button></td>";
								echo "<td><button><a href=repair.php?id={$row['ResourceID']}&type=RQ>Repair</a></button></td>";
								}
								if( $_SESSION['UserName']==$row['UserName'] & $row['Resource_Status']=="InUse") {
								echo "<td><button><a href=repair.php?id={$row['ResourceID']}&type=RQ>Repair</a></button></td>";
								}
								print "</tr>";
								
							}
							
							print "</table>";
							print "</div>";
						
						}
						}
						?>
			
					 </div> 
					
				</div> 
				
				<div class="clear"></div> 
			
			</div>    

		
			<div id="footer">                                              
				      
			</div>
			
		 
		</div>

	</body>
</html>
