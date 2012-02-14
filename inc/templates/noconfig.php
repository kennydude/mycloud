<?php

define("PAGE_TITLE", "No configuration");
include "header.php";

?>
<div class="bigmoanbox">
<h1><?php L("No Configuration"); ?></h1>
<p class="lead"><?php L("mycloud isn't conifgured! You need to configure it to work!"); ?></p>
<?php if($reason){ ?>
<p><strong><?php L("Reason/Extra Information: "); ?></strong><?php echo $reason; ?></p>
<?php } ?>
</div>
<?php

include "footer.php";

?>
