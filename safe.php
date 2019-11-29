<html>
<body>

<?php

	class FormValue {

		public $label = "";
		public $name = "";
		public $value = "";
		public $validationError = "";
		public $valid = true;

		public function __construct($label, $name, $value)
		{
			$this->label = $label;
			$this->name = $name;
			$this->value = $value;

			$this->valid = true;
			$this->validationError = "";
		}

		public function validate() {
			$valid=true;
			$validationError = "";
		}

		public function setValueFromArray($a) {
			if( isset($a[$this->name]) ) {
				$this->value=$a[$this->name];
			}
		}
				
	}

	class RegisteredNameFormValue extends FormValue
	{
		public function validate() {
			if( strlen(trim($this->value)) == 0 )
			{
				$this->validationError = "Name must be populated";
				$this->valid = false;
				return false;
			}	
			return true;
		}
	}

	class AkcNumberFormValue extends formValue
	{
		public function validate() {
			if( strlen(trim($this->value)) == 0 )
			{
				$this->validationError = "AKC# must be populated";
				$this->valid = false;
				return false;
			}	
			return true;
		}
	}


	class DogInfoForm {

		public $akcNumber;
		public $registeredName;
		public $akcTitles ;
		public $email ;
		public $url ;
		public $sireAkcNumber ;
		public $damAkcNumber ;
		public $callName ;
		public $otherTitles ;
		public $backTitles ;

		public $akcNumberForQuery;
		public $queryResults = "";
		public $updateResults = "";

		public function __construct() {
			$this->akcNumber = new AkcNumberFormValue("AKC Number", "akcNumber", "");
			$this->registeredName = new RegisteredNameFormValue("Registered Name", "registeredName", "");
			$this->akcTitles = new FormValue("AKC Titles","akcTitles", "");
			$this->email = new FormValue("E-Mail","email", "");
			$this->url = new FormValue("URL","url", "");
			$this->sireAkcNumber = new FormValue("Sire AKC Number","sireAkcNumber","");
			$this->damAkcNumber = new FormValue("Dam AKC Number","damAkcNumber", "");
			$this->callName = new FormValue("Call Name","callName", "");
			$this->otherTitles = new FormValue("Other Titles","otherTitles", "");
			$this->backTitles = new FormValue("Backend Titles","backendTitles", "");
		}

		public function initFromArray($a) {
			$this->akcNumber->setValueFromArray($a);
			$this->registeredName->setValueFromArray($a);
			$this->akcTitles->setValueFromArray($a);
			$this->email->setValueFromArray($a);
			$this->url->setValueFromArray($a);
			$this->sireAkcNumber->setValueFromArray($a);
			$this->damAkcNumber->setValueFromArray($a);
			$this->callName->setValueFromArray($a);
			$this->otherTitles->setValueFromArray($a);
			$this->backTitles->setValueFromArray($a);
		}

		public function doAkcNumberQuery($akcNumberForQuery) {

			$this->akcNumberForQuery = $akcNumberForQuery;
			if (empty($akcNumberForQuery)) {
				$this->queryResults = "AKC Number is required";
				return;
			} 
		
			/*
   				if (!preg_match("/\A[A-Z]{2}\d{8}\z/",$rec['akcNumber'])) $rec['queryResults'] = "Valid format is AANNNNNNNN"; 
			*/

			if( $akcNumberForQuery == "1" ) {
				$this->registeredName->value="Remek's Red Storm Rising";
				$this->akcNumber->value="SR12345678";
				$this->akcTitles->value="GCH DC AFC";
				$this->email->value="laurie@remekvizlas.net";
				$this->url->value="";
				$this->sireAkcNumber->value="SN17607201";
				$this->damAkcNumber->value="SN81339602";
				$this->callName->value="Jaks";
				$this->otherTitles->value="BISS";
				$this->backTitles->value="ROM";
				$this->queryResults="Record found";
			} else {
				$this->queryResults = "[".$akcNumberForQuery."] was not found";
			}
		}

		public function doUpdate()
		{
			$this->validate();

			if( $this->valid ) {
				$this->updateResults = "Updated!";
			} else {
				$this->updateResults = "Fix errors!";
			}

		}

		public function validate()
		{
			$this->valid = $this->akcNumber->validate();
			$this->valid = $this->registeredName->validate() && $this->valid;
		}
	}
	
	$dogInfoForm = new DogInfoForm();
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
 		$action = $_POST["action"];
		if( $action == "Query AKC Number") {
			if (empty($_POST["akcNumberForQuery"])) {
				$dogInfoForm->queryResults = "AKC number must be populated";
			} else {
				$dogInfoForm->doAkcNumberQuery($_POST['akcNumberForQuery']);
			}
		} else {
			$dogInfoForm->initFromArray($_POST);
			$dogInfoForm->doUpdate();
		}
	}

