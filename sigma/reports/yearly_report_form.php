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
						// Post variables of the form
						$school_year = $_POST['school_year'];																	
					}
				?>
				<section id="SearchByInstitutionForm">
				<h2>Yearly Report</h2>
				<form method="post" name ="form1" class="minimal" action="yearly_report.php">
					<label for="institution">
					<!--Form Inputs-->
						School Year:
						<select name="school_year">
							<option value="">--Select--</option>
							<?php
							$con = mysqli_connect('earth.cs.utep.edu', 'cs4342team5sp15','team5', 'cs4342team5sp15');
								$sql = "SELECT * FROM VIEW_SCHOOL_YEAR ORDER BY Year"; 								
								$result = mysqli_query($con, $sql);						
								if($result == false)
								{
									die("There was an error running the query" . mysqli_error($con));		
								}
								else 
								{									
									while($row = mysqli_fetch_assoc($result))
									{	
										$yr=$row["Year"];
										$nyr=$yr+1;
									?>															
										<option value='<?php echo $yr;?>'><?php echo $yr." - ".$nyr;?></option>;
									<?php
									
									}
								}
							?>
						</select>		
						<br/><br/>												
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