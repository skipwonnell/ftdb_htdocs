<?php
include 'initPhp.php';
include 'getConnection.php';
include 'header.html';
$conn = getConnection(); if ( systemIsBusy($conn) == true ) exit();
$eventSearchString = $_SESSION["eventSearchString"];
?>

<html>
<title>Event Search</title>
</body>
</html>
<p>
<p>

<table border=0 cellpadding=20>
<tr>
<td rowspan=2 valign='top' width=270> 
<FORM action='eventSearchPost.php4' method='post'>
Club Name: 

<?php
	if ( $eventSearchString == false )
		print "<INPUT name='eventSearchString' type='text'/>";
	else
		print "<INPUT name='eventSearchString' type='text' value='".$eventSearchString."'/>";
	print "<p>";
	include "searchHelp.html";
?>
</FORM>
</td>
<td > 

<?php
if ( $eventSearchString == false )
{
	$maximum=100;
	$dateFlag = 0;
	print "<h2>last $maximum events entered... </h2>";
}
else
{
	$dateFlag = 1;
	$maximum=100;
}
listSomeTrials($conn, $eventSearchString, $maximum, $dateFlag);
?>

</td> </tr> </table>
</body>
</html>
