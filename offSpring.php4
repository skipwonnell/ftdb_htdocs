<html>

<SCRIPT>

function postit(akcNumber)
{
	var aForm = document.createElement('form');
	aForm.action = "http://www.akc.org/store/reports/index.cfm"; aForm.method='GET';
	var aElement = document.createElement("input");
	aElement.name = 'dog_id'; aElement.type = 'hidden';
	aElement.value = akcNumber; aForm.appendChild(aElement);
	document.getElementsByTagName('body')[0].appendChild(aForm);
	aForm.submit();
}

</SCRIPT>


<?php

include 'getConnection.php';
include 'utils.php';
include 'header.html';


$conn = getConnection(); if ( systemIsBusy($conn) == true ) exit();


$akcNumber = $_GET["id"];

$name = getNameWithTitles($conn, $akcNumber);


print "<center>";
print "<p>";

print "<table>";
print "<tr> <td onclick=\"postit('".$akcNumber."')\" align=left><h2>".$name."</h2></td></tr>";

if( $akcNumber == "SE399411" )
{
	print "<tr><td align='center'>Owners: Joe Bunk/Kitty Pullen</td><tr>";
}


print "</table>";

print "&nbsp <br>";

print "<table><tr><td colspan=2 align=center>";

print "<table><tr><td align=center>";

print "<b>Offspring in this database</b></td></tr>";

print "<tr><td align=center style='font-size:smaller'>(based on owner supplied info)</td></tr>"; 

print "</td></tr></table>";


$query = "select * from dogInfo where sireAkcNumber = '$akcNumber' or damAkcNumber = '$akcNumber' order by akcNumber" ;
$result = mysqli_query($conn, $query) or DIE("Could not Execute query ".$query);
while ($row = mysqli_fetch_array($result))
{
	$parentAkcNumber = $row{'akcNumber'};
	$akcTitles = trim($row{'akcTitles'});
	if(strncmp($akcTitles, "none", 4) == 0 ) $akcTitles = "";
	$nameHttp=getNameHttp($row, $row{'registeredName'});
	$query2 = "select NFID, registeredName from nf_dog where akcNumber = '$parentAkcNumber'";
	$result2 = mysqli_query($conn, $query2);
	while ($row2 = mysqli_fetch_array($result2) )
	{
		$count=1;
		$nfid = $row2{'NFID'};
		print "<tr><td><a href=dog.php4?id=".$nfid.">".$nameHttp."</a>";

		print "</td><td>";

		if( strcmp(trim($row{'sireAkcNumber'}), trim($akcNumber))  == 0 )
			$parent2AkcNumber = $row{'damAkcNumber'};
		else
			$parent2AkcNumber = $row{'sireAkcNumber'};

		$q3 = "select * from dogInfo where akcNumber = '".$parent2AkcNumber."'";
		$r3 = mysqli_query($conn, $q3);
		$row3 = mysqli_fetch_array($r3);
		if( $row3 )
		{

			$akcTitles = $row3{'akcTitles'};
			if(strncmp($akcTitles, "none", 4) == 0 ) $akcTitles = "";
			if(strlen(trim($akcTitles)) > 0 )
				$akcTitles = "<I>$akcTitles</I> ";
			else
				$akcTitles = "";
			$nameHttp = getNameHttp($row3, $row3{'registeredName'});

			$q4 = "select NFID, registeredName from nf_dog where akcNumber = '$parent2AkcNumber'";
			$r4 = mysqli_query($conn, $q4);

			if( $row4 = mysqli_fetch_array($r4) )
			{
				print "(by <a href=dog.php4?id=".$row4{'NFID'}.">".$nameHttp."</a>)";
			}
			else
			{
				print "(by ".$nameHttp.")";
			}
		}

		print "</td></tr>";
	}

}

if ($count == 0)
{
	print "<tr><td><b><center>-- none found --</b></center></td></tr>";
}

print "</table>";



function getNameWithTitles($conn, $akcNumber)
{
	$query1 = "select * from dogInfo where akcNumber = '$akcNumber'";
	$result1 = mysqli_query($conn, $query1) or DIE("Could not Execute query ".$query1);
	$row1 = mysqli_fetch_array($result1);


	$query = "select * from nf_dog where akcNumber = '$akcNumber'";
	$result = mysqli_query($conn, $query) or DIE("Could not Execute query ".$query);
	$row = mysqli_fetch_array($result);

	if( $row1 )
		return getNameHttp($row1, $row1{'registeredName'});
	else
		return $row{'registeredName'};

	/*
	$registeredName = "";
	if( $row ) $registeredName = trim($row{registeredName});
	else if( $row1 ) $registeredName = trim($row1{registeredName});

	$akcTitles = "";
	if( $row1 ) $akcTitles = trim($row1{akcTitles});
	if(strncmp($akcTitles, "none", 4) == 0 ) $akcTitles = "";

	if( strlen($akcTitles) > 0 )
	{
		$akcTitles = "<I>".$akcTitles."</I> ";
	}

	return $akcTitles.$registeredName;
	*/
}

function getNfid($akcNumber)
{
	$query = "select * from nf_dog where akcNumber = '$akcNumber'";
	$result = mysqli_query($conn, $query) or DIE("Could not Execute query ".$query);
	$row = mysqli_fetch_array($result);

	if( $row ) return $row{'NFID'};
	else return 0;
}
    
?>

</html>
