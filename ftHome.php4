<?php
include 'initPhp.php';
include 'getConnection.php';
$conn= getConnection(); 
?>

<html>
<head>
<meta name="description" content="Comprehensive database of AKC field trials since 1999" />
<meta name="keywords" content="pointing,field trial,database,placement,AKC,birddog,field champion,judges,skip wonnell, kenneth wonnell, laurie wonnell" />
<meta name="author" content="Skip Wonnell, kenneth wonnell" />
</head>
<title>Field Trial Placements Database</title>

<?php include 'header.html'; ?>

<p>
<p>

<table border=0 cellpadding=1>
<tr>
<td align=center rowspan=3 >
<img border=5 src='images/jaks.jpg' width=270
class=pointer onclick=window.location.assign('direct.php?name=Jaks') ><br>
<a href='direct.php?name=Jaks'>Jaks</a>

</td>

<td valign="top" align="center"> <font size=5><b>recent updates </b></font><br>

<table border=0>
<tr><td >
<?php
include 'updateText.php';
?>
</tr></td>
</table>
<br>
<p>

<?php listSomeTrials($conn,false, 19, 0); ?>
</td></tr>


<tr><td align=center> -  -  -</td></tr>

<tr><td>

<table><tr>
<td width=30%>
<?php print "".getRowCount($conn, "nf_trial"); ?> </b>Events<br>
<?php print "".getRowCount($conn, "nf_stake"); ?> </b>Stakes <br>
<?php print "".getRowCount($conn, "nf_dog"); ?> </b>Dogs 

</td> 
<td>

<font size=2>
Notes:  This DB has AKC trial data from 1999 to the present.   New results are entered as soon as possible after official posting.   If you find a mistake let me know at <a href="mailto:admin@fieldtrialdatabase.com">admin@fieldtrialdatabase.com</a>.  

</td> </tr>
</table>
</table>
<p>

<?php
include 'trailer.html';
?>

</body>
</html>

