<?php
session_start();
// Get the DB connection info from the session
if (isset($_SESSION["userID"]) && isset($_SESSION["connectionOptions"]) && isset($_SESSION["userID"]) && isset($_SESSION["userType"]) && isset($_GET["fid"])) {
	$serverName = $_SESSION["serverName"];
	$connectionOptions = $_SESSION["connectionOptions"];
	$userID = $_SESSION["userID"];
	$userType = $_SESSION["userType"];
	$fid = $_GET["fid"];

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
					<h2>Insert / Edit / Delete Items from Fingerprint <?= $fid ?>
					</h2>
				</td>
			</tr>
		</table>
		<hr>

		<button class="btnUpForm" onclick="document.getElementById('myForm').style.display = 'block';">Insert
			Item</button>

		<div class="form-popup" id="myForm"
			onkeypress="if(event.keyCode==13){if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}}">
			<form name="f1" method="POST" class="form-container">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="hdnfid" value="<?= $_GET["fid"] ?>">
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
					<option value='<?= $row["TypeID"] ?>'>
						<?= $row["Title"] ?>
							<?= $row["Model"] ?>
					</option>
					<?php
                    }
                    ?>
				</select>
				<input type="button" class="btn" value="Insert"
					onclick="if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}" />
				<button type="button" class="btn cancel"
					OnClick="document.getElementById('myForm').style.display = 'none';">Cancel</button>
			</form>
		</div>
		<hr />
		<div
			onkeypress="if(event.keyCode==13){if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}}">
			<h2>List of all items in fingerprint <?=$fid?></h2>
			<form name="frmMain" method="post"
				action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?fid=" . $fid); ?>">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="idpass" value="">
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
                    $tsql = "{CALL dbo.Q3_SelectItemsOfFingerprint(?)}";
                    $params = array(
                    	array($fid, SQLSRV_PARAM_IN)
                    );
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
						<td colspan="2" align="right">
							<div align="center">
								<input class="textbtn success" name="btnAdd" type="button" id="btnUpdate" value="Update"
									onclick="if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}">
								<input class="textbtn danger" name="btnAdd" type="button" id="btnCancel" value="Cancel"
									OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>?fid=<?= $fid; ?>';">
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
						<td align="center" width="11%">
							<input class="textbtn warning" name="btnEdit" type="button" id="btnEdit" value="Edit"
								OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>?fid=<?= $fid; ?>&Action=Edit&id=<?= $objResult["ItemID"]; ?>#row<?= $objResult["ItemID"]; ?>';">
						</td>
						<td align="center" width="11%">
							<input class="textbtn danger" name="btnDelete" type="button" id="btnChange" value="Delete"
								OnClick="if(confirm('Confirm Delete?')==true){frmMain.hdnCmd.value='Delete';frmMain.idpass.value='<?= $objResult["ItemID"] ?>';frmMain.submit();}">
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
	        $strSQL = "{call dbo.Q3_EditItem(?, ?, ?, ?, ?)}";
	        $params = array(
	        	array($_POST["hdnEditItemID"], SQLSRV_PARAM_IN),
	        	array($_POST["txtEditTypeID"], SQLSRV_PARAM_IN),
	        	array($_POST["txtEditHeight"], SQLSRV_PARAM_IN),
	        	array($_POST["txtEditWidth"], SQLSRV_PARAM_IN),
	        	array($fid, SQLSRV_PARAM_IN)
	        );
	        $objQuery = sqlsrv_query($conn, $strSQL, $params);
	        $objRow = sqlsrv_fetch_array($objQuery);
	        if (!$objQuery) {
		        echo "Error Update [" . sqlsrv_errors() . "]";
	        } else {
		        $url = $_SERVER['PHP_SELF'] . "?fid=" . $fid;
	        echo "<meta http-equiv='refresh' content='0; url=$url'>";
			}
        }

        //*** Delete Condition ***//  
        if ($_POST["hdnCmd"] == "Delete") {
	        $strSQL = "{call dbo.Q3_DeleteItem(?)}";
	        $params = array(
	        	array($_POST["idpass"], SQLSRV_PARAM_IN)
	        );
	        $objQuery = sqlsrv_query($conn, $strSQL, $params);
	        $objRow = sqlsrv_fetch_array($objQuery);
	        if (!$objQuery) {
		        echo "Error Delete [" . sqlsrv_errors() . "]";
	        } else{
		        $url = $_SERVER['PHP_SELF'] . "?fid=" . $fid;
	        echo "<meta http-equiv='refresh' content='0; url=$url'>";
			}
        }

        if ($_POST["hdnCmd"] == "Insert") {
	        $strSQL = "{call dbo.Q3_InsertItem(?, ?, ?, ?)}";
	        $params = array(
	        	array($_POST["TypeID"], SQLSRV_PARAM_IN),
	        	array($_POST["Height"], SQLSRV_PARAM_IN),
	        	array($_POST["Width"], SQLSRV_PARAM_IN),
	        	array($_POST["hdnfid"], SQLSRV_PARAM_IN)
	        );
	        $objQuery = sqlsrv_query($conn, $strSQL, $params);
	        $objRow = sqlsrv_fetch_array($objQuery);
	        if (!$objQuery) {
		        echo "Error Insert [" . sqlsrv_errors() . "]";
	        } else {
		        $url = $_SERVER['PHP_SELF'] . "?fid=" . $fid;
	        echo "<meta http-equiv='refresh' content='0; url=$url'>";
			}
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
			var typeid = f1.Height.value;
			var height = f1.Width.value;
			var width = f1.TypeID.value;

			if (typeid.length > 0 && height.length > 0 && width.length > 0) {
				return true;
			}
			var str = "";
			if (typeid.length == 0)
				str += "Type ID is empty\n";
			if (height.length == 0)
				str += "Height is empty\n";
			if (width.length == 0)
				str += "Width is empty\n";
			alert(str);
			return false;
		}
		function updateValidation() {
			var typeid = frmMain.txtEditHeight.value;
			var height = frmMain.txtEditWidth.value;
			var width = frmMain.txtEditTypeID.value;

			if (typeid.length > 0 && height.length > 0 && width.length > 0) {
				return true;
			}
			var str = "";
			if (typeid.length == 0)
				str += "Type ID is empty\n";
			if (height.length == 0)
				str += "Height is empty\n";
			if (width.length == 0)
				str += "Width is empty\n";
			alert(str);
			return false;
		}  
	</script>

</body>

</html>