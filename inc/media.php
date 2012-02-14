<?php
// Mycloud media handler

function image_uploader($image = ''){
	?>
	<fieldset class="uploader">
	<?php if($image != '') {
		L("Current Images: ");echo '<br/>';
		display_images($image, 0, 50, 50);
	} ?><br/>
	<input type="file" name="f" multiple="true" id="uploader" />
	</fieldset>
	<?php
}

function display_images($images, $max=0, $width=0, $height=0){
	$i = 0;
	foreach(explode(";", $images) as $image){
		if($max != 0 && $i > $max){
			break;
		}
		if($image != ""){
			$src = ROOT . "content/media/$image";

			if($width != 0 or $height !=0){
				$src = ROOT . "thumb.php?file=" . rawurlencode($image) . "&height=$height&width=$width";
			}

			echo "<img src=\"$src\" />";
			$i++;
		}
	}
}

/**
 * Handle any image uploads
 */
function image_uploader_handler($old = ''){
	$uploads = array();
	$uploads_dir = ROOT . "content/media";
	echo $uploads_dir;
	print_r($_FILES);
	foreach ($_FILES as $file) {
		if ($file["error"] == UPLOAD_ERR_OK) {
			$tmp_name = $file["tmp_name"];
			$name = $file["name"];
			
			$ext = explode(".", $name);
			$ext = $ext[count($ext) - 1];
			$name = sha1(mktime() . "-" . $name) . "." . $ext;
			move_uploaded_file($tmp_name, "$uploads_dir/$name");
			$uploads[] = $name;
		}
	}
	if(count($uploads) == 0){
		return $old;
	}
	return implode(";",$uploads);
}
