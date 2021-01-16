<?php
	include 'getConnection.php';
	$conn = getConnection();


	$location="paging.php";
	$recordLimit = 20;
	$tableName = 'nf_dog';
	$queryString = "(registeredName like '%Rem%' or registeredName like '%Sno%')";
         
	$sql = "SELECT count(*) FROM $tableName ";
	if( isset($queryString)) {
		$sql = $sql." where ".$queryString." ";
	}
	$retval = mysqli_query( $conn, $sql);
	$row = mysqli_fetch_array($retval);
	$recordCount = $row[0];


	$pageCount = intval($recordCount/$recordLimit) + 1;

	$page=1;
    if( isset($_GET{'page'} ) ) {
    	$page = $_GET{'page'};
    }
	if( $page < 1 || $page > $pageCount ) {
		header("Location: ".$location."?page=1", true, 303);
	}

	$pageIdx = $page - 1;
    $offset = $recordLimit * $pageIdx ;
	$recordsLeft = $recordCount - $page * $recordLimit;



	/* specific stuff here */
	/* specific stuff here */
	print "<html>";
	print "<center>";

	$sql = "SELECT registeredName, breed FROM nf_dog ";
	if( isset($queryString)) {
		$sql = $sql." where ".$queryString." order by breed, registeredName";
	}
	$sql = $sql." LIMIT $offset, $recordLimit";
	$retval = mysqli_query( $conn, $sql);

	$count = 0;
	if( $retval->num_rows != 0 ) {

		print "<H2> DOG LIST </H2>";
		print "<table border=0>";

		while($row = mysqli_fetch_array($retval)) {
			echo "<tr><td width='400px'>{$row['registeredName']}</td>";
			echo "<td>{$row['breed']}</td></tr>\n";
			$count++;
		}
		while ($count < $recordLimit ) {
			print "<tr><td> &nbsp;</td></tr>";
			$count ++;
		}
		print "</table>";
		print "<p>";
	} else {
		print "<H2>DOG LIST</H2><p>no rows found!";
	}


	/* end of specific processing */
	/* end of specific processing */



	/*
	$last=1;
	if( $pageIdx > 0 ) {
		$last=$pageIdx ;
		echo "<a href = \"${location}?page=$last\">Previous $recordLimit Records</a> ";
	} 
	if( $recordsLeft > $recordLimit ) {
		echo "<a href = \"${location}?page=$nextPage\">Next $recordLimit Records</a> ";
	} else if ($recordsLeft > 0 ) {
		echo "<a href = \"${location}?page=$nextPage\">Next $recordsLeft Records</a> ";
	}
	 */
	mysqli_close($conn);

	print "<b>Page $page of $pageCount</b>";


?>

<style>
.pagination a {
  color: black;
  float: center;
  padding: 6px 12px;
  text-decoration: none;
  transition: background-color .3s;
  border-radius: 5px;
	border: 1px solid #ddd;
}
.pagination a.active {
  background-color: dodgerblue;
  color: white;
  border-radius: 5px;
}

.pagination a:hover:not(.active) {background-color: #ddd;}
</style>

<center>
 <div class="pagination">
<?php

	if( $pageCount > 1) {
	
	$previousPage = $page > 1 ? $page - 1 : $page ;
	$nextPage = $page < $pageCount ? $page + 1 : $pageCount;
  print "<a href='${location}?page=0'>&laquo;</a>";
  print "<a href='${location}?page=$previousPage'>&lt;</a>";
  print "<a href='${location}?page=$nextPage'>&gt;</a>";
  print "<a href='${location}?page=$pageCount'>&raquo;</a>";
	}
	print "</div>";
 ?>

<p>
</html>
