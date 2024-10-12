<?php

if ( ( ! is_archive() ) && ( ! is_home() ) ) {
	return;
}

$icon_class = 'folder-open';

if ( is_tag() ) {
	$icon_class = 'tag';
} elseif ( is_author() ) {
	$icon_class = 'user';
} elseif ( is_date() ) {
	$icon_class = 'calendar';
} elseif ( is_home() ) {
	$icon_class = 'box-archive';
}
?>

<div class='archive-header'>
	<i class="fas fa-<?php echo $icon_class; ?>" aria-hidden="true"></i>
	<h1>
		<?php if ( is_archive() ) {
			the_archive_title();
		} else {
			echo 'Arquivo';
		} ?>
	</h1>
	<?php if ( is_archive() ) {
		the_archive_description();
	} else {
		echo "<p>Todas as entradas existentes.</p>";
	} ?>
</div>