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
				<h2>Insert / Edit / Delete Campuses</h2>
			</td>
		</tr>
	</table>
	<hr>

	<button class="button-20" onclick="document.getElementById('myForm').style.display = 'block';">Insert
		Building</button>
	<button class="button-20" onclick="document.getElementById('myForm1').style.display = 'block';">Advanced
		Search</button>
	<button class="button-20" onclick="document.getElementById('myForm2').style.display = 'block';">Simple
		Search</button>

	<div class="form-popup" id="myForm"
		onkeypress="if(event.keyCode==13){if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}}">
		<form name="f1" method="POST" class="form-container">
			<input type="hidden" name="hdnCmd" value="">
			<h2 style="text-align:center;">Insert new campus</h2>
			<label> Name: </label>
			<input type="text" name="Name" />
			<label> Summary: </label>
			<input type="text" name="Summary" />
			<label> Website: </label>
			<input type="text" name="Website" />
			<label> RegDate: </label>
				<input type="date" name="RegDate" />
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
				<label> Campus ID: </label>
				<input type="text" name="CampusID1" value="<?=$_POST['CampusID1']?>"/>
				<label> CampusName: </label>
				<input type="text" name="CampusName1" value="<?=$_POST['CampusName1']?>"/>
				<label> Summary: </label>
				<input type="text" name="Summary1" value="<?=$_POST['Summary1']?>" />
				<label> RegDate: </label>
				<input type="date" name="RegDate1"/>
				<label> Website: </label>
				<input type="text" name="Website1" value="<?=$_POST['Website1']?>"/>
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
					onclick="formS.hdnCmd2.value='Search';formS.submit();" />
				<button type="button" class="btn cancel"
					OnClick="document.getElementById('myForm2').style.display = 'none';">Cancel</button>
			</form>
		</div>
	<hr />
	<div
		onkeypress="if(event.keyCode==13){if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}}">
		<h2>List of all buildings</h2>
		<form name="frmMain" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
			<input type="hidden" name="hdnCmd" value="">
			<input type="hidden" name="fidpass" value="">
			<table width="100%" border="1">
				<tr>
					<th width="8%">
						<div align="center">Campus ID </div>
					</th>
					<th width="9%">
						<div align="center">Name</div>
					</th>
					<th width="9%">
						<div align="center">Summary</div>
					</th>
					<th width="9%">
						<div align="center">Website</div>
					</th>
					<th width="9%">
							<div align="center">RegDate</div>
						</th>
					<th width="20%" colspan="3">
						<div align="center">Actions</div>
					</th>
				</tr>
				<?php
				if ($_POST["hdnCmd1"] == 'AdvSearch'){ // Advanced Search
					$timeNew = '';
					if ($_POST["RegDate1"] != '')
		                $timeNew = $_POST["RegDate1"] . "00";

					$tsql = "{CALL dbo.Advanced_Search_CAMPUS(?, ?, ?, ?, ?)}";
					$params = array(
						array($_POST['CampusID1'], SQLSRV_PARAM_IN),
						array($_POST['CampusName1'], SQLSRV_PARAM_IN),
						array($_POST['Summary1'], SQLSRV_PARAM_IN),
						array($timeNew, SQLSRV_PARAM_IN),
						array($_POST['Website1'], SQLSRV_PARAM_IN)
					);
					$objQuery = sqlsrv_query($conn, $tsql, $params);
				}
				else if ($_POST["hdnCmd2"] == 'Search')
				{
					$tsql = "{CALL dbo.Search_CAMPUS(?)}";
					$params = array(
						array($_POST['keyword'], SQLSRV_PARAM_IN)
					);
					$objQuery = sqlsrv_query($conn, $tsql, $params);
				}
				else {
					$tsql = "{CALL dbo.Q5_SelectCampus()}";
					$objQuery = sqlsrv_query($conn, $tsql);
				}

	while ($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC)) {
	?>

				<?php
		if ($objResult["CampusID"] == $_GET["id"] and $_GET["Action"] == "Edit") {
	?>
				<tr>
					<td>
						<div id='row<?= $objResult["CampusID"]; ?>' align="center">
							<?= $objResult["CampusID"]; ?>
						</div>
						<input type="hidden" name="hdnEditCampusID" value="<?= $objResult["CampusID"]; ?>">
					</td>
					<td align="center" style="height:40px;"><input
							style="text-align:center; width:100%; height: 100%;" maxlength="30" type="text"
							name="textEditName" value="<?= $objResult["CampusName"]; ?>"></td>
					<td align="center" style="height:40px;"><input
							style="text-align:center; width:100%; height: 100%;" maxlength="30" type="text"
							name="textEditSummary" value="<?= $objResult["Summary"]; ?>"></td>
					<td align="center" style="height:40px;"><input
							style="text-align:center; width:100%; height: 100%;" maxlength="30" type="text"
							name="textEditWebsite" value="<?= $objResult["Website"]; ?>"></td>
					<td align="center" style="height:40px;"><input
								style="text-align:center; width:100%; height: 100%;" maxlength="30" type="date"
								name="textEditRegDate" value="<?= $objResult["RegDate"]; ?>"></td>
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
						<div id='row<?= $objResult["CampusID"]; ?>' align="center">
							<?= $objResult["CampusID"]; ?>
						</div>
					</td>
					<td align="center">
						<?= $objResult["CampusName"]; ?>
					</td>
					<td align="center">
						<?= $objResult["Summary"]; ?>
					</td>
					<td align="center">
						<?= $objResult["Website"]; ?>
					</td>
					<td align="center">
						<?= $objResult["RegDate"]; ?>
					</td>
					<td align="center" width="8%">
						<input class="textbtn warning" name="btnEditItems" type="button" id="btnEditItems"
							value="Edit Buildings"
							OnClick="window.location='editbuildings.php?cid=<?= $objResult["CampusID"]; ?>';">
					</td>
					<td align="center" width="6%">
						<input class="textbtn warning" name="btnEdit" type="button" id="btnEdit" value="Edit"
							OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>?Action=Edit&id=<?= $objResult["CampusID"]; ?>#row<?= $objResult["CampusID"]; ?>';">
					</td>
					<td align="center" width="6%">
						<input class="textbtn danger" name="btnDelete" type="button" id="btnChange" value="Delete"
							OnClick="if(confirm('Confirm Delete?')==true){frmMain.hdnCmd.value='Delete';frmMain.fidpass.value='<?= $objResult["CampusID"] ?>';frmMain.submit();}">
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
	$strSQL = "{call dbo.Q5_EditCampus(?, ?, ?, ?, ?)}";

	$params = array(
		array($_POST["hdnEditCampusID"], SQLSRV_PARAM_IN),
		array($_POST["textEditName"], SQLSRV_PARAM_IN),
		array($_POST["textEditSummary"], SQLSRV_PARAM_IN),
		array($_POST["textEditWebsite"], SQLSRV_PARAM_IN),
		array($_POST["textEditRegDate"], SQLSRV_PARAM_IN)
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
	$strSQL = "{call dbo.Q4_DeleteBuilding(?)}";
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
	$strSQL = "{call dbo.Q5_InsertCampus(?, ?, ?, ?)}";
	
	
	$params = array(
		array($_POST["Name"], SQLSRV_PARAM_IN),
		array($_POST["Summary"], SQLSRV_PARAM_IN),
		array($_POST["Website"], SQLSRV_PARAM_IN),
		array($_POST["RegDate"], SQLSRV_PARAM_IN)
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
		var Summary = f1.Name.value;
		var baddress = f1.Website.value;
		var summary = f1.Summary.value;
		var RegDate = f1.RegDate.value;

		if (Summary.length > 0 && baddress.length > 0 && summary.length > 0 && RegDate.length>0) {
			return true;
		}
		var str = "";
		if (Summary.length == 0)
			str += "Campus Name is empty\n";
		if (baddress.length == 0)
			str += "Website is empty\n";
		if (summary.length == 0)
			str += "Summary is empty\n";
		if (RegDate.length == 0)
			str += "RegDate is empty\n";
		alert(str);
		return false;
	}
	function updateValidation() {
		var name = frmMain.textEditName.value;
		var website = frmMain.textEditWebsite.value;
		var summary = frmMain.textEditSummary.value;
		var RegDate = frmMain.textEditRegDate.value;

		if (name.length > 0 && website.length > 0  && summary.length > 0  && RegDate.length>0) {
			return true;
		}
		var str = "";
		if (name.length == 0)
			str += "Campus Name is empty\n";
		if (website.length == 0)
			str += "Website is empty\n";
		if (summary.length == 0)
			str += "Summary is empty\n";
		if (RegDate.length == 0)
			str += "RegDate is empty\n";
		alert(str);
		return false;
	}  
</script>

</body>

</html>