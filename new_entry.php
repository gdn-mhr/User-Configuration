<?php
	
	/**
		* @package    User Configuration
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file enables the user to eadd an entry.
	*/
	//Check if user is logged in
	include 'includes/template_session.php';
	


	// Include config file
	require_once "includes/template_config.php";
	 
	// Define variables and initialize with empty values
	$username = $password = "";
	$username_err = $password_err = $email_err = "";

	$email = $firstname = $lastname = $groups = "";
	 
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		$pw = strip_tags( trim($_POST["password"]));
		$usr = strip_tags( trim($_POST["user"]));
		$mail = strip_tags( trim($_POST["email"]));
		
		$firstname = strip_tags( trim($_POST["firstname"]));
		$lastname = strip_tags( trim($_POST["lastname"]));
		$groupsstr = strip_tags( trim($_POST["groups"]));
		
		$groupsarr = array();
		$groupsarr = explode(',', $groupsstr);
		
		
	 
		// Validate username
		if(empty($usr)){
			$username_err = "Please enter a username.";
		} else{
			// Prepare a select statement
			$sql = "SELECT ID FROM saml_users WHERE user = '" . $usr . "'";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				//mysqli_stmt_bind_param($stmt, "s", $param_username);
				
				// Set parameters
				//$param_username = $usr;
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					/* store result */
					mysqli_stmt_store_result($stmt);
					
					if(mysqli_stmt_num_rows($stmt) == 1){
						$username_err = "This username is already taken.";
					} else{
						$username = $usr;
					}
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}
			 
			// Close statement
			mysqli_stmt_close($stmt);
		}
		
		// Validate username
		if(empty($mail)){
			$email_err = "Please enter a email.";
		} else{
			// Prepare a select statement
			$sql = "SELECT ID FROM saml_users WHERE email = '" . $mail . "'";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				//mysqli_stmt_bind_param($stmt, "s", $param_email);
				
				// Set parameters
				//$param_email = $mail;
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					/* store result */
					mysqli_stmt_store_result($stmt);
					
					if(mysqli_stmt_num_rows($stmt) == 1){
						$username_err = "This email is already taken.";
					} else{
						$email = $mail;
					}
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}
			 
			// Close statement
			mysqli_stmt_close($stmt);
		}
		
		// Validate password
		if(empty($pw)){
			$password_err = "Please enter a password.";     
		} else{
			$password = $pw;
		}
		
		// Check input errors before inserting in database
		if(empty($username_err) && empty($password_err) && empty($email_err)){
			//encrypt password
			 $salt = bin2hex(openssl_random_pseudo_bytes(64));
			 $param_password = hash("sha512", ($salt.$plainPassword));
			// Prepare an insert statement
			//$sql = "INSERT INTO saml_users (ENABLED, user, email, password, salt, lastname, firstname, groups) VALUES (1, '" . $username . "', '" . $email . "', '" . $param_password . "', '" . $salt . "', '" . $lastname . "', '" . $firstname . "', '" . $groups . "')";
			$sql = "INSERT INTO saml_users (ENABLED, user, email, password, salt, lastname, firstname) VALUES (1, '" . $username . "', '" . $email . "', SHA2(CONCAT('" . $salt . "', '" . $password . "'), 512), '" . $salt . "', '" . $lastname . "', '" . $firstname . "')";
			if(!(mysqli_query($link, $sql))){
				echo "Something went wrong. Please try again later.";
			}
			
			foreach($groupsarr as $current) {
				$sql = "INSERT INTO saml_groups (userID, groups) VALUES ((SELECT ID FROM saml_users WHERE user = '" . $username. "'), '" . $current . "')";
				if(mysqli_query($link, $sql)){
					continue;
				} else {
					echo "Something went wrong. Please try again later.";
				}
			}
		}
		//echo $username_err . $password_err . $email_err . $mail . $usr . $email . $username;
		// Close connection
		//mysqli_close($link);
		header("location: index.php");
		exit;
	}

	//Start the HTML document with the header
	include 'includes/template_header.php';
	
?>

<div style="max-width: 500px; display: block; margin-left: auto; margin-right: auto;">

	

	<h2>Neuen Eintrag hinzufügen</h2>

	<br>

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" role="form" class="form-horizontal">
		<div class="form-group">
			<label for="user">USERNAME</label>
			<input type="text" class="form-control" id="user"  name="user" autocomplete="off">
		</div>
		
		<div class="form-group">
			<label for="email">EMAIL</label>
			<input type="text" class="form-control" id="email"  name="email" autocomplete="off">
		</div>
		
		<div class="form-group">
			<label for="password">PASSWORD</label>
			<input style="text-security:disc;-webkit-text-security:disc;-mox-text-security:disc;" type="text" class="form-control" id="password"  name="password" autocomplete="off">
		</div>
		
		<div class="form-group">
			<label for="lastname">LASTNAME</label>
			<input type="text" class="form-control" id="lastname"  name="lastname" autocomplete="off">
		</div>
		
		<div class="form-group">
			<label for="firstname">FIRSTNAME</label>
			<input type="text" class="form-control" id="firstname"  name="firstname" autocomplete="off">
		</div>
		
		<div class="form-group">
			<label for="groups">USERGROUPS (Comma separated without spaces)</label>
			<input type="text" class="form-control" id="groups"  name="groups">
		</div>		

		<button type="submit" class="btn btn-outline-success">Speichern</button>

	</form>

</div>


<?php 
	include 'includes/template_footer.php';
?>