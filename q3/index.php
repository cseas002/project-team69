<?php
session_start();
// Get the DB connection info from the session
if (isset($_SESSION["userID"]) && isset($_SESSION["connectionOptions"]) && isset($_SESSION["userID"]) && isset($_SESSION["userType"])) {
	$serverName = $_SESSION["serverName"];
	$connectionOptions = $_SESSION["connectionOptions"];
	$userID = $_SESSION["userID"];
	$userType = $_SESSION["userType"];

	if ($userType == '3') {
?>
<script>
	alert("Simple users can't insert/modify/delete fingerprints.");
</script>
<?php
		die('<meta http-equiv="refresh" content="0; url=../menu.php" />');

	}

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

    //*** Delete Condition ***//  
    if ($_POST["hdnCmd"] == "Delete") {
	    $strSQL = "{call dbo.Q3_DeleteFingerprint(?)}";
	    $params = array(
	    	array($_POST["fidpass"], SQLSRV_PARAM_IN)
	    );
	    $objQuery = sqlsrv_query($conn, $strSQL, $params);
	    $objRow = sqlsrv_fetch_array($objQuery);
	    if (!$objQuery) {
		    echo "Error Delete [";
			print_r(sqlsrv_errors());
			echo "]<br/>";
	    } else
		    echo "<meta http-equiv='refresh' content='0'>";
    }

    if ($_POST["hdnCmd"] == "Insert") {
	    $strSQL = "{call dbo.Q3_InsertFingerprint(?, ?, ?, ?, ?)}";

		$fz = $_POST["z"];
		$flid = NULL;
		if($_POST["FloorID"] != ''){
			$array = explode("?", $_POST["FloorID"]);
			$fz = $array[1];
			$flid = $array[0];
		}
		
		$timeNew = $_POST["RegDate"].":00";

	    $params = array(
	    	array($_POST["x"], SQLSRV_PARAM_IN),
	    	array($_POST["y"], SQLSRV_PARAM_IN),
	    	array($fz, SQLSRV_PARAM_IN),
			array($flid, SQLSRV_PARAM_IN),
			array($timeNew, SQLSRV_PARAM_IN)
	    );
	    $objQuery = sqlsrv_query($conn, $strSQL, $params);
	    $objRow = sqlsrv_fetch_array($objQuery);
	    if (!$objQuery) {
		    echo "Error Insert [" . sqlsrv_errors() . "]";
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
				<td vAlign=center align=middle>
					<h2>Insert / Edit / Delete Fingerprints</h2>
				</td>
			</tr>
		</table>
		<hr>

		<button class="button-20" onclick="document.getElementById('myForm').style.display = 'block';">Insert
			Fingerprint</button>

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

					<?php
	        if ($objResult["FingerprintID"] == $_GET["id"] and $_GET["Action"] == "Edit") {
        ?>
					<tr>
						<td>
							<div id='row<?= $objResult["FingerprintID"]; ?>' align="center">
								<?= $objResult["FingerprintID"]; ?>
							</div>
							<input type="hidden" name="hdnEditFingerprintID" value="<?= $objResult["FingerprintID"]; ?>">
						</td>
						<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="30" type="text"
								name="txtEditx" value="<?= $objResult["x"]; ?>"></td>
						<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="40" type="text"
								name="txtEdity" value="<?= $objResult["y"]; ?>"></td>
						<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="40" type="text"
								name="txtEditz" value="<?= $objResult["Level"]; ?>"></td>
						<td align="center" style="height:40px;">
							<select name="txtEditFloorID" id="txtEditFloorID">
								<option value=''> </option>
								<?php
		        $strSQL1 = "{call dbo.Q3_SelectBuildings()}";
		        $objQuery1 = sqlsrv_query($conn, $strSQL1);
		        while ($row = sqlsrv_fetch_array($objQuery1)) {
					$FloorZ = $row["FloorZ"];
					$BName = $row["BName"];
					$FloorID = $row["FloorID"];
			        if ($objResult["FloorID"] == $FloorID) {
				        echo "<option value='" . $FloorID . "?" . $FloorZ . "' selected='selected'>" . $BName . " - " . $FloorZ . "</option>";

			        } else {
				        echo "<option value='" . $FloorID . "?" . $FloorZ . "'>" . $BName . " - " . $FloorZ . "</option>";

			        }
		        }
                ?>
							</select>
						</td>
						<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" type="datetime-local"
								name="txtEditRegDate" value="<?=date("Y-m-d\TH:i:s", strtotime($objResult['RegDate']));?>"></td>
						
						<td colspan="3" align="right">
							<div align="center">
								<input class="textbtn success" name="btnAdd" type="button" id="btnUpdate" value="Update"
									onclick="if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}">
								<input class="textbtn danger" name="btnAdd" type="button" id="btnCancel" value="Cancel"
									OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>';">
							</div>
						</td>
					</tr>
					<?php
	        } else {
        ?>
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
						<td align="center" width="8%">
							<input class="textbtn warning" name="btnEditItems" type="button" id="btnEditItems"
								value="Edit Items"
								OnClick="window.location='edititems.php?fid=<?= $objResult["FingerprintID"]; ?>';">
						</td>
						<td align="center" width="7%">
							<input class="textbtn warning" name="btnEdit" type="button" id="btnEdit" value="Edit"
								OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>?Action=Edit&id=<?= $objResult["FingerprintID"]; ?>#row<?= $objResult["FingerprintID"]; ?>';">
						</td>
						<td align="center" width="7%">
							<input class="textbtn danger" name="btnDelete" type="button" id="btnChange" value="Delete"
								OnClick="if(confirm('Confirm Delete?')==true){frmMain.hdnCmd.value='Delete';frmMain.fidpass.value='<?= $objResult["FingerprintID"] ?>';frmMain.submit();}">
						</td>
					</tr>
					<?php
	        }
        ?>
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

	<script>
		function insertValidation() {
			var x = f1.x.value;
			var y = f1.y.value;
			var z = f1.z.value;
			var RegDate = f1.RegDate.value;

			var building = f1.FloorID.value;

			if(z.length == 0){
				const myArray = z.split("?");
				f1.z.value = myArray[0];
				f1.FloorID.value = myArray[1];
				z = myArray[0];
				building = myArray[1];
			}

			if (x.length > 0 && y.length > 0 && z.length > 0 && RegDate.length>0) {
				return true;
			}
			var str = "";
			if (x.length == 0)
				str += "x is empty\n";
			if (y.length == 0)
				str += "y is empty\n";
			if (z.length == 0)
				str += "z is empty\n";
			if (RegDate.length == 0)
				str += "RegDate is empty\n";
			alert(str);
			return false;
		}
		function updateValidation() {
			var x = frmMain.txtEditx.value;
			var y = frmMain.txtEdity.value;
			var z = frmMain.txtEditz.value;
			var RegDate = frmMain.txtEditRegDate.value;

			var building = frmMain.txtEditFloorID.value;

			if(z.length == 0){
				const myArray = z.split("?");
				frmMain.txtEditz.value = myArray[0];
				frmMain.txtEditFloorID.value = myArray[1];
				z = myArray[0];
				building = myArray[1];
			}

			if (x.length > 0 && y.length > 0 && z.length > 0 && RegDate.length>0) {
				return true;
			}
			var str = "";
			if (x.length == 0)
				str += "x is empty\n";
			if (y.length == 0)
				str += "y is empty\n";
			if (z.length == 0)
				str += "z is empty\n";
			if (RegDate.length == 0)
				str += "RegDate is empty\n";
			alert(str);
			return false;
		}  
	</script>

</body>

</html>