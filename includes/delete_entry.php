<?php
	
	/**
		* @package    User-Configuration
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file deletes an entry.
	*/
	//Check if user is logged in
	include 'template_session.php';
	
	//validate input
	if (!(isset($_POST['str']))) {
		header("location: ../list_all.php");
		exit;
	}
	
	$index = strip_tags(trim($_POST['str']));
	
	if(isset($link)) {
		
		
		$sql2 = "DELETE FROM saml_groups WHERE userID ='" . $index . "';" ;
		mysqli_query($link, $sql2);
		
		$sql = "DELETE FROM saml_users WHERE ID ='" . $index . "';" ;
		mysqli_query($link, $sql);
		
		
	}
	
?>