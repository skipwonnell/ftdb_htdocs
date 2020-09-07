<?php




function getTotalHits($conn)
{
	$sql = "select sum(hits) hitSum from hitCounter";
	$result = mysqli_query($conn, $sql) or DIE ("query failed");
	//$result = mysqli_query($conn, $sql) or DIE ($sql." ".mysql_error());
	$line = mysqli_fetch_array($result);
	return $line{'hitSum'};
}



function getHits($conn, $pageId)
{
	$fieldValue = "'".$pageId."'";
	$sql = "select hits from hitCounter where pageId = $fieldValue";
	$result = mysqli_query($conn, $sql) or DIE ("query failed");
	//$result = mysqli_query($conn, $sql) or DIE ($sql." ".mysql_error());
	$line = mysqli_fetch_array($result);

	if( $line == null )
	{
		$sql = "insert into hitCounter values ( $fieldValue, 1)";
		mysqli_query($conn, $sql) ;
		$hits = 1;
	}
	else
	{
		$hits = $line{'hits'} + 1;
		$sql2 = "update hitCounter set hits = ".$hits." where pageId = $fieldValue";
		$result = mysqli_query($conn, $sql2) ;
	}

	$ip = "'".$_SERVER['REMOTE_ADDR']."'";

	$sql = "insert into pageHits values ( $fieldValue, now(), $ip)";
	mysqli_query($conn, $sql);

	return $hits;
}

function systemIsBusy($conn)
{
	$result = mysqli_query($conn, "select * from nf_busy") or die (mysqli_error($conn));
	$line = mysqli_fetch_array($result);

	if ( $line{'busy'} == 1 ) 
	{
		print "<p><center>Down for just a few minutes.  Skip";
		return true;
	}
	return false;
}

function getBreedAbbr($fullName)
{

$breeds = array (
	"Wirehaired Pointing Griffon" => "WPG", 
	"German Shorthaired Pointer" => "GSP", 
	"Vizsla"  => "Vizsla",
	"Wirehaired Vizsla"  => "WH Vizsla",
	"Brittany"  => "Brit",
	"Brittany"  => "Brit",
	"Irish Setter" => "Set-Irsh",
	"Irish Red and White Setter" => "Set-Irsh Rd&Wh",
	"English Setter" => "Set-Eng",
	"Gordon Setter" => "Set-Gord",
	"German Wirehaired Pointer" => "GWP",
	"Weimaraner" => "Weim",
	"Spinone Italiano" => "Spin Ital",
	"Pointer" => "Pointer");


	foreach (array_keys($breeds) as $b)
	{
		if( $b == $fullName )
			return $breeds{$b};
	}
}

function getEarliestTrial($conn)
{
	$sql = "select * from nf_trial order by startDate asc";
	//$result = mysqli_query($conn, $sql) or DIE ($sql." ".mysql_error());
	$result = mysqli_query($conn, $sql) or DIE ("query failed");
	$line = mysqli_fetch_array($result);
	print "Events entered since: ".$line{'clubName'}." on ".$line{'startDate'};
}


function listSomeTrials($conn, $searchString, $maximum, $dateFlag)
{
	print "<style type='text/css'>";
	print "td { padding-left: 4}";
	print "td { padding-right: 4}";
	print "</style>";

	print "<table border='0'>";

	getConnection();

	$query = "SELECT nfid, eventNumber, clubName, location, city, state, DATE_FORMAT(startDate, '%m/%d/%y') fmtDate FROM nf_trial "; 


	if ( $searchString != false )
		$query = $query." where clubName like '%".addslashes($searchString)."%'";


	if( $dateFlag == 1 )
		$query = $query." order by startDate desc";
	else
		$query = $query." order by nfid desc";
		




	//$result = mysqli_query($conn, $query) or DIE($query." failed: ".mysql_error());
	$result = mysqli_query($conn, $query) or DIE(" query failed ");


		print "<tr><td>&#160</td>";
		print "<td>";
		// print "Club Name (Vizslas Placed)";
		print "Club Name";
		print "</td><td>";
		print "City, ST";
		print "</td><td>";
		print "Event Date";
		print "</td></tr>";
		print "</b>";

	$i =  0;
	while  ( ($row = mysqli_fetch_array($result) )  && $i < $maximum)
	{ 
		$i++; 
		print "<tr><td>&#160</td>";

		$cn = str_replace("German Shorthaired Pointer", "GSP", $row{'clubName'});
		$cn = str_replace("German Wirehaired Pointer", "GWP", $cn);


		$location = $row{'location'};
		$state = $row{'state'};
		$city = $row{'city'};
		$eventNumber = $row{'eventNumber'};
		$trial_nfid = $row{'nfid'};


		$sql2 = "select count(distinct(nf_dog.nfid)) kount from ".
			" nf_placement,nf_stake, nf_dog ".
			" where nf_stake.event_nfid = ".$trial_nfid." and ".
			" nf_placement.stake_nfid = nf_stake.nfid and ".
			" nf_placement.dog_nfid = nf_dog.nfid and nf_dog.breed = 'Vizsla'";
	
		//$result2 = mysqli_query($conn, $sql2) or DIE($sql2." FAILED ".mysql_error());	
		$result2 = mysqli_query($conn, $sql2) or DIE("QUERY  FAILED ");	

		$line2 = mysqli_fetch_array($result2);

		$vcount = $line2{'kount'};
		print "<td valign='top'>";

		//print "&#160 ".$row{fmtDate}."</td><td>";
		print " <a href='showTrialResults.php4?id=".$trial_nfid.
			"'>".$cn."</a>";

	//	if( $vcount > 0 )
	//		print " (".$vcount.")";
		print "</td><td align='left'>";
		print $city.", ".$state;
		print "</td><td align='right'>";
		print $row{'fmtDate'};

		print "</td></tr>";

	}
	print "</table>";
	if ( $searchString != false && $i == $maximum )
	{
		print "<p>More exist, only displaying ".$maximum."<br>";
	}
}

