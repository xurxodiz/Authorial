<div <?php post_class(); ?>>
	<?php do_action( 'archive_post_before' ); ?>
	<?php ct_author_featured_image(); ?>
	<article>
		<div class='post-header'>
			<h2 class='post-title'>
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
			</h2>
			<?php get_template_part( 'content/post-meta' ); ?>
			<?php get_template_part( 'content/post-categories' ); ?>
			<?php get_template_part( 'content/post-tags' ); ?>
		</div>
	</article>
	<?php do_action( 'archive_post_after' ); ?>
</div>