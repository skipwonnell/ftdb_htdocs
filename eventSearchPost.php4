<?php
	session_start();
	if(isset($_POST))  
		$_SESSION['eventSearchString'] = $_POST['eventSearchString'];
	else
		$_SESSION['eventSearchString'] = "";
	header("Location: eventSearch.php4", true, 303);
	exit();
?>
