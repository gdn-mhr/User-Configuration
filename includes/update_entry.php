<?php
	
	/**
		* @package    User Configuration
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file saves changes to an entry.
	*/
	//Check if user is logged in
	include 'template_session.php';
	
	//validate input
	if (!(isset($_POST['name']))) {
		exit;
	}
	
	$name = strip_tags(trim($_POST['name']));
	
	
	if (!(isset($_POST['value']))) {
		exit;
	}
	
	$value = strip_tags(trim($_POST['value']));
	
	
	if (!(isset($_POST['pk']))) {
		exit;
	}
	
	$pk = strip_tags(trim($_POST['pk']));
	if (!(is_numeric($pk))) {
		exit;
	}
	
	
	if(isset($link)) {
	
		if($name != "groups") {
			$value = mysqli_real_escape_string($link, $value);
			$upd = "UPDATE saml_users SET `" . $name . "` = '" .  $value . "' WHERE ID = '" .  $pk . "'";   
			
			mysqli_query($link, $upd);
		} else {
			$groupsarr = array();
			$groupsarr = explode(',', $value);
			
			
			$sql = "DELETE FROM saml_groups WHERE userID = '" . $pk. "'";
			mysqli_query($link, $sql);
			foreach($groupsarr as $current) {
				$sql = "INSERT INTO saml_groups (userID, groups) VALUES ('" . $pk. "', '" . $current . "')";
				if(mysqli_query($link, $sql)){
					continue;
				} else {
					echo "Something went wrong. Please try again later.";
				}
			}
		
		}
		
	}
	
?>