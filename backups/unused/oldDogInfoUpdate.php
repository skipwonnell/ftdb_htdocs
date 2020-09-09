<?php session_start() ?>
<html>

<?php

include 'utils.php';
include 'getConnection.php';
getConnection();

$i = 0;

function getDogInfoByName($name)
{
	$query = "select * from dogInfo where registeredName =  '".
		mysql_real_escape_string($name)."'";
	$result = mysql_query($query) or DIE("sql error: ".$query);
	if ( $row =  mysql_fetch_array($result) )
		return $row;

	$query = "select * from dogInfo where registeredName like  '%".
		mysql_real_escape_string($name)."%'";
	$result = mysql_query($query) or DIE("sql error: ".$query);
	return mysql_fetch_array($result);
}

function getNfDogByName($name)
{
	$query = "select * from nf_dog where registeredName =  '".
		mysql_real_escape_string($name)."'";
	$result = mysql_query($query) or DIE("sql error: ".$query);
	if ($row =  mysql_fetch_array($result) )
		return $row;

	$query = "select * from nf_dog where registeredName like  '%".
		mysql_real_escape_string($name)."%'";
	$result = mysql_query($query) or DIE("sql error: ".$query);
	return mysql_fetch_array($result);
}

function getNfDogByAkcNumber($akcNumber)
{
	$query = "Select * from nf_dog where akcNumber = '$akcNumber'";
	$result = mysql_query($query) or DIE("sql error: ".$query);
	return mysql_fetch_array($result);
}

function getDogInfoByAkcNumber($akcNumber)
{
	if( $akcNumber )
	{
		$query = "Select * from dogInfo where akcNumber = '$akcNumber'";
		$result = mysql_query($query) or DIE("sql error: ".$query);
		return mysql_fetch_array($result);
	}
}

function updateRow($row)
{

	$query = "delete from dogInfo where akcNumber = '".$row{'akcNumber'}."'";
	$result = mysql_query($query) or DIE("sql error: ".$query);
	$query = "insert into dogInfo values ( " .
		"\"". $row{'akcNumber'} . "\", " .
		"\"". $row{'registeredName'} . "\", " .
		"\"". $row{'akcTitles'} . "\", " .
		"\"". $row{'email'} . "\", " .
		"\"". $row{'url'} . "\", " .
		"\"". $row{'sireAkcNumber'} . "\", " .
		"\"". $row{'damAkcNumber'} . "\", " .
		"\"". $row{'callName'} . "\", " .
		"\"". $row{'otherTitles'} . "\", " .
		"\"". $row{'backTitles'} . "\")";

	$result = mysql_query($query) or DIE("sql error: ".$query);
}


?>

<SCRIPT>
var postValues = new Array();

