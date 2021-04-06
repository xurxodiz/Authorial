<?php get_header(); ?>
<div class="archive-header">
	<div class="post-header">
		<h1 class="post-title">
			<?php
			global $wp_query;
			$total_results = $wp_query->found_posts;
			$s             = htmlentities( $s );
			if ( $total_results ) {
				printf( esc_html( _n( '%1$d search result for "%2$s"', 'Atopados %1$d resultados para "%2$s"', $total_results, 'author' ) ), $total_results, $s );
			} else {
				printf( esc_html__( 'Non hai resultados para "%s"', 'author' ), $s );
			}
			?>
		</h1>
		<?php get_search_form(); ?>
	</div>
</div>
<div id="loop-container" class="loop-container">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			get_template_part( 'content', 'archive' );
		endwhile;
	endif;
	?>
</div>
<?php

the_posts_pagination();

// only display bottom search bar if there are search results
$total_results = $wp_query->found_posts;
if ( $total_results ) { ?>
	<div class="search-bottom archive-header">
		<p><?php _e( "Non atopaches o que buscabas? Proba con outros termos de busca:", "author" ); ?></p>
		<?php get_search_form(); ?>
	</div>
<?php }

get_footer();