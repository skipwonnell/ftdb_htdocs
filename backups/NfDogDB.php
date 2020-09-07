
<?php

class NfDogDB
{
	public $db;

	public function __construct()
	{
		$db = new mysqli('localhost', 'root', 'Satchmo1', 'fieldtrial');
		if( $db->connect_errno > 0) {
			die("unable to connect to db [". $db->connect_error ."]");
		}
		$this->db = $db;
	}


	public function queryByName($name) {
		$returnArray = array();
		$stmt = $this->db->prepare("SELECT * from nf_dog where registeredName like ?");
		$search = "%".$name."%";
		$stmt->bind_param('s', $search);
		$stmt->execute();
		$result=$stmt->get_result();
		while( $row = $result->fetch_assoc() ) {
			$returnArray[]=$row;	
		}
		return $returnArray;
	}

	public function queryByAkcNumber($name) {
		$returnArray = array();
		$stmt = $this->db->prepare("SELECT * from nf_dog where akcNumber = ?");
		$stmt->bind_param('s', $name);
		$stmt->execute();
		$result=$stmt->get_result();
		while( $row = $result->fetch_assoc() ) {
			$returnArray[]=$row;	
		}
		return $returnArray;

	}
}
?>