<?php

	if( strcmp($_GET{'action'},"querySire" ) == 0 )
	{
		$sireAkcNumber = $_GET{'sireAkcNumber'};
		foreach( array_keys($_GET) as $key ) $_GET{$key} = ""; 
		$_GET{'akcNumberQueryValue'} = $sireAkcNumber;


		if( $row2 = getDogInfoByAkcNumber($sireAkcNumber) )
		{
			$_GET{'akcNumber'} = $row2{'akcNumber'};
			$_GET{'registeredName'} = $row2{'registeredName'};
			$_GET{'akcTitles'} = $row2{'akcTitles'};
			$_GET{'otherTitles'} = $row2{'otherTitles'};
			$_GET{'backTitles'} = $row2{'backTitles'};
			$_GET{'email'} = $row2{'email'};
			$_GET{'url'} = $row2{'url'};
			$_GET{'sireAkcNumber'} = $row2{'sireAkcNumber'};
			$_GET{'damAkcNumber'} = $row2{'damAkcNumber'};
			$_GET{'callName'} = $row2{'callName'};
		}
		else if ( $row1 = getNfDogByAkcNumber($sireAkcNumber) )
		{
			$_GET{'akcNumber'} = $row1{'akcNumber'};
			$_GET{'registeredName'} = $row1{'registeredName'};
		}

	}

	if( strcmp($_GET{'action'},"queryDam" ) == 0 )
	{
		$damAkcNumber = $_GET{'damAkcNumber'};
		foreach( array_keys($_GET) as $key ) $_GET{$key} = ""; 
		$_GET{'akcNumberQueryValue'} = $damAkcNumber;

		if( $row2 = getDogInfoByAkcNumber($damAkcNumber) )
		{
			$_GET{'akcNumber'} = $row2{'akcNumber'};
			$_GET{'registeredName'} = $row2{'registeredName'};
			$_GET{'akcTitles'} = $row2{'akcTitles'};
			$_GET{'otherTitles'} = $row2{'otherTitles'};
			$_GET{'backTitles'} = $row2{'backTitles'};
			$_GET{'email'} = $row2{'email'};
			$_GET{'url'} = $row2{'url'};
			$_GET{'sireAkcNumber'} = $row2{'sireAkcNumber'};
			$_GET{'damAkcNumber'} = $row2{'damAkcNumber'};
			$_GET{'callName'} = $row2{'callName'};
		}
		else if ( $row1 = getNfDogByAkcNumber($damAkcNumber) )
		{
			$_GET{'akcNumber'} = $row1{'akcNumber'};
			$_GET{'registeredName'} = $row1{'registeredName'};
		}

	}

	if( strcmp($_GET{'action'},"queryAkcNumber" ) == 0 )
	{
		$akcNumberQueryValue = $_GET{'akcNumberQueryValue'};
		foreach( array_keys($_GET) as $key ) $_GET{$key} = ""; 
		$_GET{'akcNumberQueryValue'} = $akcNumberQueryValue;
		$_GET{'akcNumber'} = $akcNumberQueryValue;

		if( $row2 = getDogInfoByAkcNumber($akcNumberQueryValue) )
		{
			$_GET{'akcNumber'} = $row2{'akcNumber'};
			$_GET{'registeredName'} = $row2{'registeredName'};
			$_GET{'akcTitles'} = $row2{'akcTitles'};
			$_GET{'otherTitles'} = $row2{'otherTitles'};
			$_GET{'backTitles'} = $row2{'backTitles'};
			$_GET{'email'} = $row2{'email'};
			$_GET{'url'} = $row2{'url'};
			$_GET{'sireAkcNumber'} = $row2{'sireAkcNumber'};
			$_GET{'damAkcNumber'} = $row2{'damAkcNumber'};
			$_GET{'callName'} = $row2{'callName'};
		}
		else if ( $row1 = getNfDogByAkcNumber($akcNumberQueryValue) )
		{
			$_GET{'akcNumber'} = $row1{'akcNumber'};
			$_GET{'registeredName'} = $row1{'registeredName'};
		}

	}

	if( strcmp($_GET{'action'},"queryName" ) == 0 )
	{
		$nameQueryValue = $_GET{'nameQueryValue'};
		foreach( array_keys($_GET) as $key ) $_GET{$key} = ""; 
		$_GET{'nameQueryValue'} = $nameQueryValue;

		$row2 = getDogInfoByName($nameQueryValue);

		if ( $row2 )
		{
			$_GET{'akcNumber'} = $row2{'akcNumber'};
			$_GET{'registeredName'} = $row2{'registeredName'};
			$_GET{'akcTitles'} = $row2{'akcTitles'};
			$_GET{'otherTitles'} = $row2{'otherTitles'};
			$_GET{'backTitles'} = $row2{'backTitles'};
			$_GET{'email'} = $row2{'email'};
			$_GET{'url'} = $row2{'url'};
			$_GET{'sireAkcNumber'} = $row2{'sireAkcNumber'};
			$_GET{'damAkcNumber'} = $row2{'damAkcNumber'};
			$_GET{'callName'} = $row2{'callName'};
		}
		else if( $row1 = getNfDogByName($nameQueryValue) )
		{
			$_GET{'akcNumber'} = $row1{'akcNumber'};
			$_GET{'registeredName'} = $row1{'registeredName'};
		}
	}


	foreach( array_keys($_GET) as $key )
	{
		$out = "postValues[\"". $key."\"] = \"".$_GET{$key}."\";";
		print $out."\n";
	}

	if( strcmp($_GET{'action'},"update" ) == 0 )
	{
		updateRow($_GET);
	}


