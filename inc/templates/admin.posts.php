<?php
include "admin.header.php";
?>
	<h1><?php L("All posts"); ?> <a href="post.php" class="radius small button"><?php L("New Post"); ?></a></h1>
	<table class="full-width">
		<thead>
			<tr><th><?php L("Post Title"); ?></th>
				<th><?php L("Published"); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($posts as $post) { ?>
			<tr><td><a href="post.php?id=<?php echo $post->id;?>"><?php echo $post->title; ?></a></td>
				<td><?php echo $post->published; ?></td></tr>
			<?php } ?>
		</tbody>
	</table>
<?php
include "admin.footer.php";
