<?php
session_start();
// Get the DB connection info from the session


if (isset($_SESSION["userID"]) && isset($_SESSION["connectionOptions"]) && isset($_SESSION["userID"]) && isset($_SESSION["userType"])) {
	$serverName = $_SESSION["serverName"];
	$connectionOptions = $_SESSION["connectionOptions"];
	$userID = $_SESSION["userID"];
	$userType = $_SESSION["userType"];

	if ($userType != '1') {
?>
<script>
	alert("Only Administrators can add or edit other users");
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

//*** Update Condition ***//
if ($_POST["hdnCmd"] == "Update") {
	$strSQL = "{call dbo.Q1_Edit_User(?, ?, ?, ?, ?, ?, ?, ?)}";
	$params = array(
		array($_POST["txtEditFName"], SQLSRV_PARAM_IN),
		array($_POST["txtEditLName"], SQLSRV_PARAM_IN),
		array($_POST["hdnEditUserID"], SQLSRV_PARAM_IN),
		array($_POST["txtEditDOB"], SQLSRV_PARAM_IN),
		array($_POST["txtEditGender"], SQLSRV_PARAM_IN),
		array($_POST["txtEditUsername"], SQLSRV_PARAM_IN),
		array($_POST["txtEditUserType"], SQLSRV_PARAM_IN),
		array($_POST["txtEditGovID"], SQLSRV_PARAM_IN)
	);
	$objQuery = sqlsrv_query($conn, $strSQL, $params);
	$objRow = sqlsrv_fetch_array($objQuery);
	if (!$objQuery) {
		echo "Error Update [" . sqlsrv_errors() . "]";
	} else
		echo "<meta http-equiv='refresh' content='0'>";
}

//*** Update Password ***//
if ($_POST["hdnCmd"] == "ChangePass") {
	$strSQL = "{call dbo.Q1_Change_Password(?, ?)}";
	$hashedpass = hash('sha256', $_POST['passwordpass']);
	$params = array(
		array($_POST["userIDpass"], SQLSRV_PARAM_IN),
		array($hashedpass, SQLSRV_PARAM_IN)
	);
	$objQuery = sqlsrv_query($conn, $strSQL, $params);
	$objRow = sqlsrv_fetch_array($objQuery);
	if (!$objQuery) {
		echo "Error Update [" . sqlsrv_errors() . "]";
	} else
		echo "<meta http-equiv='refresh' content='0'>";
}

if ($_POST["hdnCmd"] == "Delete") {
	$strSQL = "{call dbo.Q1_Delete(?)}";
	$params = array(
		array($_POST["userIDpass"], SQLSRV_PARAM_IN),
	);
	$objQuery = sqlsrv_query($conn, $strSQL, $params);
	$objRow = sqlsrv_fetch_array($objQuery);
	if (!$objQuery) {
		echo "Error Delete [" . sqlsrv_errors() . "]";
	} else
		echo "<meta http-equiv='refresh' content='0'>";
}


if ($_POST["hdnCmdInsert"] == "insert") {
	$strSQL = "{call dbo.Q1_Insert_User(?, ?, ?, ?, ?, ?, ?, ?)}";
	$hashedpass = hash('sha256', $_POST['Password']);
	$params = array(
		array($_POST["FName"], SQLSRV_PARAM_IN),
		array($_POST["LName"], SQLSRV_PARAM_IN),
		array($_POST["Date_of_Birth"], SQLSRV_PARAM_IN),
		array($_POST["Gender"], SQLSRV_PARAM_IN),
		array($_POST["Username"], SQLSRV_PARAM_IN),
		array($hashedpass, SQLSRV_PARAM_IN),
		array($_POST["UserType"], SQLSRV_PARAM_IN),
		array($_POST["GovID"], SQLSRV_PARAM_IN)
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
	<meta charset="UTF-8" />
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
				<td vAlign=center align=middle><h2>Insert / Modify / Delete Users</h2></td>
			</tr>
		</table>
		<hr>

		<!-- <div class="addRowBtn" value=""><i class="fa fa-plus" aria-hidden="true"></i></div> -->
		<button id="btnInsertForm" class="button-20" onclick="document.getElementById('myForm').style.display = 'block';" >+</button>
		<button id="btnAdvSearchForm" class="button-20" onclick="document.getElementById('myForm1').style.display = 'block';">Advanced Search</button>			
		<button id="btnSearchForm" class="button-20" onclick="document.getElementById('myForm2').style.display = 'block';">Simple Search</button>			
		<button id="btnReset" style="display:none;" class="textbtn" onclick="window.location='<?= $_SERVER['PHP_SELF']; ?>';">Reset</button>			
		
		<div class="form-popup" id="myForm" onkeypress="if(event.keyCode==13){if(insertValidation()){frmInsert.hdnCmdInsert.value='insert';frmInsert.submit();}}"> 
		<form name="frmInsert" class="form-container" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
			<input type="hidden" name="hdnCmdInsert" value="">
			<h3>Insert new user</h3>
			<label> Username: </label>
			<input maxlength="40" type="text" name="Username" /><br/>
			<label> User Type: </label>
			<select name="UserType" id="UserType"><br/>
				<option value=""> </option>
				<option value="2">Διαχειριστής Λειτουργιών</option>
				<option value="3">Απλός χρήστης</option>
			</select>
			<label> Password: </label>
			<input maxlength="30" type="password" name="Password" /><br/>
			<label> First Name: </label>
			<input maxlength="30" type="text" name="FName" /><br/>
			<label> Last Name: </label>
			<input maxlength="30" type="text" name="LName" /><br/>
			<label> GovernmentID: </label>
			<input maxlength="30" type="text" name="GovID" /><br/>
			<label> Date of Birth: </label>
			<input type="date" name="Date_of_Birth" /><br/>
			<label> Gender: </label>
			<select name="Gender" id="Gender"><br/>
				<option value=""> </option>
				<option value="M">Male</option>
				<option value="F">Female</option>
				<option value="O">Other</option>
			</select>
			<input name="btnInsert" type="button" class="btn" value="Insert" onclick="if(insertValidation()){frmInsert.hdnCmdInsert.value='insert';frmInsert.submit();}">
			<button type ="button" class = "btn cancel" onclick="document.getElementById('myForm').style.display = 'none';">Cancel</button>
		</form>
		</div>
		<div class="form-popup" id="myForm1">
		<form name="frmAdvSearch" class="form-container" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<input type="hidden" name="hdnCmdAdvSearch" value="">
		<h3>Advanced Search</h3>
		<label> User ID: </label>
		<input maxlength="40" type="text" name="UserID2" value="<?=$_POST["UserID2"];?>"/><br/>
		<label> Username: </label>
		<input maxlength="40" type="text" name="Username2" value="<?=$_POST["Username2"];?>"/><br/>
		<label> User Type: </label>
		<select name="UserType2" id="UserType2"><br/>
		<?php
			if ($_POST["UserType2"] == "") {
				echo '<option value="" selected="selected"> </option>';
			} else
				echo '<option value=""> </option>';
			if ($_POST["UserType2"] == "1") {
				echo '<option value="1" selected="selected">Διαχειριστής Συστήματος</option>';
			} else
				echo '<option value="1">Διαχειριστής Συστήματος</option>';
			if ($_POST["UserType2"] == "2") {
				echo '<option value="2" selected="selected">Διαχειριστής Λειτουργιών</option>';
			} else
				echo '<option value="2">Διαχειριστής Λειτουργιών</option>';
			if ($_POST["UserType2"] == "3") {
				echo '<option value="3" selected="selected">Απλός χρήστης</option>';
			} else
				echo '<option value="3">Απλός χρήστης</option>';
				?>
		</select>
		<label> First Name: </label>
		<input maxlength="30" type="text" name="FName2" value="<?=$_POST["FName2"];?>"/><br/>
		<label> Last Name: </label>
		<input maxlength="30" type="text" name="LName2" value="<?=$_POST["LName2"];?>"/><br/>
		<label> Date of Birth: </label>
		<input type="date" name="Date_of_Birth2" value="<?=$_POST["Date_of_Birth2"];?>"/><br/>
		<label> Government ID: </label>
		<input maxlength="30" type="text" name="GovID2" value="<?=$_POST["GovID2"];?>"/><br/>
		<label> Gender: </label>
		<select name="Gender2" id="Gender2"><br/>
		<?php
			if ($_POST["Gender2"] == '') {
				echo '<option value="" selected="selected"> </option>';
			} else
				echo '<option value=""> </option>';
			if ($_POST["Gender2"] == 'M') {
				echo '<option value="M" selected="selected">Male</option>';
			} else
				echo '<option value="M">Male</option>';
			if ($_POST["Gender2"] == 'F') {
				echo '<option value="F" selected="selected">Female</option>';
			} else
				echo '<option value="F">Female</option>';
			if ($_POST["Gender2"] == 'O') {
				echo '<option value="O" selected="selected">Other</option>';
			} else
				echo '<option value="O">Other</option>';
		?>
		</select>
		<input name="btnAdvSearch" type="submit" class="btn" value="Search" onclick="frmAdvSearch.hdnCmdAdvSearch.value='advSearch';frmAdvSearch.submit();">
		<button type ="button" class = "btn cancel" onclick="document.getElementById('myForm1').style.display = 'none';">Cancel</button>
		</form>
		</div>
		<div class="form-popup" id="myForm2" onkeypress="if(event.keyCode==13){frmSearch.hdnCmdSearch.value='simpleSearch';frmSearch.submit();}">
		<form name="frmSearch" class="form-container" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<input type="hidden" name="hdnCmdSearch" value="">
		<h3>Search</h3>
		<label> Keyword: </label>
		<input maxlength="40" type="text" name="keyword" value="<?=$_POST["keyword"];?>"/>
		<input name="btnSimpleSearch" type="button" class="btn" value="Search"
												onclick="frmSearch.hdnCmdSearch.value='simpleSearch';frmSearch.submit();">

		<button type ="button" class = "btn cancel" onclick="document.getElementById('myForm2').style.display = 'none';">Cancel</button>

		</form>
		</div>
		<hr />

		<div onkeypress="if(event.keyCode==13){if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}}">
			<h2>List of all users</h2>
			<form name="frmMain" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="userIDpass" value="">
				<input type="hidden" name="passwordpass" value="">
				<table width="100%" border="1">
					<tr>
						<th width="9%">
							<div align="center">UserID </div>
						</th>
						<th width="10%">
							<div align="center">Username</div>
						</th>
						<th width="18%">
							<div align="center">User Type</div>
						</th>
						<th width="13%">
							<div align="center">First Name</div>
						</th>
						<th width="13%">
							<div align="center">Last Name</div>
						</th>
						<th width="13%">
							<div align="center">Date of Birth</div>
						</th>
						<th width="10%">
							<div align="center">GovID</div>
						</th>
						<th width="9%">
							<div align="center">Gender</div>
						</th>
						<th width="14%" colspan="3">
							<div align="center">Actions</div>
						</th>
					</tr>
					<?php
                    if ($_POST["hdnCmdSearch"] == "simpleSearch") {
						?>
							<script>
									document.getElementById("btnSearchForm").classList.add("warningb");
									document.getElementById("btnReset").style="display:inline-block;";
								</script>
						<?php
	                    $strSQL = "{call dbo.Q1_Simple_Select(?)}";
	                    $params = array(
	                    	array($_POST["keyword"], SQLSRV_PARAM_IN),
	                    );
	                    $objQuery = sqlsrv_query($conn, $strSQL, $params);
                    } else if ($_POST["hdnCmdAdvSearch"] == "advSearch") {
						?>
							<script>
									document.getElementById("btnAdvSearchForm").classList.add("warningb");
									document.getElementById("btnReset").style="display:inline-block;";
								</script>
						<?php
	                    $strSQL = "{call dbo.Q1_Advanced_Select(?, ?, ?, ?, ?, ?, ?, ?)}";
	                    $params = array(
	                    	array($_POST["FName2"], SQLSRV_PARAM_IN),
	                    	array($_POST["LName2"], SQLSRV_PARAM_IN),
	                    	array($_POST["UserID2"], SQLSRV_PARAM_IN),
	                    	array($_POST["Date_of_Birth2"], SQLSRV_PARAM_IN),
	                    	array($_POST["Gender2"], SQLSRV_PARAM_IN),
	                    	array($_POST["Username2"], SQLSRV_PARAM_IN),
	                    	array($_POST["UserType2"], SQLSRV_PARAM_IN),
							array($_POST["GovID2"], SQLSRV_PARAM_IN)
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
						<td style="height:40px;">
							<div id='row<?= $objResult["UserID"]; ?>' align="center">
								<?= $objResult["UserID"]; ?>
							</div>
							<input type="hidden" name="hdnEditUserID" value="<?= $objResult["UserID"]; ?>">
						</td>
						<td style="height:40px;" align="center"><input
								style="text-align:center; width:100%; height:100%;" maxlength="40" type="text"
								name="txtEditUsername" value="<?= $objResult["Username"]; ?>"></td>
						<td style="height:40px;" align="center"><select
								style="text-align:center; width:100%; height:100%;" name="txtEditUserType"
								id="txtEditUserType">
								<?php
		                    if ($objResult["UserType"] == "1") {
			                    echo '<option value="1" selected="selected">Διαχειριστής Συστήματος</option>';
		                    } else
			                    echo '<option value="1">Διαχειριστής Συστήματος</option>';
		                    if ($objResult["UserType"] == "2") {
			                    echo '<option value="2" selected="selected">Διαχειριστής Λειτουργιών</option>';
		                    } else
			                    echo '<option value="2">Διαχειριστής Λειτουργιών</option>';
		                    if ($objResult["UserType"] == "3") {
			                    echo '<option value="3" selected="selected">Απλός χρήστης</option>';
		                    } else
			                    echo '<option value="3">Απλός χρήστης</option>';
                                ?>
							</select></td>
						<td style="height:40px;" align="center"><input
								style="text-align:center; width:100%; height:100%;" maxlength="30" type="text"
								name="txtEditFName" value="<?= $objResult["FName"]; ?>"></td>
						<td style="height:40px;" align="center"><input
								style="text-align:center; width:100%; height:100%;" maxlength="30" type="text"
								name="txtEditLName" value="<?= $objResult["LName"]; ?>"></td>
						
						<td style="height:40px;" align="center"><input
								style="text-align:center; width:100%; height:100%;" type="date" name="txtEditDOB"
								value="<?= $objResult["Date_of_Birth"]; ?>"></td>
								<td style="height:40px;" align="center"><input
								style="text-align:center; width:100%; height:100%;" maxlength="30" type="text"
								name="txtEditGovID" value="<?= $objResult["GovID"]; ?>"></td>
						<td style="height:40px;" align="center">
							<select style="text-align:center; width:100%; height:100%;" name="txtEditGender"
								id="txtEditGender">
								<?php
		                    if ($objResult["Gender"] == 'M') {
			                    echo '<option value="M" selected="selected">Male</option>';
		                    } else
			                    echo '<option value="M">Male</option>';
		                    if ($objResult["Gender"] == 'F') {
			                    echo '<option value="F" selected="selected">Female</option>';
		                    } else
			                    echo '<option value="F">Female</option>';
		                    if ($objResult["Gender"] == 'O') {
			                    echo '<option value="O" selected="selected">Other</option>';
		                    } else
			                    echo '<option value="O">Other</option>';
                        ?>
							</select>
						</td>

						<td style="height:40px;" colspan="3" align="right">
							<div align="center">
								<input class="textbtn success" name="btnUpdate" type="button" id="btnUpdate"
									value="Update" onclick="if(updateValidation()){frmMain.hdnCmd.value='Update';frmMain.submit();}">
								<input class="textbtn danger" name="btnCancel" type="button" id="btnCancel"
									value="Cancel" OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>';">
							</div>
						</td>
					</tr>
					<?php
	                    } else {
		                    $userTypePresent = "";
		                    switch ($objResult["UserType"]) {
			                    case 1:
				                    $userTypePresent = "Διαχειριστής Συστήματος";
				                    break;
			                    case 2:
				                    $userTypePresent = "Διαχειριστής Λειτουργιών";
				                    break;
			                    case 3:
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
							<?= $objResult["GovID"]; ?>
						</td>
						<td align="center">
							<?php
		                    $gender = "";
		                    if ($objResult["Gender"] == 'M')
			                    $gender = "Male";
		                    else if ($objResult["Gender"] == 'F')
			                    $gender = "Female";
		                    else if ($objResult["Gender"] == 'O')
			                    $gender = "Other";
                            ?>
							<?= $gender; ?>
						</td>
						<td align="center" width="7%">

							<input class="textbtn warning" name="btnEdit" type="button" id="btnEdit" value="Edit"
								OnClick="window.location='<?= $_SERVER["PHP_SELF"]; ?>?Action=Edit&id=<?= $objResult["UserID"]; ?>#row<?= $objResult["UserID"]; ?>';">
						</td>
						<td align="center" width="7%">
							<input class="textbtn danger" name="btnChange" type="button" id="btnChange"
								value="Change password"
								OnClick="changePass(<?= $objResult["UserID"]; ?>);frmMain.hdnCmd.value='ChangePass';frmMain.submit();">
						</td>
						<td align="center" width="7%">
							<input class="textbtn danger" name="btnDelete" type="button" id="btnDelete"
								value="Delete"
								OnClick="if(confirm('Confirm Delete?')==true){frmMain.hdnCmd.value='Delete';frmMain.userIDpass.value='<?= $objResult["UserID"]; ?>';frmMain.submit();}">
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
				if (newpassword == newpasswordconf && confirm('Confirm Change of Password?') == true) {
					frmMain.userIDpass.value = userID;
					frmMain.passwordpass.value = newpassword;
					return true;
				}
				return false;
			}
			function insertValidation()  {
				var username=frmInsert.Username.value;
				var userType=frmInsert.UserType.value;
				var password=frmInsert.Password.value;
				var fname=frmInsert.FName.value;
				var lname=frmInsert.LName.value;
				var dob=frmInsert.Date_of_Birth.value;
				var gender=frmInsert.Gender.value;
				var govid=frmInsert.GovID.value;

				if(username.length>0 && userType.length>0 && password.length>0 && fname.length>0 && lname.length>0 && dob.length>0 && gender.length>0 && govid.length>0){
					return true;
				}
				var str="";
				if(username.length==0)
					str+="User Name is empty\n";
				if(userType.length==0)
					str+="User Type is empty\n";
				if(password.length==0)
					str+="Password is empty\n";
				if(fname.length==0)
					str+="First Name is empty\n";
				if(lname.length==0)
					str+="Last Name is empty\n";
				if(dob.length==0)
					str+="Date of Birth is empty\n";
				if(gender.length==0)
					str+="Gender is empty\n";
				if(govid.length==0)
					str+="Government ID is empty\n";
				alert(str);
				return false;
			}
			function updateValidation()  {
				var username=frmMain.txtEditUsername.value;
				var userType=frmMain.txtEditUserType.value;
				var fname=frmMain.txtEditFName.value;
				var lname=frmMain.txtEditLName.value;
				var dob=frmMain.txtEditDOB.value;
				var gender=frmMain.txtEditGender.value;
				var govid=frmMain.txtEditGovID.value;

				if(username.length>0 && userType.length>0 && fname.length>0 && lname.length>0 && dob.length>0 && gender.length>0 && govid.length>0){
					return true;
				}
				var str="";
				if(username.length==0)
					str+="User Name is empty\n";
				if(userType.length==0)
					str+="User Type is empty\n";
				if(fname.length==0)
					str+="First Name is empty\n";
				if(lname.length==0)
					str+="Last Name is empty\n";
				if(dob.length==0)
					str+="Date of Birth is empty\n";
				if(gender.length==0)
					str+="Gender is empty\n";
				if(govid.length==0)
					str+="Government ID is empty\n";
				alert(str);
				return false;
			}
		</script>




	</div>
</body>

</html>