?>

function keyPress(key, event, action)
{
	if(window.event) keynum = event.keyCode;
	else if( event.which) keynum = event.which;

	if( keynum == 13)
	{
		setText(key, event);
		doQuery(action);
	}

	return keynum;
}

function doQuery(qtype)
{
	postValues["action"] = qtype;
	postit();
}

function postit()
{
	var aForm = document.createElement("form");
	aForm.action = "dogInfoUpdate.php4"; aForm.method = 'GET';
	for( var i in postValues) {
		var aElement = document.createElement("input");
		aElement.name = i; aElement.type = 'hidden';
		aElement.value = postValues[i]; aForm.appendChild(aElement);
	}
	document.getElementsByTagName('body')[0].appendChild(aForm);
	aForm.submit();
}

function setText(key, event)
{
	if( event.target ) targ = event.target
	else targ = event.srcElement;
	postValues[key] = targ.value;
}

</SCRIPT>

<h2>Dog Information Update</h2>
<p>

<?php
$nameQueryValue="";
if( ! isset( $_GET{'nameQueryValue'} ) )
	$_GET{'nameQueryValue'} = "";
$nameQueryValue = $_GET{'nameQueryValue'};
print "<p>HERE $nameQueryValue</p>";
print_r($_GET);
print_r($_POST);

$akcNumberQueryValue="";
if( ! isset( $_GET{'akcNumberQueryValue'} ) )
	$_GET{'akcNumberQueryValue'} = "";
$akcNumberQueryValue = $_GET{'akcNumberQueryValue'};

$akcNumber="";
if( ! isset( $_GET{'akcNumber'} ) )
	$_GET{'akcNumber'} = "";
$akcNumber = $_GET{'akcNumber'};

$registeredName="";
if( ! isset( $_GET{'registeredName'} ) )
	$_GET{'registeredName'} = "";
$registeredName = $_GET{'registeredName'};

$akcTitles="";
if( ! isset( $_GET{'akcTitles'} ) )
	$_GET{'akcTitles'} = "";
$akcTitles = $_GET{'akcTitles'};

$otherTitles="";
if( ! isset( $_GET{'otherTitles'} ) )
	$_GET{'otherTitles'} = "";
$otherTitles = $_GET{'otherTitles'};

$backTitles="";
if( ! isset( $_GET{'backTitles'} ) )
	$_GET{'backTitles'} = "";
$backTitles = $_GET{'backTitles'};

$email="";
if( ! isset( $_GET{'email'} ) )
	$_GET{'email'} = "";
$email = $_GET{'email'};

$url="";
if( ! isset( $_GET{'url'} ) )
	$_GET{'url'} = "";
$url = $_GET{'url'};

$sireAkcNumber="";
if( ! isset( $_GET{'sireAkcNumber'} ) )
	$_GET{'sireAkcNumber'} = "";
$sireAkcNumber = $_GET{'sireAkcNumber'};

$damAkcNumber="";
if( ! isset( $_GET{'damAkcNumber'} ) )
	$_GET{'damAkcNumber'} = "";
$damAkcNumber = $_GET{'damAkcNumber'};

$callName="";
if( ! isset( $_GET{'callName'} ) )
	$_GET{'callName'} = "";
$callName = $_GET{'callName'};


?>

