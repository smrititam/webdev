<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login</title>
	<style>
	.error{
		color:red;
		font-weight:bold;
	}
	</style>
</head>

<body>
<?php 
if(strstr($_SERVER["REQUEST_URI"], "sigma"))
	include_once "includes/local-db-connection.php";
if(strstr($_SERVER["REQUEST_URI"], "team5"))	
	include_once "includes/live-db-connection.php";
?>
<?php $self = $_SERVER['PHP_SELF'] ;?>
	<div id="login">
	<h1>CAHSI Reporting System</h1>
		<h2>Login</h2>
	    <div class='error'>
		<?php 
		//print_r($_POST);
		$err_msg="";

		if(isset($_POST['submit']))
		{
		
		$entered_username=$_POST['username'];
		$entered_password=$_POST['password'];
			if($entered_username == '' || $entered_password =='' )
			{
				$err_msg = "Username or password is missing!!";
			}
			else if($entered_username == "user" &&  $entered_password == "user1")
			{
				session_start();
				$_SESSION['username']=$entered_username;
				$_SESSION['logged_in']=1;
				header("Location: home.php");
			}	
			else if($entered_username == "admin" && $entered_password == "admin1")
			{
				session_start();
				$_SESSION['username']=$entered_username;
				$_SESSION['logged_in']=1;
				header("Location: home.php");
			}
			else
			{	
				$err_msg = "Invalid username or password!!";
			}	
				echo $err_msg;
		}?>
		</div>
		<form action="<?php echo $self;?>" method="POST" name="login-form">

		
			<fieldset>
	
				<p><label for="username">Username </label></p>
				<p><input type="text" id="email" name="username" value="" ></p> 

				<p><label for="password">Password</label></p>
				<p><input type="password" id="password" name="password" value="" ></p> 

				<p><input type="submit" name="submit" value="Login"></p>

			</fieldset>

		</form>

	</div> <!-- end login -->

</body>	
</html>