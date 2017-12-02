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
						$Id_institution = $_POST['institution'];
						$Full_name = $_POST['Full_name'];
						$Email = $_POST['Email'];
						$Start_date_from = $_POST['Start_date_from'];
						$Start_date_to = $_POST['Start_date_to'];
						$End_date_from = $_POST['End_date_from'];
						$End_date_to = $_POST['End_date_to'];												
						$Education_level = $_POST['Education_level'];
						$Ethnicity = $_POST['Ethnicity'];
						$Gender = $_POST['Gender'];
						$Date_of_birth_from = $_POST['Date_of_birth_from'];
						$Date_of_birth_to = $_POST['Date_of_birth_to'];
						$Research = $_POST['Research'];
		
						if($Id_institution !='')
						{
							header("Location: ../report_by_participant.php");							
						}
						else
						{
							?><span><?php echo "Please fill all fields!";?></span>
							<?php
						}
					}
				?>
				<section id="SearchByInstitutionForm">
				<h2>Search by Participant</h2>
				<form method="post" name ="form1" class="minimal" action="report_by_participant.php">										
					<label for="institution">
					<!--Form Inputs-->
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
						<br/>
						Full Name: <input type="text" name="Full_name"/><br/>
						Email: <input type="text" name="Email"/><br/>
						Start Date: from <input type="date" name="Start_date_from"/> to <input type="date" name="Start_date_to"/><br/>
						End Date: from <input type="date" name="End_date_from"/> to <input type="date" name="End_date_to"/><br/>												
						Education_level: <input type="text" name="Education_level"/><br/>
						Ethnicity: <input type="text" name="Ethnicity"/><br/>
						Gender: <input type="text" name="Gender"/><br/>
						Date of Birth: from <input type="date" name="Date_of_birth_from"/> to <input type="date" name="Date_of_birth_to"/><br/>
						Research <input type="checkbox" name="Research" value="checked"/><br/>
						
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