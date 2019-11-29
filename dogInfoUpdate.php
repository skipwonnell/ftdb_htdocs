<html>
<head>
<style>
.error {
	color: red;
}
</style>


<body>

<?php
include 'DogInfoDB.php';
include 'NfDogDB.php';
class FormValue {
	public $label = "";
	public $name = "";
	public $value = "";
	public $validationError = "";
	public $valid = true;
	public function __construct($labelx, $name, $value) {
		$this->label = $labelx;
		$this->name = $name;
		$this->value = $value;
		$this->valid = true;
		$this->validationError = "";
	}
	public function validate() {
	}
	public function validateNonEmpty($msg) {
		if (strlen ( trim ( $this->value ) ) == 0) {
			$this->validationError = $msg;
			$this->valid = false;
			return false;
		}
		return true;
	}
	public function setValueFromArray($a) {
		if (isset ( $a [$this->name] )) {
			$this->value = $a [$this->name];
		}
	}
}
class RegisteredNameFormValue extends FormValue {
	public function validate() {
		return parent::validateNonEmpty ( "Registered Name must be populated" );
	}
}
class AkcNumberFormValue extends formValue {
	public function validate() {
		return parent::validateNonEmpty ( "AKC Number must be populated" );
	}
}
class DogInfoForm {
	public $dogInfoDB;
	public $nfDogDB;
	public $akcNumber;
	public $registeredName;
	public $akcTitles;
	public $email;
	public $url;
	public $sireAkcNumber;
	public $sireRegisteredName;
	public $damAkcNumber;
	public $damRegisteredName;
	public $callName;
	public $otherTitles;
	public $backTitles;
	public $akcNumberForQuery;
	public $queryResults = "";
	public $queryError = "";
	public $updateResults = "";
	public $updateError = "";
	public function __construct() {
		$this->dogInfoDB = new DogInfoDB ();
		$this->nfDogDB = new NfDogDB ();
		$this->akcNumber = new AkcNumberFormValue ( "AKC Number", "akcNumber", "" );
		$this->registeredName = new RegisteredNameFormValue ( "Registered Name", "registeredName", "" );
		$this->akcTitles = new FormValue ( "AKC Titles", "akcTitles", "" );
		$this->email = new FormValue ( "E-Mail", "email", "" );
		$this->url = new FormValue ( "URL", "url", "" );
		$this->sireAkcNumber = new FormValue ( "Sire AKC Number", "sireAkcNumber", "" );
		$this->sireRegisteredName = "";
		$this->damAkcNumber = new FormValue ( "Dam AKC Number", "damAkcNumber", "" );
		$this->damRegisteredName = "";
		$this->callName = new FormValue ( "Call Name", "callName", "" );
		$this->otherTitles = new FormValue ( "Other Titles", "otherTitles", "" );
		$this->backTitles = new FormValue ( "Backend Titles", "backendTitles", "" );
	}
	public function initFromArray($a) {
		$this->akcNumberForQuery = $a {'akcNumberForQuery'};
		$this->akcNumber->setValueFromArray ( $a );
		$this->registeredName->setValueFromArray ( $a );
		$this->akcTitles->setValueFromArray ( $a );
		$this->email->setValueFromArray ( $a );
		$this->url->setValueFromArray ( $a );
		$this->sireAkcNumber->setValueFromArray ( $a );
		$this->sireRegisteredName = "";
		$this->damAkcNumber->setValueFromArray ( $a );
		$this->damRegisteredName = "";
		$this->callName->setValueFromArray ( $a );
		$this->otherTitles->setValueFromArray ( $a );
		$this->backTitles->setValueFromArray ( $a );
	}
	public function getDogInfoBean() {
		$dogInfoBean = array ();
		$dogInfoBean {'registeredName'} = $this->registeredName->value;
		$dogInfoBean {'akcNumber'} = $this->akcNumber->value;
		$dogInfoBean {'akcTitles'} = $this->akcTitles->value;
		$dogInfoBean {'email'} = $this->email->value;
		$dogInfoBean {'url'} = $this->url->value;
		$dogInfoBean {'sireAkcNumber'} = $this->sireAkcNumber->value;
		$dogInfoBean {'damAkcNumber'} = $this->damAkcNumber->value;
		$dogInfoBean {'callName'} = $this->callName->value;
		$dogInfoBean {'otherTitles'} = $this->otherTitles->value;
		$dogInfoBean {'backTitles'} = $this->backTitles->value;
		return $dogInfoBean;
	}
	public function suggestSire($akcNumber) {
		if (empty ( $this->sireAkcNumber->value )) {
			$this->sireAkcNumber->value = $this->dogInfoDB->suggest ( "SIRE", $akcNumber );
		}
	}
	public function suggestDam($akcNumber) {
		if (empty ( $this->damAkcNumber->value )) {
			$this->damAkcNumber->value = $this->dogInfoDB->suggest ( "DAM", $akcNumber );
		}
	}
	public function doAkcNumberQuery($akcNumberForQuery) {
		$this->akcNumberForQuery = $akcNumberForQuery;
		
		if (empty ( $akcNumberForQuery )) {
			$this->queryError = "AKC Number is required";
			return;
		}
		
		$this->suggestSire ( $akcNumberForQuery );
		$this->suggestDam ( $akcNumberForQuery );
		
		$results = $this->dogInfoDB->queryByAkcNumber ( $akcNumberForQuery );
		
		if (count ( $results ) > 0) {
			if (count ( $results ) > 1) {
				$this->queryError = "[" . $akcNumberForQuery . "] was not unique";
				return;
			}
			
			$dogInfo = $results [0];
			$this->registeredName->value = $dogInfo {'registeredName'};
			$this->akcNumber->value = $dogInfo {'akcNumber'};
			$this->akcTitles->value = $dogInfo {'akcTitles'};
			$this->email->value = $dogInfo {'email'};
			$this->url->value = $dogInfo {'url'};
			$this->sireAkcNumber->value = $dogInfo {'sireAkcNumber'};
			$this->damAkcNumber->value = $dogInfo {'damAkcNumber'};
			$this->callName->value = $dogInfo {'callName'};
			$this->otherTitles->value = $dogInfo {'otherTitles'};
			$this->backTitles->value = $dogInfo {'backTitles'};
			$this->queryResults = "Record found";
		} else {
			$results = $this->nfDogDB->queryByAkcNumber ( $akcNumberForQuery );
			if (count ( $results ) == 0) {
				$this->queryError = "[" . $akcNumberForQuery . "] was not found";
				$this->akcNumber->value = $akcNumberForQuery;
				return;
			}
			if (count ( $results ) > 1) {
				$this->queryError = "[" . $akcNumberForQuery . "] was not unique";
				return;
			}
			
			$dogInfo = $results [0];
			$this->registeredName->value = $dogInfo {'registeredName'};
			$this->akcNumber->value = $dogInfo {'akcNumber'};
			$this->queryResults = "Record found";
		}
		
		$this->setParentName ( $this->sireAkcNumber->value, "SIRE");
		$this->setParentName ( $this->damAkcNumber->value, "DAM");
/*
		 * if (!preg_match("/\A[A-Z]{2}\d{8}\z/",$rec['akcNumber'])) $rec['queryResults'] = "Valid format is AANNNNNNNN";
		 */
	}
	public function setParentName($akcNumber, $type) {
		$results = $this->nfDogDB->queryByAkcNumber ( $akcNumber );
		if (count ( $results ) == 0) {
			$results = $this->dogInfoDB->queryByAkcNumber ( $akcNumber );
		}
		if (count ( $results ) > 0) {
			$dogInfo = $results [0];
			if ($type == 'SIRE') {
				$this->sireRegisteredName = $type." z" . $dogInfo {'registeredName'};
			} else {
				$this->damRegisteredName = $type. " x" . $dogInfo {'registeredName' };
			}
		}
	}
	/*
	public function setDamName() {
		if (! $this->damAkcNumber->validateNonEmpty ( "" )) {
			return;
		}
		$results = $this->nfDogDB->queryByAkcNumber ( $this->damAkcNumber->value );
		if (count ( $results ) == 0) {
			$this->damAkcNumber->validationError = "Not found";
			return;
		}
		if (count ( $results ) > 1) {
			$this->damAkcNumber->validationError = "AkcNumber is not unique!!";
			return;
		}
		$dogInfo = $results [0];
		$this->damRegisteredName = $dogInfo {'registeredName'};
	}
	*/
	public function doUpdate() {
		$this->validate ();
		
		if ($this->valid) {
			$this->setParentName ( $this->damAkcNumber->value, "DAM");
			$this->setParentName ( $this->sireAkcNumber->value, "SIRE");
			$this->updateResults = "Updated!";
			$results = $this->dogInfoDB->insertOrUpdate ( $this->getDogInfoBean () );
		} else {
			$this->updateError = "Fix errors!";
		}
	}
	public function validate() {
		$this->valid = $this->akcNumber->validate ();
		$this->valid = $this->registeredName->validate () && $this->valid;
	}
}

