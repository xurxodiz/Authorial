<?php

if ( comments_open() ) { ?>
	<section id="comments" class="comments">
		<div class="comments-number">
			<p>
				Podes interaxir con esta entrada de moitas formas: con pingbacks, con webmentions ou simplemente respondendo a través do Fediverso, por exemplo visitándela en Mastodon.
			</p>
		</div>
		<?php if ( have_comments() ) { ?>
			<ol class="comment-list">
				<?php wp_list_comments( array( 'callback' => 'ct_author_customize_comments' ) ); ?>
			</ol>
		<?php } ?>
	</section>
<?php } ?>