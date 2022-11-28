<?php 
	session_start(); 
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="UTF-8" />
</head>

<body>
<?php 
	session_start(); 
	// Get the DB connection info from the session
	if(isset($_SESSION["userID"]) && isset($_SESSION["connectionOptions"]) && isset($_SESSION["userID"]) && isset($_SESSION["userType"])) { 
		$serverName = $_SESSION["serverName"];
		$connectionOptions = $_SESSION["connectionOptions"];
		$userID = $_SESSION["userID"];
		$userType = $_SESSION["userType"];
	} else {
		session_unset();
		session_destroy();
		echo "Session is not correctly set! Clossing session and redirecting to start page in 3 seconds<br/>";
		die('<meta http-equiv="refresh" content="3; url=index.php" />');
	} 	
	//Establishes the connection
	$conn = sqlsrv_connect($serverName, $connectionOptions);
?>
<div class="sidenav">
		<a href="http://www.ucy.ac.cy/"><img width="160px" alt=UCY src="images/logo_en.png"></a>
		<h5>
			<a style="color: #C68F06;" href="http://www.cs.ucy.ac.cy/">Dept. of Computer Science</a>
		</h5>
		<div class="disconnectForm" style="height:70px;">
			<?php
        if (isset($_POST['disconnect'])) {
	        echo "Clossing session and redirecting to start page";
	        session_unset();
	        session_destroy();
	        die('<meta http-equiv="refresh" content="1; url=index.php" />');
        }
        ?>

			<form method="post">
				<input style="margin-top:20px;" class="disconnectBtn" type="submit" name="disconnect" value="Disconnect" /><br />
			</form>
		</div>
	</div>
	<div class="main">
	<table cellSpacing=0 cellPadding=5 width="100%" border=0>
	<tr>
		<td vAlign=center align=middle><h2>Welcome to the EPL342 project test page</h2></td>
	</tr>
    </table>
	<style>

/* CSS */
.button-20 {
  appearance: button;
  background-color: #00afff;
  background-image: linear-gradient(180deg, rgba(255, 255, 255, .15), rgba(255, 255, 255, 0));
  border: 1px solid #00afff;
  border-radius: 1rem;
  box-shadow: rgba(255, 255, 255, 0.15) 0 1px 0 inset,rgba(46, 54, 80, 0.075) 0 1px 1px;
  box-sizing: border-box;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-block;
  font-family: Inter,sans-serif;
  font-size: 1rem;
  font-weight: 500;
  line-height: 1.5;
  margin: 10;
  padding: .5rem 1rem;
  height:50px;
  width:100px;
  text-align: center;
  text-transform: none;
  transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  vertical-align: middle;
}

.button-20:focus:not(:focus-visible),
.button-20:focus {
  outline: 0;
}

.button-20:hover {
  background-color: #004fff;
  border-color: #000fff;
}

.button-20:focus {
  background-color: #004fff;
  border-color: #000fff;
  box-shadow: rgba(255, 255, 255, 0.15) 0 1px 0 inset, rgba(46, 54, 80, 0.075) 0 1px 1px, rgba(104, 101, 235, 0.5) 0 0 0 .2rem;
}

.button-20:active {
  background-color: #000fff;
  background-image: none;
  border-color: #000fff;
  box-shadow: rgba(46, 54, 80, 0.125) 0 3px 5px inset;
}

.button-20:active:focus {
  box-shadow: rgba(46, 54, 80, 0.125) 0 3px 5px inset, rgba(104, 101, 235, 0.5) 0 0 0 .2rem;
}

.button-20:disabled {
  background-image: none;
  box-shadow: none;
  opacity: .65;
  pointer-events: none;
}
    </style>
	<hr>
	<h3>Queries Explained</h3>
	<button href="q1" class="button-20" role="button">Query 1 </button>
	<button href="q2"class="button-20" role="button">Query 2 </button>
	<button href="q3"class="button-20" role="button"> Query 3 </button>
	<button href="q4"class="button-20" role="button">Query 4 </button>
	<button href="q5"class="button-20" role="button"> Query 5</button><br>
	<button href="q6"class="button-20" role="button">Query 6 </button>
	<button href="q7"class="button-20" role="button">Query 7 </button>
	<button href="q8"class="button-20" role="button">Query 8 </button>
	<button href="q9"class="button-20" role="button"> Query 9</button>
	<button href="q10"class="button-20" role="button">Query 10</button><br>
	<button href="q11"class="button-20" role="button"> Query 11 </button>
	<button href="q12"class="button-20" role="button">Query 12 </button>
	<button href="q13"class="button-20" role="button">Query 13 </button>
	<button href="q14"class="button-20" role="button">Query 14 </button>
	<button href="q15"class="button-20" role="button"> Query 15 </button><br>
	<button href="q16"class="button-20" role="button">Query 16 </button>
	<button href="q17"class="button-20" role="button"> Query 17</button>
	<button href="q18"class="button-20" role="button"> Query 18 </button>
	<button href="q19"class="button-20" role="button">Query 19</button>
	<button href="q20"class="button-20" role="button"> Query 20 </button><br>
	<button href="q21"class="button-20" role="button">Query 21</button>
	
	</form>

	<hr>
	<?php
		if(isset($_POST['disconnect'])) { 
			echo "Clossing session and redirecting to start page"; 
			session_unset();
			session_destroy();
			die('<meta http-equiv="refresh" content="2; url=index.php" />');
		} 
	?> 
	</div>
</body>
</html>