<input type="text" size="30"
value = "<?php echo $nameQueryValue; ?>"
onkeypress='keyPress("nameQueryValue", event, "queryName")'
onblur='setText("nameQueryValue", event)'>

<input type="button" name="query" value="NAME QUERY"
	onclick='doQuery("queryName")'>

<p>

<input type="text" size="11"
value = '<?php echo $_GET{'akcNumberQueryValue'}; ?>'
onkeypress='keyPress("akcNumberQueryValue", event, "queryAkcNumber")'
onblur='setText("akcNumberQueryValue", event)'>

<input type="button" name="query" value="AKC NUMBER QUERY"
	onclick='doQuery("queryAkcNumber")'>

<p>


<br>

<table>
<tr><td> akcNumber </td><td>  
<input type="text" size="10" value = "<?php echo $_GET{'akcNumber'}; ?>"
onblur='setText("akcNumber", event)'>
</td></tr>

<tr><td> registeredName </td><td>  
<input type="text" size="45" value = "<?php echo $_GET{'registeredName'}; ?>"
onblur='setText("registeredName", event)'>
</td></tr>

<tr><td> akcTitles </td><td>  
<input type="text" size="20" value = "<?php echo $_GET{'akcTitles'}; ?>"
onblur='setText("akcTitles", event)'>
</td></tr>

<tr><td> otherTitles </td><td>  
<input type="text" size="20" value = "<?php echo $_GET{'otherTitles'}; ?>"
onblur='setText("otherTitles", event)'>
</td></tr>

<tr><td> backendTitles </td><td>  
<input type="text" size="20" value = "<?php echo $_GET{'backTitles'}; ?>"
onblur='setText("backTitles", event)'>
</td></tr>

<tr><td> email </td><td>  
<input type="text" size="45" value = "<?php echo $_GET{'email'}; ?>"
onblur='setText("email", event)'>
</td></tr>

<tr><td> url </td><td>  
<input type="text" size="60" value = "<?php echo $_GET{'url'}; ?>"
onblur='setText("url", event)'>
</td></tr>

<tr><td> sireAkcNumber </td><td>  
<input type="text" size="10" value = "<?php echo $_GET{'sireAkcNumber'}; ?>"
onblur='setText("sireAkcNumber", event)'>
<input type="button" name="query" value="QUERY SIRE"
	onclick='doQuery("querySire")'>

<?php

if( isset($_GET{'sireAkcNumber'}) && strlen(trim($_GET{'sireAkcNumber'})) > 0)
if( $row = getDogInfoByAkcNumber($_GET{'sireAkcNumber'}) )
	print getNameHttp($row, $row{'registeredName'});
	// print trim(getTitleDisplay($row{akcTitles}, $row{otherTitles})." ".$row{registeredName});
else
	print $_GET{sireAkcNumber}." not found in dogInfo";

?>

</td></tr>

<tr><td> damAkcNumber </td><td>  
<input type="text" size="10" value = "<?php echo $_GET{'damAkcNumber'}; ?>"
onblur='setText("damAkcNumber", event)'>
<input type="button" name="query" value="QUERY DAM"
	onclick='doQuery("queryDam")'>

<?php

if( isset($_GET{'damAkcNumber'}) && strlen(trim($_GET{'damAkcNumber'})) > 0)
if( $row = getDogInfoByAkcNumber($_GET{'damAkcNumber'}) )
	print getNameHttp($row, $row{'registeredName'});
	 // print trim(getTitleDisplay($row{akcTitles}, $row{otherTitles})." ".$row{registeredName});
else
	print $_GET{'damAkcNumber'}." not found in dogInfo";

?>
</td></tr>

<tr><td> callName </td><td>  
<input type="text" size="12" value = "<?php echo $_GET{'callName'}; ?>"
onblur='setText("callName", event)'>
</td></tr>

</table>



<p>

<input type="button" name="query" value="UPDATE"
	onclick='doQuery("update")'>


</html>

