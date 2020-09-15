<?php
  	session_start();
	header( "refresh:10;url=ftHome.php4" );

    $url = $_SERVER['HTTP_REFERER'];
    print "REFERRER: ".$url;
    print "<br>";


	$_SESSION['beenHere'] = "yes";
	$ciphering = "BF-CBC"; 
	$iv_length = openssl_cipher_iv_length($ciphering); 
	$_SESSION['encryption_iv'] = random_bytes($iv_length); 
	$_SESSION['encryption_key'] = openssl_digest(random_int(10000000,20000000), 'MD5', TRUE); 
	$rand=random_int(1000,1010);
	print "<H1>  Oops, something went wrong.  Error Code: ".$_GET['errorId']."</H1>";
	print "<p>";
	print "<H2>  Redirecting to <a href='ftHome.php4'>Field Trial Database</a></H2>";

	print "<br>SESSION: ";
	print_r($_SESSION);	
	print "<br>POST: ";
	print_r($_POST);	
	print "<br>GET: ";
	print_r($_GET);	

?> 

