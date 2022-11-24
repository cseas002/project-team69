<?php
	session_start();
	// Get the DB connection info from the session
	if(isset($_SESSION["userID"]) && isset($_SESSION["connectionOptions"]) && isset($_SESSION["userID"]) && isset($_SESSION["userType"])) {
		$serverName = $_SESSION["serverName"];
		$connectionOptions = $_SESSION["connectionOptions"];
		$userID = $_SESSION["userID"];
		$userType = $_SESSION["userType"];

		if($userType == '2'){
			?>
			<script>
			alert("Simple users can't insert/modify/delete types.");
			</script>
			<?php
			die('<meta http-equiv="refresh" content="0; url=menu.php" />');

		}

	} else {
		session_unset();
		session_destroy();
		echo "Session is not correctly set! Clossing session and redirecting to start page in 3 seconds<br/>";
		die('<meta http-equiv="refresh" content="3; url=index.php" />');
	}
	//Establishes the connection
	$conn = sqlsrv_connect($serverName, $connectionOptions);
?>


<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "style.css">
	<style>
		table th{background:grey}
		table tr:nth-child(odd){background:LightYellow}
		table tr:nth-child(even){background:LightGray}
	</style>
</head>
<body>

<div class="sidenav">
		<a href="http://www.ucy.ac.cy/"><img width="160px" alt=UCY src="images/logo_en.png"></a>
		<h5>
			<a style="color: #C68F06;" href="http://www.cs.ucy.ac.cy/">Dept. of Computer Science</a>
		</h5>
		<a href="q1.php">Query 1</a>
		<a href="q2.php">Query 2</a>
		<a href="q3.php">Query 3</a>
		<a href="q4.php">Query 4</a>
		<div class="disconnectForm">
			<?php
        if (isset($_POST['disconnect'])) {
	        echo "Clossing session and redirecting to start page";
	        session_unset();
	        session_destroy();
	        die('<meta http-equiv="refresh" content="1; url=index.php" />');
        }
        ?>

			<form method="post">
				<input class="disconnectBtn" type="submit" value="Menu" formaction="menu.php"
					style="margin-top:20px;"><br /><br />
				<input class="disconnectBtn" type="submit" name="disconnect" value="Disconnect" /><br />
			</form>
		</div>
	</div>
	<div class="main">

	<table cellSpacing=0 cellPadding=5 width="100%" border=0>
	<tr>
		<td vAlign=center align=middle><h2>Insert / Edit / Delete Types</h2></td>
	</tr>
    </table>
	<hr>

	<button class="btnUpForm" onclick="document.getElementById('myForm').style.display = 'block';">Insert Type</button>

	<div class="form-popup" id="myForm" onkeypress="if(event.keyCode==13){if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}}">
        <form name="f1" method = "POST" class="form-container">
			<input type="hidden" name="hdnCmd" value="">
			<h2 style="text-align:center;">Insert new type</h2>
			<label> Title: </label>
			<input type = "text" name = "title" />
			<label> Model: </label>
			<input maxlength="40" type = "text" name = "model" />
			<input type ="button" class = "btn" value="Insert" onclick="if(insertValidation()){f1.hdnCmd.value='Insert';f1.submit();}" />
			<button type ="button" class = "btn cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">Cancel</button>
        </form>
    </div>

	<br/>
	<br/>
	<hr/>
	<div onkeypress="if(event.keyCode==13){if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}}">
	<h2>List of all types</h2>
	<form name="frmMain" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<input type="hidden" name="hdnCmd" value="">
		<table width="100%" border="1">
		<tr>
		<th width = "20%"> <div align="center">TypeID </div></th>
		<th width = "25%"> <div align="center">Title</div></th>
		<th width = "25%"> <div align="center">Model</div></th>
		<th width = "30%" colspan = "2"> <div align="center">Actions</div></th>
		</tr>
		<?php
		$tsql="EXEC dbo.Q2_Select";
		$objQuery = sqlsrv_query($conn, $tsql);

		while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
		{
		?>

		<?php
		if($objResult["TypeID"] == $_GET["id"] and $_GET["Action"] == "Edit")
		{
		?>
		<tr>
		<td><div id='row<?= $objResult["TypeID"]; ?>' align="center"><?=$objResult["TypeID"];?></div>
		<input type="hidden" name="hdnEditTypeID" value="<?=$objResult["TypeID"];?>">
		</td>
		<td align="center" style="height:40px;"><input style="text-align:center; width:100%; height: 100%;" maxlength="40" type="text" name="txtEditTitle" value="<?=$objResult["Title"];?>"></td>
		<td align="center" style="height:40px;"><input style="text-align:center; width:100%; height: 100%;" maxlength="30" type="text" name="txtEditModel" value="<?=$objResult["Model"];?>"></td>
		<td colspan="2" align="right"><div align="center">
		<input class="textbtn success" name="btnAdd" type="button" id="btnUpdate" value="Update" onclick="if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}">
		<input class="textbtn danger" name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">
		</div></td>
		</tr>
		<?php
		}
		else
		{
		?>
		<tr>
		<td><div id='row<?= $objResult["TypeID"]; ?>' align="center"><?=$objResult["TypeID"];?></div></td>
		<td align="center"><?=$objResult["Title"];?></td>
		<td align="center"><?=$objResult["Model"];?></td>
		<td align="center" width="15%">
			<input class="textbtn warning" name="btnEdit" type="button" id="btnEdit" value="Edit" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>?Action=Edit&id=<?= $objResult["TypeID"]; ?>#row<?= $objResult["TypeID"]; ?>';">
		</td>
		<td align="center" width="15%">
		<input class="textbtn danger" name="btnDelete" type="button" id="btnChange" value="Delete"
								OnClick="if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?Action=Del&id=<?=$objResult["TypeID"];?>';}"></td>
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
	if($_POST["hdnCmd"] == "Update")
	{
	$strSQL = "{call dbo.Q2_Update(?, ?, ?, ?)}";
	$params = array(
		array($_POST["txtEditTitle"], SQLSRV_PARAM_IN),
		array($_POST["txtEditModel"], SQLSRV_PARAM_IN),
		array($_POST["hdnEditTypeID"], SQLSRV_PARAM_IN)
	   );
	$objQuery = sqlsrv_query($conn, $strSQL, $params);
		$objRow = sqlsrv_fetch_array($objQuery);
		if (!$objQuery)
		{
			echo "Error Update [" . sqlsrv_errors() . "]";
		} else echo "<meta http-equiv='refresh' content='0'>";
	}

	//*** Delete Condition ***//
	if($_GET["Action"] == "Del")
	{
		$strSQL = "{call dbo.Q2_Delete(?, ?)}";
	$params = array(
		array($_GET["id"], SQLSRV_PARAM_IN)
	   );
	$objQuery = sqlsrv_query($conn, $strSQL, $params);
		$objRow = sqlsrv_fetch_array($objQuery);
		if (!$objQuery)
		{
			echo "Error Delete [" . sqlsrv_errors() . "]";
		}
		else echo "<meta http-equiv='refresh' content='0;url=q2.php'>";
	}

	if ($_POST["hdnCmd"] == "Insert") {
		$strSQL = "{call dbo.Q2_Insert(?, ?)}";
		$params = array(
			array($_POST["title"], SQLSRV_PARAM_IN),
			array($_POST["model"], SQLSRV_PARAM_IN)
		);
		$objQuery = sqlsrv_query($conn, $strSQL, $params);
		$objRow = sqlsrv_fetch_array($objQuery);
		if (!$objQuery)
		{
			echo "Error Insert [" . sqlsrv_errors() . "]";
		}
		else echo "<meta http-equiv='refresh' content='0'>";
	}

	// $time_end = microtime(true);

	$userTypes=array("System Admin", "Functions Admin", "Simple User");
	echo "Connecting to SQL server (" . $serverName . ")<br/>";
	echo "Database: " . $connectionOptions[Database] . ", SQL User: " . $connectionOptions[Uid] . "<br/>";
	echo "User: " . $_SESSION["userID"] . ", UserType: " . $userTypes[$_SESSION["userType"]] . "<br/>";

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
			die('<meta http-equiv="refresh" content="1; url=index.php" />');
		}
	?>
	</div>

	<script>
		function insertValidation()  {
			var title=f1.title.value;
			var model=f1.model.value;

			if(title.length>0 && model.length>0){
				return true;
			}
			var str="";
			if(title.length==0)
				str+="Title is empty\n";
			if(model.length==0)
				str+="Model is empty\n";
			alert(str);
			return false;
		}
		function updateValidation()  {
			var title=frmMain.txtEditTitle.value;
			var model=frmMain.txtEditModel.value;

			if(title.length>0 && model.length>0){
				return true;
			}
			var str="";
			if(title.length==0)
				str+="Title is empty\n";
			if(model.length==0)
				str+="Model is empty\n";
			alert(str);
			return false;
		}
	</script>

</body>
</html>
