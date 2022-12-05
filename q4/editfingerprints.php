<?php
session_start();
// Get the DB connection info from the session
if (isset($_SESSION["userID"]) && isset($_SESSION["connectionOptions"]) && isset($_SESSION["userID"]) && isset($_SESSION["userType"])) {
	$serverName = $_SESSION["serverName"];
	$connectionOptions = $_SESSION["connectionOptions"];
	$userID = $_SESSION["userID"];
	$userType = $_SESSION["userType"];
    $zid = $_GET["zid"];

	if ($userType == '3') {
?>
<script>
	alert("Simple users can't insert/modify/delete fingerprints.");
</script>
<?php
		die('<meta http-equiv="refresh" content="0; url=../menu.php" />');

	}

	if(!isset($_GET["zid"])){
		?>
<script>
	alert("Floor ID is not set. Redirecting you back to menu page.");
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


						//*** Update Condition ***//  
    if ($_POST["hdnCmd"] == "Update") {
	    $strSQL = "{call dbo.Q3_EditFingerprint(?, ?, ?, ?, ?, ?)}";

		$timeNew = $_POST["txtEditRegDate"].":00";

	    $params = array(
	    	array($_POST["hdnEditFingerprintID"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEditx"], SQLSRV_PARAM_IN),
	    	array($_POST["txtEdity"], SQLSRV_PARAM_IN),
	    	array($FloorZ, SQLSRV_PARAM_IN),
	    	array($FloorID, SQLSRV_PARAM_IN),
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

		$timeNew = $_POST["RegDate"].":00";

	    $params = array(
	    	array($_POST["x"], SQLSRV_PARAM_IN),
	    	array($_POST["y"], SQLSRV_PARAM_IN),
	    	array($FloorZ, SQLSRV_PARAM_IN),
	    	array($FloorID, SQLSRV_PARAM_IN),
			array($timeNew, SQLSRV_PARAM_IN)
	    );
	    $objQuery = sqlsrv_query($conn, $strSQL, $params);
	    $objRow = sqlsrv_fetch_array($objQuery);
	    if (!$objQuery) {
		    echo "Error Insert [" . sqlsrv_errors() . "]";
	    } else
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
        <a href="../q4/editbfloors.php?fid=<?=$BCode?>"> - Edit Floors</a>
        <a href="../q4/editfingerprints.php?zid=<?=$zid?>"> -- Edit Fingerprints</a>
		<a href="../q5">Query 5</a>
		<?php } ?>
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
					<h2>Insert / Edit / Delete Fingerprints on Building <?=$BName;?>, Floor <?=$FloorZ;?> (FloorID: <?=$FloorID;?>)</h2>
                    <h4>Z is by default = <?=$FloorZ;?></h4>
				</td>
			</tr>
		</table>
		<hr>

		<button class="button-20" onclick="document.getElementById('myForm').style.display = 'block';">Insert
			Fingerprint</button>
			<button id="btnAdvSearchForm" class="button-20" onclick="document.getElementById('myForm1').style.display = 'block';">Advanced Search</button>			
		<button id="btnSearchForm" class="button-20" onclick="document.getElementById('myForm2').style.display = 'block';">Simple Search</button>			
		<button id="btnReset" style="display:none;" class="textbtn" onclick="window.location='<?= $_SERVER['PHP_SELF']; ?>?zid=<?=$zid?>';">Reset</button>



		<div class="form-popup" id="myForm"
			onkeypress="if(event.keyCode==13){if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}}">
			<form name="f1" method="POST" class="form-container">
				<input type="hidden" name="hdnCmd" value="">
				<h2 style="text-align:center;">Insert new fingerprint</h2>
                <h4 style="text-align:center;">Z is by default = <?=$FloorZ;?></h4>
				<label> x: </label>
				<input type="text" name="x" />
				<label> y: </label>
				<input type="text" name="y" />
				<label> RegDate: </label>
				<input type="datetime-local" name="RegDate" />
				<input type="button" class="btn" value="Insert"
					onclick="if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}" />
				<button type="button" class="btn cancel"
					OnClick="document.getElementById('myForm').style.display = 'none';">Cancel</button>
			</form>
		</div>
		<div class="form-popup" id="myForm1"
			onkeypress="if(event.keyCode==13){formAdvS.hdnCmd1.value='AdvSearch';formAdvS.submit();}">
			<form name="formAdvS" method="POST" class="form-container">
				<input type="hidden" name="hdnCmd1" value="">
				<h2 style="text-align:center;">Advanced Search</h2>
				<label> Fingerprint ID: </label>
				<input type="text" name="FingerprintID1" value="<?=$_POST['FingerprintID1']?>"/>
				<label> x: </label>
				<input type="text" name="x1" value="<?=$_POST['x1']?>"/>
				<label> y: </label>
				<input type="text" name="y1" value="<?=$_POST['y1']?>" />
				<label> RegDate: </label>
				<input type="datetime-local" name="RegDate1"/>
				<input type="button" class="btn" value="AdvSearch"
					onclick="formAdvS.hdnCmd1.value='AdvSearch';formAdvS.submit();" />
				<button type="button" class="btn cancel"
					OnClick="document.getElementById('myForm1').style.display = 'none';">Cancel</button>
			</form>
		</div>
		<div class="form-popup" id="myForm2"
			onkeypress="if(event.keyCode==13){formS.hdnCmd2.value='Search';formS.submit();}">
			<form name="formS" method="POST" class="form-container">
				<input type="hidden" name="hdnCmd2" value="">
				<h2 style="text-align:center;">Search</h2>
				<label> Keyword: </label>
				<input type="text" name="keyword" value="<?=$_POST['keyword']?>"/>
				<input type="button" class="btn" value="Search"
					onclick="formS.hdnCmd2.value='AdvSearch';formS.submit();" />
				<button type="button" class="btn cancel"
					OnClick="document.getElementById('myForm2').style.display = 'none';">Cancel</button>
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
						<th width="15%">
							<div align="center">FingerprintID </div>
						</th>
						<th width="15%">
							<div align="center">x</div>
						</th>
						<th width="15%">
							<div align="center">y</div>
						</th>
						<th width="15%">
							<div align="center">RegDate</div>
						</th>
						<th width="40%" colspan="3">
							<div align="center">Actions</div>
						</th>
					</tr>
					<?php
        if($_POST['hdnCmd2']=='Search'){
			?>
			<script>
				document.getElementById("btnReset").style="display:inline-block;";
			</script>
			<?php
			$tsql = "{CALL dbo.Search_FINGERPRINT_OF_FLOOR(?, ?)}";
			$params = array(
				array($_POST['keyword'], SQLSRV_PARAM_IN),
				array($FloorID, SQLSRV_PARAM_IN)
			);
			$objQuery = sqlsrv_query($conn, $tsql, $params);
		} 
		else if($_POST['hdnCmd1']=='AdvSearch'){
			?>
			<script>
				document.getElementById("btnReset").style="display:inline-block;";
			</script>
			<?php
			$tsql = "{CALL dbo.Advanced_Search_FINGERPRINT_OF_FLOOR(?,?,?,?,?,?)}";
			$timeNew = '';
			if($_POST["RegDate1"]!='')
				$timeNew = $_POST["RegDate1"].":00";
			$params = array(
				array($_POST['FingerprintID1'], SQLSRV_PARAM_IN),
				array($_POST['x1'], SQLSRV_PARAM_IN),
				array($_POST['y1'], SQLSRV_PARAM_IN),
				array($FloorZ, SQLSRV_PARAM_IN),
				array($timeNew, SQLSRV_PARAM_IN),
				array($FloorID, SQLSRV_PARAM_IN)
			);
			$objQuery = sqlsrv_query($conn, $tsql, $params);
		} 
		else {
			$tsql = "{CALL dbo.Q4_SelectFingerprintsOfFloor(?)}";
				$params = array(
					array($FloorID, SQLSRV_PARAM_IN)
				);
				$objQuery = sqlsrv_query($conn, $tsql, $params);
		}

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
								style="text-align:center; width:100%; height: 100%;" maxlength="40" type="datetime-local"
								name="txtEditRegDate" value="<?=date("Y-m-d\TH:i:s", strtotime($objResult['RegDate']));?>"></td>
						<td colspan="3" align="right">
							<div align="center">
								<input class="textbtn success" name="btnAdd" type="button" id="btnUpdate" value="Update"
									onclick="if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}">
								<input class="textbtn danger" name="btnAdd" type="button" id="btnCancel" value="Cancel"
									OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>?zid=<?=$zid?>';">
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
							<?= $objResult["RegDate"]; ?>
						</td>
						<td align="center" width="16%">
							<input class="textbtn warning" name="btnEditItems" type="button" id="btnEditItems"
								value="Edit Items"
								OnClick="window.location='edititems.php?fid=<?= $objResult["FingerprintID"]; ?>';">
						</td>
						<td align="center" width="12%">
							<input class="textbtn warning" name="btnEdit" type="button" id="btnEdit" value="Edit"
								OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>?zid=<?=$zid?>&Action=Edit&id=<?= $objResult["FingerprintID"]; ?>#row<?= $objResult["FingerprintID"]; ?>';">
						</td>
						<td align="center" width="12%">
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
			var RegDate = f1.RegDate.value;

			if (x.length > 0 && y.length > 0 && RegDate.length>0) {
				return true;
			}
			var str = "";
			if (x.length == 0)
				str += "x is empty\n";
			if (y.length == 0)
				str += "y is empty\n";
			if (RegDate.length == 0)
				str += "RegDate is empty\n";
			alert(str);
			return false;
		}
		function updateValidation() {
			var x = frmMain.txtEditx.value;
			var y = frmMain.txtEdity.value;
			var RegDate = frmMain.txtEditRegDate.value;

			if (x.length > 0 && y.length > 0 && RegDate.length>0) {
				return true;
			}
			var str = "";
			if (x.length == 0)
				str += "x is empty\n";
			if (y.length == 0)
				str += "y is empty\n";
				
			if (RegDate.length == 0)
				str += "RegDate is empty\n";
			alert(str);
			return false;
		}  
	</script>

</body>

</html>