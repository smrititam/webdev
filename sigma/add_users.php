<?php session_start();?>
<?php
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != '')) 
{
header("Location: index.php");
}
else{
?>
<!DOCTYPE html>
<!--HTML 5 + IE HACK--><!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />
	<title>CAHSI Reporting System</title>
	<link rel="shortcut icon" href="/css/images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="css/forms.css" type="text/css" media="all" />
	<!--<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="all" />
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,500,700' rel='stylesheet' type='text/css' />-->
	<!--[if lt IE 9]>
		<script src="js/modernizr.custom.js"></script>
	<![endif]-->
	<!--<script src="js/jquery.flexslider-min.js" type="text/javascript"></script>-->
	<script src="js/functions.js" type="text/javascript"></script>
		<script src="js/jquery-1.11.2.min.js"></script>	
</head>
<body>
<?php 
if(strstr($_SERVER["REQUEST_URI"], "sigma"))
	include_once "includes/local-db-connection.php";
if(strstr($_SERVER["REQUEST_URI"], "team5"))	
	include_once "includes/live-db-connection.php";
?>
	<div id="wrapper">
		
		<!-- top-nav -->
		<?php include_once "includes/top-nav.php";?>
		<!-- end of top-nav -->
			<!-- header -->
			<?php include_once "includes/header-logo.php";?>
			<!-- end of header -->
		<div class="main">
			<div class="shell">
			<!-- REMOVED EVERYTHING INSIDE OF MAIN -->
			<a href="person_list.php">View CAHSI Member List</a><br />
			<a href="system_user_list.php">View CAHSI System User List</a><br /><br />			
			<?php
			//PRINT_R($_POST);
			
			if(isset($_POST['user_submit']))
			{
			//PRINT_R($_POST);
			
			// Things that get inserted into 'user' table
			$utype = $_POST['user_type'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$u_is_active = $_POST['is_active'];
			$u_picture = $_POST['user_picture'];	
			$uid = $_SESSION['person_id'];
			$sql1 = "INSERT INTO USER(Id_user, Type, Username, Password, Is_active, Picture)VALUES('$uid','$utype','$username','$password','$u_is_active','$u_picture')"; 
			$result1 = mysqli_query($con, $sql1);
			if($result1 == false)
				{
				die("There was an error running the query " . mysqli_error($con));			
				}	
				
			if($result1)	
				{
				echo "CAHSI system user added!<br />";
/* 				echo "<a href='person_list.php'>View CAHSI Member List</a><br />";
				echo "<a href='system_user_list.php'>View CAHSI System User List</a>"; */
				}
			}
			else
			{
			//echo "Post not submitted";
			}
			?>
			<!-- TRYING HTML5 WITH CSS3 -->
			<section id="AddUserBox">
				<h2>Add CAHSI System User</h2>
				<form id="useradd" method="post" class="minimal" action="#">
					<label for="usertype">
						User type:
						<select name="user_type" id="user_type">
						<option value="">--Select--</option>
						<option value="CAHSI Manager">CAHSI Manager</option>
						<option value="PI">PI</option>
						<option value="Student Advocate">Student Advocate</option>
						<option value="Evaluator">Evaluator</option>
						</select>
					</label>					
					<label for="username">
						Username:
						<input type="text" name="username" id="username" placeholder="Username must be between 8 and 20 characters" pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{8,20}$" required="required" />
					</label>
					<label for="password">
						Password:
						<input type="password" name="password" id="password" placeholder="Password must contain 1 uppercase, lowercase and number" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required="required" />
					</label>	
					<label for="Active">
						Status
						<select name="is_active">
						<option value="1">Active</option>
						<option value="0">Inactive</option>
						</select>
					</label>					
					<label for="User picture">
						Picture
						<input type="text" name="user_picture" id="user_picture"  />
					</label>
					<input type="submit" name="user_submit" class="btn-minimal" value="Add User">

				</form>
			</section>
			<?php include_once "includes/right-nav.php"; ?>
			</div>
		</div>				
		</div>	
		<!-- footer-push -->
		<div id="footer-push"></div>
		<!-- end of footer-push -->
	
	<!-- end of wrapper -->
	<!-- footer -->
	<?php include_once "includes/footer.php"?>
	<!-- end of footer -->
</body>
</html>
<?php }?>