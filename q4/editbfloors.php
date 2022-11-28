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
		<a href="../q4/editbfloors.php?fid=<?=$fid?>"> - Edit Floors</a>
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
					<h2>Insert / Edit / Delete Items from Building <?= $fid ?>
					</h2>
				</td>
			</tr>
		</table>
		<hr>

		<button class="btnUpForm" onclick="document.getElementById('myForm').style.display = 'block';">Insert
			Floor</button>

		<div class="form-popup" id="myForm"
			onkeypress="if(event.keyCode==13){if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}}">
			<form name="f1" method="POST" class="form-container" enctype="multipart/form-data">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="hdnfid" value="<?= $_GET["fid"] ?>">
				<h2 style="text-align:center;">Insert new floor</h2>
				<label> FloorZ: </label>
				<input type="text" name="FloorZ" />
				<div style="display:inline">
					<div style="float: left; width: 50%;">
						<br/>
						<label> Topo Plan: </label>
					</div>
					<div style="float: left; width: 25%;">
						<label style="width: 80%;padding:10px;margin: 5px 0 12px 0;border: none;background: #f1f1f1;"
							for="upload-photo1" class="textbtn blue">Upload Photo</label>
						<input style="opacity: 0; position: absolute; z-index: -1;" type="file" name="imageInsert"
							id="upload-photo1" />
					</div>
					<div style="float: left; width: 25%;">
						<label style="width: 80%;padding:10px;margin: 5px 0 12px 0;border: none;background: #f1f1f1;"
							for="upload-base641" class="textbtn blue">Upload Base64</label>
						<input style="opacity: 0; position: absolute; z-index: -1;" type="file" name="base64Insert"
							id="upload-base641" />
					</div>
				</div>


				<label> Summary: </label>
				<input type="text" name="Summary" />
				<input type="button" class="btn" value="Insert"
					onclick="if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}" />
				<button type="button" class="btn cancel"
					OnClick="document.getElementById('myForm').style.display = 'none';">Cancel</button>
			</form>
		</div>
		<hr />
		<div
			onkeypress="if(event.keyCode==13){if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}}">
			<h2>List of all floors in building <?= $fid ?>
			</h2>
			<form name="frmMain" method="post" enctype="multipart/form-data"
				action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?fid=" . $fid); ?>">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="idpass" value="">
				<table width="100%" border="1">
					<tr>
						<th width="20%">
							<div align="center">Floor Z </div>
						</th>
						<th width="20%">
							<div align="center">Summary</div>
						</th>
						<th width="20%">
							<div align="center">Topo Plan</div>
						</th>
						<th width="40%" colspan="4">
							<div align="center">Actions</div>
						</th>
					</tr>
					<?php
                    $tsql = "{CALL dbo.Q4_SelectFloorOfBuilding(?)}";
                    $params = array(
                    	array($fid, SQLSRV_PARAM_IN)
                    );
                    $objQuery = sqlsrv_query($conn, $tsql, $params);

                    while ($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC)) {
                    ?>

					<?php
	                    if ($objResult["FloorZ"] == $_GET["id"] and $_GET["Action"] == "Edit") {
                    ?>
					<tr>
						<td align="center" style="height:40px;">
							<div id='row<?= $objResult["FloorZ"]; ?>' align="center">
							</div>
							<input style="text-align:center; width:100%; height: 100%;" maxlength="30" type="text"
								name="txtEditFloorZ" value="<?= $objResult["FloorZ"]; ?>">
						</td>
						<td align="center" style="height:40px;">
							<input style="text-align:center; width:100%; height: 100%;" maxlength="40" type="text"
								name="txtEditSummary" value="<?= $objResult["Summary"]; ?>">
						</td>
						<td align="center" style="height:40px;">
							<label for="upload-photo" class="textbtn blue">
								Upload Photo
							</label>
							<input style="opacity: 0; position: absolute; z-index: -1;" type="file" name="image"
								id="upload-photo">
							<label for="upload-base64" class="textbtn blue">
								Upload Base64
							</label>
							<input style="opacity: 0; position: absolute; z-index: -1;" type="file" name="base64"
								id="upload-base64">
				<input type="hidden" name="hdnimage" value="<?= $objResult["TopoPlan"] ?>">
						</td>
						<td colspan="4" align="right">
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
							<div id='row<?= $objResult["FloorZ"]; ?>' align="center">
								<?= $objResult["FloorZ"]; ?>
							</div>
						</td>
						<td align="center">
							<?= $objResult["Summary"]; ?>
						</td>
						<td align="center">
							<?php
								$base64code = $objResult["TopoPlan"];
								$substr = strtoupper(substr($base64code, 0, 5));
								$fileext = "";
								if($substr == "IVBOR")
									$fileext = "png";
								else if ($substr == "/9J/4")
									$fileext = "jpg";
								$base64file = "data:image/" . $fileext . ";base64," .	$base64code;
							?>
							<img src='<?= $base64file; ?>' height="100px" />
						</td>
						<td align="center" width="10%">
							<input class="textbtn warning" name="btnEditPOIS" type="button" id="btnEditPOIS" value="Edit POIs"
								OnClick="window.location='editpois.php?fid=<?= $fid; ?>&zid=<?= $objResult["FloorZ"]; ?>';">
						</td>
						<td align="center" width="10%">
							<input class="textbtn warning" name="btnEditFingerprints" type="button" id="btnEditFingerprint" value="Edit Fingerprints"
								OnClick="window.location='editfingerprints.php?fid=<?= $fid; ?>&zid=<?= $objResult["FloorZ"]; ?>';">
						</td>
						<td align="center" width="10%">
							<input class="textbtn warning" name="btnEdit" type="button" id="btnEdit" value="Edit"
								OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>?fid=<?= $fid; ?>&Action=Edit&id=<?= $objResult["FloorZ"]; ?>#row<?= $objResult["FloorZ"]; ?>';">
						</td>
						<td align="center" width="10%">
							<input class="textbtn danger" name="btnDelete" type="button" id="btnChange" value="Delete"
								OnClick="if(confirm('Confirm Delete?')==true){frmMain.hdnCmd.value='Delete';frmMain.idpass.value='<?= $objResult["FloorZ"] ?>';frmMain.submit();}">
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
			
	        $errors = array();
	        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
	        $file_name = $_FILES['image']['name'];
	        $file_ext = strtolower(end(explode('.', $file_name)));
	        $file_size = $_FILES['image']['size'];
	        $file_tmp = $_FILES['image']['tmp_name'];
	        echo $file_tmp;
	        echo "<br>";
	        $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
	        $data = file_get_contents($file_tmp);
	        $base64_1 =  base64_encode($data);
			//'data:image/' . $file_ext . ';base64,' .
	        if (in_array($file_ext, $allowed_ext) === false) {
		        $errors[] = 'Extension not allowed ' . $file_ext;
	        }
	        if ($file_size > 2097152/2) {
		        $errors[] = 'File size must be under 1mb';
	        }
	        if (empty($errors)) {
		        if (move_uploaded_file($file_tmp, 'images/' . $file_name))
			        ; {
			        echo 'File uploaded';
		        }
	        } else {
		        foreach ($errors as $error) {
			        echo $error, '<br/>';
		        }
	        }
			$file_name1 = $_FILES['base64']['name'];
	        $file_ext1 = strtolower(end(explode('.', $file_name1)));
	        $file_size1 = $_FILES['base64']['size'];
	        $file_tmp1 = $_FILES['base64']['tmp_name'];
	        echo $file_tmp1;
	        echo "<br>";
	        $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
	        $data1 = file_get_contents($file_tmp1);
						
			$base64 = "";
			if($data != ""){
				$base64 = $base64_1 ;
			}
			else if($data1 != ""){
				$base64 = $data1;
			}
			else{
				$base64 = $_POST["hdnimage"];
			}

	        $strSQL = "{call dbo.Q4_UpdateFloor(?, ?, ?, ?)}";
	        $params = array(
	        	array($fid, SQLSRV_PARAM_IN),
	        	array($_POST["txtEditFloorZ"], SQLSRV_PARAM_IN),
	        	array($base64, SQLSRV_PARAM_IN),
	        	array($_POST["txtEditSummary"], SQLSRV_PARAM_IN)
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
	        $strSQL = "{call dbo.Q4_DeleteFloor(?, ?)}";
	        $params = array(
	        	array($fid, SQLSRV_PARAM_IN),
	        	array($_POST["idpass"], SQLSRV_PARAM_IN),
	        );
	        $objQuery = sqlsrv_query($conn, $strSQL, $params);
	        $objRow = sqlsrv_fetch_array($objQuery);
	        if (!$objQuery) {
		        echo "Error Delete [" . sqlsrv_errors() . "]";
	        } else {
		        $url = $_SERVER['PHP_SELF'] . "?fid=" . $fid;
		        echo "<meta http-equiv='refresh' content='0; url=$url'>";
	        }
        }

        if ($_POST["hdnCmd"] == "Insert") {
			$errors = array();
	        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
	        $file_name = $_FILES['imageInsert']['name'];
	        $file_ext = strtolower(end(explode('.', $file_name)));
	        $file_size = $_FILES['imageInsert']['size'];
	        $file_tmp = $_FILES['imageInsert']['tmp_name'];
	        echo $file_tmp;
	        echo "<br>";
	        $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
	        $data = file_get_contents($file_tmp);
	        $base64_1 =  base64_encode($data);
			//'data:image/' . $file_ext . ';base64,' .
	        if (in_array($file_ext, $allowed_ext) === false) {
		        $errors[] = 'Extension not allowed ' . $file_ext;
	        }
	        if ($file_size > 2097152/2) {
		        $errors[] = 'File size must be under 1mb';
	        }
	        if (empty($errors)) {
		        if (move_uploaded_file($file_tmp, 'images/' . $file_name))
			        ; {
			        echo 'File uploaded';
		        }
	        } else {
		        foreach ($errors as $error) {
			        echo $error, '<br/>';
		        }
	        }
			$file_name1 = $_FILES['base64Insert']['name'];
	        $file_ext1 = strtolower(end(explode('.', $file_name1)));
	        $file_size1 = $_FILES['base64Insert']['size'];
	        $file_tmp1 = $_FILES['base64Insert']['tmp_name'];
	        echo $file_tmp1;
	        echo "<br>";
	        $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
	        $data1 = file_get_contents($file_tmp1);
						
			$base64 = NULL;
			if($data != ""){
				$base64 = $base64_1 ;
			}
			else if($data1 != ""){
				$base64 = $data1;
			}
			echo $base64_1;
			echo $data1;

	        $strSQL = "{call dbo.Q4_InsertFloor(?, ?, ?, ?)}";
	        $params = array(
	        	array($fid, SQLSRV_PARAM_IN),
	        	array($_POST["FloorZ"], SQLSRV_PARAM_IN),
	        	array($base64, SQLSRV_PARAM_IN),
	        	array($_POST["Summary"], SQLSRV_PARAM_IN)
	        );
	        $objQuery = sqlsrv_query($conn, $strSQL, $params);
	        $objRow = sqlsrv_fetch_array($objQuery);
	        if (!$objQuery) {
		        echo "Error Insert [";
			print_r(sqlsrv_errors());
			echo "]<br/>";
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
		function openInNewTab(a) {
			var win = window.open();
			win.document.write('<iframe src="' + a + '" frameborder="0" style="border:0; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%;" allowfullscreen></iframe>');
		}
		function insertValidation() {
			var summary = f1.Summary.value;

			if (summary.length > 0) {
				return true;
			}
			var str = "";
			if (summary.length == 0)
				str += "Summary is empty\n";
			alert(str);
			return false;
		}
		function updateValidation() {
			var summary = frmMain.txtEditSummary.value;

			if (summary.length > 0) {
				return true;
			}
			var str = "";
			
			if (summary.length == 0)
				str += "Summary is empty\n";
			alert(str);
			return false;
		}  
	</script>

</body>

</html>