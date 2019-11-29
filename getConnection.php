<?php

getConnection();

function getConnection()
{

# $hostname = "127.0.0.1:3306";
$hostname = "127.0.0.1";
$username = "root";
$password = "Satchmo1";
$dbname = "fieldtrial";

#$hostname = "mysql125.hosting.earthlink.net";
#$username = "fieldtrial";
#$password = "remy..12";
#$dbname = "fieldtrial";


$conn = mysqli_connect($hostname, $username, $password, $dbname) or DIE("Unable to connect to MySQL server $hostname");

mysqli_select_db($conn, $dbname) or DIE("Could not select requested db $dbname");

return $conn;
}

?>
