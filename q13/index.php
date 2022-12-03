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
$conn = sqlsrv_connect($serverName, $connectionOptions);


    //*** Update Condition ***//  
    if ($_POST["hdnCmd"] == "Update") {
	    $strSQL = "{call dbo.Q3_EditFingerprint(?, ?, ?, ?, ?, ?)}";

		$fz = $_POST["txtEditz"];
		$flid = NULL;
		if($_POST["txtEditFloorID"] != ''){
			$array = explode("?", $_POST["txtEditFloorID"]);
			$fz = $array[1];
			$flid = $array[0];
		}

		
		$timeNew = $_POST["txtEditRegDate"].":00";
		//echo $_POST["hdnEditFingerprintID"] . " " . $_POST["txtEditx"] ." " .  $_POST["txtEdity"] . " ".$fz. " ". $flid . " ". $timeNew;

	    $params = array(
	    	array($_POST["hdnEditFingerprintID"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEditx"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEdity"], SQLSRV_PARAM_IN),
			array($fz, SQLSRV_PARAM_IN),
	    	array($flid, SQLSRV_PARAM_IN),
			array($timeNew, SQLSRV_PARAM_IN)
	    );
	    $objQuery = sqlsrv_query($conn, $strSQL, $params);
	    $objRow = sqlsrv_fetch_array($objQuery);
	    if (!$objQuery) {
			echo "Error Update [";
			print_r(sqlsrv_errors());
			echo "]<br/>";
	    } 
		else
		    echo "<meta http-equiv='refresh' content='0'>";
    }
            ?>


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
					<h2>Insert / Edit / Delete Fingerprints</h2>
				</td>
			</tr>
		</table>
		<hr>

		

		<div class="form-popup" id="myForm"
			onkeypress="if(event.keyCode==13){if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}}">
			<form name="f1" method="POST" class="form-container">
				<input type="hidden" name="hdnCmd" value="">
				<h2 style="text-align:center;">Insert new fingerprint</h2>
				<label> x: </label>
				<input type="text" name="x" />
				<label> y: </label>
				<input type="text" name="y" />
				<label> Level: </label>
				<input type="text" name="z" />
				<label> Building/Floor: </label>
				<select name="FloorID" id="FloorID">
					<option value=''> </option>
					<?php
                $strSQL = "{call dbo.Q3_SelectBuildings()}";
                $objQuery = sqlsrv_query($conn, $strSQL);
                while ($row = sqlsrv_fetch_array($objQuery)) {
                ?>
					<option value='<?= $row["FloorID"] . "?" . $row["FloorZ"] ?>'>
						<?= $row["BName"] . " - " . $row["FloorZ"] ?>
					</option>
					<?php
                }
                        ?>
				</select>
				<label> RegDate: </label>
				<input type="datetime-local" name="RegDate" />
				<input type="button" class="btn" value="Insert"
					onclick="if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}" />
				<button type="button" class="btn cancel"
					OnClick="document.getElementById('myForm').style.display = 'none';">Cancel</button>
			</form>
		</div>
		<hr />
		<div
			onkeypress="if(event.keyCode==13){if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}}">
			<h2>List of all fingerprints</h2>
			<form name="frmMain" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="fidpass" value="">
				<table width="100%" border="1">
					<tr>
						<th width="13%">
							<div align="center">FingerprintID </div>
						</th>
						<th width="13%">
							<div align="center">x</div>
						</th>
						<th width="13%">
							<div align="center">y</div>
						</th>
						<th width="13%">
							<div align="center">Level</div>
						</th>
						<th width="13%">
							<div align="center">Building - Floor</div>
						</th>
						<th width="13%">
							<div align="center">RegDate</div>
						</th>
						<th width="22%" colspan="3">
							<div align="center">Actions</div>
						</th>
					</tr>
					<?php
        $tsql = "EXEC dbo.Q3_SelectFingerprints";
        $objQuery = sqlsrv_query($conn, $tsql);

        while ($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC)) {
        ?>
			<tr>
					<tr>
						<td>
							<div id='row<?= $objResult["FingerprintID"]; ?>' align="center">
								<?= $objResult["FingerprintID"]; ?>
							</div>
						</td>
						<td align="center">
							<?= $objResult["x"]; ?>
						</td>
						<td align="center">
							<?= $objResult["y"]; ?>
						</td>
						<td align="center">
							<?= $objResult["Level"]; ?>
						</td>
						<?php
						$FloorLabel = '';
						if($objResult["FloorID"] != ''){
						$strSQL1 = "{call dbo.Q3_SelectFloorsByID(?)}";
						$params = array(
							array($objResult["FloorID"], SQLSRV_PARAM_IN)
						);
						$objQuery1 = sqlsrv_query($conn, $strSQL1, $params);
						$row = sqlsrv_fetch_array($objQuery1);
						$FloorZ = $row["FloorZ"];
						$BName = $row["BName"];
						$FloorID = $row["FloorID"];
						$FloorLabel = $BName . " - " . $FloorZ;
						}
						?>
						<td align="center">
							<?= $FloorLabel; ?>
						</td>
						<td align="center">
							<?= $objResult["RegDate"]; ?>
						</td>
						<td align="center" width="7%">
							<?php
		                    echo '<a href="';
							echo "Select.php?Action=Select&id={$objResult['FingerprintID']}&row={$objResult['FingerprintID']}";
							echo '">Select</a>';
								?>
						</td>
						
					</tr>
					<?php
	        }
        ?>
				</table>
			</form>
		</div>

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

