<?php session_start() ?>
<?php

class DogInfoDB
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


	public function insertOrUpdate($dogInfoBean) {
			$sql = "REPLACE INTO DOGINFO "
					."(AKCNUMBER,REGISTEREDNAME,AKCTITLES,EMAIL,URL,SIREAKCNUMBER,"
					."DAMAKCNUMBER,CALLNAME,OTHERTITLES,BACKTITLES) "
					."VALUES(?,?,?,?,?,?,?,?,?,?)";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('ssssssssss', 
					$dogInfoBean{'akcNumber'}, 
					$dogInfoBean{'registeredName'},
					$dogInfoBean{'akcTitles'}, 
					$dogInfoBean{'email'},
					$dogInfoBean{'url'},
					$dogInfoBean{'sireAkcNumber'},
					$dogInfoBean{'damAkcNumber'},
					$dogInfoBean{'callName'},
					$dogInfoBean{'otherTitles'},
					$dogInfoBean{'backTitles'});
			$stmt->execute();
	}

	public function queryByName($name) {
		$returnArray = array();
		$stmt = $this->db->prepare("SELECT * from dogInfo where registeredName like ?");
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
		$stmt = $this->db->prepare("SELECT * from dogInfo where akcNumber = ?");
		$search = "$name";
		$stmt->bind_param('s', $name);
		$stmt->execute();
		$result=$stmt->get_result();
		while( $row = $result->fetch_assoc() ) {
			$returnArray[]=$row;	
		}
		return $returnArray;

	}

	public function suggest($suggestType, $akcNumber) {
		if( empty($akcNumber) || strlen($akcNumber) < 8 ) {
				return;
		}

		if ( $suggestType == 'DAM' || $suggestType == 'SIRE' )
		{
			if( $suggestType == 'DAM' ) 
					$varName = 'damAkcNumber';
			else
					$varName = 'sireAkcNumber';

			$litterNo = substr($akcNumber, 0, 8);
			$stmt = $this->db->prepare("SELECT akcNumber, ".$varName." from dogInfo where akcNumber like ?");
			$search = $litterNo."__";
			$stmt->bind_param('s', $search);
			$stmt->execute();
			$result=$stmt->get_result();
			$suggestParent = "";
			while( $row = $result->fetch_assoc() ) {
				$currentAkcNumber = $row{'akcNumber'};
				$currentParentAkcNumber = $row{$varName};
				if( !empty($currentParentAkcNumber ) ) {
					if( $currentAkcNumber == $akcNumber ) {
						$suggestParent = $currentParentAkcNumber;
						break;
					}
					$suggestParent = $currentParentAkcNumber;
				}
			}
			return $suggestParent;
		}
	}
}

/*
$dogInfoDB = new DogInfoDB();
$results = $dogInfoDB->suggest("DAM", "testonly88");
print_r($results);
 */
?>
