<?php
// mycloud Admin index page

define("PAGE", "ad_dash");
define("ROOT", "../");
define("SUBPAGE", "dash");
require("common.php");
dashboard_tabs();

define("PAGE_TITLE", _("Admin for ") . $blogName);

require_admin();

require("../inc/templates/admin.dashboard.php");
