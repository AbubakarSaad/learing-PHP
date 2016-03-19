<?php ob_start();?>
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
		<h4>Select a Region</h4>
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
							<th>Vaccinate Site Name</th>
							<th>Address</th>
						</thead>
					</tr>
					<tbody>
					<?php
						if(isset($_POST['selected'])){
							

							$selected_region = $_POST['Regions'];
							echo "<tr>Region id: " . $selected_region . " Vaccination Site and Address<br>" ;
							

							$sname = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "PRD";

							$conn = mysqli_connect($sname, $username, $password, $dbname);

							if(!$conn){
								die("Connection Failed: " . mysqli_connect_error());
							}

							$sql = "SELECT vac.VsName, ads.Housenum, ads.Street, ads.Pcode
									FROM VacSite vac, LocateAt la, Address ads, Resides re
									WHERE re.Vacsitenum = vac.Vacsitenum AND vac.Vacsitenum = la.Vacsitenum AND la.Housenum = ads.Housenum AND re.Regionnum=". $selected_region;
							$result = $conn->query($sql);

							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<tr>";
									echo "<td>" . $row["VsName"] . "</td>";
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
							<th>Vaccinate Site Name</th>
							<th>Address</th>
						</thead>
					</tr>
					<tbody>
					<?php
						if(isset($_POST['selected'])){
							

							$selected_region = $_POST['Regions'];
							echo "<tr>Region id: " . $selected_region . " <br>" ;
							

							$sname = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "PRD";

							$conn = mysqli_connect($sname, $username, $password, $dbname);

							if(!$conn){
								die("Connection Failed: " . mysqli_connect_error());
							}

							$sql = "SELECT P.pName, P.Healthnum FROM Region R, Resides R2,VacSite V, LocateAt L, Address A, PersonLivesAt P WHERE R.Regionnum = R2.Regionnum AND R2.Vacsitenum = V.Vacsitenum AND V.Vacsitenum = L.Vacsitenum AND L.Housenum = A.Housenum AND A.Housenum = P.Housenum AND P.Alive='Alive' AND R2.Regionnum =". $selected_region;
							$result = $conn->query($sql);

							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<tr>";
									echo "<td>" . $row["pName"] . "</td>";
									echo "<td>" . $row["Healthnum"] . "</td>";
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
	

</body>
</html>
