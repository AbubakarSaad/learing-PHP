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
				echo "<a class='history' href='phepage.php'><button type='btn' class='btn btn-default' name='history'>PHE Panel</button></a>";
				echo "<a class='logout' href='logout.php'><button type='btn' class='btn btn-default' name='delete'>Logout</button></a>";	
			}

		?>
		<div class="row">
			<h2 style="text-align: center;">Vaccination History</h2>
			<table class="table table-bordered">
					<tr>
						<thead>
							<th>Id</th>
							<th>Name</th>
							<th>Vaccine Id</th>
							<th>Virus name</th>
						</thead>
					</tr>
					<tbody>
					<?php
							$sname = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "PRD";

							$conn = mysqli_connect($sname, $username, $password, $dbname);

							if(!$conn){
								die("Connection Failed: " . mysqli_connect_error());
							}

							$sql = "SELECT DISTINCT Pat.Patientid, Per.pName, Vac.Vid, Vir.Vname
									FROM PersonLivesAt Per, Patient Pat, VaccinateWith Vac, ProtectsAgainst Pro, Virus Vir
									WHERE Pat.Patientid=Vac.Patientid AND Per.Healthnum=Pat.Patientid AND Vac.Vid=Pro.Vid AND Vir.Virusnum=Pro.Virusnum";
							$result = $conn->query($sql);

							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<tr>";
									echo "<td>" . $row["Patientid"] . "</td>";
									echo "<td>" . $row["pName"] . "</td>";
									echo "<td>" . $row["Vid"] . "</td>";
									echo "<td>" . $row["Vname"] . "</td>";
									echo "</td>";
								}
							}else{
								
							}

							$conn->close();
					
					?>
					</tbody>
			</table>
			
			<h3 style="text-align: center;">Remove Only Patients That been vaccinated and two weeks have passed</h3>
			<table class="table table-bordered">
					<tr>
						<thead>
							<th>Id</th>
							<th>Delete</th>
						</thead>
					</tr>
					<tbody>
					<?php
						$sname = "localhost";
						$username = "root";
						$password = "root";
						$dbname = "PRD";

						$conn = mysqli_connect($sname, $username, $password, $dbname);

						if(!$conn){
							die("Connection Failed: " . mysqli_connect_error());
						}

						$sql = "SELECT Pa.Patientid FROM Patient Pa WHERE Pa.Patientid IN ( SELECT DISTINCT Pat.Patientid FROM PersonLivesAt Per, Patient Pat, VaccinateWith Vac, ProtectsAgainst Pro, Virus Vir WHERE Pat.Patientid=Vac.Patientid AND Per.Healthnum=Pat.Patientid AND Vac.Vid=Pro.Vid AND Vir.Virusnum=Pro.Virusnum AND Vir.Vname='Counting Disease' AND DATEDIFF(CURDATE(),Vac.VacDate) > 1 AND DATEDIFF(CURDATE(),Vac.VacDate) < 15)";
						$result = $conn->query($sql);

						if($result->num_rows > 0){
							while($row = $result->fetch_assoc()){
								echo "<tr>";
								echo "<td>" . $row["Patientid"] . "</td>";
								echo "<td><a href='del.php?id=" . $row["Patientid"] . "'>del</a></td>";
								echo "</tr>";
							}
						}else{
							
						}

						$conn->close();
					?>
					</tbody>
			</table>
			<h3 style="text-align: center;">Update Emergency Contact</h3>
			<table class="table table-bordered">
					<tr>
						<thead>
							<th>PersonID</th>
							<th>EmergencyID</th>
							<th>Update</th>
						</thead>
					</tr>
					<tbody>
					<?php
						$sname = "localhost";
						$username = "root";
						$password = "root";
						$dbname = "PRD";

						$conn = mysqli_connect($sname, $username, $password, $dbname);

						if(!$conn){
							die("Connection Failed: " . mysqli_connect_error());
						}

						$sql = "SELECT Personid,Econtactid FROM EContact";
						$result = $conn->query($sql);

						if($result->num_rows > 0){
							while($row = $result->fetch_assoc()){
								echo "<tr>";
								echo "<td>" . $row["Personid"] . "</td>";
								echo "<td>" . $row["Econtactid"] . "</td>";
								echo "<td><a href='update.php?id=" . $row["Personid"] . "'>update</a></td>";
								echo "</tr>";
							}
						}else{
							
						}

						$conn->close();
					?>
					</tbody>
			</table>
			<h3 style="text-align: center;">Select age to get person attributes</h3>
			<form action="" method="POST">
				<label class="col-sm-2 control-label">Min Age</label>
				<div class="form-group">
					<input type="text" class="form-control" name="minage">
				</div>
				<label class="col-sm-2 control-label">Max Age</label>
				<div class="form-group">
					<input type="text" class="form-control" name="maxage">
				</div>
				<button type="submit" class="btn btn-success" name="selectage">Select</button>
			</form>
			<table class="table table-bordered">
					<tr>
						<thead>
							<th>Housenum</th>
							<th>Healthnum</th>
							<th>pName</th>
							<th>DOB</th>
							<th>BloodType</th>
							<th>Alive</th>
						</thead>
					</tr>
					<tbody>
					<?php
						if(isset($_POST['selectage'])){
							$minage = $_POST['minage'];
							$maxage = $_POST['maxage'];

							$sname = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "PRD";

							$conn = mysqli_connect($sname, $username, $password, $dbname);

							if(!$conn){
								die("Connection Failed: " . mysqli_connect_error());
							}


							$sql = "SELECT DISTINCT Per.Housenum, Per.Healthnum, Per.pName, Per.DOB, Per.BloodType, Per.Alive FROM PersonLivesAt Per WHERE (DATEDIFF(CURDATE(),Per.DOB) > (365 * $minage)) AND (DATEDIFF(CURDATE(),Per.DOB) < (365 * $maxage))";
							$result = $conn->query($sql);


							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<tr>";
									echo "<td>" . $row["Housenum"] . "</td>";
									echo "<td>" . $row["Healthnum"] . "</td>";
									echo "<td>" . $row["pName"] . "</td>";
									echo "<td>" . $row["DOB"] . "</td>";
									echo "<td>" . $row["BloodType"] . "</td>";
									echo "<td>" . $row["Alive"] . "</td>";
									echo "</tr>";
								}
							}else{
								
							}
							$conn->close();
							}
					?>
					</tbody>
			</table>
			<h3 style="text-align: center;">Update Virus Risk</h3>
			<table class="table table-bordered">
					<tr>
						<thead>
							<th>VirusNum</th>
							<th>Virus Name</th>
							<th>Risk</th>
							<th>Update</th>
						</thead>
					</tr>
					<tbody>
					<?php
						$sname = "localhost";
						$username = "root";
						$password = "root";
						$dbname = "PRD";

						$conn = mysqli_connect($sname, $username, $password, $dbname);

						if(!$conn){
							die("Connection Failed: " . mysqli_connect_error());
						}

						$sql = "SELECT Vir.Virusnum, Vir.Vname, Vir.Risk FROM Virus Vir";
						$result = $conn->query($sql);

						if($result->num_rows > 0){
							while($row = $result->fetch_assoc()){
								echo "<tr>";
								echo "<td>" . $row["Virusnum"] . "</td>";
								echo "<td>" . $row["Vname"] . "</td>";
								echo "<td>" . $row["Risk"] . "</td>";
								echo "<td><a href='updatev.php?id=" . $row["Virusnum"] . "'>update</a></td>";
								echo "</tr>";
							}
						}else{
							
						}

						$conn->close();
					?>
					</tbody>
			</table>
			<h3 style="text-align: center;">Susceptible</h3>
			<table class="table table-bordered">
					<tr>
						<thead>
							<th>Health Number</th>
							<th>Person Name</th>
							<th>House Number</th>
							<th>Street</th>
							<th>Postal Code</th>
						</thead>
					</tr>
					<tbody>
					<?php
						$sname = "localhost";
						$username = "root";
						$password = "root";
						$dbname = "PRD";

						$conn = mysqli_connect($sname, $username, $password, $dbname);

						if(!$conn){
							die("Connection Failed: " . mysqli_connect_error());
						}

						$sql = "SELECT Per.Healthnum, Per.pName, Adr.Housenum, Adr.Street, Adr.Pcode
								FROM PersonLivesAt Per, Patient Pat, InfectedWith Inf, Virus Vir, Address Adr
								WHERE ((Vir.Vname='Ebola Disease' AND Vir.Virusnum=Inf.Virusnum AND Inf.Treated='TREATED' AND Inf.Patientid=Pat.Patientid And Pat.Patientid=Per.Healthnum) AND
								Per.Alive='Alive' AND Per.Housenum=Adr.Housenum)";
						
						$result = $conn->query($sql);

						if($result->num_rows > 0){
							while($row = $result->fetch_assoc()){
								echo "<tr>";
								echo "<td>" . $row["Healthnum"] . "</td>";
								echo "<td>" . $row["pName"] . "</td>";
								echo "<td>" . $row["Housenum"] . "</td>";
								echo "<td>" . $row["Street"] . "</td>";
								echo "<td>" . $row["Pcode"] . "</td>";
								echo "</tr>";
							}
						}else{
							
						}

						$conn->close();
					?>
					</tbody>
			</table>

		</div>
	</div>
</body>
</html>
