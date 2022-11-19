<html>  
<head>  
    <title>PHP login system</title>   
    <link rel = "stylesheet" type = "text/css" href = "style.css">   
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
	</tr>
	<tr>
		<td vAlign=center align=middle><h2>Spatial Data Management System</h2></td>
	</tr>
    </table>
    <div id = "frm">  
        <h1>Login</h1>  
        <form name="f1" action = "connect.php" onsubmit = "return validation()" method = "POST">  
            <p>  
                <label> UserName: </label>  <br/> 
                <input type = "text" id ="user" name  = "user" />  
            </p>  
            <p>  
                <label> Password: </label> <br/>  
                <input type = "password" id ="pass" name  = "pass" />  
            </p>  
            <p>     
                <input type =  "submit" id = "btn" value = "Login" name = "connect"/>  
            </p>  
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
</html>  

