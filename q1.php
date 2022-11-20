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
	<link rel="stylesheet" type="text/css" href="style.css">
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
	<meta charset="UTF-8" />
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

				<td vAlign=center align=middle>
					<h2>Insert / Edit Users</h2>
				</td>
			</tr>
		</table>
		<hr>
		<form name="frmUp" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
			<input type="hidden" name="hdnCmd1" value="">
			<table style="width:100%; background-color:transparent;" cellspacing="0" cellpadding="0">
				<tbody>
					<tr style="width:100%; background-color:transparent">
						<td style="background-color: #cccccc;text-align:center;width:35%;">
							<br />
							<h3 style="font-family: Arial, Helvetica, sans-serif;">Insert new user</h3>
							<table cellspacing="0" cellpadding="0" align="center">
								<tbody>
									<tr style="background-color:transparent">
										<td style="width:130px;">
											<label> Username: </label>
										</td>
										<td>
											<input maxlength="40" type="text" name="Username" />
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> User Type: </label>
										</td>
										<td>
											<select name="UserType" id="UserType">
												<option value=""> </option>
												<option value="0">Διαχειριστής Συστήματος</option>
												<option value="1">Διαχειριστής Λειτουργιών</option>
												<option value="2">Απλός χρήστης</option>
											</select>
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> Password: </label>
										</td>
										<td>
											<input maxlength="30" type="password" name="Password" />
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> First Name: </label>
										</td>
										<td>
											<input maxlength="30" type="text" name="FName" />
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> Last Name: </label>
										</td>
										<td>
											<input maxlength="30" type="text" name="LName" />
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> Date of Birth: </label>
										</td>
										<td>
											<input type="date" name="Date_of_Birth" />
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> Gender: </label>
										</td>
										<td>
											<select name="Gender" id="Gender">
												<option value=""> </option>
												<option value="M">Male</option>
												<option value="F">Female</option>
												<option value="O">Other</option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
							<table cellspacing="0" cellpadding="0" align="center">
								<tbody>
									<tr style="background-color:transparent">
										<td>
											<br />
											<input name="btnInsert" type="button" class="btnUpForm" value="Insert"
												OnClick="frmUp.hdnCmd1.value='insert';frmUp.submit();">
										</td>
										<td style="padding-left:30px;">
											<br />
											<a href="<?= $_SERVER["PHP_SELF"]; ?>">Reset</a>
										</td>
									</tr>
								</tbody>
							</table>
							<br />
							<br />
						</td>
						<td style="width:30px; text-align:center; padding-left:5%">

							<h3>Advanced Search</h3>
							<table cellspacing="0" cellpadding="0" align="center">
								<tbody>
									<tr style="background-color:transparent">
										<td style="width:130px;">
											<label> User ID: </label>
										</td>
										<td>
											<input maxlength="40" type="text" name="UserID2" />
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> Username: </label>
										</td>
										<td>
											<input maxlength="40" type="text" name="Username2" />
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> User Type: </label>
										</td>
										<td>
											<select name="UserType2" id="UserType2">
												<option value=""> </option>
												<option value="0">Διαχειριστής Συστήματος</option>
												<option value="1">Διαχειριστής Λειτουργιών</option>
												<option value="2">Απλός χρήστης</option>
											</select>
										</td>
									</tr>

									<tr style="background-color:transparent">
										<td>
											<label> First Name: </label>
										</td>
										<td>
											<input maxlength="30" type="text" name="FName2" />
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> Last Name: </label>
										</td>
										<td>
											<input maxlength="30" type="text" name="LName2" />
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> Date of Birth: </label>
										</td>
										<td>
											<input type="date" name="Date_of_Birth2" />
										</td>
									</tr>
									<tr style="background-color:transparent">
										<td>
											<label> Gender: </label>
										</td>
										<td>
											<select name="Gender2" id="Gender2">
												<option value=""> </option>
												<option value="M">Male</option>
												<option value="F">Female</option>
												<option value="O">Other</option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
							<table cellspacing="0" cellpadding="0" align="center">
								<tbody>
									<tr style="background-color:transparent">
										<td>
											<br />
											<input name="btnAdvSearch" type="button" class="btnUpForm" value="Search"
												OnClick="frmUp.hdnCmd1.value='advSearch';frmUp.submit();">
										</td>
										<td style="padding-left:30px;">
											<br />
											<a href="<?= $_SERVER["PHP_SELF"]; ?>">Reset</a>
										</td>
									</tr>
								</tbody>
							</table>



						</td>
						<td style="width:30%; text-align:center;">

							<h3>Search</h3>
							<table cellspacing="0" cellpadding="0" align="center">
								<tbody>
									<tr style="background-color:transparent">
										<td style="width:100px;">
											<label> Keyword: </label>
										</td>
										<td>
											<input maxlength="40" type="text" name="keyword" />
										</td>
									</tr>
								</tbody>
							</table>
							<table cellspacing="0" cellpadding="0" align="center">
								<tbody>
									<tr style="background-color:transparent">
										<td>
											<br />
											<input name="btnSimpleSearch" type="button" class="btnUpForm" value="Search"
												OnClick="frmUp.hdnCmd1.value='simpleSearch';frmUp.submit();">
										</td>
										<td style="padding-left:30px;">
											<br />
											<a href="<?= $_SERVER["PHP_SELF"]; ?>">Reset</a>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					</tbdoy>
			</table>
		</form>
		<hr />

		<div>
			<h2>List of all users</h2>
			<form name="frmMain" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="userIDpass" value="">
				<input type="hidden" name="passwordpass" value="">
				<table width="100%" border="1">
					<tr>
						<th width="12%">
							<div align="center">UserID </div>
						</th>
						<th width="12%">
							<div align="center">Username</div>
						</th>
						<th width="12%">
							<div align="center">User Type</div>
						</th>
						<th width="12%">
							<div align="center">First Name</div>
						</th>
						<th width="12%">
							<div align="center">Last Name</div>
						</th>
						<th width="12%">
							<div align="center">Date of Birth</div>
						</th>
						<th width="12%">
							<div align="center">Gender</div>
						</th>
						<th width="16%" colspan="2">
							<div align="center">Actions</div>
						</th>
					</tr>
					<?php
                    if ($_POST["hdnCmd1"] == "simpleSearch") {
	                    $strSQL = "{call dbo.Q1_Simple_Select(?)}";
	                    $params = array(
	                    	array($_POST["keyword"], SQLSRV_PARAM_IN),
	                    );
	                    $objQuery = sqlsrv_query($conn, $strSQL, $params);
                    } else if ($_POST["hdnCmd1"] == "advSearch") {
	                    $strSQL = "{call dbo.Q1_Advanced_Select(?, ?, ?, ?, ?, ?, ?)}";
	                    $params = array(
	                    	array($_POST["FName2"], SQLSRV_PARAM_IN),
	                    	array($_POST["LName2"], SQLSRV_PARAM_IN),
	                    	array($_POST["UserID2"], SQLSRV_PARAM_IN),
	                    	array($_POST["Date_of_Birth2"], SQLSRV_PARAM_IN),
	                    	array($_POST["Gender2"], SQLSRV_PARAM_IN),
	                    	array($_POST["Username2"], SQLSRV_PARAM_IN),
	                    	array($_POST["UserType2"], SQLSRV_PARAM_IN),
	                    );
	                    $objQuery = sqlsrv_query($conn, $strSQL, $params);


                    } else {
	                    $strSQL = "EXEC dbo.Q1_Select;";
	                    $objQuery = sqlsrv_query($conn, $strSQL);
                    }


                    while ($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC)) {
                    ?>

					<?php
	                    if ($objResult["UserID"] == $_GET["id"] and $_GET["Action"] == "Edit") {
                    ?>
					<tr>
						<td style="height:50px;">
							<div id='row<?= $objResult["UserID"]; ?>' align="center">
								<?= $objResult["UserID"]; ?>
							</div>
							<input type="hidden" name="hdnEditUserID" value="<?= $objResult["UserID"]; ?>">
						</td>
						<td style="height:50px;" align="center"><input style="text-align:center; width:100%; height:100%;" maxlength="40" type="text"
								name="txtEditUsername" value="<?= $objResult["Username"]; ?>"></td>
						<td style="height:50px;" align="center"><select style="text-align:center; width:100%; height:100%;" name="txtEditUserType" id="txtEditUserType"
								value="<?= $objResult["UserType"]; ?>">
								<option value="0">Διαχειριστής Συστήματος</option>
								<option value="1">Διαχειριστής Λειτουργιών</option>
								<option value="2">Απλός χρήστης</option>
							</select></td>
						<td style="height:50px;" align="center"><input style="text-align:center; width:100%; height:100%;" maxlength="30" type="text"
								name="txtEditFName" value="<?= $objResult["FName"]; ?>"></td>
						<td style="height:50px;" align="center"><input style="text-align:center; width:100%; height:100%;" maxlength="30" type="text"
								name="txtEditLName" value="<?= $objResult["LName"]; ?>"></td>
						<td style="height:50px;" align="center"><input style="text-align:center; width:100%; height:100%;" type="date" name="txtEditDOB"
								value="<?= $objResult["Date_of_Birth"]; ?>"></td>
						<td style="height:50px;" align="center"><select style="text-align:center; width:100%; height:100%;" name="txtEditGender" id="txtEditGender"
								value="<?= $objResult["Gender"]; ?>">
								<option value="M">Male</option>
								<option value="F">Female</option>
								<option value="O">Other</option>
							</select></td>

						<td style="height:50px;" colspan="2" align="right">
							<div align="center">
								<input class="textbtn success" name="btnUpdate" type="button" id="btnUpdate" value="Update"
									OnClick="frmMain.hdnCmd.value='Update';frmMain.submit();">
								<input class="textbtn danger" name="btnCancel" type="button" id="btnCancel" value="Cancel"
									OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>';">
							</div>
						</td>
					</tr>
					<?php
	                    } else {
		                    $userTypePresent = "";
		                    switch ($objResult["UserType"]) {
			                    case 0:
				                    $userTypePresent = "Διαχειριστής Συστήματος";
				                    break;
			                    case 1:
				                    $userTypePresent = "Διαχειριστής Λειτουργιών";
				                    break;
			                    case 2:
				                    $userTypePresent = "Απλός Χρήστης";
				                    break;
		                    }

                    ?>
					<tr>
						<td>
							<div id='row<?= $objResult["UserID"]; ?>' align="center">
								<?= $objResult["UserID"]; ?>
							</div>
						</td>
						<td align="center">
							<?= $objResult["Username"]; ?>
						</td>
						<td align="center">
							<?= $userTypePresent; ?>
						</td>
						<td align="center">
							<?= $objResult["FName"]; ?>
						</td>
						<td align="center">
							<?= $objResult["LName"]; ?>
						</td>
						<td align="center">
							<?= $objResult["Date_of_Birth"]; ?>
						</td>
						<td align="center">
							<?= $objResult["Gender"]; ?>
						</td>
						<td align="center" width="8%">
							
								<input class="textbtn warning" name="btnEdit" type="button" id="btnEdit" value="Edit" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>?Action=Edit&id=<?= $objResult["UserID"]; ?>#row<?= $objResult["UserID"]; ?>';"> 
						</td>
						<td align="center" width="8%">
							<input class="textbtn danger" name="btnChange" type="submit" id="btnChange" value="Change password"
								OnClick="changePass(<?= $objResult["UserID"]; ?>);frmMain.hdnCmd.value='ChangePass';frmMain.submit();">
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
	        $strSQL = "{call dbo.Q1_Edit_User(?, ?, ?, ?, ?, ?, ?)}";
	        $params = array(
	        	array($_POST["txtEditFName"], SQLSRV_PARAM_IN),
	        	array($_POST["txtEditLName"], SQLSRV_PARAM_IN),
	        	array($_POST["hdnEditUserID"], SQLSRV_PARAM_IN),
	        	array($_POST["txtEditDOB"], SQLSRV_PARAM_IN),
	        	array($_POST["txtEditGender"], SQLSRV_PARAM_IN),
	        	array($_POST["txtEditUsername"], SQLSRV_PARAM_IN),
	        	array($_POST["txtEditUserType"], SQLSRV_PARAM_IN)
	        );
	        $objQuery = sqlsrv_query($conn, $strSQL, $params);
	        $objRow = sqlsrv_fetch_array($objQuery);
	        if (!$objQuery) {
		        echo "Error Update [" . sqlsrv_errors() . "]";
	        } else if ($objRow[0] == '0') { //Simple user
        		$_POST = array();
		        $_POST["hdnCmd"] = "";
        ?>
		<script>
			alert("Simple users cannot update types.");
		</script>
		<?php
	        } else
		        echo "<meta http-equiv='refresh' content='0'>";
        }

        //*** Update Password ***//  
        if ($_POST["hdnCmd"] == "ChangePass") {
	        echo $_POST["userIDpass"];
	        $strSQL = "{call dbo.Q1_Change_Password(?, ?)}";
	        $params = array(
	        	array($_POST["userIDpass"], SQLSRV_PARAM_IN),
	        	array($_POST["passwordpass"], SQLSRV_PARAM_IN)
	        );
	        $objQuery = sqlsrv_query($conn, $strSQL, $params);
	        $objRow = sqlsrv_fetch_array($objQuery);
	        if (!$objQuery) {
		        echo "Error Update [" . sqlsrv_errors() . "]";
	        } else if ($objRow[0] == '0') { //Simple user
        		$_POST = array();
		        $_POST["hdnCmd"] = "";
        ?>
		<script>
			alert("Simple users cannot update types.");
		</script>
		<?php
	        } else
		        echo "<meta http-equiv='refresh' content='0'>";
        }


        if ($_POST["hdnCmd1"] == "insert") {
	        $strSQL = "{call dbo.Q1_Insert_User(?, ?, ?, ?, ?, ?, ?)}";
	        $params = array(
	        	array($_POST["FName"], SQLSRV_PARAM_IN),
	        	array($_POST["LName"], SQLSRV_PARAM_IN),
	        	array($_POST["Date_of_Birth"], SQLSRV_PARAM_IN),
	        	array($_POST["Gender"], SQLSRV_PARAM_IN),
	        	array($_POST["Username"], SQLSRV_PARAM_IN),
	        	array($_POST["Password"], SQLSRV_PARAM_IN),
	        	array($_POST["UserType"], SQLSRV_PARAM_IN)
	        );
	        $objQuery = sqlsrv_query($conn, $strSQL, $params);
	        $objRow = sqlsrv_fetch_array($objQuery);
	        if (!$objQuery) {
		        echo "Error Insert [" . sqlsrv_errors() . "]";
	        } else if ($objRow["Result"] == '0') { //Simple user
        		$_POST = array();
		        $_POST["hdnCmd1"] = "";
        ?>
		<script>
			alert("All fields are required.");
		</script>
		<?php
	        } else
		        echo "<meta http-equiv='refresh' content='0'>";
        }



        // $time_end = microtime(true);
        $userTypes=array("System Admin", "Functions Admin", "Simple User");
        echo "Connecting to SQL server (" . $serverName . ")<br/>";
        echo "Database: " . $connectionOptions[Database] . ", SQL User: " . $connectionOptions[Uid] . "<br/>";
		echo "User: " . $_SESSION["userID"] . ", UserType: " . $userTypes[$_SESSION["userType"]] . "<br/>";

        /* Free connection resources. */
        sqlsrv_close($conn);

        // $execution_time = round((($time_end - $time_start)*1000),2);
        // echo 'QueryTime: '.$execution_time.' ms';
        
        ?>

		<script>
			function changePass(userID) {
				let text;
				let newpassword = prompt("Please enter your new password:", "");

				if (newpassword == null || newpassword == "") {
					text = "User cancelled the prompt.";
					alert(text);
					return false;
				}
				let newpasswordconf = prompt("Please confirm your new password:", "");
				if (newpasswordconf == null || newpassword != newpasswordconf) {
					text = "Passwords do not match.";
					alert(text);
				}
				if (confirm('Confirm Change of Password?') == true) {
					frmMain.userIDpass.value = userID;
					frmMain.passwordpass.value = newpassword;
					return true;
				}
				return false;
			}
		</script>




	</div>
</body>

</html>