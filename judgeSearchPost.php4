<?php
	session_start();
	if(isset($_POST))  
		$_SESSION['judgeSearchString'] = $_POST['judgeSearchString'];
	else
		$_SESSION['judgeSearchString'] = "";
	header("Location: judgeSearch.php4", true, 303);
	exit();
?>
