<?php
	
	/**
		* @package    User Configuration
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file lets a user update his password.
	*/
	// Initialize the session
	session_start();
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
		// last request was more than 30 minutes ago
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
		header("location: user_login.php");
		exit;
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	
	// Check if the user is logged in, if not then redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: user_login.php");
		exit;
	}
	
	// Include config file
	require_once "includes/template_config.php";
	
	
	
	
	// Define variables and initialize with empty values
	$new_password = $confirm_password = "";
	$new_password_err = $confirm_password_err = "";
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$pw = strip_tags( trim($_POST["new_password"]));
		$cpw = strip_tags( trim($_POST["confirm_password"]));
		$id = strip_tags( trim($_POST["id"]));
		
		// Validate new password
		if(empty($pw)){
			$new_password_err = "Please enter the new password.";     
			} elseif(strlen($pw) < 6){
			$new_password_err = "Password must have atleast 6 characters.";
			} else{
			$new_password = $pw;
		}
		
		// Validate confirm password
		if(empty($cpw)){
			$confirm_password_err = "Please confirm the password.";
			} else{
			$confirm_password = $cpw;
			if(empty($new_password_err) && ($new_password != $confirm_password)){
				$confirm_password_err = "Password did not match.";
			}
		}
        
		// Check input errors before updating the database
		if(empty($new_password_err) && empty($confirm_password_err)){
			// Prepare an update statement
			$salt = bin2hex(openssl_random_pseudo_bytes(64));
			$sql = "UPDATE saml_users SET password = SHA2(CONCAT('" . $salt . "', '" . $new_password . "'), 512), salt = '" . $salt . "' WHERE ID = '" . $id . "'";
			if(!(mysqli_query($link, $sql))){
				echo "Something went wrong. Please try again later2.";
			} else {
				header("location: list_all.php");
				exit();
			}
		}
		
		// Close connection
		mysqli_close($link);
	} else {
		if (!(isset($_GET['id']))) {
			header("location: index.php");
			exit;
		}
		
		$id = htmlspecialchars($_GET["id"]);
		if (!(is_numeric($id))) {
			header("location: list_all.php");
			exit;
		}
	}
?>

<?php
	include 'includes/template_header.php';
?>

<div class="wrapper">
	<h2>Reset Password</h2>
	<p>Please fill out this form to set a new password for user <?php echo $id ?>.</p>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>" />
		<div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
			<label>New Password</label>
			<input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
			<span class="help-block"><?php echo $new_password_err; ?></span>
		</div>
		<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
			<label>Confirm Password</label>
			<input type="password" name="confirm_password" class="form-control">
			<span class="help-block"><?php echo $confirm_password_err; ?></span>
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-primary" value="Submit">
			<a class="btn btn-link" href="index.php">Cancel</a>
		</div>
	</form>
</div>    
<?php
	include 'includes/template_footer.php';
?>	