function expandStakeName($abbr)
{

$stakes = array(
	"OP" => "Open Puppy" ,
	"OD" => "Open Derby" ,
	"AWP" => "Amateur Walking Puppy" ,
	"AWD" => "Amateur Walking Derby" ,
	"AGD" => "Amateur Gun Dog" ,
	"ALGD" => "Amateur Limited Gun Dog" ,
	"GALGD" => "Grand Amt. Ltd Gun Dog" ,
	"OGD" => "Open Gun Dog" ,
	"OLGD" => "Open Limited Gun Dog" ,
	"GOLGD" => "Grand Open Ltd Gun Dog" ,
	"OAA" => "Open All-Age" ,
	"OLAA" => "Open Limited All-Age" ,
	"GOLAA" => "Grand Open Ltd All-Age" ,
	"AAA" => "Amateur All-Age" ,
	"ALAA" => "Amateur Limited All-Age" ,
	"GALAA" => "Grand Am. Ltd All-Age" ,
	"NAFC" => "National Amateur Championship" ,
	"NGDC" => "National Gun Dog Championship" ,
	"NAGDC" => "National Amateur Gun Dog Championship" ,
	"NWGDC" => "National Walking Gun Dog Championship" ,
	"NFC" => "National Championship" );

	foreach( array_keys($stakes) as $key )
	{
		if( $key == $abbr )
			return $stakes[$key];
	}

	return $abbr." NOT FOUND";

}

function getJudge($conn, $judge_nfid)
{
	
	$query = "SELECT * FROM nf_judge where nfid = '$judge_nfid'";
	$result = mysqli_query($conn, $query) or DIE("Could not Execute Query ");
	$row = mysqli_fetch_array($result);
	return $row;
	
}


function getStarters($conn, $stake_nfid)
{
	$query = "SELECT SUM(starters) sum FROM nf_starters where stake_nfid = '$stake_nfid'";
	$result = mysqli_query($conn, $query) or DIE("Could not Execute Query ");
	$starters = mysqli_fetch_array($result);
	return $starters['sum'];
}

