<?php session_start() ?>

<html>
<head>
<meta name="description" content="Current Vizsla point lists"/>
</head>

<title>Vizsla Point Lists</title>
<?php

include 'header.html';
include 'getConnection.php';
include 'utils.php';

if ( isset($_SESSION['vcount'] ) )
{
	$vcount = $_SESSION['vcount'] + 1;
}
else
{
    $vcount = 0;
}

$_SESSION['vcount'] = $vcount;

//print "<h2>$vcount<p>";
if( $vcount == "changethis" )
{
print "<p>&nbsp";
print "<p>&nbsp";
print "<center>";
print "<div style=\"width:500px; padding:20px; border:2px solid black;
background-color:lightblue\" > ";

print "<h1>Happy New Year</H1>";
print "</div>";
print "<p>&nbsp<p>";
print "<center><a href='topN.php4'>Continue to Vizsla Point List page</a>";
exit;
}


if( $vcount > 5)
{
	$_SESSION['vcount'] = 0;
}




$conn = getConnection(); if ( systemIsBusy($conn) == true ) exit();



function getTopPD($conn, $aDate, $listCount)
{
	// input date looks like Mon Year ( Jan 2008)
	//

	$a = explode( " ", trim($aDate));
	$month = $a[0];
	$year = $a[1];
	$tmpStr = $month." 1, ".$year;
	$time = strtotime($tmpStr);
	$dateStr = strftime("%Y-%m-%d", $time);

	$sql = "select akcNumber, owners, DATE_FORMAT(dateOfBirth, '%b %Y') dob, nf_dog.nfid, registeredName, sum(points) pts from nf_dog, (  ".
		"select  nf_dog.nfid, ".
		"SUM(starters) n, nf_placement.placement p, ".
		"((SUM(starters) - placement) * ( 5 - placement)) points ".
		"from nf_dog, nf_starters, nf_placement, nf_stake, nf_trial ".
		"where ".
		"nf_dog.nfid != 0 and ".
		"nf_dog.breed = 'Vizsla' and ".
		"nf_stake.stake  in ('OD', 'OP') and ".
		"nf_placement.dog_nfid = nf_dog.nfid and ".
		"nf_placement.stake_nfid = nf_stake.nfid and ".
		"nf_starters.stake_nfid = nf_stake.nfid and ".
		"nf_stake.event_nfid = nf_trial.nfid ".
		" and nf_trial.startDate < ('$dateStr' + INTERVAL 1 MONTH) and ".
		"nf_dog.dateOfBirth >= ('$dateStr' - INTERVAL 2 YEAR ) ".
		"group by nf_stake.nfid, nf_dog.nfid ".
		"order by nf_dog.akcNumber, points desc ) a ".
		"where nf_dog.nfid = a.nfid ".
		"group by nf_dog.nfid ".
		"order by pts desc";




$result = mysqli_query($conn, $sql) or DIE("TOP TEN QUERY FAILED ".mysqli_error());

print "<table border=0 align='center' cellspacing=0>";


$i = 0;
	print "<tr><b>";
	print "<td><b>Rank</td>";
	print "<td><b>Dog Name</td>";
	print "<td><b>Whelped</b></td>";
	print "<td><b>Owners</b></td>";
	print "<td><b>Points</td>";
	print "</tr></b>";


$rank = 0;
$lastPoints = -1;
while ( ($line = mysqli_fetch_array($result)) && $i < $listCount )
{

	$owners = $line{'owners'};
	$owners = str_replace("/", ", ", $owners);

	$i = $i + 1;

	if ( $lastPoints != $line{'pts'} ) $rank = $i;

	$query2 = "select * from dogInfo where akcNumber = '".
		$line{'akcNumber'}."'";
	$result2 = mysqli_query($conn, $query2) or 
		DIE("QUERY FAILED ".query2." ".mysqli_error());
	$row2=mysqli_fetch_array($result2);

	/*
	$akcTitles = "";
	if( $row2 ) $akcTitles = getTitleDisplay($row2{akcTitles}, $row2{otherTitles});

	if( strlen($akcTitles) > 0 )
		$name = "<I>".$akcTitles."</I> ".$line{registeredName};
	else
		$name = $line{registeredName};
	$name = substr($name, 0, 43);
	*/
		
	$name = getNameHttp($row2, $line{'registeredName'});

	$owners = substr($owners, 0, 32);

	
	print "<tr>";
	print "<td align='center'>$rank</td>";
	print "<td>&#160<a href='dog.php4?id=".$line{'nfid'}."'>".$name."</a></td>";
	print "<td align='right'>&#160".$line{'dob'}."&#160</td>";
	print "<td>&#160".$owners."</td>";
	print "<td align='right'>".$line{'pts'}."</td>";
	print "</tr>";


	$lastPoints = $line{'pts'};
}


print "</table>";

}



