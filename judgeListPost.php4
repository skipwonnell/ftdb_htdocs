<?php
	 session_start();

//print_r($_GET); exit();

	if(! isset($_GET) || ! isset($_GET['judgeId'] )) { 
		header("Location: errorPage.php4", true, 303);
	} else {
		header("Location: judgeList.php4?judgeId=".$_GET['judgeId']);
	}

/*
	if(! isset($_POST) || ! isset($_POST['judgeId'] )) { 
		$_SESSION['judgeId'] = false;
		header("Location: errorPage.php4", true, 303);
	} else {
		$_SESSION['judgeId'] = $_POST['judgeId'];
	}
	header("Location: judgeList.php4", true, 303);
 */
	exit();
?>
