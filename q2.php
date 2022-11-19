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
	<table cellSpacing=0 cellPadding=5 width="100%" border=0>
	<tr>
		<td vAlign=top width=170><img height=91 alt=UCY src="images/ucy.jpg" width=94>
			<h5>
				<a href="http://www.ucy.ac.cy/">University of Cyprus</a><BR/>
				<a href="http://www.cs.ucy.ac.cy/">Dept. of Computer Science</a>
			</h5>
		</td>
		<td vAlign=center align=middle><h2>Insert / Edit / Delete Types</h2></td>
	</tr>
    </table>
	<hr>


	<div>  
        <h1>Insert new type</h1>  
		
        <form name="f1" method = "POST">  
			<table>
				<tbody>
					<tr style = "background-color:transparent">
						<td>
							<label> Title: </label>  
						</td>
						<td>
							<input type = "text" name = "title" />  
						</td>
					</tr> 
					<tr style = "background-color:transparent">
						<td>
							<label> Model: </label>  
						</td>
						<td>
							<input maxlength="40" type = "text" name = "model" />  
						</td>
					</tr> 
					<tr>
						<td>
							<input maxlength="30" type =  "submit" id = "btn" value = "Insert" name = "insert" /> 
						</td>
					</tr> 
				</tbody>
			</table>
			<hr/>
        </form>  
    </div> 

	<div>
	<form name="frmMain" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">  
		<input type="hidden" name="hdnCmd" value="">  
		<table width="100%" border="1">  
		<tr>  
		<th width = "20%"> <div align="center">TypeID </div></th>  
		<th width = "20%"> <div align="center">Title</div></th>  
		<th width = "20%"> <div align="center">Model</div></th>  
		<th width = "40%" colspan = "2"> <div align="center">Actions</div></th>  
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
		<td><div align="center"><?=$objResult["TypeID"];?></div>
		<input type="hidden" name="hdnEditTypeID" value="<?=$objResult["TypeID"];?>">
		</td> 
		<td align="center"><input style="text-align:center; width:100%;" maxlength="40" type="text" name="txtEditTitle" value="<?=$objResult["Title"];?>"></td>  
		<td align="center"><input style="text-align:center; width:100%;" maxlength="30" type="text" name="txtEditModel" value="<?=$objResult["Model"];?>"></td>  
		<td colspan="2" align="right"><div align="center">  
		<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frmMain.hdnCmd.value='Update';frmMain.submit();">  
		<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">  
		</div></td>  
		</tr>  
		<?php 
		}  
		else  
		{  
		?>  
		<tr>  
		<td><div align="center"><?=$objResult["TypeID"];?></div></td>  
		<td align="center"><?=$objResult["Title"];?></td>  
		<td align="center"><?=$objResult["Model"];?></td>  
		<td align="center" width="20%"><a href="<?=$_SERVER["PHP_SELF"];?>?Action=Edit&id=<?=$objResult["TypeID"];?>">Edit</a></td>  
		<td align="center" width="20%"><a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?Action=Del&id=<?=$objResult["TypeID"];?>';}">Delete</a></td>  
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

	echo "Connecting to SQL server (" . $serverName . ")<br/>";
	echo "Database: " . $connectionOptions[Database] . ", SQL User: " . $connectionOptions[Uid] . "<br/>";

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
	
	<form method="post"> 
		<input type="submit" name="disconnect" value="Disconnect"/> 
		<input type="submit" value="Menu" formaction="connect.php">
	</form> 
</body>
</html>