function getTopN($conn, $reportType, $frDate, $toDate, $listCount)
{

$stakeType = "'OLGD', 'NFC', 'NGDC', 'OLAA', 'NWGDC', 'GOLGD', 'GOLAA' ";
if( $reportType == "Amateur" )
	$stakeType = "'ALGD', 'NAFC', 'ALAA', 'GALAA', 'GALGD'";

$sql = "select akcNumber, owners, nf_dog.nfid, registeredName, sum(points) pts from nf_dog, ( ".
	"select  nf_dog.nfid, ".
	"SUM(starters) n, nf_placement.placement p, ".
	"((SUM(starters) - placement) * ( 5 - placement)) points ".
	"from nf_dog, nf_starters, nf_placement, nf_stake, nf_trial ".
	"where ".
	"nf_dog.nfid != 0 and ".
	"nf_dog.breed = 'Vizsla' and ".
	"nf_stake.stake  in (".$stakeType.") and ".
	"nf_placement.dog_nfid = nf_dog.nfid and ".
	"nf_placement.stake_nfid = nf_stake.nfid and ".
	"nf_starters.stake_nfid = nf_stake.nfid and ".
	"nf_stake.event_nfid = nf_trial.nfid and ".
	"nf_trial.startDate >= '".$frDate."' ".
	"and nf_trial.startDate <= '".$toDate."' ".
	"group by nf_stake.nfid, nf_dog.nfid ".
	"order by nf_dog.akcNumber, points desc ) a ".
	"where nf_dog.nfid = a.nfid ".
	"group by nf_dog.nfid ".
	"order by pts desc; ";


 //print $sql;

$result = mysqli_query($conn, $sql) or DIE("TOP TEN QUERY FAILED".mysqli_error());

print "<table border=0 align='center' cellspacing=0>";


$i = 0;
	print "<tr><b>";
	print "<td><b>Rank</td>";
	print "<td><b>Dog Name</td>";
	print "<td><b>Owners</td>";
	print "<td><b>Points</td>";
	print "</tr></b>";

$rank = 0;
$lastPoints = -1;

while ( ($line = mysqli_fetch_array($result)) && $i < $listCount )
{
	$owners = $line{'owners'};
	$owners = str_replace("/", ", ", $owners);

	$i = $i + 1;

	if ( $lastPoints != $line{'pts'} ) $rank = $i;

	$query2 = "select * from dogInfo where akcNumber = '".
		$line{'akcNumber'}."'";
	$result2 = mysqli_query($conn, $query2) or 
		DIE("QUERY FAILED ".query2." ".mysqli_error());
	$row2=mysqli_fetch_array($result2);

	$name = getNameHttp($row2, $line{'registeredName'});

	/*
	$akcTitles = "";
	if( $row2 ) $akcTitles = getTitleDisplay($row2{akcTitles}, $row2{otherTitles});

		$name = "<I>".$akcTitles."</I> ".$line{registeredName};
	else
		$name = $line{registeredName};
	*/


	print "<tr>";
	print "<td align='center'>$rank</td>";
	print "<td>&#160<a href='dog.php4?id=".$line{'nfid'}."'>".$name."</a></td>";
	print "<td>&#160".$owners."</td>";
	print "<td align='right'>".$line{'pts'}."</td>";
	print "</tr>";

	$lastPoints = $line{'pts'};
}
$i = $i - 1;


while ( $line && $lastPoints == $line{'pts'} )
{
	$owners = $line{'owners'};
	$owners = str_replace("/", ", ", $owners);

	$query2 = "select * from dogInfo where akcNumber = '".
		$line{'akcNumber'}."'";
	$result2 = mysqli_query($conn, $query2) or 
		DIE("QUERY FAILED ".query2." ".mysqli_error());
	$row2=mysqli_fetch_array($result2);
	$name = getNameHttp($row2, $line{'registeredName'});

	/*
	$akcTitles = "";
	if( $row2 ) $akcTitles = getTitleDisplay($row2{akcTitles}, $row2{otherTitles});

	if( strlen($akcTitles) > 0 )
		$name = "<I>".$akcTitles."</I> ".$line{registeredName};
	else
		$name = $line{registeredName};
	*/

	print "<tr>";
	print "<td align='center'>$rank</td>";
	print "<td>&#160<a href='dog.php4?id=".$line{'nfid'}."'>".$name."</a></td>";
	print "<td>&#160".$owners."</td>";
	print "<td align='right'>".$line{'pts'}."</td>";
	print "</tr>";

	$lastPoints = $line{'pts'};

	$line = mysqli_fetch_array($result);
}



print "</table>";


}

