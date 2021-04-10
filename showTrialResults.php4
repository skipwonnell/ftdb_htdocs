<?php
include 'initPhp.php';
include 'getConnection.php';
$conn = getConnection(); if ( systemIsBusy($conn) == true ) exit();
$event_nfid= decryptIt($_GET["eventId"]);
$stmt = $conn->prepare("SELECT eventNumber, clubName, location, city, state, DATE_FORMAT(startDate, '%b %d, %Y') fmtDate FROM nf_trial where nfid= ?");
$stmt->bind_param('s', $event_nfid);
$stmt->execute();
$result = $stmt->get_result();
$row = mysqli_fetch_array($result);
if( $row == null ) {
?> 
<script type="text/javascript">
   window.location.href = "errorPage.php4?errorId=1006"
</script>
<?php	
}
include 'header.html';

print "<html>";
print "<head>";
print "<meta name=\"description\" content=\"Results from the trial on ".$row['fmtDate']." held at ".$row['location'].", ".$row['city'].", ".$row['state'].".\"/>";
print "</head>";
print "<body>";

$eventNumber=$row['eventNumber'];

print "<title>".$row['clubName']."</title>";
print "<p>";
print "<center>";
print "<h2>".$row['clubName']."</h2>";
print $row['location']."<br>";
print $row['city'].", ".$row['state']."<br>";
print $row['fmtDate']."<br>";
$link="http://www.akc.org/events/search/index_results.cfm?action=plan&event_number=".$eventNumber."&cde_comp_group=FT  &cde_comp_type=&NEW_END_DATE1=&key_stkhldr_event=&mixed_breed=N";
print "<a href=\"".$link."\" target=\"_blank\">AKC Results Page</a>";
print "<br>";

print "</center> <p>";


$stmt = $conn->prepare("SELECT * FROM nf_stake where event_nfid= ? order by nfid");
$stmt->bind_param('s', $event_nfid); // 's' specifies the variable type => 'string'
$stmt->execute();
$result2 = $stmt->get_result();


$i = 0;

print "<table border=0 cellpadding=5 align=center>";
while ($row2 = mysqli_fetch_array($result2))
{

	if ( $i % 2 == 0 )
		print "<tr>";

	print "<td>";

	$judge1_nfid = $row2['judge1_nfid'];
	$judge2_nfid = $row2['judge2_nfid'];
    $stake_nfid = $row2['nfid'];
	$stakeName = expandStakeName($row2['stake']);
	print "<b>".$stakeName."</b>";

	$retInd=$row2['retInd'];
	if ( $retInd == 1 )
		echo " (Retrieving)";
	echo "<br>";


	$judgeA = getJudge($conn, $judge1_nfid);
	$judgeB = getJudge($conn, $judge2_nfid);


    print "<form style='margin:0px;padding:0px' action='judgeListPost.php4' method='get'>";

	// print "<a href=judgeList.php4?id=".encryptIt($judge1_nfid).">";
	//
	//
	
	if ( strlen($judgeA['firstName']) > 0 && strlen($judgeA['lastName']) > 0 )
		$j1str = $judgeA['firstName']." ".$judgeA['lastName'];
	else
		$j1str = $judgeA['akcName'];

    print "<button type='submit' class='db-link'".
	    "name='judgeId'".
    	"value='".encryptIt($judge1_nfid)."'>$j1str</button>";

	print " and ";

	
	if ( strlen($judgeB['firstName']) > 0 && strlen($judgeB['lastName']) > 0 )
		$j2str = $judgeB['firstName']." ".$judgeB['lastName'];
	else
		$j2str = $judgeB['akcName'];

    print "<button type='submit' class='db-link'".
	    "name='judgeId'".
    	"value='".encryptIt($judge2_nfid)."'>$j2str</button>";

	print "</form>";

	print getStarters($conn, $stake_nfid)." Starters";

	$query = "SELECT * FROM nf_starters where stake_nfid = '$stake_nfid'";
	// $result = mysqli_query($conn, $query) or DIE("Could not Execute Query ".$query. " " . mysql_error());
	$result = mysqli_query($conn, $query) or DIE("Could not Execute Query ");

	$row = mysqli_fetch_array($result);
	$breed = $row['breed'];

	if( strncmp($breed, "noBreeds", 8) != 0 )
	{
		$starters = $row['starters'];
		echo "  ($starters   $breed";

		while ($row = mysqli_fetch_array($result) )
		{
		 	$breed = $row['breed'];
		 	$starters = $row['starters'];
		 	echo ", $starters $breed";
		}
		echo ")";
	}
	echo "<br>";


	$query = "SELECT * FROM nf_placement where stake_nfid = '$stake_nfid' order by placement";
	$result = mysqli_query($conn, $query) or DIE("Could not Execute Query ");

	print "<form action='dogPost.php4' method='get'>";

	while ($row = mysqli_fetch_array($result) )
	{
		 $placement= $row['placement'];
		 $dog_nfid = $row['dog_nfid'];

		$query3 = "SELECT * FROM nf_dog where nfid= '$dog_nfid'";
		$result3 = mysqli_query($conn, $query3) or DIE("Could not Execute Query ");
		$row3 = mysqli_fetch_array($result3);
		$name = $row3['registeredName'];
		$sex = $row3['sex'];
		$breed = getBreedAbbr($row3['breed']);
		$akcNumber = $row3['akcNumber'];

		echo " &#160 $placement - ";
		if( $akcNumber != 'WITHHELD' )
		{
			if( $placement == 1 )
				echo "<button type='submit' class='db-link-bold' name='dogId' value='".encryptIt($dog_nfid)."'>$name</button>";
			else
				echo "<button type='submit' class='db-link' name='dogId' value='".encryptIt($dog_nfid)."'>$name</button>";

			echo " ($breed)<br>";
		}
		else {
			 echo "Withheld<br>";
		}

	}
	print "</form>";

	print "</td>\n";

	$i++;
	if( $i % 2 == 0 )
		print "</tr>";

}

print "</table>";
?>
<?php include 'trailer.html' ?>
</body>
</html>
