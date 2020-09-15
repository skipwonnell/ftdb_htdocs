<?php
	session_start();
	if(! isset($_POST) || ! isset($_POST['judgeId'] )) { 
		$_SESSION['judgeId'] = false;
		header("Location: errorPage.php4", true, 303);
	} else {
		$_SESSION['judgeId'] = $_POST['judgeId'];
	}
	header("Location: judgeList.php4", true, 303);
	exit();
?>
