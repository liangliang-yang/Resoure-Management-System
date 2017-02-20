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



/* display user  */
// $query = "SELECT Name FROM User WHERE User.UserName='{$_SESSION['UserName']}'" ;


$query = "SELECT User.Name as Displayname, 
       (CASE WHEN C.UserName IS NOT NULL THEN CONCAT('Headquarter: ',C.Headquarter)
       WHEN G.UserName IS NOT NULL THEN CONCAT('Jurisdiction: ',G.Jurisdiction)
       WHEN M.UserName IS NOT NULL THEN CONCAT('Population: ',M.PopulationSize)
       WHEN I.UserName IS NOT NULL THEN '' END) as Displayplus
		FROM User 
		LEFT JOIN Company AS C
		ON User.UserName=C.UserName 
		LEFT JOIN Government_Agency AS G
		ON User.UserName=G.UserName 
		LEFT JOIN Municipality AS M   
		ON User.UserName= M.UserName
		LEFT JOIN Individual AS I
		ON User.UserName=I.UserName
		WHERE User.UserName='{$_SESSION['UserName']}' ";
	 
		 
$result = mysqli_query($connect, $query);
if (!$result) {
	print "<p class='error'>Error: " . mysqli_error() . "</p>";
	exit();
}

$row = mysqli_fetch_array($result);

if (!$row) {
	print "<p>Error: No data returned from database.  Administrator login NOT supported.</p>";
	print "<a href='logout.php'>Logout</a>";
	exit();
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
					<li class="selected"><a href="menu.php">Main Menu</a></li>
					<li><a href="add_resource.php">Add Resource</a></li>
					<li><a href="add_incident.php">Add Emergency Incident</a></li>
					<li><a href="search_resources.php">Search Resources</a></li>
					<li><a href="resource_status.php">Resource Status</a></li>
					<li><a href="resource_report.php">Resource Report</a></li>
					<li><a href="exit.php">Exit</a></li>              
				</ul>
			</div>
			
			<div class="center_content">
			
				<div class="center_left">
					<div class="title_name"><?php print $row['Displayname'] ; ?></div> 
					<div class="features">     
					<div class="profile_section">
						<div class="subtitle"><?php print $row['Displayplus'] ; ?></div> 
						<ul>                                                                         
							
							<li class="item_label"><a href="add_resource.php">Add Resource</a></li>
							<li class="item_label"><a href="add_incident.php">Add Emergency Incident</a></li>
							<li class="item_label"><a href="search_resources.php">Search Resources</a></li>
							<li class="item_label"><a href="resource_status.php">Resource Status</a></li>
							<li class="item_label"><a href="resource_report.php">Resource Report</a></li>
							<li class="item_label"><a href="exit.php">Exit</a></li>     
							          
						</ul>   


					</div>   
					</div>  
					

			
				</div>    

		
			<div id="footer">                                              
				    
			</div>
			
		 
		</div>

	</body>
</html>