?>


<p>


<SCRIPT>
var postValues = new Array();
<?php 


	if( sizeof($_POST) <= 1 )
	{
		$_POST{'action'} = "GDB";

		$_POST{'gdType'} = "Amateur";
		$_POST{'gdYear'} = "2016";
		$_POST{'gdUserFromDate'} = "";
		$_POST{'gdUserToDate'} = "";
		$_POST{'pdMonth'} = "";
		$_POST{'pdUserDate'} = "";
		$_POST{'ERROR'} = "";

	}

	foreach ( array_keys($_POST) as $key )
	{
		$out = "postValues[\"".  $key."\"] = \"".$_POST{$key}."\";";
		print $out."\n";
	}

?>

postValues['ERROR'] = "";

function getUserDefinedGD()
{
	gdUserFromDate = postValues['gdUserFromDate'];
	gdUserToDate = postValues['gdUserToDate'];
	if( gdUserFromDate == "" )
	{
		postValues['ERROR'] = "Please enter Gun Dog from date";
		postit();
	}
	else if( gdUserToDate == "" )
	{
		postValues['ERROR'] = "Please enter Gun Dog to date";
		postit();
	}

	var fd = Date.parse(gdUserFromDate);
	if( isNaN(fd) )
	{
		postValues['ERROR'] = "Bad Gun Dog from date ["  +
			gdUserFromDate +"]";
		postit();
	}

	var td = Date.parse(gdUserToDate);
	if( isNaN(td) )
	{
		postValues['ERROR'] = "Bad Gun Dog to date [" +
			gdUserToDate +"]";
		postit();
	}

	if( fd >= td )
	{
		postValues['ERROR'] = "From date must be less than To date";
		postit();
	}

	postValues["action"] = "GDU";


	postit();
}

function getUserDefinedPD()
{

	pdUserDate = postValues['pdUserDate'];
	if( pdUserDate == "" )
	{
		postValues['ERROR'] = "Please enter a date";
		postit();
	}


	var s1 = pdUserDate.split("/");
	if( s1.length != 2 )
		s1 = pdUserDate.split(",");
	if( s1.length != 2 )
		s1 = pdUserDate.split(" ");
	if( s1.length != 2 )
		s1 = pdUserDate.split("-");

	if( s1.length != 2 )
	{
		aMonth = pdUserDate.substring(0,3);
		aYear = pdUserDate.substring(3);
	}
	else
	{
		aMonth = s1[0];
		aYear = s1[1];
	}

	if( aYear.length <= 2 )
	{
		if ( aYear > 50 && aYear < 100 )
			aYear = "19" + aYear;
		else
			aYear = "20"  + aYear;
	}

	if( s1.length != 2 || isNaN(aMonth))
		tmpDate = aMonth + " 1, " + aYear;
	else
		tmpDate = aMonth+"/1/"+aYear;

	var td = Date.parse(tmpDate);
	if( isNaN(td) )
	{
		tmpDate = aMonth + "/1/" + aYear;
		td = Date.parse(aMonth + "/1/" + aYear);
	}

	if( isNaN(td) )
	{
		postValues['ERROR'] = "Bad date for Puppy/Derby";
		postit();
	}

	postValues['pdUserDateFull'] = tmpDate;
	postValues["action"] = "PDU";
	postit();
}

function setValue(action, key, value)
{
	postValues["action"] = action;
	postValues[key] = value;

	if ( action == "GDB" )
	{
		postValues["gdUserFromDate"]="";
		postValues["gdUserToDate"]="";
	}

	if ( action == "PDB" )
		postValues["pdUserDate"]="";

	postit();
}

function setText(key, event)
{
	if( event.target  ) targ = event.target;
	else targ = event.srcElement;
	postValues[key] = targ.value;
}
	

function postit() 
{
	var aForm = document.createElement("form"); 
	aForm.action = "topN.php4"; aForm.method = 'POST'; 

	for ( var i in postValues ) {
		var aElement = document.createElement("input");
		aElement.name = i ; aElement.type = 'hidden';
		aElement.value = postValues[i]; aForm.appendChild(aElement);
	}
	document.getElementsByTagName('body')[0].appendChild(aForm); 
	aForm.submit();
}

</SCRIPT>

<center>

<table border=0 cellspacing=0>
<tr>

<td><b>GUN DOG</b></td>
<td>
<input type = "radio" name="reportType"  value="Amateur" 
	onclick='setValue("GDR", "gdType","Amateur")'
	<?php if ( $_POST{'gdType'} == "Amateur" ) echo "checked" ; ?>
	>Amateur 
