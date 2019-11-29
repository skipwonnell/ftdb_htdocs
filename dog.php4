
<?php

include 'getConnection.php';
include 'utils.php';


$conn=getConnection(); if ( systemIsBusy($conn) == true ) exit();


$dog_nfid = $_GET["id"];


if( $dog_nfid != null )
{
	$query = "SELECT *, DATE_FORMAT(dateOfBirth, '%b %d, %Y') bd FROM nf_dog where nfid = $dog_nfid";
	$result = mysqli_query($conn, $query) or DIE("Could not Execute Query ");
	//$result = mysqli_query($conn, $query) or DIE($query." Could not Execute Query ");

}
else
{
	$akcNum = $_GET["akcNumber"];
	if( $akcNum != null )
	{
		$query = "SELECT *, DATE_FORMAT(dateOfBirth, '%b %d, %Y') bd FROM nf_dog where akcNumber = '$akcNum'";
//		$result=mysqli_query($conn, $query) or DIE ($query." Could not Execute Query ");
		$result=mysqli_query($conn, $query) or DIE (" Could not Execute Query ");
	}
}

$row = mysqli_fetch_array($result);

if( $row == null )
{
	print "NOT FOUND";
	return;
}

$akcNumber = $row{'akcNumber'};
$dog_nfid = $row{'NFID'};



$hasOwnerInfo = 0;
$callName = "";
$url = "";
$akcTitles = "";
$sireNfid = 0;
$damNfid = 0;
$email = "";


