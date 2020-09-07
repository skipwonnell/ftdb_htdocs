<?php session_start() ?>
<?php

include 'getConnection.php';
include 'utils.php';
$judge_id = $_GET["id"];
$conn = getConnection(); if ( systemIsBusy($conn) == true ) exit();
$judgeA = getJudge($conn, $judge_id);


?>

<html>
<head>
<meta name="description" content="Judging Locations for <?php print $judgeA{'akcName'}; ?> "/>

</head>

<title>
<?php print $judgeA{'akcName'} ?> Judging Locations
</title>


<?php
include 'header.html';




$query = "select city,state,count(*) from nf_trial where ".
"nfid in (select distinct event_nfid from nf_stake where ".
"judge1_nfid = ".$judgeA{'NFID'}." or judge2_nfid = ".$judgeA{'NFID'}.")".
" group by city,state order by count(*) desc, city";


$result = mysqli_query($conn, $query) or DIE("Could not execute query");
$nLocs = mysqli_num_rows($result);

//$rv = getHits($_SERVER['REQUEST_URI']); 
print "<center><h3>";
if ( strlen($judgeA{'firstName'}) > 0 && strlen($judgeA{'lastName'}) > 0 )
	print "Locations for ".$judgeA{'firstName'}." ".$judgeA{'lastName'}."</h3></b>";
else
	print "Locations for ".$judgeA{'akcName'}."</h3></b>";




print "<table cellpadding=0 border=0 align=center>";
print "<tr>";
print "<td><b>Trials</td>";
print "<td>&nbsp&nbsp<b>Location</td>";
print "</tr>";

while( $row = mysqli_fetch_array($result) )
{


print "<tr>";
print "<td align=center>".$row{'count(*)'}."</td>";
print "<td>&nbsp&nbsp".$row{'city'}.", ".$row{'state'}."</td>";
print "</tr>";
	
}

print "</table>";
print "<p>";

print "<a href=judgeList.php4?id="; 
print $judgeA{'NFID'};
print ">back to stakes</a>";

print "</html>";
