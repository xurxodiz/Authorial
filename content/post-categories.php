<?php

$categories = get_the_category( $post->ID );
$separator  = '';
$output     = '';

if ( $categories ) {
	echo '<div class="post-categories"><ul>';
		foreach ( $categories as $category ) {
			// output category name linked to the archive
			$output .= '<li><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all posts in %s", 'View all posts in post category', 'author' ), $category->name ) ) . '">' . esc_html( $category->cat_name ) . '</a></li>' . $separator;
		}
		echo trim( $output, $separator );
	echo "</ul></div>";
}