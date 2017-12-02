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
<script type="text/javascript">
$( document ).ready(function() {
    $('.confirmit').on('click', function () {
        return confirm('Are you sure?'+"\n"+'You can edit and set the user to inactive.'+"\n"+'Deleting a user will also delete all of its related information with events participation, research etc.');
	})	
    });
</script>
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
	if(isset($_GET['editid']))
	{
			// Start of IF for UPDATE form
			?>
			<!-- TRYING HTML5 WITH CSS3 -->
			<section id="AddUserBox">
				<h2>Edit CAHSI System User</h2>
				<form method="post" class="minimal" action="#">
				<?php
				$ueid = isset($_GET['editid']) ? $_GET['editid'] : 0;
				$sql1 = "SELECT * FROM USER WHERE Id_user ='$ueid'"; 
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
					<label for="usertype">
						User type:
						<select name="user_type" id="user_type">
						<option value="">--Select--</option>
						<option value="CAHSI Manager" <?php echo (isset($row1['Type']) && $row1['Type']=='CAHSI Manager') ? 'selected': ''?>>CAHSI Manager</option>
						<option value="PI" <?php echo (isset($row1['Type']) && $row1['Type']=='PI') ? 'selected': ''?>>PI</option>
						<option value="Student Advocate" <?php echo (isset($row1['Type']) && $row1['Type']=='Student Advocate') ? 'selected': ''?> >Student Advocate</option>
						<option value="Evaluator" <?php echo (isset($row1['Type']) && $row1['Type']=='Evaluator') ? 'selected': ''?> >Evaluator</option>
						</select>
					</label>					
					<label for="username">
						Username:
						<input type="text" name="username" id="username" value="<?php echo $row1['Username']?>" placeholder="Username must be between 8 and 20 characters" pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{8,20}$" required="required" />
					</label>
					<label for="password">
						Password:
						<input type="text" name="password" id="password" value="<?php echo $row1['Password']?>" placeholder="Password must contain 1 uppercase, lowercase and number" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required="required" />
					</label>	
					<label for="Active">
						Status
						<select name="is_active">
						<option value="1" <?php echo (isset($row1['Is_active']) && $row1['Is_active']==1) ? 'selected': ''?>>Active</option>
						<option value="0" <?php echo (isset($row1['Is_active']) && $row1['Is_active']==0) ? 'selected': ''?> >Inactive</option>
						</select>
					</label>					
					<label for="User picture">
						Picture
						<input type="text" name="user_picture" id="user_picture" value="<?php echo $row1['Picture'] ?>"  />
					</label>
					<input type="submit" name="user_submit" class="btn-minimal" value="Edit User">
				<?php
					}
				}			
				?>
				</form>
			</section>				
	<?php 
	// END OF IF for UPDATE FORM
	} 
	?>	
			<?php 
			// Things that get updated into 'user' table
			//$ueid = isset($GET['editid']) ? $GET['editid'] : 0;
			
			if (isset($_POST['user_submit']))
			{	//print_r($_POST);
			$utype = $_POST['user_type'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$u_is_active = $_POST['is_active'];
			$u_picture = $_POST['user_picture'];
				if(isset($ueid) && $ueid != 0)
				{
				$sql = "UPDATE USER SET Type='$utype', Username='$username', Password ='$password', Is_active='$u_is_active', Picture='$u_picture' WHERE Id_user='$ueid'"; 
				$result = mysqli_query($con, $sql);					
				if($result == false)
					{
					die("There was an error running the query" . mysqli_error($con));			
					}
				else
					{
					echo "CAHSI system user info updated!<br />";	
					//echo '<a href="system_user_list.php">View CAHSI System User List</a>';
					}
				} // end of UPDATE code
			}// End of $POST SUBMIT section
			
			//start of delete code
			$udid = isset($_GET['delid']) ? $_GET['delid'] : 0;
			
			
			if(isset($udid) && $udid != 0)
			{			
			$sql0 ="UPDATE PERSON SET Id_mentor = NULL WHERE Id_mentor = '$udid'";
			$sql0 .="DELETE FROM USER WHERE Id_user = '$udid'";
/* 			$sql0 .="DELETE FROM PERSON WHERE Id_person = '$udid';";
			$sql0 .="DELETE FROM RESEARCH WHERE Id_person = '$udid'"; */

			$result0 = mysqli_multi_query($con, $sql0);
			
/* 			$sql = "DELETE FROM USER WHERE Id_user='$udid'"; 
			$result = mysqli_query($con, $sql); */
				if($result0 == true)
				{
					echo "CAHSI system user DELETED!<br />";	
					//echo '<a href="system_user_list.php">View CAHSI System User List</a>';
				}						
				else
				{
					die("There was an error running the query" . mysqli_error($con));	
				}

			}//End of delete code			
			?>			
			<?php
			$sql = "SELECT * FROM USER"; 
			$result = mysqli_query($con, $sql);	
					
				if($result == false)
				{
					die("There was an error running the query" . mysqli_error($con));			
				}
				else 
				{	$row_cnt = mysqli_num_rows($result);
					if($row_cnt>0)
					{
					echo '<table border="1">';
						echo '<tr>';
						echo '<th>Username</th>';
						echo '<th>User Type</th>';
						echo '<th>User Staus</th>';
						echo '<th>Edit User</th>';
						echo '<th>Delete User</th>';
						echo '</tr>';					
					while($row = mysqli_fetch_assoc($result)) {
						echo '<tr>';
						echo '<td>'.$row["Username"].'</td>';
						echo '<td>'.$row["Type"].'</td>';
						echo '<td>'.$row["Is_active"].'</td>';
						echo '<td><a href="system_user_list.php?editid='.$row["Id_user"].'">Edit</a></td>';
						echo '<td><a href="person_list.php?delid='.$row["Id_user"].'" class="confirmit">Delete</a></td>';
						echo '</tr>';
					}  
					echo '</table>';
					}
					else
					{
					echo "No system user data available.";
					}
				
				}
			//}
			?>
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