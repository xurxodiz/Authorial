<?php get_header(); ?>
	<div class="entry">
		<article>
			<div class='post-header'>
				<h1 class='post-title'><?php _e( '404: Non encontrado', 'author' ); ?></h1>
			</div>
			<div class="post-content">
				<?php _e( 'Neste enderezo non hai nada. Proba a buscar o que querías na seguinte caixa.', 'author' ); ?>
			</div>
			<?php get_search_form(); ?>
		</article>
	</div>
<?php get_footer(); ?>