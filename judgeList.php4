<?php
include 'initPhp.php';
include 'getConnection.php';

$judge_id = decryptIt($_GET["judgeId"]);
$conn=getConnection(); if ( systemIsBusy($conn) == true ) exit();
$judgeA = getJudge($conn, $judge_id);

if( $judgeA == null ) {
?> 
<script type="text/javascript">
   window.location.href = "errorPage.php4?errorId=1008"
</script>
<?php	
}
include 'header.html';
?>
<html>
<head>
<meta name="description" content="Judging assignments for <?php print $judgeA['akcName']; ?> "/>
</head>
<title>
<?php print $judgeA['akcName'] ?> Judging Assignments
</title>
<?php

$query = "select  nf_trial.clubName, nf_trial.city, nf_trial.state, nf_trial.location,
DATE_FORMAT(nf_trial.startDate,'%b %d, %Y') fmtDate, nf_trial.nfid trial_nfid, nf_stake.judge1_nfid,
judge2_nfid, nf_stake.nfid stake_nfid, stake, retInd, nf_placement.placement, registeredName, nf_dog.nfid, nf_dog.breed
from  nf_dog, nf_placement, nf_stake, nf_trial
where (judge1_nfid = $judge_id or judge2_nfid = $judge_id) and nf_placement.stake_nfid = nf_stake.nfid
and nf_dog.nfid = nf_placement.dog_nfid 
and nf_trial.nfid = nf_stake.event_nfid
order by nf_trial.startDate desc, nf_stake.nfid, placement";
$result = mysqli_query($conn, $query) or DIE("Could not execute query");
$nStakes = mysqli_num_rows($result)/4;


$query2 = "select sum(starters) as startersSum from nf_starters, nf_stake where ( judge1_nfid = $judge_id or judge2_nfid = $judge_id) and nfid = stake_nfid ";
$result2 = mysqli_query($conn, $query2); 
$row2 = mysqli_fetch_array($result2);
$startersSum = $row2['startersSum'];
 

//$rv = getHits($_SERVER['REQUEST_URI']); 
print "<center><h3>";
if ( strlen($judgeA['firstName']) > 0 && strlen($judgeA['lastName']) > 0 )
	//print $nStakes." Stakes ".$startersSum." starters judged by ".$judgeA['firstName']." ".$judgeA['lastName']."</h3></b>";
	print $nStakes." Stakes judged by ".$judgeA['firstName']." ".$judgeA['lastName']."</h3></b>";
else
	print $nStakes." Stakes judged by ".$judgeA['akcName']."</h3></b>";



$jfn="";
if ( strlen($judgeA['firstName']) > 2 )
{
	$jfn=" for ".$judgeA['firstName'];
}
print "<a href=\"http://www.akc.org/judges_directory/index.cfm?action=refresh_index_init&judge_id=".$judgeA['judgesNumber']."\" target=\"_blank\">AKC Judges page</a><br>";
print "<br><a href=judgeGeo.php4?id=";
print encryptIt($judgeA['NFID']);
print ">Locations judged</a>";
print "</center>";

$lastStakeId = -1;

$i = 0;

print "<table cellpadding=15 align=center>";

while( $row = mysqli_fetch_array($result) )
{

    $stake_nfid = $row['stake_nfid'];
	if( $lastStakeId != $stake_nfid )
	{
		if( $i %2 == 0 )
			print "<tr>";

		$i = $i + 1;
		print "<td>";

		$lastStakeId = $stake_nfid ;
		$judge1_nfid = $row['judge1_nfid'];
		$judge2_nfid = $row['judge2_nfid'];
		$judgeA = getJudge($conn, $judge1_nfid);
		$judgeB = getJudge($conn, $judge2_nfid);

		$stakeName = expandStakeName($row['stake']);

		print "<form style='margin:0px;padding:0px' action='showTrialResultsPost.php4' method='get'>";
		print "<button type='submit' class='db-link'".
    		"name='eventId'".
    		"value='".encryptIt($row['trial_nfid'])."'>".$row['clubName']." </button>";
		print "</form>";

		print $row['location'].", ";
		print $row['city'].", ".$row['state']."<br>";
		print $stakeName." ";
		if ( $row['retInd'] == 1 ) print "- Retrieving ";

		print " &nbsp &nbsp &nbsp ".$row['fmtDate']."<br>";
	
		print getStarters($conn, $stake_nfid)." Starters ";


		$query3 = "SELECT * FROM nf_starters where stake_nfid = '$stake_nfid'";
		// $result3 = mysqli_query($conn, $query3) or DIE("Could not Execute Query ".$query3. " " . mysqli_error());
		$result3 = mysqli_query($conn, $query3) or DIE("Could not Execute Query ");
		$row3 = mysqli_fetch_array($result3);

		

		$breed = $row3['breed'];
		$starters = $row3['starters'];

		if( strncmp($breed, "noBreeds", 8) != 0 )
		{
			echo ": $starters   $breed";

			while ($row3 = mysqli_fetch_array($result3) )
			{
		 		$breed = $row3['breed'];
		 		$starters = $row3['starters'];
		 		echo ", $starters $breed";
			}
		}

		print "<form style='margin:0px;padding:0px' action='judgeListPost.php4' method='get'>";

		print "Judges: ";
		print "<button type='submit' class='db-link'".
   			"name='judgeId'".
   			"value='".encryptIt($judge1_nfid)."'>";
		if ( strlen($judgeA['firstName']) > 0 && strlen($judgeA['lastName']) > 0 )
			print $judgeA['firstName']." ".$judgeA['lastName'];
		else
			print $judgeA['akcName'];
		print "</button>";
		print " and ";

		print "<button type='submit' class='db-link'".
   			"name='judgeId'".
   			"value='".encryptIt($judge2_nfid)."'>";
		if ( strlen($judgeB['firstName']) > 0 && strlen($judgeB['lastName']) > 0 )
			print $judgeB['firstName']." ".$judgeB['lastName'];
		else
			print $judgeB['akcName'];
		print "</button>";

		print "</form>";
	}

	print "<form style='margin:0px;padding:0px' action='dogPost.php4' method='get'>";
	print "&nbsp&nbsp&nbsp ".$row['placement'].". ";

	if( $row['nfid'] == 0 ) 
		print "Withheld<br>";
	else
	{
	//	print "<a href=dog.php4?id=".encryptIt($row['nfid']).">".$row['registeredName']."</a> - ";
		
		if( $row['placement'] == 1 )
	    	print "<button type='submit' class='db-link-bold'";
		else
	    	print "<button type='submit' class='db-link'";

    	print "name='dogId'".
    	"value='".encryptIt($row['nfid'])."'>".$row['registeredName']." </button>";

		print " &nbsp". getBreedAbbr($row['breed'])."<br>";
	}
	print "</form>";

	if( $row['placement'] == 4 ) print "</td>";
	
}

?>

</table>

<?php include 'trailer.html' ?>

</html>


