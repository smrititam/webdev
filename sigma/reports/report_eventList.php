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
<?php
				// Retrieve variables from the url
				$Id_person = $_GET['Id_person'];
				
				// Construct query
				$sql = "SELECT * FROM PERSON WHERE Id_person = '" . $Id_person . "'"; 

				// Perform query
				$result = mysqli_query($con, $sql);						
				if($result == false)
				{
					die("There was an error running the query" . mysqli_error($con));		
				}
				else 
				{
					while($row = mysqli_fetch_assoc($result))
					{
					// Display search criteria
					echo $row["Full_name"].'<br/><br/>';
					}
				}
?>
				<table border = 1>
					 <thead>
						<tr>
						  <th>Name</th>
						  <th>Id_event</th>
						  <th>Type</th>					  
						  <th>Location</th>
						  <th>Datetime</th>
						  <th>Id_host</th>
						</tr>
					 </thead>
					 <tbody>					
<?php					
						// Construct query for list of events
						$sql = "SELECT Name, Id_event, Type, Datetime, Location, Id_host
							FROM CAHSI_EVENT
							WHERE Id_event IN (
								SELECT Id_event
								FROM PARTICIPANT t1 INNER JOIN PARTICIPANT_ATTENDS_EVENT t2
									ON t1.Id_participant = t2.Id_participant
								WHERE  t1.Id_participant = '" . $Id_person . "')";
						
						// Perform query
						$result = mysqli_query($con, $sql);						
						if($result == false)
						{
							die("There was an error running the query" . mysqli_error($con));		
						}
						else 
						{
							// Keep track of number of results
							$count = 0;
							
							// For each query result, display in a table
							while($row = mysqli_fetch_assoc($result))
							{
?>	
						<tr>
							<td><a href='report_participationList.php?Id_event=<?php echo $row["Id_event"];?>'/><?php echo $row["Name"];?></td>
							<td><?php echo $row["Id_event"];?> </td>
							<td><?php echo $row["Type"];?> </td>						
							<td><?php echo $row["Location"];?> </td>
							<td><?php echo $row["Datetime"];?> </td>
							<td><?php echo $row["Id_host"];?> </td>
						</tr>
<?php		
							$count++;
							}
						// Display number of results
						echo 'Results: ' . $count;
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