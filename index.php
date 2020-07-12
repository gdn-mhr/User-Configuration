<?php
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
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: user_login.php");
    exit;
}
?>
 
<?php
	include 'includes/template_header.php';
?>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Willkomen.</h1>
    </div>
    <h4>Bitte nutze eine der folgenden Optionen, um die Benutzer anzusehen oder zu ändern!</h4>
	<br>
	
	<h5>Neuen Benutzer erstellen</h5>
	<p><a style="display:inline-block; padding:5px;" href="new_entry.php" class="btn btn-outline-success">Neuen Benutzer erstellen</a></p>
		
	<h5>Schaue dir alle Einträge an</h5>
	<p><a style="display:inline-block; padding:5px;" href="list_all.php" class="btn btn-outline-info">Alle Benutzer anzeigen</a></p>
	
	<h3>Benutzerverwaltung</h3>
	<br>
        <p><a style="display:inline-block; padding:5px;" href="user_password.php" class="btn btn-outline-warning">Passwort ändern</a>
        <a style="display:inline-block; padding:5px;" href="user_logout.php" class="btn btn-outline-danger">Abmelden</a>
        <?php if ( $_SESSION["access_level"] > 4) {echo '<a style="display:inline-block; padding:5px;" href="user_register.php" class="btn btn-outline-success">Neuen Benutzer erstellen</a>';} ?>
		<?php if ( $_SESSION["access_level"] > 4) {echo '<a style="display:inline-block; padding:5px;" href="user_list.php" class="btn btn-outline-info">Benutzer anzeigen</a>';} ?>
		</p>
<?php
	include 'includes/template_footer.php';
?>