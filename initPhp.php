<?php
  	session_start();


	if( !isset($_SESSION['beenHere']) ) {
		$_SESSION['beenHere'] = "yes";
		$ciphering = "BF-CBC"; 
		$iv_length = openssl_cipher_iv_length($ciphering); 
		$_SESSION['encryption_iv'] = random_bytes($iv_length); 
		$_SESSION['encryption_key'] = openssl_digest(random_int(10000000,20000000), 'MD5', TRUE); 
		header("Location: ftHome.php4");
	}

	function encryptIt($clearText) {
		$ciphering = "BF-CBC"; 
		$encryption = base64_encode(openssl_encrypt($clearText, $ciphering, 
        	$_SESSION['encryption_key'], 0, $_SESSION['encryption_iv']));
		return $encryption;
	}
	
	function decryptIt($cipherText) {
		$ciphering = "BF-CBC"; 
		$decryption = openssl_decrypt (base64_decode($cipherText), $ciphering, 
            	$_SESSION['encryption_key'], 0, $_SESSION['encryption_iv']); 
		return $decryption;
	}

  
	include 'utils.php';
?>
