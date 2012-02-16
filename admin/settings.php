<?php
// Settings
define("PAGE", "ad_dash");
define("SUBPAGE", "settings");
define("ROOT", "../");
require("common.php");

dashboard_tabs();

define("PAGE_TITLE", _("Settings"));

require_admin();

require(ROOT . "inc/forms.php");

class SettingsForm extends Form{
	public function add_field($field){
		include ROOT . "config.php";
		$fn = $field->name;
		if($fn == "apps"){
			$field->set_value(array_keys($$fn));
		} else
			$field->set_value($$fn);
		parent::add_field($field);
	}

	public function handle_posting(){
		echo "todo";
	}
}

$form = new SettingsForm("settins", array("tabs"));
$form->section(_("General"));

// Generic stuff
$form->add_field(new Field("blogName", _("Site Name")));
$form->add_field(new Field("blogTagline", _("Site Tagline")));

$form->add_field(new TextArea("welcomeText", _("Welcome text")));

$p_themes = dir_contents(ROOT . "content/themes");
$themes = array();
foreach ($p_themes as $value) {
	$themes[$value] = $value;
}
$form->add_field(new SelectBox("theme", _("Site Theme"), $themes));

$setup = array(
	"posts" => "Show most recent posts",
	"page" => "Show a page"
);
$form->add_field(new SelectBox("home", _("Homepage setup"), $setup));

$form->add_field(new Field("public_addr", _("Public Address")));

// Apps
$p_apps = dir_contents(ROOT . "content/apps");
$apps = array();
$en_apps = array();
foreach($p_apps as $app){
	$apps[$app] = json_decode(file_get_contents(ROOT . "content/apps/" . $app . "/manifest.json"), true);
	$en_apps[$app] = "<strong>" . $apps[$app]['name'] . "</strong>: " . $apps[$app]['description'];
}
$form->add_field(new CheckboxArray("apps", _("Enabled Applications"), $en_apps));

foreach($apps as $app){
	if($app['settings']){
		$form->section($app['name']);
		
	}
}

// Database setup
$form->section(_("Setup"));

$form->add_field(new Field("salt", _("Security Salt")));

$types = array(
	"mysql" => _("MySQL"),
	"sqlite" => _("SQLite"),
	"pgsql" => _("PostgreSQL") 
);
$form->add_field(new SelectBox("dbType", _("Database type"), $types));
$form->add_field(new Field("dbConnectDetails", _("Database location")));
$form->add_field(new Field("dbUser", _("Database Username")));
$form->add_field(new Field("dbPassword", _("Database Password")));

$form->done();

include ROOT . "inc/templates/admin.header.php";

echo "<h1>" . _("Settings") . "</h1>";

$form->render();

include ROOT . "inc/templates/admin.footer.php";
