<?php
session_start();
// Get the DB connection info from the session
if (isset($_SESSION["userID"]) && isset($_SESSION["connectionOptions"]) && isset($_SESSION["userID"]) && isset($_SESSION["userType"])) {
	$serverName = $_SESSION["serverName"];
	$connectionOptions = $_SESSION["connectionOptions"];
	$userID = $_SESSION["userID"];
	$userType = $_SESSION["userType"];
    $zid = $_GET["zid"];

	if ($userType == '2') {
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

$strSQL1 = "{call dbo.Q3_SelectFloorsByID(?)}";
						$params = array(
							array($zid, SQLSRV_PARAM_IN)
						);
						$objQuery1 = sqlsrv_query($conn, $strSQL1, $params);
						$row = sqlsrv_fetch_array($objQuery1);
						$FloorZ = $row["FloorZ"];
						$BName = $row["BName"];
						$BCode = $row["BCode"];
						$FloorID = $zid;
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
        <a href="../q4/editbfloors.php?fid=<?=$BCode?>"> - Edit Floors</a>
        <a href="../q4/editpois.php?zid=<?=$zid?>"> -- Edit POIs</a>
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
					<h2>Insert / Edit / Delete POIs on Building <?=$BName;?>, Floor <?=$FloorZ;?> (FloorID: <?=$FloorID;?>)</h2>
                    <h4>Z is by default = <?=$FloorZ;?></h4>
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
				<h2 style="text-align:center;">Insert new POI</h2>
                <h4 style="text-align:center;">Z is by default = <?=$FloorZ;?></h4>
                <label> Name: </label>
				<input type="text" name="POIName" />
                <label> Summary: </label>
				<input type="text" name="Summary" />
                <label> Type: </label>
				<input type="text" name="POIType" />
                <label> Owner: </label>
				<input type="text" name="POIOwner" />
				<label> x: </label>
				<input type="text" name="x" />
				<label> y: </label>
				<input type="text" name="y" />
				<input type="button" class="btn" value="Insert"
					onclick="if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}" />
				<button type="button" class="btn cancel"
					OnClick="document.getElementById('myForm').style.display = 'none';">Cancel</button>
			</form>
		</div>
		<hr />
		<div
			onkeypress="if(event.keyCode==13){if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}}">
			<h2>List of all fingerprints on Building <?=$BName;?>, Floor <?=$FloorZ;?></h2>
            <h4>Z is by default = <?=$FloorZ;?></h4>
			<form name="frmMain" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']."?zid=".$zid); ?>">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="fidpass" value="">
				<table width="100%" border="1">
					<tr>
						<th width="10%">
							<div align="center">POIID </div>
						</th>
                        <th width="15%">
							<div align="center">Name</div>
						</th>
                        <th width="15%">
							<div align="center">Summary</div>
						</th>
                        <th width="10%">
							<div align="center">Type</div>
						</th>
                        <th width="10%">
							<div align="center">Owner</div>
						</th>
						<th width="10%">
							<div align="center">x</div>
						</th>
						<th width="10%">
							<div align="center">y</div>
						</th>
						<th width="20%" colspan="2">
							<div align="center">Actions</div>
						</th>
					</tr>
					<?php
        $tsql = "{CALL dbo.Q4_SelectPOIsOfFloor(?)}";
        $params = array(
	    	array($FloorID, SQLSRV_PARAM_IN)
	    );
        $objQuery = sqlsrv_query($conn, $tsql, $params);

        while ($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC)) {
        ?>

					<?php
	        if ($objResult["POIID"] == $_GET["id"] and $_GET["Action"] == "Edit") {
        ?>
					<tr>
						<td>
							<div id='row<?= $objResult["POIID"]; ?>' align="center">
								<?= $objResult["POIID"]; ?>
							</div>
							<input type="hidden" name="hdnEditPOIID" value="<?= $objResult["POIID"]; ?>">
						</td>
						<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="30" type="text"
								name="txtEditPOIName" value="<?= $objResult["POIName"]; ?>"></td>
						<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="40" type="text"
								name="txtEditSummary" value="<?= $objResult["Summary"]; ?>"></td>
                        <td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="30" type="text"
								name="txtEditPOIType" value="<?= $objResult["POIType"]; ?>"></td>
						<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="40" type="text"
								name="txtEditPOIOwner" value="<?= $objResult["POIOwner"]; ?>"></td>
                        <td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="30" type="text"
								name="txtEditx" value="<?= $objResult["x"]; ?>"></td>
						<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="40" type="text"
								name="txtEdity" value="<?= $objResult["y"]; ?>"></td>
						<td colspan="2" align="right">
							<div align="center">
								<input class="textbtn success" name="btnAdd" type="button" id="btnUpdate" value="Update"
									onclick="if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}">
								<input class="textbtn danger" name="btnAdd" type="button" id="btnCancel" value="Cancel"
									OnClick="window.location='<?= $_SERVER["PHP_SELF"]."?zid=". $zid;?>';">
							</div>
						</td>
					</tr>
					<?php
	        } else {
        ?>
					<tr>
						<td>
							<div id='row<?= $objResult["POIID"]; ?>' align="center">
								<?= $objResult["POIID"]; ?>
							</div>
						</td>
                        <td align="center">
							<?= $objResult["POIName"]; ?>
						</td>
						<td align="center">
							<?= $objResult["Summary"]; ?>
						</td>
                        <td align="center">
							<?= $objResult["POIType"]; ?>
						</td>
						<td align="center">
							<?= $objResult["POIOwner"]; ?>
						</td>
						<td align="center">
							<?= $objResult["x"]; ?>
						</td>
						<td align="center">
							<?= $objResult["y"]; ?>
						</td>
						<td align="center" width="10%">
							<input class="textbtn warning" name="btnEdit" type="button" id="btnEdit" value="Edit"
								OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>?zid=<?=$zid?>&Action=Edit&id=<?= $objResult["POIID"]; ?>#row<?= $objResult["POIID"]; ?>';">
						</td>
						<td align="center" width="10%">
							<input class="textbtn danger" name="btnDelete" type="button" id="btnChange" value="Delete"
								OnClick="if(confirm('Confirm Delete?')==true){frmMain.hdnCmd.value='Delete';frmMain.fidpass.value='<?= $objResult["POIID"] ?>';frmMain.submit();}">
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
    // $time_start = microtime(true);
    
    //*** Update Condition ***//  
    if ($_POST["hdnCmd"] == "Update") {
	    $strSQL = "{call dbo.Q4_EditPOI(?, ?, ?, ?, ?, ?, ?, ?)}";
	    $params = array(
	    	array($_POST["hdnEditPOIID"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEditPOIName"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEditSummary"], SQLSRV_PARAM_IN),
            array($_POST["txtEditPOIType"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEditPOIOwner"], SQLSRV_PARAM_IN),
            array($_POST["txtEditx"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEdity"], SQLSRV_PARAM_IN),
	    	array($FloorID, SQLSRV_PARAM_IN)
	    );
	    $objQuery = sqlsrv_query($conn, $strSQL, $params);
	    $objRow = sqlsrv_fetch_array($objQuery);
	    if (!$objQuery) {
			echo "Error Update [";
			print_r(sqlsrv_errors());
			echo "]<br/>";
	    } else
		    echo "<meta http-equiv='refresh' content='0'>";
    }

    //*** Delete Condition ***//  
    if ($_POST["hdnCmd"] == "Delete") {
	    $strSQL = "{call dbo.Q4_DeletePOI(?)}";
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
	    $strSQL = "{call dbo.Q4_InsertPOI(?, ?, ?, ?, ?, ?, ?)}";
	    $params = array(
	    	array($_POST["POIName"], SQLSRV_PARAM_IN),
	    	array($_POST["Summary"], SQLSRV_PARAM_IN),
            array($_POST["POIType"], SQLSRV_PARAM_IN),
	    	array($_POST["POIOwner"], SQLSRV_PARAM_IN),
            array($_POST["x"], SQLSRV_PARAM_IN),
	    	array($_POST["y"], SQLSRV_PARAM_IN),
	    	array($FloorID, SQLSRV_PARAM_IN),
	    );
	    $objQuery = sqlsrv_query($conn, $strSQL, $params);
	    $objRow = sqlsrv_fetch_array($objQuery);
	    if (!$objQuery) {
		    echo "Error Insert [" . sqlsrv_errors() . "]";
	    } else
		    echo "<meta http-equiv='refresh' content='0'>";
    }

    // $time_end = microtime(true);
    
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
            var name = f1.POIName.value;
			var summary = f1.Summary.value;
            var type = f1.POIType.value;
			var x = f1.x.value;
			var y = f1.y.value;

			if (x.length > 0 && y.length > 0 && name.length>0 && summary.length>0 && type.length>0) {
				return true;
			}
			var str = "";
			if (name.length == 0)
				str += "Name is empty\n";
			if (summary.length == 0)
				str += "Summary is empty\n";
            if (type.length == 0)
				str += "Type is empty\n";
            if (x.length == 0)
				str += "x is empty\n";
			if (y.length == 0)
				str += "y is empty\n";
			alert(str);
			return false;
		}
		function updateValidation() {
			var name = frmMain.txtEditPOIName.value;
			var summary = frmMain.txtEditSummary.value;
            var type = frmMain.txtEditPOIType.value;
			var x = frmMain.txtEditx.value;
			var y = frmMain.txtEdity.value;

			if (x.length > 0 && y.length > 0 && name.length>0 && summary.length>0 && type.length>0) {
				return true;
			}
			var str = "";
			if (name.length == 0)
				str += "Name is empty\n";
			if (summary.length == 0)
				str += "Summary is empty\n";
            if (type.length == 0)
				str += "Type is empty\n";
            if (x.length == 0)
				str += "x is empty\n";
			if (y.length == 0)
				str += "y is empty\n";
			alert(str);
			return false;
		}  
	</script>

</body>

</html>