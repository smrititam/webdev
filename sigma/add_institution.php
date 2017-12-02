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
			
			<?php
			if(isset($_POST['submit']))
			{
			$icode = $_POST['ins_code'];
			$iname = $_POST['ins_name'];
			$iaddress = $_POST['ins_address'];
			$ipicture = $_POST['ins_picture'];
			$sql = "INSERT INTO institution(Code_institution, Name, Address, Picture)VALUES('$icode', '$iname', '$iaddress', '$ipicture')"; 
			$result = mysqli_query($con, $sql);
				if($result == false)
				{
					die("There was an error running the query " . mysqli_error($con));			
				}
				else 
				{
					//echo "Inserted INSTITUTION!!!!!!!!!!!!!!!!!!!!1";
				}
						
			}
			?>
			<!-- TRYING HTML5 WITH CSS3 -->
			<section id="AddInstitution">
				<h2>Add CAHSI Institution</h2>
				<form method="post" class="minimal" action="#">
					<label for="code">
						Institution code:
						<input type="text" name="ins_code" id="ins_code" required="required" />
					</label>
					<label for="name">
						Name:
						<input type="text" name="ins_name" id="ins_name"   required="required" />
					</label>						
					<label for="address">
						Address:
						<input type="text" name="ins_address" id="ins_address  />
					</label>					
					<label for="picture">
						Picture:
						<input type="text" name="ins_picture" id="ins_picture"  />
					</label>						
					<input type="submit" name="submit" class="btn-minimal" value="Add Institution">
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
	<?php include_once "includes/footer.php";?>
	<!-- end of footer -->
</body>
</html>
<?php }?>