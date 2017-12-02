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
				// Retrieve posted variables from the form
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
?>
				<table border = 1>
					<thead>
						<tr>
						  <th>Name</th>
						  <th>Id_event</th>
						  <th>Type</th>					  
						  <th>Location</th>
						  <th>DateTime</th>
						  <th>Host</th>
						</tr>
					 </thead>
					 <tbody>					
<?php
						// Query builder initial construction
						$sql = "SELECT t1.Id_event, t1.Name, t1.Type, t1.Location, t1.Datetime, t2.Name as Host
								FROM CAHSI_EVENT t1, INSTITUTION t2
								WHERE t1.Id_host = t2.Id_institution";		
						$sql_Id_institution = "";					
						$sql_Id_event = "";
						$sql_Type = "";
						$sql_Name = "";
						$sql_Location = "";					
						$sql_DateTime = "";
						$sql_Participant_DOB = "";
						$sql_Participant_Gender = "";
						$sql_Participant_Ethnicity= "";

						// Query builder conditional statements
						if($Id_institution!=""){
							$sql_Id_institution =  " AND Id_host = '" . $Id_institution . "'";
						}
						if($Id_event!=""){
							$sql_Id_event =  " AND Id_event = '" . $Id_event . "'";
						}
						if($Type!=""){
							$sql_Type =  " AND Type LIKE '%" . $Type . "%'";
						}
						if($Name!=""){
							$sql_Name =  " AND Name LIKE '%" . $Name . "%'";
						}
						if($Location!=""){
							$sql_Location =  " AND Location LIKE '%" . $Location . "%'";
						}
						if($DateTime_From!="" && $DateTime_To!=""){						
							$sql_DateTime =  " AND DateTime > '" . $DateTime_From . "'" . " AND DateTime < '" . $DateTime_To . "'";					
						}
						// Append query
						$sql = $sql . $sql_Id_event . $sql_Type . $sql_Name . $sql_Location . $sql_DateTime .$sql_Id_institution;

						// Query builder conditional statements
						if($Participant_DOB_From!="" && $Participant_DOB_To!=""){						
							$sql_Participant_DOB =  " AND Id_event IN (
													SELECT Id_event
													FROM PARTICIPANT t1 INNER JOIN PARTICIPANT_ATTENDS_EVENT t2
													ON t1.Id_participant = t2.Id_participant
													WHERE Date_of_birth >= '" . $Participant_DOB_From . "' ". 
													"AND Date_of_birth <= '" . $Participant_DOB_To . "')";					
						}
						if($Participant_Gender!=""){
							$sql_Participant_Gender =  " AND Id_event IN (
														SELECT Id_event
														FROM PARTICIPANT t1 INNER JOIN PARTICIPANT_ATTENDS_EVENT t2
														ON t1.Id_participant = t2.Id_participant										
														WHERE Gender LIKE '%" . $Participant_Gender . "%')";
						}
						if($Participant_Ethnicity!=""){
							$sql_Participant_Ethnicity =  " AND Id_event IN (
															SELECT Id_event
															FROM PARTICIPANT t1 INNER JOIN PARTICIPANT_ATTENDS_EVENT t2
															ON t1.Id_participant = t2.Id_participant
															WHERE Ethnicity LIKE '%" . $Participant_Ethnicity . "%')";
						}
						// Append query
						$sql = $sql . $sql_Participant_DOB . $sql_Participant_Gender . $sql_Participant_Ethnicity;
					
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
							<td><?php echo $row["Host"];?> </td>
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