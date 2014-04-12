<?php

/*

	Author: Jay Engineer
	Home Page: https://github.com/jay754/lifegoals-app
	Script: lifegoals App

	license: The BSD 3-Clause License
*/

session_start(); //session start

class Dreams{

	//main class for the entire website

	/*
	todo:

	1. add a gravatar method
	2. add update user method
	*/

    public $db = null; //this is the db handeler for the entire class

	/**
		the constructor Method
		
		@paras - none
		
		Setting up the db for the rest of the class so don't have to keep reopening connection to db
	**/

    function __construct(){
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=beta', "root", "");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
        catch(PDOException $e) {
           echo 'ERROR: ' . $e->getMessage();
        }
    }

	/**
		the gravatar Method
		
		@paras - $currentUserEmail
		
		Getting the current Users gravatar if they have one
	**/

    public static function gravatar($currentUserEmail){
        $gravatar_email = MD5($currentUserEmail);
        $gravatar_url = "https://secure.gravatar.com/avatar/".$gravatar_email;

        return $gravatar_url;
    }


	/**
		the redirect Method
		
		@paras - None
		
		Redirecting the user if logged in
	**/

    public static function redirect(){
        if(loggedin()){
            header('Location: index.php');	
        }
    }

	/**
		getUsers Method
		
		@paras - none
		
		Returns the all the users of the website
	**/

    public function getUsers(){
        $stmt = $this->db->prepare('SELECT * FROM users');

        if($stmt->execute()){
            $data = array();

            while($row = $stmt->fetch()){
                $data[] = $row;
            }

            return $data;
        }
        else{
            return "error with getting the content";
        }
    }

	/**
		loggedin Method
		
		@paras - none
		
		Returns if the user is logged in or not
	**/

    public function loggedin(){
        if ((isset($_SESSION['name']))){
            return True;
        }
    }

	/**
		registerUser Method
		
		@paras - $firstname, $lastname, $password, $username, $ip, $code
		
		Registers the user in the database.
		Also emails the user with the activation link
	**/