if ($result) {

	$query7 = "SELECT * FROM dogInfo where akcNumber = '$akcNumber'";
	$result7 = mysqli_query($conn, $query7) or DIE("Could not Execute Query ");
	
	$otherTitles="";
	$backTitles="";
	if( $result7  && $row7 = mysqli_fetch_array($result7))
	{
		$hasOwnerInfo = 1;
		$callName = trim($row7{'callName'});
		$url = trim($row7{'url'});
		$akcTitles = trim($row7{'akcTitles'});
		$otherTitles = trim($row7{'otherTitles'});
		$backTitles = trim($row7{'backTitles'});
		if ( strncmp($akcTitles, "none", 4) == 0 )
			$akcTitles = "";
		$email = $row7{'email'};
		$sireAkcNumber = $row7{'sireAkcNumber'};
		$damAkcNumber = $row7{'damAkcNumber'};
		$sireName = trim(getNameWithTitles($conn, $sireAkcNumber));
		$sireNfid = getNfid($conn, $sireAkcNumber);
		$damName = trim(getNameWithTitles($conn, $damAkcNumber));
		$damNfid = getNfid($conn, $damAkcNumber);
	}

$nameHttp=getNameHttp($row7,$row{'registeredName'});

$display=getTitleDisplay($akcTitles, $otherTitles);
if(strlen($display)>0) $display=$display." ";
$display=$display.$row{'registeredName'}." ".$backTitles;

print "<html>\n";
print "<head><meta name=\"description\" content=\"AKC Field Trial results for ".$display."\"/></head>\n";
print "<body>\n";
print "<title>".$display."</title>\n";

include 'header.html';



?>

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


$bw=0;


print "&nbsp<br>";
print "<table border=0 width=100% cellpadding=0>";
print "<tr><td colspan=2 align=center valign=center>";
print "<font size=5><b>";

print $nameHttp;
print "</td></tr>";
print "<tr><td colspan=2 align=center>";
print "<br>&nbsp<br>";
print "</td></tr>";



print "<tr><td align=center>";
print "<table  cellspacing=2 cellpadding=2 border=0>";


print "<tr><td align=left valign=top>Breed</td><td>  ".$row{'breed'}; 
print "</td></tr>";
print "<tr> <td onclick=\"postit('".$row{'akcNumber'}."')\" align=left>AKC Number</td> <td>    ".$row{'akcNumber'}."  </td></tr>";
print "<tr><td align=left valign=top>Owner(s)</td><td>  ".$row{'owners'}; 
print "</td></tr>";
print "<tr><td colspan=2>";
$breed=$row{'breed'};
$akcNumber = $row{'akcNumber'};
print "</table>";
print "</td>";

print "<td align=center valign=top >";
print "<table border=0 height=100%>";

print "<tr><td align=center colspan=2>";
//print "<table width=100% bgcolor='EEEEFF' border=1 cellspacing=0>";


//print "<tr><td align=center>";

$rn = urlencode($row{'registeredName'});

//print "</td></tr> ";
//print "</table>";



//if( $hasOwnerInfo > 0 )
if(false)
{
if( strlen($callName) > 0  )
{
	print "<tr><td>Call Name&nbsp&nbsp&nbsp&nbsp</td><td>$callName";

	if( strlen($url) > 0 )
	{
		print "&nbsp&nbsp&nbsp(<a href=$url target=_blank>see home page</a>)";
	}
		
	print "</td></tr>";
}
else
{
	if( strlen($url) > 0 )
	{
		print "<tr><td>Home Page";
		print "</td><td>";
		print "<a href=$url target=_blank>$url</a>";
		print "</td></tr>";
	}
}


if( strlen($sireName) > 0 )
{
	if( $sireNfid > 0 )
		print "<tr><td>Sire&nbsp&nbsp&nbsp&nbsp </td><td><a href=dog.php4?id=$sireNfid>$sireName<a></td></tr>";
	else
	{
		print "<tr><td>Sire&nbsp&nbsp&nbsp&nbsp </td><td><a href=offSpring.php4?id=$sireAkcNumber>$sireName<a> ";
		print "<font size=1> (offspring only) </font></td></tr>";
	}
}

if( strlen($damName) > 0 )
{
	if( $damNfid > 0 )
		print "<tr><td>Dam&nbsp&nbsp&nbsp&nbsp </td><td><a href=dog.php4?id=$damNfid>$damName<a></td></tr>";
	else
	{
		print "<tr><td>Dam&nbsp&nbsp&nbsp&nbsp </td><td><a href=offSpring.php4?id=$damAkcNumber>$damName<a> ";
		print "<font size=1> (offspring only) </font></td></tr>";
	}
}

}

print "</table>";
print "</table>";








$query2 = "SELECT nf_placement.* FROM nf_placement,nf_stake,nf_trial where nf_placement.dog_nfid = $dog_nfid and nf_stake.nfid = nf_placement.stake_nfid and nf_trial.nfid = nf_stake.event_nfid order by nf_trial.startDate desc";
$result2 = mysqli_query($conn, $query2) or DIE("Could not Execute Query ");


print "<p><center><b> ".mysqli_num_rows($result2)." PLACEMENTS </b><p>";

print "<center><table border=1 cellpadding=2>";

echo "<tr><td align='center'><b>";
echo "Club</td><td align='center'><b>Date</td><td align='center'><b>Stake</td><td align='center'><b>Place</td>";
echo "<td align='center'><b>Starters</b></td>";
echo "<td align='center' colspan='2'><b>Judges</b></td>";
echo "</tr></b>";

while ($row2 = mysqli_fetch_array($result2))
{
    $stake_nfid = $row2{'stake_nfid'};
    $placement = $row2{'placement'};


    $query3 = "SELECT nf_stake.*, nf_trial.startDate FROM nf_stake,nf_trial where nf_stake.nfid = $stake_nfid and nf_trial.nfid = nf_stake.event_nfid order by nf_trial.startDate desc";
    $result3 = mysqli_query($conn, $query3) or DIE("Could not Execute query ");
$row3 = mysqli_fetch_array($result3);


    $event_nfid = $row3[1];
    $stake = $row3[4];
   
    $query5 = "SELECT * FROM nf_trial where nfid = $event_nfid";
    //$result5 = mysqli_query($conn, $query5) or DIE("Could not Execute query ".$query5);
    $result5 = mysqli_query($conn, $query5) or DIE("Could not Execute query ");
	$row5 = mysqli_fetch_array($result5);




 	$cn = str_replace("German Shorthaired Pointer", "GSP", $row5{'clubName'});
  $cn = str_replace("German Wirehaired Pointer", "GWP", $cn);

    $startDate = $row5{'startDate'};

    print "<tr><td><a href='showTrialResults.php4?id=$event_nfid'>";
	print $cn."</a></td><td>$startDate</td>";
    // print "<td><a href=stake.php?id=$stake_nfid>$stake</a></td><td align=center>$placement</td>";

	$starters = getStarters($conn, $stake_nfid);

	if( $starters == 1 ) $starters = "?";

    print "<td align='center'> $stake </td><td align='center'> $placement </td>";
	 print "<td align='center'>  $starters</td>";
	

	$judgeA=getJudge($conn, $row3[2]);
	$judgeB=getJudge($conn, $row3[3]);

 	if ( strlen($judgeA{'firstName'}) > 0 && strlen($judgeA{'lastName'}) > 0 )
		$j1str = $judgeA{'firstName'}." ".$judgeA{'lastName'};
	else
		$j1str = $judgeA{'akcName'};

 	if ( strlen($judgeB{'firstName'}) > 0 && strlen($judgeB{'lastName'}) > 0 )
		$j2str = $judgeB{'firstName'}." ".$judgeB{'lastName'};
	else
		$j2str = $judgeB{'akcName'};

	print "<td align='left'>";
	print "<a href=judgeList.php4?id=".$judgeA{'NFID'}.">".$j1str."</a>";
	print "</td><td align='left'>";
	print "<a href=judgeList.php4?id=".$judgeB{'NFID'}.">".$j2str."</a>";
	print "</td";
	
	print "</tr>";

	
}
  print "</table>";
} 

$count=0;

print "<p> &nbsp <br><p>";

function getNameWithTitles($conn, $akcNumber)
{
	$query1 = "select * from dogInfo where akcNumber = '$akcNumber'";
	$result1 = mysqli_query($conn, $query1) or DIE("Could not Execute query ");
	//$result1 = mysqli_query($conn, $query1) or DIE("Could not Execute query ".$query1);
	$row1 = mysqli_fetch_array($result1);


	$query = "select * from nf_dog where akcNumber = '$akcNumber'";
	$result = mysqli_query($conn, $query) or DIE("Could not Execute query ");
	//$result = mysqli_query($conn, $query) or DIE("Could not Execute query ".$query);
	$row = mysqli_fetch_array($result);

	$registeredName = "";
	if( $row ) $registeredName = trim($row{'registeredName'});
	else if( $row1 ) $registeredName = trim($row1{'registeredName'});

	return getNameHttp($row1, $registeredName);

}

function getNfid($conn, $akcNumber)
{
	$query = "select * from nf_dog where akcNumber = '$akcNumber'";
	$result = mysqli_query($conn, $query) or DIE("Could not Execute query ");
	//$result = mysqli_query($conn, $query) or DIE("Could not Execute query ".$query);
	$row = mysqli_fetch_array($result);

	if( $row ) return $row{'NFID'};
	else return 0;
}
    
?>
<?php include 'trailer.html' ?>