<input type = "radio" name="reportType"  value="Open" 
	onclick='setValue("GDR", "gdType", "Open")' 
	<?php if ( $_POST{'gdType'} == "Open" ) echo "checked" ; ?>
	>Open 
</td><td>

<input type= "button"  name="year" value="2016" 
	onclick='setValue("GDB", "gdYear","2016")'>
<input type= "button"  name="year" value="2015" 
	onclick='setValue("GDB", "gdYear","2015")'>
<input type= "button"  name="year" value="2014" 
	onclick='setValue("GDB", "gdYear","2014")'>
<input type= "button"  name="year" value="2013" 
	onclick='setValue("GDB", "gdYear","2013")'>
<input type= "button"  name="year" value="2012" 
	onclick='setValue("GDB", "gdYear","2012")'>
<input type= "button"  name="year" value="GO" onclick='postit()'/>
</td>

</tr></table>
<p>


<table border = 0 cellspacing=0>
<tr>
<td><b> PUPPY DERBY <b></td>
<td colspan = '2'> 
<input type= "button"  name="year" value="Jul 16" 
	onclick='setValue("PDB", "pdMonth", "Jul 2016")'>
<input type= "button"  name="year" value="Jun 16" 
	onclick='setValue("PDB", "pdMonth", "Jun 2016")'>
<input type= "button"  name="year" value="May 16" 
	onclick='setValue("PDB", "pdMonth", "May 2016")'>
<input type= "button"  name="year" value="Mar 16" 
	onclick='setValue("PDB", "pdMonth", "Mar 2016")'>
<input type= "button"  name="year" value="Feb 16" 
	onclick='setValue("PDB", "pdMonth", "Feb 2016")'>
<input type= "button"  name="year" value="Jan 16" 
	onclick='setValue("PDB", "pdMonth", "Jan 2016")'>
</td><td>

Month/Year <input type="text"  
value="<?php echo $_POST{'pdUserDate'}; ?>"
size=4 onblur='setText("pdUserDate", event)'>

<input type= "button"  name="year" value="GO" 
	onclick='getUserDefinedPD()'>

</td></tr></table>
</center>
<p>


<p>
<center><h2>

<?php

$action = $_POST{'action'};
$gdType = $_POST{'gdType'};
$gdYear = $_POST{'gdYear'};
$gdUserFromDate = $_POST{'gdUserFromDate'};
$gdUserToDate = $_POST{'gdUserToDate'};
$pdMonth = $_POST{'pdMonth'};
$pdUserDate = $_POST{'pdUserDate'};
if ( isset($_POST{'pdUserDateFull'}) )
{
	$pdUserDateFull = $_POST{'pdUserDateFull'};
}
$error = $_POST{'ERROR'};

if ($error != "" )
{
	echo $error;
}
else
{





if ( strncmp($action, "GD", 2) == 0 )
{
	echo $gdType." ";
	echo "Point List for " ;



	if( $gdUserFromDate != "" )
	{
		$fTime = strtotime($gdUserFromDate);
		$tTime = strtotime($gdUserToDate);
		$fDate = strftime("%b %d, %Y", $fTime);
		$tDate = strftime("%b %d, %Y", $tTime);
		echo $fDate." to ".$tDate;
		$fDate = strftime("%Y-%m-%d", $fTime);
		$tDate = strftime("%Y-%m-%d", $tTime);
	}
	else
	{
		echo $gdYear;
		$fDate = $gdYear."-1-1";
		$tDate = $gdYear."-12-31";
	}

	print " (Vizsla only)</h2>";
//	$rv = getHits($_SERVER['REQUEST_URI'].":".$gdType." GD:".$fDate.":".$tDate);
//	print "(".$rv." hits)"; 
// 	fbs_link("topN.php4", "Share this on Facebook");

	getTopN($conn, $gdType, $fDate, $tDate, 100);



}
else if ( strncmp($action, "PD", 2) == 0 )
{
	echo "Puppy/Derby Point List for ";
	if( $pdUserDate != "")
	{
		$fTime = strtotime($pdUserDateFull);
		$pdMonth = strftime("%b %Y", $fTime);
	}

	print $pdMonth;
	print " (Vizsla only) </h2>";
//	$rv = getHits($_SERVER['REQUEST_URI'].":P/D:".$pdMonth); 
//	print "(".$rv." hits)"; 


	getTopPD($conn, $pdMonth, 500);
}

}


?>

</center></h1>

<p>


<a href="http://www.fieldtrialdatabase.com/topTenRules.html" target="_blank">See how points are calculated</a>

<?php	include 'trailer.html' ?>

</html>




