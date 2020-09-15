<?php
	session_start();
	if(! isset($_POST) || ! isset($_POST['dogId'] )) { 
		$_SESSION['dogId'] = false;
		header("Location: errorPage.php4", true, 303);
	} else {
		$_SESSION['dogId'] = $_POST['dogId'];
	}
	header("Location: dog.php4", true, 303);
	exit();
?>
