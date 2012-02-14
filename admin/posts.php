<?php
// mycloud Admin index page

define("PAGE", "ad_posts");
define("ROOT", "../");
require("../inc/main.php");

define("PAGE_TITLE", _("Posts"));

require_admin();

$posts = get_posts();

require("../inc/templates/admin.posts.php");
