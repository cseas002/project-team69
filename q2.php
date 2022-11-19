<?php 
	session_start(); 
	// Get the DB connection info from the session
	if(isset($_SESSION["serverName"]) && isset($_SESSION["connectionOptions"])) { 
		$serverName = $_SESSION["serverName"];
		$connectionOptions = $_SESSION["connectionOptions"];
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
		<td vAlign=center align=middle><h2>Insert / Add / Delete</h2></td>
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
							<input type = "text" name = "model" />  
						</td>
					</tr> 
					<tr>
						<td>
							<input type =  "submit" id = "btn" value = "Insert" name = "insert" /> 
						</td>
					</tr> 
				</tbody>
			</table>
			<hr/>
        </form>  
    </div> 

	<div>
	<form name="frmMain" method="post" action="<?=$_SERVER["PHP_SELF"];?>">  
		<input type="hidden" name="hdnCmd" value="">  
		<table width="100%" border="1">  
		<tr>  
		<th width = "20%"> <div align="center">TypeID </div></th>  
		<th width = "20%"> <div align="center">Title</div></th>  
		<th width = "20%"> <div align="center">Model</div></th>  
		<th width = "40%" colspan = "2"> <div align="center">Actions</div></th>  
		</tr>  
		<?php 
		$tsql="SELECT * FROM TYPES ORDER BY TypeID ASC;";
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
		<td><input type="text" name="txtEditTitle" value="<?=$objResult["Title"];?>"></td>  
		<td><input type="text" name="txtEditModel" value="<?=$objResult["Model"];?>"></td>  
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
		<td><?=$objResult["Title"];?></td>  
		<td><?=$objResult["Model"];?></td>  
		<td align="center"><a href="<?=$_SERVER["PHP_SELF"];?>?Action=Edit&id=<?=$objResult["TypeID"];?>">Edit</a></td>  
		<td align="center"><a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?Action=Del&id=<?=$objResult["TypeID"];?>';}">Delete</a></td>  
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
	$strSQL = "UPDATE dbo.TYPES SET Title = '" . $_POST["txtEditTitle"] . "', Model = '" . $_POST["txtEditModel"] . "' ";  
	$strSQL .="WHERE TypeID = '" . $_POST["hdnEditTypeID"] . "' ";  
	$objQuery = sqlsrv_query($conn, $strSQL);
	if(!$objQuery)  
	{  
		echo "Error Update [" . sqlsrv_errors() . "]"; 
	}  
	else{
		echo "<meta http-equiv='refresh' content='0'>";
	}
	}  
	
	//*** Delete Condition ***//  
	if($_GET["Action"] == "Del")  
	{  
	$strSQL = "DELETE FROM dbo.TYPES WHERE TypeID = '" . $_GET["id"] . "' ";  
	$objQuery = sqlsrv_query($conn, $strSQL);  
	if(!$objQuery)  
	{  
		echo "Error Delete [" . sqlsrv_errors() . "]"; 
	} 
		else{
			echo "<meta http-equiv='refresh' content='0;url=q2.php'>";
			// header("Location: https://www.cs.ucy.ac.cy/~cseas002/q2.php");
		} 
	}

	if ($_POST["insert"] == "Insert") {
		$tsql2 = "INSERT INTO dbo.TYPES (Title, Model) VALUES ('" . $_POST["title"] . "', '" . $_POST["model"] . "');";
		$objQuery = sqlsrv_query($conn, $tsql2);
		if (!$objQuery)
		{
			echo "Error Insert [" . sqlsrv_errors() . "]";
		}
		echo "<meta http-equiv='refresh' content='0'>";
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
