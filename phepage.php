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
		<div class="row">
			<div class="col-md-2">
				<form class="regions" method="POST" action="#">
					<select name="Regions">
						<?php 
							$sname = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "PRD";

							$con = mysqli_connect($sname, $username, $password, $dbname);

							if(!$con){
								die("Connection Failed: " . mysqli_connect_error());
							}

							$sql = "SELECT * FROM Region";
							$result = $con->query($sql);

							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<option value='" . $row["Regionnum"] . "'>" . $row["rName"] . "</option>";
								}
							}else{
								echo "something is wrong";
							}

							$con->close();
						?>	

					</select>
					<button type="submit" class="btn btn-default" name="selected">Selected</button>
				</form>
			</div>
			<div class="col-md-10">
				<table class="table table-bordered">
					<tr>
						<thead>
							<th>Healthnum</th>
							<th>Person Name</th>
							<th>Address</th>
						</thead>
					</tr>
					<tbody>
					<?php
						if(isset($_POST['selected'])){
							

							$selected_region = $_POST['Regions'];
							echo "<tr>Region id: " . $selected_region . " who have received a vaccine against ETCC<br>" ;
							

							$sname = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "PRD";

							$conn = mysqli_connect($sname, $username, $password, $dbname);

							if(!$conn){
								die("Connection Failed: " . mysqli_connect_error());
							}

							$sql = "SELECT P.Healthnum, P.pName,P.Housenum, A.Street, A.Pcode
								FROM Region R NATURAL JOIN Resides R1, LocateAt L, PersonLivesAt P, Patient P1, VaccinateWith V,Vaccine V2, ProtectsAgainst P2, Virus V3, Address A 
										WHERE R1.Vacsitenum = L.Vacsitenum AND L.Housenum = A.Housenum AND A .Housenum = P.Housenum AND P.Healthnum = P1.Patientid AND P1.Patientid = V.Patientid AND V.Vid = V2.Vid AND V2.Vid = P2.Vid AND P2.Virusnum = V3.Virusnum AND V3.Vname = 'ETCC Disease' AND R.Regionnum=" . $selected_region;
							$result = $conn->query($sql);

							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<tr>";
									echo "<td>" . $row["Healthnum"] . "</td>";
									echo "<td>" . $row["pName"] . "</td>";
									echo "<td>" . $row["Housenum"] . " " . $row["Street"] . " " . $row["Pcode"] .  "</td>";
									echo "</td>";
								}
							}else{
								
							}

							$conn->close();
						}
					?>
					</tbody>
				</table>
				<table class="table table-bordered">
					<tr>
						<thead>
							<th>Healthnum</th>
							<th>Person Name</th>
							<th>Address</th>
						</thead>
					</tr>
					<tbody>
					<?php
						if(isset($_POST['selected'])){
							

							$selected_region = $_POST['Regions'];
							echo "<tr>Region id: " . $selected_region . " who have received a vaccine against ETCC in the past two weeks<br></tr>";
							

							$sname = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "PRD";

							$conn = mysqli_connect($sname, $username, $password, $dbname);

							if(!$conn){
								die("Connection Failed: " . mysqli_connect_error());
							}

							$sql = "SELECT P.Healthnum, P.pName,P.Housenum, A.Street, A.Pcode 
									FROM Region R NATURAL JOIN Resides R1, LocateAt L, PersonLivesAt P, Patient P1, VaccinateWith V,Vaccine V2, ProtectsAgainst P2, Virus V3, Address A 
										WHERE R1.Vacsitenum = L.Vacsitenum AND L.Housenum = A.Housenum AND A .Housenum = P.Housenum AND P.Healthnum = P1.Patientid AND P1.Patientid = V.Patientid AND V.Vid = V2.Vid AND V2.Vid = P2.Vid AND P2.Virusnum = V3.Virusnum AND V3.Vname = 'ETCC' AND (DATEDIFF(CURDATE(), V.VacDate))> 1 AND (DATEDIFF(CURDATE(), V.VacDate)) < 14 AND R.Regionnum=" . $selected_region;
							$result = $conn->query($sql);

							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<tr>";
									echo "<td>" . $row["Healthnum"] . "</td>";
									echo "<td>" . $row["pName"] . "</td>";
									echo "<td>" . $row["Housenum"] . " " . $row["Street"] . " " . $row["Pcode"] .  "</td>";
									echo "</td>";
								}
							}else{
								
							}

							$conn->close();
						}
					?>
					</tbody>
				</table>
				<table class="table table-bordered">
					<tr>
						<thead>
							<th>Healthnum</th>
							<th>Person Name</th>
							<th>Address</th>
						</thead>
					</tr>
					<tbody>
					<?php
						if(isset($_POST['selected'])){
							

							$selected_region = $_POST['Regions'];
							echo "<tr>Region id: " . $selected_region . " who have net yet received a vaccine against ETCC<br></tr>";
							

							$sname = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "PRD";

							$conn = mysqli_connect($sname, $username, $password, $dbname);

							if(!$conn){
								die("Connection Failed: " . mysqli_connect_error());
							}

							$sql = "SELECT pla.Healthnum, pla.pName, ad.Housenum, ad.Street, ad.Pcode 
									FROM PersonLivesAt pla NATURAL JOIN Address ad NATURAL JOIN LocateAt la NATURAL JOIN Resides Res NATURAL JOIN Region Reg
									WHERE  pla.Healthnum NOT IN (SELECT P.Healthnum
						    									FROM Region R NATURAL JOIN Resides R1, LocateAt L, PersonLivesAt P, Patient P1, VaccinateWith V,Vaccine V2, ProtectsAgainst P2, Virus V3, Address A 
																WHERE R1.Vacsitenum = L.Vacsitenum AND L.Housenum = A.Housenum AND A .Housenum = P.Housenum AND P.Healthnum = P1.Patientid AND P1.Patientid = V.Patientid AND V.Vid = V2.Vid AND V2.Vid = P2.Vid AND P2.Virusnum = 												V3.Virusnum AND V3.Vname = 'ETCC Disease') AND Reg.Regionnum = " . $selected_region;
							$result = $conn->query($sql);

							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<tr>";
									echo "<td>" . $row["Healthnum"] . "</td>";
									echo "<td>" . $row["pName"] . "</td>";
									echo "<td>" . $row["Housenum"] . " " . $row["Street"] . " " . $row["Pcode"] .  "</td>";
									echo "</td>";
								}
							}else{
								
							}

							$conn->close();
						}
					?>
					</tbody>
				</table>
				<table class="table table-bordered">
					<tr>
						<thead>
							<th>Person Name</th>
						</thead>
					</tr>
					<tbody>
					<?php
						if(isset($_POST['selected'])){
							

							$selected_region = $_POST['Regions'];
							echo "<tr>Region id: " . $selected_region . "  show the names of their supervisors<br></tr>";
							

							$sname = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "PRD";

							$conn = mysqli_connect($sname, $username, $password, $dbname);

							if(!$conn){
								die("Connection Failed: " . mysqli_connect_error());
							}

							$sql = "SELECT DISTINCT pla.pName  
									FROM Region R, Resides Res, VacSite Vac, Supervises sup, PHE ph, PersonLivesAt pla 
								WHERE Res.Vacsitenum = Vac.Vacsitenum AND Vac.Vacsitenum = sup.Vacsitenum AND sup.PHEid = ph.PHEid AND ph.PHEid = pla.Healthnum AND Res.Regionnum =" . $selected_region;
							$result = $conn->query($sql);

							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<tr>";
									echo "<td>" . $row["pName"] . "</td>";
									echo "</td>";
								}
							}else{
								
							}

							$conn->close();
						}
					?>
					</tbody>
				</table>
				<table class="table table-bordered">
					<tr>
						<thead>
							<th>Number of Patients</th>
						</thead>
					</tr>
					<tbody>
					<?php
						if(isset($_POST['selected'])){
							

							$selected_region = $_POST['Regions'];
							echo "<tr>Region id: " . $selected_region . "  how many patients still require vaccination against ETCC<br></tr>";
							

							$sname = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "PRD";

							$conn = mysqli_connect($sname, $username, $password, $dbname);

							if(!$conn){
								die("Connection Failed: " . mysqli_connect_error());
							}

							$sql = "SELECT COUNT(*) AS 'NumberofPatients'
										FROM Patient pa 
										WHERE pa.Patientid NOT IN (SELECT pat.Patientid 
																   FROM Resides res, Vacsite vas, LocateAt la, Address ads, PersonLivesAt pla, Patient pat, VaccinateWith vw, Vaccine vac, ProtectsAgainst pag, Virus vi 
																   WHERE res.Vacsitenum = vas.Vacsitenum AND vas.Vacsitenum = la.Vacsitenum AND la.Housenum = ads.Housenum AND ads.Housenum = pla.Housenum AND pla.Healthnum = pat.Patientid AND pat.Patientid = vw.Patientid AND vw.Vid = vac.Vid AND vac.vid = pag.vid AND pag.Virusnum = vi.Virusnum AND vi.Vname = 'ETCC Disease' AND res.Regionnum = '" . $selected_region . "')";
							$result = $conn->query($sql);

							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<tr>";
									echo "<td>" . $row["NumberofPatients"] . "</td>";
									echo "</td>";
								}
							}else{
								
							}

							$conn->close();
						}
					?>
					</tbody>
				</table>


			</div>
		</div>
	</div>
</body>
</html>
