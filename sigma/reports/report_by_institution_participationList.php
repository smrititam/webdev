<?php session_start();?>
<?php
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != '')) 
{
header("Location: index.php");
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
			<!-- REMOVED EVERYTHING INSIDE OF MAIN -->			
		
			<?php
				$Id_event = $_GET['Id_event'];
				$sql = "SELECT * FROM CAHSI_EVENT WHERE Id_event =" . $Id_event; 
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
					echo $row["Name"].'<br/>';
					}
				}
?>
				<table border = 1>
				 <thead>
					<tr>
					  <th>Full_name</th>
					  <th>Email</th>
					  <th>Start_date</th>
					  <th>End_date</th>
					  <th>Education_level</th>
					  <th>Ethnicity</th>
					  <th>Gender</th>
					  <th>Date_of_birth</th>
					</tr>
				 </thead>
				 <tbody>					
<?php
					$sql = "SELECT Full_name, Email, Start_date, End_date, Education_level, Ethnicity, Gender, Date_of_birth
							FROM PERSON t1 INNER JOIN PARTICIPANT t2 ON t1.Id_person =
							t2.Id_participant
							WHERE Id_participant IN (
							SELECT Id_participant
							FROM PARTICIPANT_ATTENDS_EVENT
							WHERE Id_event  =" . $Id_event .")";				
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
					<tr>
						<td><?php echo $row["Full_name"];?> </td>
						<td><?php echo $row["Email"];?> </td>
						<td><?php echo $row["Start_date"];?> </td>
						<td><?php echo $row["End_date"];?> </td>
						<td><?php echo $row["Education_level"];?> </td>
						<td><?php echo $row["Ethnicity"];?> </td>
						<td><?php echo $row["Gender"];?> </td>
						<td><?php echo $row["Date_of_birth"];?> </td>
					</tr>
<?php		
						}
					}
?>	
				</tbody>
			</table>


			</div>
		</div>				
	</div>	
	<div id="footer-push"></div>
	<?php include_once "../includes/footer.php"?>
</body>
</html>
<?php }?>