?>

<?php

	if (empty($_POST["akcNumberForQuery"])) {
		$rec['queryResults'] = "Name is required";
  	} else {
    		$rec['akcNumberForQuery'] = test_input($_POST["akcNumberForQuery"]);
		/*
    		if (!preg_match("/\A[A-Z]{2}\d{8}\z/",$rec['akcNumber'])) {
      			$rec['queryResults'] = "Valid format is AANNNNNNNN"; 
		}
		*/
    	}
	if ( ! isset($rec['queryResults'] ) )
	{
		$rec = queryForAkcNumber($rec['akcNumberForQuery']);
	}
  	return $rec;
} 

function queryForAkcNumber($akcNumberForQuery) {
	$result = initRecord();

	$result['akcNumberForQuery'] = $akcNumberForQuery;

	if( $akcNumberForQuery == "1" )
	{
		$result['registeredName'] = "Remek's Red Storm Rising";
		$result['akcNumber'] = "SR12345678";
		$result['akcTitles'] = "GCH DC AFC";
		$result['email'] = "laurie@remekvizlas.net";
		$result['url'] = "";
		$result['sireAkcNumber'] = "SN17607201";
		$result['damAkcNumber'] = "SN81339602";
		$result['callName'] = "Jaks";
		$result['otherTitles'] = "BISS";
		$result['backTitles'] = "ROM";
	}
	else
	{
		$result['queryResults'] = "[".$akcNumberForQuery."] was not found";
	}
	
	return $result;
}

function doUpdate()
{
	$rec = validateUpdate($_POST);

	return $rec;
}

function validateUpdate($updateRec)
{
	$result=initRecord();
	$result['registeredName'] = $updateRec['registeredName'];
	$result['akcNumber'] = $updateRec['akcNumber'];
	$result['akcTitles'] = $updateRec['akcTitles'];
	$result['email'] = $updateRec['email'];
	$result['url'] = $updateRec['url'];
	$result['sireAkcNumber'] = $updateRec['sireAkcNumber'];
	$result['damAkcNumber'] = $updateRec['damAkcNumber'];
	$result['callName'] = $updateRec['callName'];
	$result['otherTitles'] = $updateRec['otherTitles'];
	$result['backTitles'] = $updateRec['backTitles'];

	$result = validateRegisteredName($result);
	$result = validateAkcNumber($result);

	if( ! $result['validationErr'] )
	{
			$result['updateResults'] = "Updated!";
	}
	return $result;

}


function validateAkcNumber($result)
{
		$validationError = false;
		$rn = $result['akcNumber'];

		if( strlen(trim($rn)) == 0 )
		{
			$result['akcNumberErr'] = "AKC number must be populated";
			$validationError = true;
		}	

		if( $validationError )
		{
				$result['validationError'] = true;
				$result['updateResults'] = "Update failed, correct errors";
		}

	return $result;
}

function validateRegisteredName($result)
{
		$validationError = false;
		$rn = $result['registeredName'];

		if( strlen(trim($rn)) == 0 )
		{
			$result['registeredNameErr'] = "Registered name must be populated";
			$validationError = true;
		}	

		if( $validationError )
		{
				$result['validationError'] = true;
				$result['updateResults'] = "Update failed, correct errors";
		}

	return $result;
}

?>

<?php 
function test_input($data)
{
	return $data;
}

function printValue($rec, $name)
{
	print getValue($rec, $name);
}

function getValue($rec, $name)
{
	if( isset($rec[$name]) )
		return $rec[$name];
	else
		return "";
}

?>
<h2>Dog Info update form</h2>


