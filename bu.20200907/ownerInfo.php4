<?php session_start() ?>
<?php
if ( isset( $_GET['registeredName']) ) 
	$rn = $_GET['registeredName'];
if ( isset( $_GET['akcNumber']) ) 
	$akcNum = $_GET['akcNumber'];
?>

<html>
<title>FT DB Owner Info Help</title>

<body BGCOLOR="#F2F8F9"  LINK="#0000CC" >

<p>

<H2><center>Have more information for you dog?</h2>
Fill out as much of the following as you want and send it to  me, Skip, at <a href="mailto:admin@fieldtrialdatabase.com?subject=Dog Info Update Request&body=
%0D
%0D AKC Number (REQUIRED):  <?php if(isset($akcNum)) print $akcNum ?>
%0D Registered Name:  <?php if(isset($rn)) print $rn ?>
%0D Call name: 
%0D URL: 
%0D Contact e-mail: 
%0D AKC Titles: 
%0D Sire's AKC Number: 
%0D Dams's AKC Number: 
">admin@fieldtrialdatabase.com</a>.
<p><b>
This is the only format I accept!
</b>
<p>
<table>
<tr><td> AKC Number <b>REQUIRED!</B> </td><td>
<?php
if( isset($akcNum) ) print $akcNum;
else  print '______________________________';
?>
</td><td>
<tr><td> Registered Name</td><td>
<?php
if( isset($rn) ) print $rn;
else  print '______________________________';
?>
</td><td>
<tr><td> Call name  </td><td>______________________________</td></tr>
<tr><td> URL</td><td>______________________________</td></tr>
<tr><td> Contact e-mail</td><td>______________________________</td></tr>
<tr><td> AKC Titles (front-only) </td><td>______________________________</td></tr>
<tr><td> Sire's AKC Number </td><td>______________________________</td></tr>
<tr><td> Dams's AKC Number </td><td>______________________________</td></tr>
</table>
<p>
I'll make the updates as time permits,
<br>
Skip
</table>
<?php include 'trailer.html' ?>
</html>

