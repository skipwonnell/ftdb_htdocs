<?php
include 'initPhp.php';
include 'getConnection.php';
include 'header.html';

$conn = getConnection(); 

$searchString = "";
if ( isset($_POST["searchString"]) )
	$searchString = $_POST["searchString"];


?>

<html>
<title>Judge Search</title>

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

	$searchString = "%".addslashes($searchString)."%";

	$query = "SELECT firstName, lastName, akcName, nfid FROM nf_judge"; 
	$query = $query." where concat(firstName,lastName) like ? or akcName like ? order by akcName";

	$stmt = $conn->prepare($query);
	$stmt->bind_param('ss', $searchString, $searchString);
	$stmt->execute();
	$result = $stmt->get_result();


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


		print "<a href=judgeList.php4?id=".encryptIt($nfid).">".$name."</a>";

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

