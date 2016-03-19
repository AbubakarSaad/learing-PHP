<?php ob_start(); 
	session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Zombies</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script type="text/javascript " src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript " src="js/bootstrap.js"></script>
<script type="text/javascript " src="js/script.js"></script>
</head>
<body>
	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	        <li class="active"><a href="index.php">Home <span class="sr-only">(current)</span></a></li>
	        <li><a href="public.php">Public Region</a></li>
	        <form id="Phe" class="navbar-form navbar-right" method="POST" role="search" action="">
			  <div class="form-group">
			    <input type="text" class="form-control" name="phe_id" placeholder="Enter Your Id....">
			  </div>
			  <button type="submit" class="btn btn-default" name="submit">Login</button>
			</form>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>

	<?php
		if(isset($_POST["submit"])){
			$phe_id = $_POST["phe_id"];
			$sname = "localhost";
			$username = "root";
			$password = "root";
			$dbname = "PRD";

			$con = mysqli_connect($sname, $username, $password, $dbname);

			if(!$con){
				die("Connection Failed: " . mysqli_connect_error());
			}
			
			echo $phe_id;
			$sql = "SELECT p.PHEid FROM PHE p WHERE p.PHEid=" . $phe_id;
			$result = $con->query($sql);

			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					echo "id: " . $row["PHEid"] . "</br>";
					$p_id = $row["PHEid"];
				}

				if ($phe_id == $p_id){
					session_start();
					$_SESSION['sess_user'] = $phe_id;

					header("Location: phepage.php");
				}
			}else { 
				echo "No match found";
			}	
		}
	?>

	<div class="container">
		<?php 
			if(!isset($_SESSION["sess_user"])){
				header("Location: index.php");
			}else{
				echo "<h2>WELCOME: " . $_SESSION["sess_user"] . "</h2>";
				echo "<a class='history' href='history.php'><button type='btn' class='btn btn-default' name='history'>History</button></a>";
				echo "<a class='logout' href='logout.php'><button type='btn' class='btn btn-default' name='delete'>Logout</button></a>";	
			}

		?>

		<?php
			
			$id = $_GET['id'];

			$sname = "localhost";
			$username = "root";
			$password = "root";
			$dbname = "PRD";

			$conn = mysqli_connect($sname, $username, $password, $dbname);

			if(!$conn){
				die("Connection Failed: " . mysqli_connect_error());
			}

			$sql = "SELECT Vir.Virusnum, Vir.Vname, Vir.Risk FROM Virus Vir WHERE Vir.Virusnum=" . $id;

			$result = $conn->query($sql);

			$row = $result->fetch_assoc();

			$conn->close();
		?>

		<form action="" method="POST">
			<label class="col-sm-2 control-label">Virus Number</label>
			<div class="form-group">
			    <input type="text" class="form-control" name="vnum" value="<?php echo $row['Virusnum']; ?>" disabled>
			  </div>
			 <label class="col-sm-2 control-label">Virus Name</label>
			<div class="form-group">
			    <input type="text" class="form-control" name="vname" value="<?php echo $row['Vname']; ?>" disabled>
			  </div>
			   <label class="col-sm-2 control-label">Risk</label>
			  <div class="form-group">
			    <input type="text" class="form-control" name="vrisk" value="<?php echo $row['Risk']; ?>">
			  </div>
			 <button type="submit" class="btn btn-success" name="update">Update</button>
		</form>

		<?php 
		// 27525241
			if(isset($_POST['update'])){
				$id = $_GET['id'];
				$risk = $_POST['vrisk'];


				$sname = "localhost";
				$username = "root";
				$password = "root";
				$dbname = "PRD";

				$conn = mysqli_connect($sname, $username, $password, $dbname);

				if(!$conn){
					die("Connection Failed: " . mysqli_connect_error());
				}

				$sql = "UPDATE Virus SET Risk='$risk' WHERE Virusnum=" . $id;

				$result = $conn->query($sql);

				$conn->close();

				header("Location: history.php");
			}
		?>
	</div>
</body>
</html>
