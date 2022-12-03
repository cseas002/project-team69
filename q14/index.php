<?php
session_start();
// Get the DB connection info from the session
if (isset($_SESSION["userID"]) && isset($_SESSION["connectionOptions"]) && isset($_SESSION["userID"]) && isset($_SESSION["userType"])) {
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
$conn = sqlsrv_connect($serverName, $connectionOptions); ?>


<html>

<head>
	<link rel="stylesheet" type="text/css" href="../style.css">
	<style>
		table th {
			background: grey
		}

		table tr:nth-child(odd) {
			background: LightYellow
		}

		table tr:nth-child(even) {
			background: LightGray
		}
	</style>
</head>

<body>

	<div class="sidenav">
		<a href="http://www.ucy.ac.cy/"><img width="160px" alt=UCY src="../images/logo_en.png"></a>
		<h5>
			<a style="color: #C68F06;" href="http://www.cs.ucy.ac.cy/">Dept. of Computer Science</a>
		</h5>
		<a href="../q1">Query 1</a>
		<a href="../q2">Query 2</a>
		<a href="../q3">Query 3</a>
		<a href="../q4">Query 4</a>
		<a href="../q5">Query 5</a>
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
				<td vAlign=center align=middle>
					<h2>Find k item types with the least participations</h2>
				</td>
			</tr>
		</table>


	<?php
    
	//*** When the user types a number ***//
	if (isset($_POST["num"])) {
		?>
		<table width="100%" border="1">
		<tr>  
			<th width = "50%"> <div align="center">Type ID</div></th>  
			<th width = "50%"> <div align="center">Amount</div></th> 
		</tr>  
		
		<?php
		$strSQL = "{call dbo.Q14(?)}";
		$params = array(
			array($_POST["num"], SQLSRV_PARAM_IN)
		);

		$objQuery = sqlsrv_query($conn, $strSQL, $params);

		while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
		{
			?>
		<tr>
		<td><div align="center"><?=$objResult["TypeID"];?></div></td>
		<td><div align="center"><?=$objResult["cnt"];?></div></td>
		</tr>

		<?php 
		}  

		if (!$objQuery) {
			echo "Error [" . sqlsrv_errors() . "]";
		} 
	}
 ?>
	</table> 
 	<button id="btnSelectForm" class="button-20" onclick="document.getElementById('myForm').style.display = 'block';" >Number</button>
	
	<div class="form-popup" id="myForm" onkeypress="if(event.keyCode==13){if(insertValidation()){frmInsert.submit();}}"> 
		
	<form name="frmInsert" class="form-container" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
			<h3>Select a fingerprint</h3>
			<label>Number:</label>
			<input maxlength="40" type="text" name="num" /><br/>
			

			<input name="btnInsert" type="button" class="btn" value="Go" onclick="if(insertValidation()){frmInsert.submit();}">
			<button type ="button" class = "btn cancel" onclick="document.getElementById('myForm').style.display = 'none';">Cancel</button>
		</form>
		</div>

		<script>
			function insertValidation()  {
				var num=frmInsert.num.value;
				var str = "";
				if(num.length > 0){
					return true;
				}
				else
					str="Cannot select an empty fingerprint\n";
				alert(str);
				return false;
			}
		</script>


<?php
    $userTypes = array("System Admin", "Functions Admin", "Simple User");
    echo "Connecting to SQL server (" . $serverName . ")<br/>";
    echo "Database: " . $connectionOptions[Database] . ", SQL User: " . $connectionOptions[Uid] . "<br/>";
    echo "User: " . $_SESSION["userID"] . ", UserType: " . $userTypes[$_SESSION["userType"]-1] . "<br/>";

    /* Free connection resources. */
    sqlsrv_close($conn);

    // $execution_time = round((($time_end - $time_start)*1000),2);
    // echo 'QueryTime: '.$execution_time.' ms';
    
    ?>

		<hr>
		<?php
    if (isset($_POST['disconnect'])) {
	    echo "Clossing session and redirecting to start page";
	    session_unset();
	    session_destroy();
	    die('<meta http-equiv="refresh" content="1; url=../index.php" />');
    }
    ?>
	</div>

</body>

</html>

