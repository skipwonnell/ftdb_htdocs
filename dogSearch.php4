<?php
	include 'initPhp.php';
	include 'getConnection.php';
	$conn= getConnection(); 
	include 'header.html';
?>

<html>
<title>Dog Search</title>
<?php 
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
?>
<form action="dogSearchPost.php4" method="post">
&#160<p>
<table border = 0 cellpadding=20> <tr><td valign = "top">
<table border = 0 width=280> <tr><td colspan=2>
<table border=1 cellspacing=0 cellpadding=0><tr><td>
<tr><td > <table border=0 cellspacing=5 bgcolor="#EEEEFF"><tr><td>
Search by dog name or owner name.  <b>Don't include titles!</b>
</td></tr> </table> </td></tr> </td></tr> </table> </td></tr> <tr><td><p>&#160 </td></tr> <tr><td >

Dog Name: </td><td> <input type="text"   size="15"
name="dogSearchString" value="<?php  echo $_SESSION{'dogSearchString'}; ?>" >
</td></tr> <tr><td>

Owner: </td><td> <input type="text"   size="15"
name="ownerSearchString" value="<?php echo $_SESSION{'ownerSearchString'}; ?>" >
<br> </td></tr> <tr><td>

Breed:&#160 </td><td> 
<select name="breed"  >
<?php
	foreach ( array_keys($breedValues) as $key )
	{
		$breed = $breedValues{$key};
		if ( $_SESSION{'breed'} == $breed )
			echo "<option selected = \"yes\" >";
		else
			echo "<option>";
		echo $breedValues{$key};
		echo "</option>";
	}
?>
</select>
<br> </td></tr> <tr><td> </td></tr> <tr><td> </td><td align = left>

<input type="submit" name="search" value="search">
<input type="submit" name="clear" value="clear">

</td></tr> <tr><td colspan=2> &#160 <p>
<?php include 'searchHelp.html'; ?>
</td></tr> </table>

</td><td valign=top>
</form>
<?php
/*
	print "<br>ds: ".$_SESSION['dogSearchString'];
	print "<br>os: ".$_SESSION['ownerSearchString'];
	print "<br>br: ".$_SESSION['breed'];
	print "<br>hp: ".$_SESSION['hasPosted'];
 */
	if ( $_SESSION['hasPosted'] )
	listSomeDogs($conn, $_SESSION{'dogSearchString'}, $_SESSION{'ownerSearchString'}, $_SESSION['breed'], 50);
?>
</td></tr></table>
</body>
</html>