$dogInfoForm = new DogInfoForm ();
if ($_SERVER ["REQUEST_METHOD"] == "POST") {
	if ($_POST ['action'] == "Query AKC Number") {
		if (empty ( $_POST ["akcNumberForQuery"] )) {
			$dogInfoForm->queryError = "AKC number must be populated";
		} else {
			$dogInfoForm->doAkcNumberQuery ( $_POST ['akcNumberForQuery'] );
		}
	} else {
		$dogInfoForm->initFromArray ( $_POST );
		$dogInfoForm->doUpdate ();
	}
}

?>


<h2>Dog Info update form</h2>

<?php
/*
 * print "REQUEST METHOD: ".$_SERVER["REQUEST_METHOD"]."<br>";
 * print "POST: ";
 * print_r($_POST);
 * print "<p>";
 * print_r($dogInfoForm);
 * print "<p>";
 */
?>

<form method="post"
		action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

		AKC Number: <input type="text" name="akcNumberForQuery"
			value="<?php print $dogInfoForm->akcNumberForQuery;?>"> <br> <input
			type="submit" name="action" value="Query AKC Number"> <span
			class="error"> <?php print $dogInfoForm->queryError; ?></span> <span
			class="status"> <?php print $dogInfoForm->queryResults; ?></span>
		<p>
		
		
		<table>

			<tr>
				<td> <?php print $dogInfoForm->akcNumber->label ?> </td>
				<td><input type="text"
					name="<?php print $dogInfoForm->akcNumber->name?>"
					value="<?php  print $dogInfoForm->akcNumber->value ?>"> <span
					class="error"> <?php print $dogInfoForm->akcNumber->validationError; ?></span>
				</td>
			</tr>

			<tr>
				<td> <?php print $dogInfoForm->registeredName->label ?> </td>
				<td><input type="text"
					name="<?php print $dogInfoForm->registeredName->name?>" size="50"
					value="<?php  print $dogInfoForm->registeredName->value ?>"> <span
					class="error"> <?php print $dogInfoForm->registeredName->validationError; ?></span>
				</td>
			</tr>

			<tr>
				<td> <?php print $dogInfoForm->callName->label ?> </td>
				<td><input type="text"
					name="<?php print $dogInfoForm->callName->name?>"
					value="<?php  print $dogInfoForm->callName->value ?>"> <span
					class="error"> <?php print $dogInfoForm->callName->validationError; ?></span>
				</td>
			</tr>

			<tr>
				<td> <?php print $dogInfoForm->akcTitles->label ?> </td>
				<td><input type="text"
					name="<?php print $dogInfoForm->akcTitles->name?>"
					value="<?php  print $dogInfoForm->akcTitles->value ?>"> <span
					class="error"> <?php print $dogInfoForm->akcTitles->validationError; ?></span>
				</td>
			</tr>

			<tr>
				<td> <?php print $dogInfoForm->otherTitles->label ?> </td>
				<td><input type="text"
					name="<?php print $dogInfoForm->otherTitles->name?>"
					value="<?php  print $dogInfoForm->otherTitles->value ?>"> <span
					class="error"> <?php print $dogInfoForm->otherTitles->validationError; ?></span>
				</td>
			</tr>

			<tr>
				<td> <?php print $dogInfoForm->backTitles->label ?> </td>
				<td><input type="text"
					name="<?php print $dogInfoForm->backTitles->name?>"
					value="<?php  print $dogInfoForm->backTitles->value ?>"> <span
					class="error"> <?php print $dogInfoForm->backTitles->validationError; ?></span>
				</td>
			</tr>

			<tr>
				<td> <?php print $dogInfoForm->sireAkcNumber->label ?> </td>
				<td><input type="text"
					name="<?php print $dogInfoForm->sireAkcNumber->name?>"
					value="<?php  print $dogInfoForm->sireAkcNumber->value ?>"> <span
					class="error"> <?php print $dogInfoForm->sireAkcNumber->validationError; ?></span>
					<span class="status"><?php print $dogInfoForm->sireRegisteredName; ?></span>
				</td>
			</tr>

			<tr>
				<td> <?php print $dogInfoForm->damAkcNumber->label ?> </td>
				<td><input type="text"
					name="<?php print $dogInfoForm->damAkcNumber->name?>"
					value="<?php  print $dogInfoForm->damAkcNumber->value ?>"> <span
					class="error"> <?php print $dogInfoForm->damAkcNumber->validationError; ?></span>
					<span class="status"> <?php print $dogInfoForm->damRegisteredName; ?></span>
				</td>
			</tr>

			<tr>
				<td> <?php print $dogInfoForm->url->label ?> </td>
				<td><input type="text" name="<?php print $dogInfoForm->url->name?>"
					value="<?php  print $dogInfoForm->url->value ?>"> <span
					class="error"> <?php print $dogInfoForm->url->validationError; ?></span>
				</td>
			</tr>

			<tr>
				<td> <?php print $dogInfoForm->email->label ?> </td>
				<td><input type="text"
					name="<?php print $dogInfoForm->email->name?>"
					value="<?php  print $dogInfoForm->email->value ?>"> <span
					class="error"> <?php print $dogInfoForm->email->validationError; ?></span>
				</td>
			</tr>

		</table>

		<input type="submit" name="action" value="Update Values"> <span
			class="error"> <?php print $dogInfoForm->updateError;  ?></span> <span
			class="status"> <?php print $dogInfoForm->updateResults;  ?></span>
	</form>

</body>
</html>
