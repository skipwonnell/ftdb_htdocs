<?php
	session_start();
	if(! isset($_POST) || isset($_POST['clear' ])) { 
		$_SESSION['eventSearchString'] = false;
	} else {
		$_SESSION['eventSearchString'] = $_POST['eventSearchString'];
	}
	header("Location: eventSearch.php4", true, 303);
	exit();
?>
