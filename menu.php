<?php 
	session_start(); 
?>
<html>

<body>
<?php 
	session_start(); 
	// Get the DB connection info from the session
	if(isset($_SESSION["userID"]) && isset($_SESSION["connectionOptions"]) && isset($_SESSION["userID"]) && isset($_SESSION["userType"])) { 
		$serverName = $_SESSION["serverName"];
		$connectionOptions = $_SESSION["connectionOptions"];
		$userID = $_SESSION["userID"];
		$userType = $_SESSION["userType"];
	} else {
		session_unset();
		session_destroy();
		echo "Session is not correctly set! Clossing session and redirecting to start page in 3 seconds<br/>";
		die('<meta http-equiv="refresh" content="3; url=index.php" />');
	} 	
	//Establishes the connection
	$conn = sqlsrv_connect($serverName, $connectionOptions);
?>
	<table cellSpacing=0 cellPadding=5 width="100%" border=0>
	<tr>
		<td vAlign=top width=170><img height=91 alt=UCY src="images/ucy.jpg" width=94>
			<h5>
				<a href="http://www.ucy.ac.cy/">University of Cyprus</a><BR/>
				<a href="http://www.cs.ucy.ac.cy/">Dept. of Computer Science</a>
			</h5>
		</td>
		<td vAlign=center align=middle><h2>Welcome to the EPL342 project test page</h2></td>
	</tr>
    </table>
	<hr>
	<a href="q1.php">Query 1 (Simple SQL statement)</a><br>
	<a href="q2.php">Query 2 (Calling a stored procedure without parameters)</a><br>
	<form action="q3.php" method="get">
		Query 3 (Calling a stored procedure with parameters)</a><br>
		Parameter: <input type="text" name="city" value="Seattle">
    <input type="submit" name="Query 3">
	</form>

	<hr>
	<?php
		if(isset($_POST['disconnect'])) { 
			echo "Clossing session and redirecting to start page"; 
			session_unset();
			session_destroy();
			die('<meta http-equiv="refresh" content="2; url=index.php" />');
		} 
	?> 

	
	<form method="post"> 
		<input type="submit" name="disconnect" value="Disconnect"/> 
	</form> 

</body>
</html>
