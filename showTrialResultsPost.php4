<?php
	session_start();

	if(! isset($_GET) || ! isset($_GET['eventId'] )) { 
		header("Location: errorPage.php4", true, 303);
	} else {
		header("Location: showTrialResults.php4?eventId=".$_GET['eventId']);
	}

/*
	if(! isset($_POST) || ! isset($_POST['eventId'] )) { 
		$_SESSION['eventId'] = false;
		header("Location: errorPage.php4", true, 303);
	} else {
		$_SESSION['eventId'] = $_POST['eventId'];
	}
	header("Location: showTrialResults.php4", true, 303);
	exit();
*/

?>
