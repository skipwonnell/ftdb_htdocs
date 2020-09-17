<?php
include 'initPhp.php';
include 'getConnection.php';
$conn=getConnection(); 

if( !isSet($_GET) || !isSet($_GET["name"])) {
	header("Location: ftHome.php4");
}

$directName = $_GET["name"];
$query = "SELECT * FROM direct_link where linkName = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('s', $directName);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = mysqli_fetch_array($result);

if( $row == null ) {
	header("Location: ftHome.php4");
}

$nfid = $row{'nfid'};
$destinationWindow = $row{'destinationWindow'};

if( strcmp($destinationWindow, "dog") == 0 ) {
	$link = "dog.php4?dogId=".encryptIt($nfid);
	echo $link;
	header("Location: $link ");
}
?>
