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
		<div class="disconnectform" style="height:70px;">
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

body {
  background-image: url("./images/UCY_lib.jpg");
  background-repeat: no-repeat; 
  background-attachment: fixed;
  background-size: cover;
  background-blend-mode: overlay;
}

</style>
	
	<hr>
	<h3 style="color: #C68F06;">Queries Explained</h3>
	<button class="button-20" role="button" onclick="document.location.href='./q1'">Query 1 </button> 
	<p style="display:inline; color: #C68F06;" >Προσθήκη/Διόρθωση/Εµφάνιση στοιχείων ΔΛ και ΑΧ</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q2'">Query 2 </button> 
	<p style="display:inline; color: #C68F06;">Διαχείριση τύπων αντικειµένων</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q3'">Query 3 </button>
	<p style="display:inline; color: #C68F06;">Διαχείριση fingerprint</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q4'">Query 4 </button>
	<p style="display:inline; color: #C68F06;">Διαχείριση κτηρίων/ορόφων</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q5'">Query 5</button>
	<p style="display:inline; color: #C68F06;">Διαχείριση εγκαταστάσεων</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q6'">Query 6 </button>
	<p style="display:inline; color: #C68F06;">Λίστα fingerprints</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q7'">Query 7 </button>
	<p style="display:inline; color: #C68F06;">Εύρεση δηµοφιλέστερων τύπων αντικειµένων</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q8'">Query 8 </button>
	<p style="display:inline; color: #C68F06;">Αριθµός τύπων POIs ανά όροφο</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q9'">Query 9</button>
	<p style="display:inline; color: #C68F06;">Μέσο πλήθος αντικειµένων ανά τύπο</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q10'">Query 10</button>
	<p style="display:inline; color: #C68F06;">Εύρεση µεγάλων ορόφων</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q11'">Query 11 </button>
	<p style="display:inline; color: #C68F06;">Εύρεση µικρότερων ορόφων</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q12'">Query 12 </button>
	<p style="display:inline; color: #C68F06;">Εύρεση fingerprint µε κοινούς τύπους αντικειµένων</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q13'">Query 13 </button>
	<p style="display:inline; color: #C68F06;">Εύρεση κοινών τύπων αντικειµένων</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q14'">Query 14 </button>
	<p style="display:inline; color: #C68F06;">Εύρεση των k τύπων αντικειµένων µε τις λιγότερες συµµέτοχες</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q15'">Query 15 </button>
	<p style="display:inline; color: #C68F06;">Τύποι αντικειµένων που βρίσκονται σε κάθε fingerprint</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q16'">Query 16 </button>
	<p style="display:inline; color: #C68F06;">Εύρεση πλήθους αντικειµένων εντός ενός πλαίσιου οριοθέτησης</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q17'">Query 17</button>
	<p style="display:inline; color: #C68F06;">Εύρεση πλαίσιού οριοθέτησης κτηρίου</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q18'">Query 18 </button>
	<p style="display:inline; color: #C68F06;">Εύρεση πλησιέστερου (Nearest Neighbor - NN) POI</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q19'">Query 19</button>
	<p style="display:inline; color: #C68F06;">Εύρεση k πλησιέστερων (k Nearest Neighbor - kNN) POI</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q20'">Query 20 </button>
	<p style="display:inline; color: #C68F06;">Εύρεση όλων των k πλησιέστερων (All k Nearest Neighbor - AkNN) POI ενός ορόφου</p><br>
	<button class="button-20" role="button" onclick="document.location.href='./q21'">Query 21</button>
	<p style="display:inline; color: #C68F06;">Συνολικό πλήθος αντικειµένων διαδροµής fingerprint</p><br>
	
	</form>
	</body>
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

