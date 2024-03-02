<div <?php post_class(); ?>>
	<?php do_action( 'post_before' ); ?>
	<?php ct_author_featured_image(); ?>
	<article>
		<div class='post-header'>
			<?php get_template_part( 'content/post-meta' ); ?>
			<?php get_template_part( 'content/post-categories' ); ?>
			<?php get_template_part( 'content/post-tags' ); ?>
			<h1 class='post-title'><?php the_title(); ?></h1>
		</div>
		<div class="post-content">
			<?php ct_author_output_last_updated_date(); ?>
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before' => '<p class="singular-pagination">' . esc_html__( 'Pages:', 'author' ),
				'after'  => '</p>',
			) ); ?>
		</div>
	</article>
	<?php do_action( 'post_after' ); ?>
	<?php comments_template(); ?>
</div>