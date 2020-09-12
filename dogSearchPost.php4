<?php

	//	foreach( array_keys($_POST) as $key ) {
	//		print "<br>$key -> $_POST[$key]";
	//	}
 
	session_start();
	if(! isset($_POST) || isset($_POST['clear' ])) { 
		$_SESSION['dogSearchString'] = false;
		$_SESSION['ownerSearchString'] = false;
		$_SESSION['breed'] = false;
		$_SESSION['hasPosted'] = false;
	} else {
		$_SESSION['dogSearchString'] = $_POST['dogSearchString'];
		$_SESSION['ownerSearchString'] = $_POST['ownerSearchString'];
		$_SESSION['breed'] = $_POST['breed'];
		$_SESSION['hasPosted'] = true;
	}
	//print "<br>ds: ".$_SESSION['dogSearchString'];
	//print "<br>os: ".$_SESSION['ownerSearchString'];
	//print "<br>br: ".$_SESSION['breed'];
	header("Location: dogSearch.php4", true, 303);
	exit();
?>
