<?php ob_start();
	session_start();
	unset($_SESSION['sess_user']);
	session_destroy();
	header("Location: index.php");
?>