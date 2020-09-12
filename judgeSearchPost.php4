<?php
	session_start();
	if(! isset($_POST) || isset($_POST['clear' ])) 
		$_SESSION['judgeSearchString'] = "";
	else
		$_SESSION['judgeSearchString'] = $_POST['judgeSearchString'];
	header("Location: judgeSearch.php4", true, 303);
	exit();
?>
