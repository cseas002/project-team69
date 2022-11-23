<?php
session_start();
// Get the DB connection info from the session
if (isset($_SESSION["userID"]) && isset($_SESSION["connectionOptions"]) && isset($_SESSION["userID"]) && isset($_SESSION["userType"])) {
	$serverName = $_SESSION["serverName"];
	$connectionOptions = $_SESSION["connectionOptions"];
	$userID = $_SESSION["userID"];
	$userType = $_SESSION["userType"];

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
					<h2>Insert / Edit / Delete Items from Fingerprint <?=$_GET["fid"]?></h2>
				</td>
			</tr>
		</table>
		<hr>

		<button class="btnUpForm" onclick="document.getElementById('myForm').style.display = 'block';">Insert
			Type</button>

		<div class="form-popup" id="myForm"
			onkeypress="if(event.keyCode==13){if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}}">
			<form name="f1" method="POST" class="form-container">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="hdnfid" value="<?=$_GET["fid"]?>">
				<h2 style="text-align:center;">Insert new item</h2>
				<label> Height: </label>
				<input type="text" name="Height" />
				<label> Width: </label>
				<input type="text" name="Width" />
				<label> Type: </label>
				<select name="TypeID" id="selectTypeID">
					<option value=''> </option>
					<?php
                $strSQL1 = "{call dbo.Q2_Select()}";
                $objQuery1 = sqlsrv_query($conn, $strSQL1);
                while ($row = sqlsrv_fetch_array($objQuery1)) {
                ?>
					<option value='<?= $row["TypeID"]?>'>
						<?= $row["Title"]?> <?= $row["Model"]?>
					</option>
					<?php
                }
                        ?>
				</select>
				<input type="button" class="btn" value="Insert"
					onclick="if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}" />
				<button type="button" class="btn cancel"
					OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>';">Cancel</button>
			</form>
		</div>
		<hr />
		<div
			onkeypress="if(event.keyCode==13){if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}}">
			<h2>List of all types</h2>
			<form name="frmMain" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="fidpass" value="<?=$_GET["fid"]?>">
				<table width="100%" border="1">
					<tr>
						<th width="13%">
							<div align="center">ItemID </div>
						</th>
						<th width="13%">
							<div align="center">Height</div>
						</th>
						<th width="13%">
							<div align="center">Width</div>
						</th>
						<th width="13%">
							<div align="center">Type</div>
						</th>
						<th width="22%" colspan="3">
							<div align="center">Actions</div>
						</th>
					</tr>
					<?php
        $tsql = "EXEC dbo.Q3_SelectItemsOfFingerprint(?)";
		$params = array(
	    	array($_GET["fid"], SQLSRV_PARAM_IN));
        $objQuery = sqlsrv_query($conn, $tsql, $params);

        while ($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC)) {
        ?>

					<?php
	        if ($objResult["ItemID"] == $_GET["id"] and $_GET["Action"] == "Edit") {
        ?>
					<tr>
						<td>
							<div id='row<?= $objResult["ItemID"]; ?>' align="center">
								<?= $objResult["ItemID"]; ?>
							</div>
							<input type="hidden" name="hdnEditItemID" value="<?= $objResult["ItemID"]; ?>">
						</td>
						<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="30" type="text"
								name="txtEditHeight" value="<?= $objResult["Height"]; ?>"></td>
						<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="40" type="text"
								name="txtEditWidth" value="<?= $objResult["Width"]; ?>"></td>
						<td align="center" style="height:40px;">
							<select name="txtEditTypeID" id="txtEditTypeID">
								<option value=''> </option>
								<?php
		        $strSQL1 = "{call dbo.Q2_Select()}";
		        $objQuery1 = sqlsrv_query($conn, $strSQL1);
		        while ($row = sqlsrv_fetch_array($objQuery1)) {
		
			        if ($objResult["TypeID"] == $row["TypeID"]) {
				        echo "<option value='" . $row["TypeID"] . "' selected='selected'>" . $row["Title"] . " - " . $row["Model"] . "</option>";

			        } else {
				        echo "<option value='" . $row["TypeID"] . "'>" . $row["Title"] . " - " . $row["Model"] . "</option>";

			        }
		        }
                ?>
							</select>
			</td>
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
							<div id='row<?= $objResult["ItemID"]; ?>' align="center">
								<?= $objResult["ItemID"]; ?>
							</div>
						</td>
						<td align="center">
							<?= $objResult["Height"]; ?>
						</td>
						<td align="center">
							<?= $objResult["Width"]; ?>
						</td>
						<td align="center">
							<?= $objResult["TypeID"]; ?>
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
								OnClick="if(confirm('Confirm Delete?')==true){frmMain.fidpass.value='<?= $objResult["FingerprintID"] ?>';frmMain.submit();}">
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
	    $strSQL = "{call dbo.Q3_EditFingerprint(?, ?, ?, ?, ?, ?)}";
	    $floor = null;
	    if ($_POST["txtEditFloorZ"] != '')
		    $floor = $_POST["txtEditFloorZ"];
	    $bcode = null;
	    if ($_POST["txtEditBCode"] != '')
		    $bcode = $_POST["txtEditBCode"];
	    $params = array(
	    	array($_POST["hdnEditFingerprintID"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEditx"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEdity"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEditz"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEditFloorZ"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEditBCode"], SQLSRV_PARAM_IN)
	    );
	    $objQuery = sqlsrv_query($conn, $strSQL, $params);
	    $objRow = sqlsrv_fetch_array($objQuery);
	    if (!$objQuery) {
		    echo "Error Update [" . sqlsrv_errors() . "]";
	    } else
		    echo "<meta http-equiv='refresh' content='0'>";
    }

    //*** Delete Condition ***//  
    if ($_POST["hdnCmd"] == "Delete") {
	    $strSQL = "{call dbo.Q3_DeleteFingerprint(?)}";
	    $params = array(
	    	array($_POST["FingerprintID"], SQLSRV_PARAM_IN)
	    );
	    $objQuery = sqlsrv_query($conn, $strSQL, $params);
	    $objRow = sqlsrv_fetch_array($objQuery);
	    if (!$objQuery) {
		    echo "Error Delete [" . sqlsrv_errors() . "]";
	    } else
		    echo "<meta http-equiv='refresh' content='0'>";
    }

    if ($_POST["hdnCmd"] == "Insert") {
	    $strSQL = "{call dbo.Q3_InsertFingerprint(?, ?, ?, ?, ?)}";
	    $floor = null;
	    if ($_POST["FloorZ"] != '')
		    $floor = $_POST["FloorZ"];
	    $bcode = null;
	    if ($_POST["BCode"] != '')
		    $bcode = $_POST["BCode"];
	    $params = array(
	    	array($_POST["x"], SQLSRV_PARAM_IN),
	    	array($_POST["y"], SQLSRV_PARAM_IN),
	    	array($_POST["z"], SQLSRV_PARAM_IN),
	    	array($floor, SQLSRV_PARAM_IN),
	    	array($bcode, SQLSRV_PARAM_IN)
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
    echo "User: " . $_SESSION["userID"] . ", UserType: " . $userTypes[$_SESSION["userType"]] . "<br/>";

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

			if (x.length > 0 && y.length > 0 && z.length > 0) {
				return true;
			}
			var str = "";
			if (x.length == 0)
				str += "x is empty\n";
			if (y.length == 0)
				str += "y is empty\n";
			if (z.length == 0)
				str += "z is empty\n";
			alert(str);
			return false;
		}
		function updateValidation() {
			var x = frmMain.txtEditx.value;
			var y = frmMain.txtEdity.value;
			var z = frmMain.txtEditz.value;

			if (x.length > 0 && y.length > 0 && z.length > 0) {
				return true;
			}
			var str = "";
			if (x.length == 0)
				str += "x is empty\n";
			if (y.length == 0)
				str += "y is empty\n";
			if (z.length == 0)
				str += "z is empty\n";
			alert(str);
			return false;
		}  
	</script>

</body>

</html>