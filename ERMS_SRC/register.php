<?php
/* connect to database */
$connect = mysqli_connect("localhost", "erms", "123456","erms_team26");
if (mysqli_connect_errno()) {
 die( "Unable to select database: ") . mysqli_connect_error() ." (" . mysqli_connect_errno() . ")";
 }
$errorMsg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (empty($_POST['UserName']) or empty($_POST['Name']) or empty($_POST['Password'])) {
		$errorMsg_empty = "Please provide both UserName Name and Password.";
	}
	else {

		$UserName = mysqli_real_escape_string($connect,$_POST['UserName']);
		$Name = mysqli_real_escape_string($connect,$_POST['Name']);
		$Password = mysqli_real_escape_string($connect,$_POST['Password']);
		$DateHired = mysqli_real_escape_string($connect,$_POST['DateHired']);
		$Jobtitle = mysqli_real_escape_string($connect,$_POST['Jobtitle']);
		$PopulationSize = mysqli_real_escape_string($connect,$_POST['PopulationSize']);
		$Headquarter = mysqli_real_escape_string($connect,$_POST['Headquarter']);
		$Jurisdiction = mysqli_real_escape_string($connect,$_POST['Jurisdiction']);

		$query = "INSERT INTO User (Username, Name, Password)" . "VALUES ('$UserName', '$Name', '$Password')";
		$query_individual = "INSERT INTO Individual (Username, DateHired, Jobtitle)" . "VALUES ('$UserName', '$DateHired', '$Jobtitle')";
		$query_municipality = "INSERT INTO Municipality (Username, PopulationSize)" . "VALUES ('$UserName', '$PopulationSize')";
		$query_company = "INSERT INTO Company (Username, Headquarter)" . "VALUES ('$UserName', '$Headquarter')";
		$query_government = "INSERT INTO Government_agency (Username, Jurisdiction)" . "VALUES ('$UserName', '$Jurisdiction')";
		//$result = mysqli_query($connect,$query);
		// if(!$result){
		// 	die("Failed to register");
		// }
		if (!mysqli_query($connect,$query)) {
				$errorMsg = "Error: Failed to register user.";
			}

		else {
			if(!empty($DateHired) && !empty($Jobtitle) ){
				mysqli_query($connect,$query_individual);
			}

			if(!empty($PopulationSize) ){
				mysqli_query($connect,$query_municipality);
			}

			if(!empty($Headquarter) ){
				mysqli_query($connect,$query_company);
			}

			if(!empty($Jurisdiction) ){
				mysqli_query($connect,$query_government);
			}

			/* register successful */
			// session_start();
			// $_SESSION = array();

			/* redirect to the login page */
			header('Location: login.php');
		}

	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<title>ERMS Login</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>

	<body>

		<div id="main_container">
			<div id="header">
				<div class="logo"><img src="images/erms_logo.gif" border="0" alt="" title="" style="width:px;height:80px;"/></div>
			</div>

			<div class="center_content">

				<div class="text_box_reg"> 

					<form action="register.php" method="post">

						<div class="title">ERMS Register</div>
							<div class="login_form_row">
							<label class="login_label">UserName:</label>
							<input type="text" name="UserName" class="login_input" />
						</div>

						<div class="login_form_row">
							<label class="login_label">Name:</label>
							<input type="text" name="Name" class="login_input" />
						</div>

						<div class="login_form_row">
							<label class="login_label">Password:</label>
							<input type="Password" name="Password" class="login_input" />
						</div>

						<div class="login_form_row">
							<label class="login_label">DateHired (Individual):</label>
							<input type="date" name="DateHired" class="login_input" />
						</div>

						<div class="login_form_row">
							<label class="login_label">Jobtitle (Individual):</label>
							<input type="text" name="Jobtitle" class="login_input" />
						</div>

						<div class="login_form_row">
							<label class="login_label">PopulationSize (municipality):</label>
							<input type="text" name="PopulationSize" class="login_input" />
						</div>

						<div class="login_form_row">
							<label class="login_label">Headquarter (company):</label>
							<input type="text" name="Headquarter" class="login_input" />
						</div>

						<div class="login_form_row">
							<label class="login_label">Jurisdiction (government):</label>
							<input type="text" name="Jurisdiction" class="login_input" />
						</div>

						<input type="submit" value="Register" name="Register"/>


					<form/>

					<?php
					if (!empty($errorMsg_empty)) {
						print "<div class='login_form_row' style='color:red'>$errorMsg_empty</div>";
					}
					?>

					<?php
					if (!empty($errorMsg)) {
						print "<div class='login_form_row' style='color:red'>$errorMsg</div>";
					}
					?>

				</div>

				<div class="clear"><br/></div>

			</div>

			<div id="footer"></div>

		</div>
	</body>
</html>
