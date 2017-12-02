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
	
	<script src="js/jquery-1.8.0.min.js" type="text/javascript"></script>
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
			if(isset($_POST['submit']))
			{
			// Things that get entered into 'person' table
			$uemail = $_POST['user_email'];
			$uphone = $_POST['user_phone'];
			$ufullname = $_POST['user_fullname'];
			$uinstitution = $_POST['user_institution'];
			$umentor = $_POST['user_mentor'];
				if(empty($umentor))
				{				
					$sql = "INSERT INTO PERSON(Email, Phone, Full_name, Id_institution)VALUES('$uemail','$uphone','$ufullname','$uinstitution')"; 
					$result = mysqli_query($con, $sql);
					if($result == false)
						{
						die("There was an error running the query" . mysqli_error($con));			
						}						
					if($result)	
						{
						echo "CAHSI member added!<br />";
						$last_id = mysqli_insert_id($con);
						$_SESSION['person_id'] = $last_id;
						echo "<a href='add_users.php'>Add CAHSI system user</a>";
						}
				}
				else
				{
					$umentor = $_POST['user_mentor'];
					$sql = "INSERT INTO PERSON(Email, Phone, Full_name, Id_institution, Id_mentor)VALUES('$uemail','$uphone','$ufullname','$uinstitution','$umentor')"; 
					$result = mysqli_query($con, $sql);
					
					if($result == false)
						{
						die("There was an error running the query" . mysqli_error($con));			
						}
					if($result)	
						{
						echo "CAHSI member added!<br />";
						$last_id = mysqli_insert_id($con);
						$_SESSION['person_id'] = $last_id;
						echo "<a href='add_users.php'>Add CAHSI system user</a>";			
						}
				}
			}
			else
			{
			//echo "Post not submitted";
			}
			?>
			<!-- TRYING HTML5 WITH CSS3 -->
			<section id="AddUserBox">
				<h2>Add CAHSI Person</h2>
				<form method="post" class="minimal" action="#">
					<label for="fullname">
						Fullname:
						<input type="text" name="user_fullname" id="user_fullname" required="required" />
					</label>
					<label for="email">
						Email:
						<input type="text" name="user_email" id="user_email" placeholder="Email"  required="required" />
					</label>				
					<label for="phone">
						Phone:
						<input type="text" name="user_phone" id="user_phone"  />
					</label>							
					<label for="institution">
						Institution:
						<select name="user_institution">
						<option value="">--Select--</option>
						<?php
							$sql = "SELECT * FROM INSTITUTION"; 
							$result = mysqli_query($con, $sql);						
							if($result == false)
							{
								die("There was an error running the query" . mysqli_error($con));		
							}
							else 
							{
							//$row = mysqli_fetch_array($result);
							while($row = mysqli_fetch_assoc($result))
								{
								?>							
								<option value='<?php echo $row["Id_institution"];?>'><?php echo $row["Name"];?></option>;
								<?php
								}
							}
						?>
						</select>
					</label>
					<label for="mentor">
						Mentor:
						<select name="user_mentor" id="user_mentor">
						<option value="">--Select--</option>
						<?php
							$sql = "SELECT * FROM USER WHERE Type='PI'"; 
							$result = mysqli_query($con, $sql);						
							if($result == false)
							{
								die("There was an error running the query" . mysqli_error($con));			
							}
							else 
							{
								while($row = mysqli_fetch_assoc($result))
								{	//echo 'User IDs:'.$row['Id_user'];
									echo $sql1 = "SELECT Id_person, Full_name FROM PERSON where Id_person =".$row['Id_user']; 
									$result1 = mysqli_query($con, $sql1);	
									if($result1 == false)
									{
										die("There was an error running the query" . mysqli_error($con));			
									}
									else 
									{		
									while($row1 = mysqli_fetch_assoc($result1))
										{
										?>							
										<option value='<?php echo $row1["Id_person"];?>'><?php echo $row1["Full_name"];?></option>;
									<?php
										}
									}
								}
							}	
						?>							
						</select>
					</label>					

					<input type="hidden" id="pid" name="person_id" value="<?php echo (isset($last_id))? $last_id: ''?>" />
					<input type="submit" name="submit" class="btn-minimal" value="Add Person">

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