<?php
// Include config file
require_once "includes/template_config.php";
die();
if($link) {
	//table for users
	$usr = "CREATE TABLE saml_admin_users (
		id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		username VARCHAR(1024) NOT NULL UNIQUE,
		password TEXT NOT NULL,
		access_level VARCHAR(1) NOT NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP
	);";
	mysqli_query($link, $usr);
	
	//default user
	$param_password = password_hash("12345678", PASSWORD_DEFAULT);
	$def_usr = "INSERT INTO saml_admin_users (username, password, access_level) VALUES ('root', '" . $param_password . "', 5);";
	mysqli_query($link, $def_usr);
	
	$main = "CREATE TABLE saml_users (
		ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		user VARCHAR(1024) NOT NULL UNIQUE,
		email VARCHAR(1024) NOT NULL UNIQUE,
		password TEXT NOT NULL,
		salt BLOB NOT NULL,
		firstname VARCHAR(1024) NOT NULL,
		lastname VARCHAR(1024) NOT NULL
	);";
	mysqli_query($link, $main);
	
	$group = "CREATE TABLE saml_groups (
		ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		userID INT NOT NULL,
		groups VARCHAR(1024) NOT NULL,
		FOREIGN KEY (userID) REFERENCES saml_users(ID)
	);";
	mysqli_query($link, $group);
	
	echo "Success! ";
	echo "You should now delete this file!";
	
} else {
	echo "Error";
}
?>