<?php

session_start();

class lifegoals{

	public $db = null;

	function __construct(){
		try {
	    	$this->db = new PDO('mysql:host=localhost;dbname=beta', "root", "");
	    	$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
	    	echo 'ERROR: ' . $e->getMessage();
		}
	}

	public function getUsers(){
		$stmt = $this->db->prepare('SELECT * FROM users');
		if($stmt->execute()){
			$data = array();

			while($row = $stmt->fetch()){$data[] = $row;}

    		return $data;
    	}
    	else{
    		return "error with getting the content";
    	}
	}

	public function loggedin(){
		if ((isset($_SESSION['name']))){
			return True;
		}
	} 
}

$betaOBJ = new lifegoals();
?>