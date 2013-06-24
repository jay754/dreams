<?php
require "classes.php";
?>

<html>

<head>

<title>Login - LifeGoals App</title>

</head>

<body>

<form action='login.php' method='post'>
Username: <br /> <input type='text' name='username'/> <br />
Password: <br /> <input type='password' name='password' /> <br />
<input type="submit" value="login" /> <br />

<?php
if((isset($_POST["username"])&&isset($_POST["password"]))&&(!empty($_POST["username"]))&&(!empty($_POST["username"]))){
	$username = $_POST["username"];
	$password = $_POST["password"];
	try {
	    	$db = new PDO('mysql:host=localhost;dbname=beta', "root", "");
	    	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	    	$data = $db->prepare("SELECT * FROM users WHERE FirstName = :username AND password = :password");
	    	$data->bindParam(":username", $username);
			$data->bindParam(":password", $password);
	    	$data->execute();
	    	$result = $data->fetch(PDO::FETCH_ASSOC);
	    	if($data->execute()==1){
	    		$_SESSION["id"] = $result["P_id"];
	    		$_SESSION["name"] = $result["FirstName"];

	    		header('Location: index.php');
	    	}
	    	else{
	    		print "wrong combo";
	    	}
		} catch(PDOException $e) {
	    	echo 'ERROR: ' . $e->getMessage();
		}
}
?>

</body>

</html>