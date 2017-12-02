<?php session_start();?>
<?php
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != '')) 
{
header("Location: ../index.php");
}
else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />
	<title>CAHSI Reporting System</title>
	<link rel="shortcut icon" href="../css/images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="../css/forms.css" type="text/css" media="all" />
	<script src="js/jquery-1.8.0.min.js" type="text/javascript"></script>
	<script src="js/functions.js" type="text/javascript"></script>
	<script src="js/jquery-1.11.2.min.js"></script>	
</head>
<body>
<?php 
if(strstr($_SERVER["REQUEST_URI"], "sigma"))
	include_once "../includes/local-db-connection.php";
if(strstr($_SERVER["REQUEST_URI"], "team5"))	
	include_once "../includes/live-db-connection.php";
?>
	<div id="wrapper">
		<?php include_once "includes/top-nav.php";?>
		<?php include_once "../includes/header-logo.php";?>
		<div class="main">
			<div class="shell">		
				<?php					
					if(isset($_POST['submit']))
					{
						
						$Id_institution = $_POST['institution'];
					
						if($Id_institution !='')
						{
							header("Location: ../report_by_institution_eventList.php");							
						}
						else
						{
							?><span><?php echo "Please fill all fields!";?></span>
							<?php
						}
					}
				?>
				<section id="SearchByInstitutionForm">
				<h2>Search by Institution</h2>
				<form method="post" name ="form1" class="minimal" action="report_by_institution_eventList.php">										
					<label for="institution">
						Institution:
						<select name="Id_institution">
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
									while($row = mysqli_fetch_assoc($result))
									{
									?>							
									<option value='<?php echo $row["Id_institution"];?>'><?php echo $row["Name"];?></option>;
									<?php
									}
								}
							?>
						</select>		
						<input type="submit" name="submit" class="btn-minimal" value="Search">
					</form>
				</section>
			</div>
		</div>				
	</div>	
	<div id="footer-push"></div>
	<?php include_once "../includes/footer.php"?>
</body>
</html>
<?php }?>