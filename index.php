<html>  
<!-- <div class = "login-background ">	 -->
<head>  
    <title>PHP login system</title>   
    <link rel = "stylesheet" type = "text/css" href = "style.css">   
</head>  



<body style='background-blend-mode: unset; background-image: url("./images/ucy_bannerq.jpg");'>
	 
<?php
	session_start(); 
	if($_SESSION['userID']!=""){
		echo '<meta http-equiv="refresh" content="0; url=menu.php" />';
	}
	else if (!empty($_POST)) {
		$sqlUser = $_POST['user'];
		$sqlPass = $_POST['pass'];
				
		$_SESSION["serverName"] = "mssql.cs.ucy.ac.cy";
		$_SESSION["connectionOptions"] = array(
			// "Database" => "cseas002",
			// "Uid" => "cseas002",
			// "PWD" => "hdX8VtLW",
			// "Database" => "gchora01",
			// "Uid" => "gchora01",
			// "PWD" => "w5hU2k6Y",
			"Database" => "ldiony01",
			"Uid" => "ldiony01",
			"PWD" => "WJK3tBmc",
			"CharacterSet"=>"UTF-8"
		);

		$serverName = $_SESSION["serverName"];
		$connectionOptions = $_SESSION["connectionOptions"];
		$hashedpass = hash('sha256', $sqlPass);
		$strSQL = "EXEC dbo.UserLogin @Username='".$sqlUser."', @UPassword='".$hashedpass."';"; 
		$conn = sqlsrv_connect($serverName, $connectionOptions);
		$objQuery = sqlsrv_query($conn, $strSQL);
		$objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC);
		if(!$objQuery)
		{  
			$_POST = array();
			?>
			<script>
			alert("Error Connecting. Try again.");
			</script>
			
			<?php
			echo '<meta http-equiv="refresh" content="0; url=index.php" />';
		}  
		else if(!$objResult){
			$_POST = array();
			?>
			<script>
			alert("Wrong credentials.");
			</script>
			<?php
			echo '<meta http-equiv="refresh" content="0; url=index.php" />';
			
		}
		else{
			$_SESSION["userID"] = $objResult["UserID"];
			$_SESSION["userType"] = $objResult["UserType"];
			$_POST = array();
			echo '<meta http-equiv="refresh" content="0; url=menu.php" />';
		}	
	}
	?>
	<table cellSpacing=0 cellPadding=5 width="100%" border=0>
		
	<tr>
		<td vAlign=top width=170>
		<a href="http://www.ucy.ac.cy/"><img width="220px" alt=UCY src="images/logo_en.png"></a>
		<h4 >
			<a style="color: #C68F06;" href="http://www.cs.ucy.ac.cy/">Dept. of Computer Science</a>
		</h4>
		</td>
	</tr>
	<tr>
		<td vAlign=center align=middle><h2 style="color:#C68F06">Spatial Data Management System</h2></td>
	</tr>
    </table>
    <div  class = "frm" >  
        <h1 style="text-align:center ;">Login</h1>  
        <form class="form-container" style="background-color:transparent" name="f1" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return validation()"  method = "POST">  
                <label> Username: </label>
                <input type = "text" id ="user" name  = "user" />  <br/>
                <label> Password: </label>  
                <input type = "password" id ="pass" name  = "pass" />  <br/><br/>
                <input type =  "submit" class = "btn ucygreen" value = "Login" name = "connect"/>  
        </form>  
    </div>   
    <script>  
		function validation()  
		{  
			var id=document.f1.user.value;  
			var ps=document.f1.pass.value;
			if(id.length=="" && ps.length=="") {  
				alert("User Name and Password fields are empty");  
				return false;  
			}  
			else  
			{  
				if(id.length=="") {  
					alert("User Name is empty");  
					return false;  
				}   
				if (ps.length=="") {  
				alert("Password field is empty");  
					return false;  
				}  
				
			}	
	
		}  
    </script> 
	
</body>     
<!-- </div>  -->
</html>  

