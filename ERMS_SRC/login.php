<?php
/* connect to database */
$connect = mysqli_connect("localhost", "erms", "123456","erms_team26");
if (mysqli_connect_errno()) {
 die( "Unable to select database: ") . mysqli_connect_error() ." (" . mysqli_connect_errno() . ")";
 }
$errorMsg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (empty($_POST['UserName']) or empty($_POST['Password'])) {
		$errorMsg = "Please provide both UserName and Password.";
	}
	else {

		$UserName = mysqli_real_escape_string($connect,$_POST['UserName']);
		$Password = mysqli_real_escape_string($connect,$_POST['Password']);

		$query = "SELECT * FROM User WHERE UserName = '$UserName' AND Password = '$Password'";
		$result = mysqli_query($connect,$query);
		if(!$result){
			die("Database query failed.");
		}

		if (mysqli_num_rows($result) == 0) {
			/* login failed */
			$errorMsg = "Login failed.  Please try again.";

		}
		else {
			/* login successful */
			session_start();
			$_SESSION['UserName'] = $UserName;

			/* redirect to the profile page */
			header('Location: menu.php');
			exit();
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

				<div class="text_box">

					<form action="login.php" method="post">

						<div class="title">ERMS Login</div>
							<div class="login_form_row">
							<label class="login_label">UserName:</label>
							<input type="text" name="UserName" class="login_input" />
						</div>

						<div class="login_form_row">
							<label class="login_label">Password:</label>
							<input type="Password" name="Password" class="login_input" />
						</div>

						<div class="login_form_row">
							<a href="register.php">Register here</a>
						</div>

						<input type="image" src="images/login.gif" class="login" />

					<form/>

					<?php
					if (!empty($errorMsg)) {
						print "<div class='login_form_row' style='color:red'>$errorMsg</div>";
					}
					?>

				</div>

				<div class="clear"><br/></div>

			</div>

			<div id="footer">
				<div class="right_footer"><a href="http://csstemplatesmarket.com"  target="_blank"></a></div>
			</div>

		</div>
	</body>
</html>
