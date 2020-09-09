<?php
include 'initPhp.php';
include 'getConnection.php';
$conn= getConnection(); 
include 'header.html';
?>

<html>
<title>Dog Search</title>

<SCRIPT>
var postValues = new Array();

<?php 

	if( sizeof($_POST) < 1 )
	{
		$_POST{'dogSearchString'} = "xxxx";
		$_POST{'ownerSearchString'} = "";
		$_POST{'breedIndex'} = "10";
	}

	$breedValues[0] = "Any";
	$breedValues[1] = "Brittany";
	$breedValues[2] = "English Setter";
	$breedValues[3] = "German Shorthaired Pointer";
	$breedValues[4] = "German Wirehaired Pointer";
	$breedValues[5] = "Gordon Setter";
	$breedValues[6] = "Irish Setter";
	$breedValues[7] = "Irish Red and White Setter";
	$breedValues[8] = "Pointer";
	$breedValues[9] = "Spinone Italiano";
	$breedValues[10] = "Vizsla";
	$breedValues[11] = "Weimaraner";
	$breedValues[12] = "Wirehaired Pointing Griffon";

	foreach ( array_keys($_POST) as $key )
	{
		echo "postValues[\"$key\"] = \"$_POST[$key]\"; ";
	}
?>

function breedTypeChange(selectObject)
{
	if( selectObject != null )
	{
		breedIndex = selectObject.selectedIndex;
		postValues["breedIndex"] = breedIndex;
	}
	postit();
}

function keyPress(key, event)
{
	if(window.event) keynum = event.keyCode;
	else if( event.which) keynum = event.which;

	if( keynum == 13)
	{
		setText(key, event);
		postit();
	}

	return keynum;
}

function setText(key, event)
{
	if( event.target  ) targ = event.target;
	else targ = event.srcElement;
	postValues[key] = targ.value;
}

function clearSearch()
{
	postValues["dogSearchString"] = "xxxx";
	postValues["ownerSearchString"] = "";
	postValues["breedIndex"] = 8;
	postit();
}
	

function postit() 
{
	var aForm = document.createElement("form"); 
	aForm.action = "dogSearch.php4"; aForm.method = 'POST'; 

	for ( var i in postValues ) {
		var aElement = document.createElement("input");
		aElement.name = i ; aElement.type = 'hidden';
		aElement.value = postValues[i]; aForm.appendChild(aElement);
	}
	document.getElementsByTagName('body')[0].appendChild(aForm); 
	aForm.submit();
}


</SCRIPT>


&#160<p>

<table border = 0 cellpadding=20>
<tr><td valign = "top">

<table border = 0 width=280>
<tr><td colspan=2>
<table border=1 cellspacing=0 cellpadding=0><tr><td>
<tr><td >
<table border=0 cellspacing=5 bgcolor="#EEEEFF"><tr><td>
Search by dog name or owner name.  <b>Don't include titles!</b>
</td></tr>
</table>
</td></tr>
</td></tr>
</table>
</td></tr>
<tr><td><p>&#160 </td></tr>
<tr><td >
Dog Name: </td><td> <input type="text"   size="15"
name="ownerSearchText"
value="<?php if( $_POST{'dogSearchString'} != 'xxxx' ) echo stripslashes($_POST{'dogSearchString'}); ?>"
onblur='setText("dogSearchString", event)' 
onkeypress='keyPress("dogSearchString", event)' >
</td></tr>

<tr><td>
Owner: </td><td> <input type="text"   size="15"
name="dogSearchText"
value="<?php echo $_POST{'ownerSearchString'}; ?>"
onblur='setText("ownerSearchString", event)' 
onkeypress='keyPress("ownerSearchString", event)' >
<br>
</td></tr>


<tr><td>
Breed:&#160 </td><td> 
<select name="breedTypeSel" onChange='breedTypeChange(this)' >
<?php
	foreach ( array_keys($breedValues) as $key )
	{
		$breed = $breedValues{$key};
		if ( $breedValues[$_POST{'breedIndex'}] == $breed )
			echo "<option selected = \"yes\" >";
		else
			echo "<option>";

		echo $breedValues{$key};
		echo "</option>";
	}
?>

</select>
<br>
</td></tr>

<tr><td>
</td></tr>
<tr><td> </td><td align = left>
<input type="button" value="clear" onclick="clearSearch()">
<input type="button" value="search" onclick="postit()">
</td></tr>
<tr><td colspan=2> 
&#160
<p>
<?php include 'searchHelp.html'; 
?>
</td></tr>
</table>


</td><td valign=top>

<?php
	listSomeDogs($conn, $_POST{'dogSearchString'}, $_POST{'ownerSearchString'}, $breedValues[$_POST{'breedIndex'}], 150);
?>

</td></tr></table>



</body>
</html>
