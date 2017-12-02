<?php
//$con = mysqli_connect('localhost', 'root','', 'cs4342team5sp15');
$con = mysqli_connect('localhost', 'root','', 'sigma');

if(mysqli_errno($con))	
{
	die("Could not connect :". mysqli_error());
}
else
{
	echo "<br />Connection with local DB working!!";
	echo "<br />";
}
?>