    public function registerUser($fistname, $lastname, $email, $password, $username, $ip. $code){
        $stmt = $this->db->prepare('INSERT INTO users (Firstname, Lastname, Email, Password, Code, Activation) 
                                    VALUES (:first, :last, :email, :pass, :ip, :code, :activation)');

        $activation = 0;
        $data->bindParam(":first", $firstname);
        $data->bindParam(":last", $lastname);
        $data->bindParam(":email", $email);
        $data->bindParam(":pass", md5($password));
        $data->bindParam(":ip", $ip);
        $data->bindParam(":code", $code);
        $data->bindParam(":activation", $activation);
        $data->execute();

        if($data->execute()){
            $to = $email;
            $subject = "email activation";
            $headers = "from: infolifegoals.com";
            $body = "Please click the following link to activate your account activate.php/?code=".$code;
			
            if(mailto($to, $subject, $body, $headers)){
               echo "An Email has been sent";
            }
            else {
               echo "an error in sending email";
            }
        }
        else{
            echo "error in inserting into database";
        }
    }

	/**
		banIP Method
		
		@paras - $userId 
		 
		To check whether or not the current user is banned from the site
	**/

    public function banIP($userId){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE User_id=:id");
        $data->bindParam(":id", $userId);
        $data->execute();
        $result = $data->fetch(PDO::FETCH_ASSOC);

        if($result['banned'] == 1){
            return "You have been banned from this site";
        }
    }

	/**
		checkActivation Method
		
		@paras - $userId 
		 
		To see whether or not the user has been activated on the site
	**/

    public function checkActivation($userId){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE User_id=:id");
        $data->bindParam(":id", $userId);
        $data->execute();
        $result = $data->fetch(PDO::FETCH_ASSOC);

        if($result['Activation'] == 0){
            return "You have not been activated for this site";
        }
    }

	/**
		selectCode Method
		
		@paras - $codeId
		 
		To Display the id of the unique key given to the user to activate the account
	**/

    public function selectCode($codeId){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE Code=:code");
        $data->bindParam(":code", $codeID);
        $data->execute();

        if($stmt->execute()){
            $data = array();

            while($row = $stmt->fetch()){
                $data[] = $row;
            }

            return $data;
        }
    	
        else {
            return "error with getting the content";
        }
    }

	/**
		updateUser Method
		
		@paras - $codeId
		 
		Updating the user from 0 to 1 if clicked the right link after sent to email
	**/

    public function updateUser($codeID){
        $stmt = $this->db->prepare("UPDATE users SET Activation='1' WHERE Code=:code");
        $data->bindParam(":code", $codeID);
        $data->execute();

        if($data->execute()){
            print "your account is now active";
        }
        else{
            print "there was an error with updating";
        }
    }

	/**
		getCurrentUser method
		
		@paras - $userID
		 
		getting the current user on the homepage and displaying info or to update info
	**/

    public function getCurrentUser($userid){
        $stmt = $this->db->prepare('SELECT * FROM users 
                                    WHERE User_id = :id');

        $data->bindParam(":id", $userid);
        $data->execute();

        if($data->execute()){
            $data = array();

            while($row = $stmt->fetch()){
                $data[] = $row;
            }

            return $data;
        }
        else {
            return "error with getting the content";
        }
    }

	/**
		Create List method
		
		@paras - $userID, $title, $desc
		 
		A method for creating a list
	**/

    public function createList($userId, $title, $desc){
        $stmt = $this->db->prepare('INSERT INTO lists (User_id, Title, Des, date) 
                                    VALUES (:id, :title, :desc, :date)');

        $data->bindParam(":id", $userId);
        $data->bindParam(":Title", $title);
        $data->bindParam(":desc", $desc);
        $data->bindParam(":date", "NOW()");
        $data->execute;

        if($data->execute()){
            print "post succesfully inserted";
        }
        else {
            print "error with getting the content";
        }
    }

	/**
		Insert Feed method
		
		@paras - $userID, $title, $listid
		 
		A method for after creating the list and logging the info into db
	**/

    public function insertFeed($userId, $title, $listid){
        $stmt = $this->db->prepare('INSERT INTO feed (User_id, feed, Action, date, list_id) 
                                    VALUES (:id, :title, :action, :date, :list_id)';

        $action = "created a list";
        $data->bindParam(":id", $userId);
        $data->bindParam(":Title", $title);
        $data->bindParam(":desc", $action);
        $data->bindParam(":date", "NOW()");
        $data->bindParam(":date", $listid);
        $data->execute;

        if($data->execute()){
            print "post succesfully inserted";
        }
        else {
            print "error with getting the content";
        }
    }

	/**
		getListsforUser Feed method
		
		@paras - $userID
		 
		A method for listing lists for the current user
		Had to do a left join with the user table and lists table using the current user Id
	**/

    public function getListsforUser($userid){
        $stmt = $this->db->prepare('SELECT users.User_id, lists.*
                                    FROM users
                                    LEFT JOIN lists
                                    ON users.User_id=lists.User_id
                                    WHERE users.User_id = :id
                                    ORDER BY DATE ASC');
		
        $data->bindParam(":id", $id);
        $data->execute;

        if($data->execute()){
           $data = array();

            while($row = $stmt->fetch()){
            	$data[] = $row;
            }

            return $data;
        }
        else {
            return "error with getting the content";
        }
    }

	/**
		getfeedforUser method
		
		@paras - $userID
		 
		A method for the feed for the current user
		Had to do a left join with the user table and feed table using the current user Id
	**/

	public function getFeedforUser($userID){
        $stmt = $this->db->prepare("SELECT users.User_id, feed.*
                                    FROM users
                                    LEFT JOIN feed
                                    ON users.User_id=feed.User_id
                                    WHERE users.User_id = :id
                                    ORDER BY DATE DESC LIMIT 15");

        $data->bindParam(":id", $id);
        $data->execute;

        if($data->execute()){
            $data = array();

            while($row = $stmt->fetch()){
                $data[] = $row;
            }

            return $data;
        }
        else {
            return "error with getting the content";
        }
    }

	/**
		Useridandlist Feed method
		
		@paras - $userId, $itemId
		 
		A Method for listing the item along with user who owns it so no one else can edit or delete the list
	**/

    public function UserIdandlist($userId, $itemId){
        $stmt = $this->db->prepare("SELECT * FROM lists 
                                    WHERE item_id=:itemId AND User_id=:userId");
		
        $data->bindParam(":itemId", $itemId);
        $data->bindParam(":userId", $userId);
        $data->execute();

        if($stmt->execute()) {
            $data = array();

            while($row = $stmt->fetch()){
                $data[] = $row;
            }

            return $data;
        }
        else {
            return "error with getting the content";
        }
    }

	/**
		deleteList method
		
		@paras - $Id, $itemId
		 
		A Method for deleting a list Id
	**/

    public function deleteList($id, $userId){
        $stmt = $this->db->prepare("DELETE FROM lists 
                                    WHERE item_id=:id
                                    AND User_id=:userId");

        $data->bindParam(":id", $id);
        $data->bindParam(":userId", $userId);
        $data->execute;

        if($data->execute()){
            print "list destroyed"
        }
        else {
            print "error with getting the content";
        }
    }

	/**
		displayList method
		
		@paras - $listId
		 
		A Method for displaying a list Id
	**/

	public function displayList($listId){
		$stmt = $this->db->prepare("SELECT * FROM lists 
									WHERE item_id=:id";
	
		$data->bindParam(":id", $listId);
		$data->execute;

		if($data->execute()){
			$data = array();

			while($row = $stmt->fetch()){$data[] = $row;}

    		return $data;
    	}
    	else {
    		return "error with getting the content";
    	}
	}

	/**
		editList method
		
		@paras - $title, $desc, $item_id, $user_id
		
		A Method for editing and than updating a list Id
	**/

	public function editList($title, $desc, $item_id, $user_id){
		$stmt = $this->db->prepare("UPDATE lists 
									SET Title=:title, Desc=:desc
									WHERE item_id=:itemid 
									AND User_id=:userId");
		
		$data->bindParam(":title", $title);
		$data->bindParam(":desc", $desc);
		$data->bindParam(":itemid", $item);
		$data->bindParam(":userid", $user_id);
		$data->execute;

		if($data->execute()){
			print "saved succesfully";
    	}
    	else {
    		print "error with getting the content";
    	}
	}

	/**
		getFollowers method
		
		@paras - $userId, 
		 
		A Method for getting all the followers for the user
	**/

	public function getFollowers($userId){
		$stmt = $this->db->prepare("SELECT relationship.*, users.*
									FROM relationship
									LEFT JOIN users
									ON relationship.Follower_id=users.User_id
									WHERE users.User_id = :userId");

		$data->bindParam(":userId", $userId);
		$data->execute;

		if($data->execute()){
			$data = array();

			while($row = $stmt->fetch()){$data[] = $row;}

    		return $data;
    	}
    	else {
    		return "error with getting the content";
    	}
	}

	/**
		getFollowing method
		
		@paras - $userId, 
		 
		A Method for getting all the following for the user
	**/

	public function getFollowing($userId){
		$stmt = $this->db->prepare("SELECT relationship.*, users.*
									FROM relationship
									LEFT JOIN users
									ON relationship.Following_id=users.User_id
									WHERE users.User_id = :userId");

		$data->bindParam(":userId", $userId);
		$data->execute;

		if($data->execute()){
			$data = array();

			while($row = $stmt->fetch()){$data[] = $row;}

    		return $data;
    	}
    	else {
    		return "error with getting the content";
    	}
	}
}

$betaOBJ = new Dreams();
?>