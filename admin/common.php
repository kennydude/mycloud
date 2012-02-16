<?php
// Common Admin
require("../inc/main.php");

function dashboard_tabs(){
	global $admin_tabs;
	$admin_tabs = array(
		"index.php" => array(
			"key" => "dash",
			"name" => _("Dashboard")
		),
		"settings.php" => array(
			"key" => "settings",
			"name" => _("Settings")
		)
	);
}