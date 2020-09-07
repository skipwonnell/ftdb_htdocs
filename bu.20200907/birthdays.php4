<?php session_start() ?>
<html>

<!--
aForm.action="https://www.apps.akc.org/apps/store/index.cfm?view=product&report_category_cde=DOG&report_cde=CMPREC&report_type_option=CMPREC&external=yes&external=yes&dog_id=SR82515001";
-->
<SCRIPT>

function postit(akcNumber)
{
	var aForm = document.createElement('form');
	aForm.action="https://www.apps.akc.org/apps/store/index.cfm?view=product";
	aForm.method='GET';



	var view = document.createElement("input");
	view.name = 'view'; 
	view.type = 'hidden';
	view.value = "product"; 
	aForm.appendChild(view);

	var rcc = document.createElement("input");
	rcc.name = 'report_category_cde'; 
	rcc.type = 'hidden';
	rcc.value = "DOG"; 
	aForm.appendChild(rcc);


	var rc = document.createElement("input");
	rc.name = 'report_cde'; 
	rc.type = 'hidden';
	rc.value = "CMPREC"; 
	aForm.appendChild(rc);

	var rto = document.createElement("input");
	rto.name = 'report_type_option'; 
	rto.type = 'hidden';
	rto.value = "CMPREC"; 
	aForm.appendChild(rto);

	var ext = document.createElement("input");
	ext.name = 'external'; 
	ext.type = 'hidden';
	ext.value = "yes"; 
	aForm.appendChild(ext);

	var dogId = document.createElement("input");
	dogId.name = 'dog_id'; 
	dogId.type = 'hidden';
	dogId.value = akcNumber; 
	aForm.appendChild(dogId);

	document.getElementsByTagName('body')[0].appendChild(aForm);
	aForm.submit();
}

</SCRIPT>

<?php


include 'getConnection.php';
include 'utils.php';


$conn = getConnection(); if ( systemIsBusy($conn) == true ) exit();

$sql = "select * from nf_dog where dateOfBirth is null and breed = 'Vizsla' and akcNumber not like '%x%' and akcNumber != 'RN036434' and akcNumber != 'GN451835LC' order by akcNumber desc";

$res = mysqli_query($conn, $sql) or die($sql." failed");

print "<table border=1>";

while ($row = mysqli_fetch_array($res) )
{
	print "<tr>";
	print "<td>".$row{'akcNumber'}."</td>";
	print "<td onclick=\"postit('".$row{'akcNumber'}."')\">".$row{'registeredName'}."</td>";
	print "<td>".$row{'dateOfBirth'}."</td>";
	print "<td>".$row{'sex'}."</td>";
	print "</tr>";
}
print "</table>";

?>

</html>
