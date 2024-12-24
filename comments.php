<?php if ( ! is_page() ) { ?>
	<section id="comments" class="comments">
		<div class="comments-number">
			<p>
				<span class="line">Podes interaxir con esta entrada de moitas formas: pingbacks, webmentions, ActivityPub…</span>
				<span class="line">ou simplemente respondendo a través do Fediverso, por exemplo visitándoa en Mastodon.</span>
			</p>
		</div>
		<ol class="comment-list">
			<?php wp_list_comments( array( 'callback' => 'ct_author_customize_comments' ) ); ?>
		</ol>
	</section>
<?php } ?>