<?php
print "REQUEST METHOD: ".$_SERVER["REQUEST_METHOD"]."<br>"; 
print "POST: ";
print_r($_POST);
print "<p>";
print_r($dogInfoForm);
print "<p>";
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  

AKC Number: <input type="text" name="akcNumberForQuery" value="<?php print $dogInfoForm->akcNumberForQuery;?>" >
<br>
<input type="submit" name="action" value="Query AKC Number"> 
<span class="error"> <?php print $dogInfoForm->queryResults; ?></span>
<p>
<table> 

<tr><td> <?php print $dogInfoForm->akcNumber->label ?> </td><td> 
<input type="text" name="<?php print $dogInfoForm->akcNumber->name?>" 
value="<?php  print $dogInfoForm->akcNumber->value ?>" > 
<span class="error"> <?php print $dogInfoForm->akcNumber->validationError; ?></span>
</td></tr>

<tr><td> <?php print $dogInfoForm->registeredName->label ?> </td><td> 
<input type="text" name="<?php print $dogInfoForm->registeredName->name?>" 
value="<?php  print $dogInfoForm->registeredName->value ?>" > 
<span class="error"> <?php print $dogInfoForm->registeredName->validationError; ?></span>
</td></tr>

<tr><td> <?php print $dogInfoForm->callName->label ?> </td><td> 
<input type="text" name="<?php print $dogInfoForm->callName->name?>" 
value="<?php  print $dogInfoForm->callName->value ?>" > 
<span class="error"> <?php print $dogInfoForm->callName->validationError; ?></span>
</td></tr>

<tr><td> <?php print $dogInfoForm->akcTitles->label ?> </td><td> 
<input type="text" name="<?php print $dogInfoForm->akcTitles->name?>" 
value="<?php  print $dogInfoForm->akcTitles->value ?>" > 
<span class="error"> <?php print $dogInfoForm->akcTitles->validationError; ?></span>
</td></tr>

<tr><td> <?php print $dogInfoForm->otherTitles->label ?> </td><td> 
<input type="text" name="<?php print $dogInfoForm->otherTitles->name?>" 
value="<?php  print $dogInfoForm->otherTitles->value ?>" > 
<span class="error"> <?php print $dogInfoForm->otherTitles->validationError; ?></span>
</td></tr>

<tr><td> <?php print $dogInfoForm->backTitles->label ?> </td><td> 
<input type="text" name="<?php print $dogInfoForm->backTitles->name?>" 
value="<?php  print $dogInfoForm->backTitles->value ?>" > 
<span class="error"> <?php print $dogInfoForm->backTitles->validationError; ?></span>
</td></tr>

<tr><td> <?php print $dogInfoForm->sireAkcNumber->label ?> </td><td> 
<input type="text" name="<?php print $dogInfoForm->sireAkcNumber->name?>" 
value="<?php  print $dogInfoForm->sireAkcNumber->value ?>" > 
<span class="error"> <?php print $dogInfoForm->sireAkcNumber->validationError; ?></span>
</td></tr>

<tr><td> <?php print $dogInfoForm->damAkcNumber->label ?> </td><td> 
<input type="text" name="<?php print $dogInfoForm->damAkcNumber->name?>" 
value="<?php  print $dogInfoForm->damAkcNumber->value ?>" > 
<span class="error"> <?php print $dogInfoForm->damAkcNumber->validationError; ?></span>
</td></tr>

<tr><td> <?php print $dogInfoForm->url->label ?> </td><td> 
<input type="text" name="<?php print $dogInfoForm->url->name?>" 
value="<?php  print $dogInfoForm->url->value ?>" > 
<span class="error"> <?php print $dogInfoForm->url->validationError; ?></span>
</td></tr>

<tr><td> <?php print $dogInfoForm->email->label ?> </td><td> 
<input type="text" name="<?php print $dogInfoForm->email->name?>" 
value="<?php  print $dogInfoForm->email->value ?>" > 
<span class="error"> <?php print $dogInfoForm->email->validationError; ?></span>
</td></tr>

</table>

<input type="submit" name="action" value="Update Values"> 
<span class="error"> <?php print $dogInfoForm->updateResults;  ?></span>
</form>


</body>
</html>
