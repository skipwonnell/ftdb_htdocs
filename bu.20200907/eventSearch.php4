<?php session_start() ?>
<html>
<title>Event Search</title>

<?php

include 'getConnection.php';
include 'utils.php';
include 'header.html';

$conn = getConnection(); if ( systemIsBusy($conn) == true ) exit();

$searchString = "";
if( isset($_POST["searchString"])  )
	$searchString = $_POST["searchString"];


?>

</body>
</html>


<p>
<p>

<table border=0 cellpadding=20>
<tr>
<td rowspan=2 valign='top' width=270> 

<FORM action='eventSearch.php4' method='post'>

Club Name: 


<?php
if ( $searchString == false )
	print "<INPUT name='searchString' type='text'/>";
else
	print "<INPUT name='searchString' type='text' value='".$searchString."'/>";

print "<p>";

include "searchHelp.html";
/*
$rv = getHits($_SERVER['REQUEST_URI']); 
print "<p>(".$rv." hits)";
*/
?>

</FORM>
</td>

<td > 


<?php
if ( $searchString == false )
{
	$maximum=150;
	$dateFlag = 0;
	print "<h2>last 150 events entered... </h2>";
}
else
{
	$dateFlag = 1;
	$maximum=9999;
}





listSomeTrials($conn, $searchString, $maximum, $dateFlag);

?>

</td>

</tr>



</body>
</html>
