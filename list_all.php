<?php
	
	/**
		* @package    User Configuration
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file lists all users.
	*/
	//Check if user is logged in
	include 'includes/template_session.php';
	
	// Check if the user has valid access_level
	//if($_SESSION["access_level"]<5){
	//	header("location: index.php");
	//	exit;
	//}

	include 'includes/template_header.php';
?>	
<h2>Benutzer verwalten</h2>

<?php
		
		$sql = "SELECT saml_users.ID as ID, ENABLED, user, email, firstname, lastname, GROUP_CONCAT(saml_groups.groups SEPARATOR ',') as groups FROM saml_users LEFT JOIN saml_groups on saml_users.ID = saml_groups.userID GROUP BY user;";

		$result = $link->query($sql);
		
		//prepare statement to retrieve real data
		
		
		$cols = array();
		while($row = mysqli_fetch_array($result))
		{
			$thisone = array();
			$thisone[0] = $row['ID'];
			$thisone[1] = $row['ENABLED'];
			$thisone[2] = $row['user'];
			$thisone[3] = $row['email'];
			$thisone[4] = $row['firstname'];
			$thisone[5] = $row['lastname'];
			$thisone[6] = $row['groups'];
			$cols[$row['ID']] =  $thisone;
		}
		
		print_r(cols[7]);
		
		
		
		echo "<div><table id=\"table\" 
		data-locale=\"de-DE\"
		data-toggle=\"table\"
		data-search=\"true\"
		data-show-export=\"true\"
		data-pagination=\"true\"
		data-id-field=\"ID\"
		data-page-list=\"[10, 25, 50, 100, all]\"
		data-editable=\"true\"
		data-editable-url=\"includes/update_entry.php\"
		
		data-show-columns=\"true\" 
		data-toolbar=\"#toolbar\" 
		data-show-columns-toggle-all=\"true\"
		data-click-to-select=\"true\" 
		data-page-size=\"50\" 
		data-filter-control=\"false\" 
		data-show-search-clear-button=\"true\">
		<thead>
		<tr>";
		
		echo "<th data-field=\"ID\" data-editable=\"false\">ID</th>";
		echo "<th data-field=\"enabled\" data-editable=\"true\" data-filter-control=\"input\" data-sortable=\"true\" >Aktiviert?</th>";
		echo "<th data-field=\"user\" data-editable=\"true\" data-filter-control=\"input\" data-sortable=\"true\" >Benutzername</th>";
		echo "<th data-field=\"email\" data-editable=\"true\" data-filter-control=\"input\" data-sortable=\"true\" >Email-Adresse</th>";
		echo "<th data-field=\"firstname\" data-editable=\"true\" data-filter-control=\"input\" data-sortable=\"true\" >Vorname</th>";
		echo "<th data-field=\"lastname\" data-editable=\"true\" data-filter-control=\"input\" data-sortable=\"true\" >Nachname</th>";
		echo "<th data-field=\"groups\" data-editable=\"true\" data-filter-control=\"input\" data-sortable=\"true\" >Gruppen</th>";
		echo '<th data-field="Delete" data-editable="false" data-formatter="operateFormatter" data-events="operateEvents">Delete & Update</th>';
		echo "</tr>";
		echo "</thead>";		
		
		foreach ($cols as $i => $cname) {
			echo "<tr>";
			echo "<td>" . $cname[0] . "</td>";
			echo "<td>" . $cname[1] . "</td>";
			echo "<td>" . $cname[2] . "</td>";
			echo "<td>" . $cname[3] . "</td>";
			echo "<td>" . $cname[4] . "</td>";
			echo "<td>" . $cname[5] . "</td>";
			echo "<td>" . $cname[6] . "</td>";
			echo "<td data-record-id=\"" . $cname[0] . "\" data-record-title=\"" . $cname[2] . "\" >" . $cname[0] . "</td>";
			
			echo "</tr>";
		}
		unset($cname);
		unset($i);
		echo "</table> </div>";	
?>

<hr>

<!-- Option to create a new view -->
<div style="max-width: 500px; display: block; margin-left: auto; margin-right: auto;">
	<h2>Neuen Benutzer anlegen</h2>
	<a href="new_entry.php"><button type="submit" class="btn btn-outline-success">Neu</button></a>
</div>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Löschen bestätigen</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				
			</div>
			<div class="modal-body">
				<p>Willst Du <b><i class="title"></i></b> wirklich löschen?</p>
				<p>Diese Aktion kann nicht rückgängig gemacht werden!</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
				<button type="button" class="btn btn-danger btn-ok">Löschen</button>
			</div>
		</div>
	</div>
</div>

<div id="toolbar">
	<?php echo isset($buttons) ? $buttons : ''; ?>
	<button id="filter-toggle" class="btn btn-outline-info" onClick="toggleFilter();" >
		<i class="glyphicon glyphicon-remove"></i> Erweiterte Filter
	</button>
</div>

<?php 
	include 'includes/template_footer.php';
?>

<script>
	$.fn.editable.defaults.mode = 'inline';
	
	$(function() {
		$('#table').bootstrapTable();
	})
	
    function operateFormatter(value, row, index) {
		//if (1 > value) {
			return '<div> <a class="change_pass" href="change_pass.php?id=' +  value + '" onclick="location.replace(\'change_pass.php?id=' +  value + '\'),\'_top\'" title="Passwort ändern" data-toggle=\"modal\" data-target=\"change_pass.php\"><i class="fa fa-key" style="padding-right: 10px; color: green;"></i></a> <a class="remove" href="javascript:void(0)" title="Löschen" data-toggle=\"modal\" data-target=\"#confirm-delete\"><i class="fa fa-trash" style="color: red;" ></i></a></div>'
		//	} else {
		//	return '<a class="locked" href="javascript:void(0)" title="This item has been locked."><i class="fa fa-lock" style="color: orange;" ></i></a>'
		//}
	}
	
    var operateEvents = {
		'click .remove': function (e, value, row, index) {
			$('#confirm-delete').on('click', '.btn-ok', function(e) {
				var $modalDiv = $(e.delegateTarget);
				var id = row['ID'];
				$.post('includes/delete_entry.php', { str: id }).then(function() {
					$modalDiv.modal('hide');
					location.reload();
				})});
				
				$('#confirm-delete').on('show.bs.modal', function(e) {
					var data = $(e.relatedTarget).data();
					$('.title', this).text(row['user']);
					$('.btn-ok', this).data('recordId', data.recordId);
				});
		},
		'click .locked': function (e, value, row, index) {
		
		}
	}
	
	
	var isset = false
	function toggleFilter () {
		if (isset == true) { 
			$('#table').bootstrapTable('refreshOptions', {
				filterControl: false,
			});
			isset = false;
			} else {
			$('#table').bootstrapTable('refreshOptions', {
				filterControl: true,
			});
			isset = true;
		}
	}
</script>