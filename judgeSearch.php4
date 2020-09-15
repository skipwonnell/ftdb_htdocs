<?php
include 'initPhp.php';
include 'getConnection.php';
include 'header.html';
$conn = getConnection(); if ( systemIsBusy($conn) == true ) exit();
$judgeSearchString = $_SESSION["judgeSearchString"];

//if( isset($_GET) ) {  // TODO : why doesn't this work when selected from the header
	//header("refresh:0;url=errorPage.php4?errorId=2005");
//}

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
<FORM action='judgeSearchPost.php4' method='post'>

<table border=0>
<tr><td>
Judge Name: 
</td><td>
<?php
	if ( $judgeSearchString == false )
		print "<INPUT name='judgeSearchString' type='text'/>";
	else
		print "<INPUT name='judgeSearchString' type='text' value='".$judgeSearchString."'/>";
?>
</td></tr>
<tr><td>
</td><td>
<input type='submit' name='search' value='search'>
<input type='submit' name='clear' value='clear'>
</td></tr><td colspan=2>
&#160<br>
<?php include "searchHelp.html"; ?>
</tr></td></tr>
</table>
</FORM>
</td>

<td valign="top"> 

<?php
	if( $judgeSearchString != false)
	{
	print "<table border='0'>";

	$judgeSearchString = "%".addslashes($judgeSearchString)."%";

	$query = "SELECT firstName, lastName, akcName, nfid FROM nf_judge"; 
	$query = $query." where concat(firstName,lastName) like ? or akcName like ? order by akcName";

	$stmt = $conn->prepare($query);
	$stmt->bind_param('ss', $judgeSearchString, $judgeSearchString);
	$stmt->execute();
	$result = $stmt->get_result();

	$maximum = 50;
	$i =  0;
	print "<form action='judgeListPost.php4' method='post'>";
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


		//print "<a href=judgeList.php4?id=".encryptIt($nfid).">".$name."</a>";

		print "<button type='submit' class='db-link'".
		    "name='judgeId'".
    		"value='".encryptIt($nfid)."'>".$name." </button>";


		print "</td></tr>";

	}
	if ( $i == 0 )
	{
		print "<tr><td>&#160</td><td>No judges found</td></tr>";
	}
	print "</form></table>";
	}
?>

</td> </tr> </table>
</body>
</html>
