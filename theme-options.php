<?php

function ct_author_register_theme_page()
{
    add_theme_page(
        sprintf(esc_html__('%s Dashboard', 'author'), wp_get_theme()),
        sprintf(esc_html__('%s Dashboard', 'author'), wp_get_theme()),
        'edit_theme_options',
        'author-options',
        'ct_author_options_content'
    );
}
add_action('admin_menu', 'ct_author_register_theme_page');

function ct_author_options_content()
{
    $pro_url = 'https://www.competethemes.com/author-pro/?utm_source=wp-dashboard&utm_medium=Dashboard&utm_campaign=Author%20Pro%20-%20Dashboard'; ?>
	<div id="author-dashboard-wrap" class="wrap author-dashboard-wrap">
		<h2><?php printf(esc_html__('%s Dashboard', 'author'), wp_get_theme()); ?></h2>
		<?php do_action('theme_options_before'); ?>
		<div class="main">
			<div class="getting-started">
				<h3>Get Started with Author</h3>
				<p>Follow this step-by-step guide to customize your website with Author:</p>
				<a href="https://www.competethemes.com/help/getting-started-author/" target="_blank">Read the Getting Started Guide</a>
			</div>
		</div>
		<div class="sidebar">
			<div class="dashboard-widget">
				<h4>More Amazing Resources</h4>
				<ul>
					<li><a href="https://www.competethemes.com/documentation/author-support-center/" target="_blank">Author Support Center</a></li>
					<li><a href="https://wordpress.org/support/theme/author" target="_blank">Support Forum</a></li>
					<li><a href="https://www.competethemes.com/help/author-changelog/" target="_blank">Changelog</a></li>
					<li><a href="https://www.competethemes.com/help/author-css-snippets/" target="_blank">CSS Snippets</a></li>
					<li><a href="https://www.competethemes.com/help/child-theme-author/" target="_blank">Starter child theme</a></li>
					<li><a href="https://www.competethemes.com/help/author-demo-data/" target="_blank">Author demo data</a></li>
					<li><a href="<?php echo $pro_url; ?>" target="_blank">Author Pro</a></li>
				</ul>
			</div>
			<div class="dashboard-widget">
				<h4>Reset Customizer Settings</h4>
				<p><b>Warning:</b> Clicking this buttin will erase the Author theme's current settings in the Customizer.</p>
				<form method="post">
					<input type="hidden" name="author_reset_customizer" value="author_reset_customizer_settings"/>
					<p>
						<?php wp_nonce_field('author_reset_customizer_nonce', 'author_reset_customizer_nonce'); ?>
						<?php submit_button('Reset Customizer Settings', 'delete', 'delete', false); ?>
					</p>
				</form>
			</div>
		</div>
		<?php do_action('theme_options_after'); ?>
	</div>
<?php
}
