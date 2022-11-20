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
			<meta http-equiv="refresh" content="0; url=menu.php" />
			<?php
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
				<input class="disconnectBtn" type="submit" value="Menu" formaction="connect.php"
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


	<div>  
        <div style="text-align:center; background-color: #cccccc; margin-right:65%; min-width: fit-content;">
		<br/>
		<h2>Insert new type</h2>
		
        <form name="f1" method = "POST">  
			<table style="text-align:left; padding-left:30px; padding-right: 30px;" cellspacing="0" cellpadding="0" align="center">
				<tbody>
					<tr style = "background-color:transparent">
						<td  style="width:110px;">
							<label> Title: </label>  
						</td>
						<td>
							<input type = "text" name = "title" />  
						</td>
					</tr> 
					<tr style = "background-color:transparent">
						<td>
						<br/>
							<label> Model: </label>  
						</td>
						<td>
							<br/>
							<input maxlength="40" type = "text" name = "model" />  
						</td>
					</tr> 
				</tbody>
			</table>
			<br/>
			<table style="text-align:center" cellspacing="0" cellpadding="0" align="center">
				<tbody>
				<tr>
						<td>
							<input maxlength="30" type =  "submit" class = "btnUpForm" value = "Insert" name = "insert" /> 
						</td>
					</tr> 
	</tbody>

	</table>
	<br/>
	<br/>
	</div>
			<hr/>
        </form>  
    </div> 

	<div>
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
		<input class="textbtn success" name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frmMain.hdnCmd.value='Update';frmMain.submit();">  
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
		array($_POST["hdnEditTypeID"], SQLSRV_PARAM_IN),
		array($_SESSION["userType"], SQLSRV_PARAM_IN)
	   ); 
	$objQuery = sqlsrv_query($conn, $strSQL, $params);
		$objRow = sqlsrv_fetch_array($objQuery);
		if (!$objQuery)
		{
			echo "Error Update [" . sqlsrv_errors() . "]";
		}
		else if($objRow[0]=='0'){ //Simple user
			?>
			<script>
				alert("Simple users cannot update types.");
			</script>
			<?php
		}
		else echo "<meta http-equiv='refresh' content='0'>";
	}  
	
	//*** Delete Condition ***//  
	if($_GET["Action"] == "Del")  
	{  
		$strSQL = "{call dbo.Q2_Delete(?, ?)}";  
	$params = array(  
		array($_GET["id"], SQLSRV_PARAM_IN),
		array($_SESSION["userType"], SQLSRV_PARAM_IN)
	   ); 
	$objQuery = sqlsrv_query($conn, $strSQL, $params);
		$objRow = sqlsrv_fetch_array($objQuery);
		if (!$objQuery)
		{
			echo "Error Delete [" . sqlsrv_errors() . "]";
		}
		else if($objRow[0]=='0'){ //Simple user
			?>
			<script>
				alert("Simple users cannot delete types.");
			</script>
			<?php
		}
		echo "<meta http-equiv='refresh' content='0;url=q2.php'>";
	}

	if ($_POST["insert"] == "Insert") {
		$strSQL = "{call dbo.Q2_Insert(?, ?, ?)}";  
		$params = array(  
			array($_POST["title"], SQLSRV_PARAM_IN),
			array($_POST["model"], SQLSRV_PARAM_IN),
			array($_SESSION["userType"], SQLSRV_PARAM_IN)
		); 
		$objQuery = sqlsrv_query($conn, $strSQL, $params);
		$objRow = sqlsrv_fetch_array($objQuery);
		if (!$objQuery)
		{
			echo "Error Insert [" . sqlsrv_errors() . "]";
		}
		else if($objRow[0]=='0'){ //Simple user
			?>
			<script>
				alert("Simple users cannot insert new types.");
			</script>
			<?php
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
</body>
</html>
