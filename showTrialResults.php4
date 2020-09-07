<?php session_start() ?>
<?php
include 'header.html';

include 'getConnection.php';
include 'utils.php';



$conn = getConnection(); if ( systemIsBusy($conn) == true ) exit();
$event_nfid= decryptIt($_GET["id"]);

/* conversion to prepared statement
print "$event_nfid";
$stmt = $conn->prepare("SELECT eventNumber, clubName, location, city, state, DATE_FORMAT(startDate, '%b %d, %Y') fmtDate FROM nf_trial where nfid= ?");
print "<br>1";
$stmt->bind_param('s', $event_nfid); // 's' specifies the variable type => 'string'
print "<br>2";
$stmt->execute();
print "<br>3";
$result = $stmt->get_result();
print "<br>4";

while ($row = $result->fetch_assoc()) {
		print "<br>GOTIT!";
}
 */

$query = "SELECT eventNumber, clubName, location, city, state, DATE_FORMAT(startDate, '%b %d, %Y') fmtDate FROM nf_trial where nfid= $event_nfid";
//$result = mysqli_query($conn, $query) or DIE("Could not Execute Query ".$query);
$result = mysqli_query($conn, $query) or DIE("Could not Execute Query ");
$row = mysqli_fetch_array($result);

print "<html>\n";
print "<head>";
print "<meta name=\"description\" content=\"Results from the trial on ".$row{'fmtDate'}." held at ".$row{'location'}.", ".$row{'city'}.", ".$row{'state'}.".\"/>";
print "</head>";
print "<body>\n";

$eventNumber=$row{'eventNumber'};

print "<title>".$row{'clubName'}."</title>";

print "<p>";

//$rv = getHits($_SERVER['REQUEST_URI']); 

print "<center>";
print "<h2>".$row{'clubName'}."</h2>";
print $row{'location'}."<br>";
print $row{'city'}.", ".$row{'state'}."<br>";
print $row{'fmtDate'}."<br>";
$link="http://www.akc.org/events/search/index_results.cfm?action=plan&event_number=".$eventNumber."&cde_comp_group=FT  &cde_comp_type=&NEW_END_DATE1=&key_stkhldr_event=&mixed_breed=N";
print "<a href=\"".$link."\" target=\"_blank\">AKC Results Page</a>";
// print "(".$rv." hits)";
print "<br>";
//fbs_link("showTrialResults.php4?id=".$event_nfid, "Share this trial on Facebook");

print "</center> <p>";



$query2 = "SELECT * FROM nf_stake where event_nfid= $event_nfid order by nfid";
//$result2 = mysqli_query($conn, $query2) or DIE("Could not Execute Query ".$query2. " " . mysql_error());
$result2 = mysqli_query($conn, $query2) or DIE("Could not Execute Query ");


$i = 0;

print "<table cellpadding=15 align=center>";
while ($row2 = mysqli_fetch_array($result2))
{

if ( $i % 2 == 0 )
	print "<tr>";
print "<td>";

	$judge1_nfid = $row2{'judge1_nfid'};
	$judge2_nfid = $row2{'judge2_nfid'};
    $stake_nfid = $row2{'nfid'};
	$stakeName = expandStakeName($row2{'stake'});
	print "<b>".$stakeName."</b>";

	$retInd=$row2{'retInd'};
	if ( $retInd == 1 )
		echo " (Retrieving)";
	echo "<br>";


	$judgeA = getJudge($conn, $judge1_nfid);
	$judgeB = getJudge($conn, $judge2_nfid);


	print "<a href=judgeList.php4?id=".encryptIt($judge1_nfid).">";
	if ( strlen($judgeA{'firstName'}) > 0 && strlen($judgeA{'lastName'}) > 0 )
		$j1str = $judgeA{'firstName'}." ".$judgeA{'lastName'};
	else
		$j1str = $judgeA{'akcName'};
	print $j1str;
	print "</a> and ";


	print "<a href=judgeList.php4?id=".encryptIt($judge2_nfid).">";
	if ( strlen($judgeB{'firstName'}) > 0 && strlen($judgeB{'lastName'}) > 0 )
		$j2str = $judgeB{'firstName'}." ".$judgeB{'lastName'};
	else
		$j2str = $judgeB{'akcName'};
	print $j2str;
	print "</a><br>";


/*
	print $judgeA{firstName}." ".$judgeA{lastName}."  and  ";
	print $judgeB{firstName}." ".$judgeB{lastName}."<br>";
*/

	print getStarters($conn, $stake_nfid)." Starters";


	$query = "SELECT * FROM nf_starters where stake_nfid = '$stake_nfid'";
	// $result = mysqli_query($conn, $query) or DIE("Could not Execute Query ".$query. " " . mysql_error());
	$result = mysqli_query($conn, $query) or DIE("Could not Execute Query ");

	$row = mysqli_fetch_array($result);
	$breed = $row{'breed'};

	if( strncmp($breed, "noBreeds", 8) != 0 )
	{
		$starters = $row{'starters'};
		echo "  ($starters   $breed";

		while ($row = mysqli_fetch_array($result) )
		{
		 	$breed = $row{'breed'};
		 	$starters = $row{'starters'};
		 	echo ", $starters $breed";
		}
		echo ")";
	}
	echo "<br>";


	$query = "SELECT * FROM nf_placement where stake_nfid = '$stake_nfid' order by placement";
	$result = mysqli_query($conn, $query) or DIE("Could not Execute Query ");


	while ($row = mysqli_fetch_array($result) )
	{
		 $placement= $row{'placement'};
		 $dog_nfid = $row{'dog_nfid'};

		$query3 = "SELECT * FROM nf_dog where nfid= '$dog_nfid'";
		$result3 = mysqli_query($conn, $query3) or DIE("Could not Execute Query ");
		$row3 = mysqli_fetch_array($result3);
		$name = $row3{'registeredName'};
		$sex = $row3{'sex'};
		$breed = getBreedAbbr($row3{'breed'});
		$akcNumber = $row3{'akcNumber'};

		echo " &#160 $placement - ";
//		echo " &#160 <b>$placement</b> - ";
		if( $akcNumber != 'WITHHELD' )
		{
//			if ( $breed == "Vizsla" )
				echo "<a href=dog.php4?id=".encryptIt($dog_nfid).">";

			if( $placement == 1 )
				echo "<b>$name</b>";
			else
				echo "$name";
			echo "</a>";

//			if ( $breed == "Vizsla" )
//				echo " ($breed $sex)<br>";
//			else
				echo " ($breed)<br>";
		}
		else
			 echo "Withheld<br>";


	}

	print "</td>\n";

	$i++;
	if( $i % 2 == 0 )
		print "</tr>";

}

print "</table>";


?>






<?php include 'trailer.html' ?>

</html>
</body>







