<?php
// mycloud Admin index page

define("PAGE", "ad_dash");
define("ROOT", "../");
require("../inc/main.php");

define("PAGE_TITLE", _("Admin for ") . $blogName);

require_admin();

require("../inc/templates/admin.dashboard.php");
