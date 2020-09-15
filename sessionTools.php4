<style type="text/css">
:root {
	 --main-link-color: green;
	}
button.db-link { background:none;border:none;color:var(--main-link-color); }
button.db-link-bold { background:none;border:none;color:var(--main-link-color);font-weight:bold; }
button.db-button { color:black }
</style>

<br>

<?php
	$url = $_SERVER['REQUEST_URI'];
	print $url;
	print "<br>";


	session_start();

	print_r($_SESSION);
	print "<br>";
	print_r($_POST);

	if(! isset($_SESSION['dates'] )) {
		$_SESSION['dates']=array();
	} 
	$dates=$_SESSION['dates'];

 	$dates[] = time();
	print "<br>SIZE: ".sizeof($dates);

	while ( sizeof($dates) >  10 ) {
		array_shift($dates);
	}

	foreach (array_keys($dates) as $key ) {
		print "<br>Key: $key -->  $dates[$key]";
	}

	$_SESSION['dates'] = $dates;

	$first = $dates[0];
	$last  = $dates[sizeof($dates) - 1];
	$elapsed = $last - $first;

	print "<br> done, elapsed = $elapsed";
?>


<?php
	$TODO="todo";
	print "<form style='margin:0px;padding:0px' action='$todopurple' method='post'>";
	//print "<form action='$todo' method='post'>";

	print "<button type='submit' class='db-link'".
	"name='eventId'".
	"value='".encryptIt($todo)."'>".$todo." </button>";
?>
<br>
	<button type="submit" class="db-link-bold" name="id" value="satch">Snow Ridge Struttin Lil Man </button>
<br>
	<button type="submit" class="db-button">Refresh</button>
<br>
</form>

