<html>

<?php>

include 'getConnection.php';
getConnection(); if ( systemIsBusy() == true ) exit();


print "<table  cellspacing=2 cellpadding=2>";


$query = "SELECT * FROM nf_trial  order by startDate desc";
$result = mysql_query($query) or DIE("Could not Execute Query ");

if ($result) {


print "<p><b> Events</b><p>";

print "<table border=1>";

echo "<tr><td><b>";
echo "Club</td><td><b>Date</td><td><b>Location</td>";
echo "</tr></b>";


while ($row2 = mysql_fetch_array($result))
{
    $eventNumber= $row2{eventNumber};
    $clubName= $row2{clubName};
    $startDate= $row2{startDate};
    $location= $row2{location};

print "<td><a href=listStakes.php4?id=$row2{'nfid'}>$clubName</a></td>";
 
    print "<td>$startDate</td><td>$location</td></tr>";
 

}

  print "</table>";
} 

    
?>

</html>