function listSomeDogs($conn, $dogSearchString, $ownerSearchString, $breed, $maximum)
{
	print "<table border='0'>";

	$i =  0;

	$akcFrontTitles = array("NAGDC", "NGDC", "NAFC", "NFC", 
		"DC", "AFC", "TC", "FC", "GCH", "CH", "OTCH", "MACH");


	if( $dogSearchString != "xxxx" || $ownerSearchString != "")
	{
		if( $dogSearchString == "xxxx" )
			$dogSearchString = "";

		getConnection();

		$dss = addSlashes($dogSearchString);
		$oss = addSlashes($ownerSearchString);

/*
		$done = false;
		while ( ! $done )
		{
			$done = true;
			foreach ($akcFrontTitles as $title)
			{
				$title=$title." ";
				if( strncmp($dss, $title, strlen($title)) == 0)
				{
					$dss = substr($dss, strlen($title));
					$done = false;
					break;
				}
			}
		}
*/
		$query="SELECT * FROM nf_dog where registeredName like '%".$dss."%'".
		" and owners like '%".$oss."%' ";

		if( $breed != 'Any' )
			$query = $query." and breed = '$breed'";
		$query = $query."  order by registeredName";

 //print("<tr><td>q: $query</tr></td>"); 

		//$result = mysqli_query($conn, $query) or DIE($query." failed: ".mysql_error());
		$result = mysqli_query($conn, $query) or DIE(" query failed ");


		while  ( ($row = mysqli_fetch_array($result) )  && $i < $maximum)
		{ 
			$query2="select * from dogInfo where akcNumber = '".
				$row{'akcNumber'}."'";
			//$result2 = mysqli_query($conn, $query2) or DIE($query2." failed ".mysql_error());
			$result2 = mysqli_query($conn, $query2) or DIE(" query failed ");
			$row2 = mysqli_fetch_array($result2);

			$akcTitles = "";


			$registeredName = getNameHttp($row2, $row{'registeredName'});

			

			$i++; 



			$dog_nfid = $row{'NFID'};

			print "<tr><td>&#160</td>";
			print "<td>";
			print " <a href='dog.php4?id=".$dog_nfid."'>".$registeredName."</a>";
			print " - ".$row{'breed'};
			print " - ".$row{'owners'};
			print "</td></tr>";

		}

		if ( $i == 0 )
		{
			print "<p><center><b>Sorry, no dogs found for your query</b> </center>";
		}

	}

	print "</table>";

	if (  $i == $maximum )
	{
			print "<p>More exist, only displaying ".$maximum."<br>";
	}

}

function getRowCount($conn, $table)
{
	$query="SELECT count(*) FROM $table";
	$result = mysqli_query($conn, $query) or DIE(" query failed ");
	$row = mysqli_fetch_array($result);
	return $row[0];
}



function fbs_link($link, $text)
{
	$elink = urlencode("http://www.fieldtrialdatabase.com/".$link);
	$text = "Share on Facebook";

	print "<font size=2 face='arial' color='blue'>";
	print "<a rel=\"nofollow\" onclick=\"window.open('http://www.facebook.com/sharer.php?u=".$elink."', 'sharer', 'toolbar=0, status=0, width=626, height=436, location=0')\" target=\"_blank\" class=\"pointer fb_share_link\"><U>".$text."</a></u>";
	print "<font color='black'>";
}


function getNameHttp($dogInfoRow, $regName)
{
	if( $dogInfoRow )
	{
		$oTitles = trim($dogInfoRow{'otherTitles'});
		$aTitles = trim($dogInfoRow{'akcTitles'});
		$bTitles = trim($dogInfoRow{'backTitles'});

		$fTitles = trim(getTitleDisplay($aTitles, $oTitles));

		$dname="";
		if( strlen($fTitles) > 0) $dname = "<I>".$fTitles."</I> ";

		$dname = $dname.trim($regName);

		if( strlen($bTitles) > 0 )
		$dname = $dname." <I>".trim($bTitles)."</I>";

		return $dname;
	}
	else
	{
		return $regName;
	}
}

function getTitleDisplay($aTitles, $oTitles)
{
		$otherTitles = trim($oTitles);
		$akcTitles = trim($aTitles);

		if($otherTitles=="none") $otherTitles="";
		if($akcTitles=="none") $akcTitles="";


		$otherArray=preg_split("/ /",$otherTitles, null, PREG_SPLIT_NO_EMPTY);
		$akcArray=preg_split("/ /",$akcTitles, null, PREG_SPLIT_NO_EMPTY);


		// print "os ".sizeof($otherArray)."\n";
		// print "as ".sizeof($akcArray)."\n";

		//print "OTHERS (";
		//foreach($otherArray as $title) print $title." ";
		//print ")\n";

		//print "AKC    (";
		//foreach($akcArray as $title) print $title." ";
		//print ")\n";



		$titleDisplay=$otherTitles;


		foreach($akcArray as $akcTitle)
		{
			$found = false;
			foreach($otherArray as $otherTitle)
			{
				if( $otherTitle == $akcTitle )
				{
					$found = true;
					break;
				}

				if( $otherTitle == "DC" && 
					( $akcTitle == "CH" || $akcTitle == "FC" ) )
				{
					$found = true;
					break;
				}

				if( ( $otherTitle == "CH" || $otherTitle == "FC" ) &&
					$akcTitle == "DC" )
				{
					$found = true;
					break;
				}

				$x = strpos(strtoupper($otherTitle), "X");
				if( $x ) $otherTitle = substr($otherTitle, $x+1);

				if( $otherTitle == $akcTitle) 
				{
					$found = true;
					break;
				}
			}

			if( $found == false )
				$titleDisplay = $titleDisplay." ".$akcTitle;
		}

		$titleDisplay=trim($titleDisplay);

		return $titleDisplay;
}

?>
