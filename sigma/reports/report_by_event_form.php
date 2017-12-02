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
						$Id_institution = $_POST['Id_institution'];
						$Id_event = $_POST['Id_event'];
						$Type = $_POST['Type'];
						$Name = $_POST['Name'];
						$Location = $_POST['Location'];
						$DateTime_From = $_POST['DateTime_From'];
						$DateTime_To = $_POST['DateTime_To'];
						
						$Participant_DOB_From = $_POST['Participant_DOB_From'];
						$Participant_DOB_To = $_POST['Participant_DOB_To'];								
						$Participant_Gender = $_POST['Participant_Gender'];
						$Participant_Ethnicity = $_POST['Participant_Ethnicity'];
								
						/*if($Id_institution !='')
						{
							// To redirect form on a particular page
							header("Location: ../report_by_event.php");							
						}
						else
						{
							?><span><?php echo "Please fill all fields!";?></span>
							<?php
						}
						*/
					}
				?>
				<section id="SearchByInstitutionForm">
				<h2>Search by Event</h2>
				<form method="post" name ="form1" class="minimal" action="report_by_event.php">										
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
						Id_event: <input type="text" name="Id_event"/><br/>
						Type: <input type="text" name="Type"/><br/>
						Name: <input type="text" name="Name"/><br/>
						Location: <input type="text" name="Location"/><br/>
						Date: from <input type="date" name="DateTime_From"/> to <input type="date" name="DateTime_To"/><br/>
						<br/><br/>
						Participant Information<br/>
						Gender: <input type="text" name="Participant_Gender"/><br/>
						Date of Birth: from <input type="date" name="Participant_DOB_From"/> to <input type="date" name="Participant_DOB_To"/><br/>
						Ethnicity: <input type="text" name="Participant_Ethnicity"/><br/>
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