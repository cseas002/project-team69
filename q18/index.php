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
		die('<meta http-equiv="refresh" content="3; url=../index.php" />');
	} 	
	//Establishes the connection
	$conn = sqlsrv_connect($serverName, $connectionOptions);
?>


<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "../style.css"> 
	<style>
		table th{background:grey}
		table tr:nth-child(odd){background:LightYellow}
		table tr:nth-child(even){background:LightGray}
	</style>
</head>
<body>

<div class="sidenav">
		<a href="http://www.ucy.ac.cy/"><img width="160px" alt=UCY src="../images/logo_en.png"></a>
		<h5>
			<a style="color: #C68F06;" href="http://www.cs.ucy.ac.cy/">Dept. of Computer Science</a>
		</h5>
		<?php if ($userType == '1') { ?><a href="../log">Log</a><a href="../q1">Query 1</a><?php }?>
		<?php if ($userType != '3') { ?>
		<a href="../q2">Query 2</a>
		<a href="../q3">Query 3</a>
		<a href="../q4">Query 4</a>
		<a href="../q5">Query 5</a>
		<?php }?>
		<a href="../q6">Query 6</a>
		<a href="../q7">Query 7</a>
		<a href="../q8">Query 8</a>
		<a href="../q9">Query 9</a>
		<a href="../q10">Query 10</a>
		<a href="../q11">Query 11</a>
		<a href="../q12">Query 12</a>
		<a href="../q13">Query 13</a>
		<a href="../q14">Query 14</a>
		<a href="../q15">Query 15</a>
		<a href="../q16">Query 16</a>
		<a href="../q17">Query 17</a>
		<a href="../q18">Query 18</a>
		<a href="../q19">Query 19</a>
		<a href="../q20">Query 20</a>
		<a href="../q21">Query 21</a>
		<div class="disconnectForm">
			<?php
        if (isset($_POST['disconnect'])) {
	        echo "Clossing session and redirecting to start page";
	        session_unset();
	        session_destroy();
	        die('<meta http-equiv="refresh" content="1; url=../index.php" />');
        }
        ?>

			<form method="post">
				<input class="disconnectBtn" type="submit" value="Menu" formaction="../menu.php"
					style="margin-top:20px;"><br /><br />
				<input class="disconnectBtn" type="submit" name="disconnect" value="Disconnect" /><br />
			</form>
		</div>
	</div>
	<div class="main">

	<table cellSpacing=0 cellPadding=5 width="100%" border=0>
	<tr>
		<td vAlign=center align=middle><h2>(Nearest Neighbor - NN) POI</h2></td>
	</tr>
    </table>

	<hr/>

	<button id="btnInsertForm" class="button-20" onclick="document.getElementById('myForm').style.display = 'block';" >Input parameters</button>
		
	<div class="form-popup" id="myForm"
			onkeypress="if(event.keyCode==13){if(insertValidation()){f1.submit();}}">
			<form name="f1" method="POST" class="form-container">
				<h2 style="text-align:center;">Insert parameters</h2>
                <label> x: </label>
				<input type="text" name="x" />
				<label> y: </label>
				<input type="text" name="y" />
				<label> z: </label>
				<input type="text" name="z" />
				<input type="button" class="btn" value="Go"
					onclick="if(insertValidation()){f1.submit();}" />
				<button type="button" class="btn cancel"
					OnClick="document.getElementById('myForm').style.display = 'none';">Cancel</button>
			</form>
		</div>

		<hr/>
	
		
		<?php 
		if (isset($_POST["x"])) {
			$strSQL = "{call dbo.Q18(?, ?, ?)}";
			$params = array(
				array($_POST["x"], SQLSRV_PARAM_IN),
				array($_POST["y"], SQLSRV_PARAM_IN),
				array($_POST["z"], SQLSRV_PARAM_IN)
			);
			$objQuery = sqlsrv_query($conn, $strSQL, $params);



		$objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC);
	        
		?>
		<table width="100%" border="1">  
		<tr>  
		<th> <div align="center">POI ID</div></th>  
		<th> <div align="center">POI Name</div></th>
		</tr>  
		<tr>
		<td align="center"><?=$objResult["POIID"];?></td>
		<td align="center"><?=$objResult["POIName"];?></td>
		</tr>
		</table>  
		<hr/>

		<?php
		}
		?>

		<script>

			function insertValidation()  {
				var x=f1.x.value;
				var y=f1.y.value;
				var z=f1.z.value;

				if(x.length>0 && y.length>0 && z.length>0){
					return true;
				}
				var str="";
				if(x.length==0)
					str+="x is empty\n";
				if(y.length==0)
					str+="y is empty\n";
				if(z.length==0)
					str+="z is empty\n";
				alert(str);
				return false;
			}
		</script>

	<?php

	
	// $time_start = microtime(true);

	$userTypes=array("System Admin", "Functions Admin", "Simple User");
	echo "Connecting to SQL server (" . $serverName . ")<br/>";
	echo "Database: " . $connectionOptions[Database] . ", SQL User: " . $connectionOptions[Uid] . "<br/>";
	echo "User: " . $_SESSION["userID"] . ", UserType: " . $userTypes[$_SESSION["userType"]-1] . "<br/>";

	/* Free connection resources. */
	sqlsrv_close( $conn);

	// $execution_time = round((($time_end - $time_start)*1000),2);
	// echo 'QueryTime: '.$execution_time.' ms';

	?>

	<hr>
	<?php
		if(isset($_POST['disconnect'])) {
			echo "Clossing session and redirecting to start page";
			session_unset();
			session_destroy();
			die('<meta http-equiv="refresh" content="1; url=../index.php" />');
		} 
	?>  
	</div>
</body>
</html>
