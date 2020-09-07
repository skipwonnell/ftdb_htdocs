<?php session_start() ?>

<html>
<title>Judge Search</title>

<?php

include 'getConnection.php';
include 'utils.php';
include 'header.html';

$conn = getConnection(); 
//if ( systemIsBusy($conn) == true ) exit();


$searchString = "";
if ( isset($_POST["searchString"]) )
	$searchString = $_POST["searchString"];


?>

</body>
</html>


<p>
<p>

<table border=0 cellpadding=20>
<tr>
<td rowspan=2 valign='top' width=270> 

<FORM action='judgeSearch.php4' method='post'>

Judge Name: 


<?php
if ( $searchString == false )
	print "<INPUT name='searchString' type='text'/>";
else
	print "<INPUT name='searchString' type='text' value='".$searchString."'/>";

print "<p>";

include "searchHelp.html";

//$rv = getHits($_SERVER['REQUEST_URI']); 
//print "<p>(".$rv." hits)";

?>

</FORM>
</td>

<td > 


<?php


	if( $searchString != false)
	{

	print "<style type='text/css'>";
	print "td { padding-left: 4}";
	print "td { padding-right: 4}";
	print "</style>";

	print "<table border='0'>";


	$query = "SELECT firstName, lastName, akcName, nfid FROM nf_judge"; 


	$query = $query." where concat(firstName,lastName) like '%".addslashes($searchString)."%' or akcName like '%".addslashes($searchString)."%' order by akcName";



	$result = mysqli_query($conn, $query) or DIE($query." failed: ".mysqli_error());


	$maximum = 50;
	$i =  0;
	while  ( ($row = mysqli_fetch_array($result) )  && $i < $maximum)
	{ 
		$i++; 
		print "<tr><td>&#160</td>";

		$firstName = $row{'firstName'};
		$lastName = $row{'lastName'};
		$akcName = $row{'akcName'};
		$nfid = $row{'nfid'};

		print "<td>";
		if( strlen($firstName) > 0 && strlen($lastName) > 0 )
		{
			$name = $lastName.", ".$firstName;
		}
		else
			$name = $akcName;


		print "<a href=judgeList.php4?id=$nfid>".$name."</a>";

		print "</td></tr>";

	}
	if ( $i == 0 )
	{
		print "<tr><td>&#160</td><td>No judges found</td></tr>";
	}
	print "</table>";
	}

?>


</html>

