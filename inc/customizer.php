<?php

add_action( 'customize_register', 'ct_author_add_customizer_content' );

function ct_author_add_customizer_content( $wp_customize ) {

	/***** Reorder default sections *****/

	$wp_customize->get_section( 'title_tagline' )->priority = 2;

	// check if exists in case user has no pages
	if ( is_object( $wp_customize->get_section( 'static_front_page' ) ) ) {
		$wp_customize->get_section( 'static_front_page' )->priority = 5;
		$wp_customize->get_section( 'static_front_page' )->title    = __( 'Front Page', 'author' );
	}

	/***** Add PostMessage Support *****/

	// Add postMessage support for site title and description.
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	/***** Add Custom Controls *****/
	// create multi-checkbox/select control
	class ct_author_multi_checkbox_control extends WP_Customize_Control {
		public $type = 'multi-checkbox';

		public function render_content() {

			if ( empty( $this->choices ) ) {
				return;
			}
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select id="comment-display-control" <?php $this->link(); ?> multiple="multiple" style="height: 100%;">
					<?php
					foreach ( $this->choices as $value => $label ) {
						$selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
						echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
					}
					?>
				</select>
			</label>
		<?php }
	}

	/***** Author Pro Control *****/

	class ct_author_pro_ad extends WP_Customize_Control {
		public function render_content() {
			$link = 'https://www.competethemes.com/author-pro/';
			echo "<a href='" . $link . "' target='_blank'><img src='" . get_template_directory_uri() . "/assets/images/author-pro.png' srcset='" . get_template_directory_uri() . "/assets/images/author-pro-2x.png 2x' /></a>";
			echo "<p class='bold'>" . sprintf( __('<a target="_blank" href="%1$s">%2$s Pro</a> is the plugin that makes advanced customization simple - and fun too!', 'author'), $link, wp_get_theme( get_template() ) ) . "</p>";
			echo "<p>" . sprintf( __('%1$s Pro adds the following features to %1$s:', 'author'), wp_get_theme( get_template() ) ) . "</p>";
			echo "<ul>
					<li>" . __('Custom colors', 'author') . "</li>
					<li>" . __('New fonts', 'author') . "</li>
					<li>" . __('Flexible header image', 'author') . "</li>
					<li>" . __('+ 9 more features', 'author') . "</li>
				  </ul>";
			echo "<p class='button-wrapper'><a target=\"_blank\" class='author-pro-button' href='" . $link . "'>" . sprintf( __( 'View %s Pro', 'author' ), wp_get_theme( get_template() ) ) . "</a></p>";
		}
	}

	/***** Author Pro Section *****/

	// don't add if Author Pro is active
	if ( !function_exists( 'ct_author_pro_init' ) ) {
		// section
		$wp_customize->add_section( 'ct_author_pro', array(
			'title'    => sprintf( __( '%s Pro', 'author' ), wp_get_theme( get_template() ) ),
			'priority' => 1
		) );
		// Upload - setting
		$wp_customize->add_setting( 'author_pro', array(
			'sanitize_callback' => 'absint'
		) );
		// Upload - control
		$wp_customize->add_control( new ct_author_pro_ad(
			$wp_customize, 'author_pro', array(
				'section'  => 'ct_author_pro',
				'settings' => 'author_pro'
			)
		) );
	}

	/***** Avatar *****/

	// section
	$wp_customize->add_section( 'ct_author_avatar', array(
		'title'    => __( 'Avatar', 'author' ),
		'priority' => 15
	) );
	// setting
	$wp_customize->add_setting( 'avatar_method', array(
		'default'           => 'none',
		'sanitize_callback' => 'ct_author_sanitize_avatar_method'
	) );
	// control
	$wp_customize->add_control( 'avatar_method', array(
		'label'       => __( 'Avatar image source', 'author' ),
		'section'     => 'ct_author_avatar',
		'settings'    => 'avatar_method',
		'type'        => 'radio',
		'description' => __( 'Gravatar uses the admin email address.', 'author' ),
		'choices'     => array(
			'gravatar' => __( 'Gravatar', 'author' ),
			'upload'   => __( 'Upload an image', 'author' ),
			'none'     => __( 'Do not display avatar', 'author' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'avatar', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'avatar', array(
			'label'    => __( 'Upload your avatar', 'author' ),
			'section'  => 'ct_author_avatar',
			'settings' => 'avatar'
		)
	) );

	/***** Logo Upload *****/

	// section
	$wp_customize->add_section( 'ct_author_logo_upload', array(
		'title'       => __( 'Logo', 'author' ),
		'priority'    => 25,
		'description' => __( 'Use this instead of the avatar if you want a non-rounded logo image.', 'author' )
	) );
	// setting
	$wp_customize->add_setting( 'logo_upload', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'logo_image', array(
			'label'    => __( 'Upload custom logo.', 'author' ),
			'section'  => 'ct_author_logo_upload',
			'settings' => 'logo_upload'
		)
	) );

	/***** Social Media Icons *****/

	// get the social sites array
	$social_sites = ct_author_social_array();

	// set a priority used to order the social sites
	$priority = 5;

	// section
	$wp_customize->add_section( 'ct_author_social_media_icons', array(
		'title'       => __( 'Social Media Icons', 'author' ),
		'priority'    => 35,
		'description' => __( 'Add the URL for each of your social profiles.', 'author' )
	) );

	// create a setting and control for each social site
	foreach ( $social_sites as $social_site => $value ) {
		// if email icon
		if ( $social_site == 'email' ) {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_author_sanitize_email'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'    => __( 'Email Address', 'author' ),
				'section'  => 'ct_author_social_media_icons',
				'priority' => $priority,
			) );
		} else {

			$label = ucfirst( $social_site );

			if ( $social_site == 'google-plus' ) {
				$label = 'Google Plus';
			} elseif ( $social_site == 'rss' ) {
				$label = 'RSS';
			} elseif ( $social_site == 'soundcloud' ) {
				$label = 'SoundCloud';
			} elseif ( $social_site == 'slideshare' ) {
				$label = 'SlideShare';
			} elseif ( $social_site == 'codepen' ) {
				$label = 'CodePen';
			} elseif ( $social_site == 'stumbleupon' ) {
				$label = 'StumbleUpon';
			} elseif ( $social_site == 'deviantart' ) {
				$label = 'DeviantArt';
			} elseif ( $social_site == 'hacker-news' ) {
				$label = 'Hacker News';
			} elseif ( $social_site == 'whatsapp' ) {
				$label = 'WhatsApp';
			} elseif ( $social_site == 'qq' ) {
				$label = 'QQ';
			} elseif ( $social_site == 'vk' ) {
				$label = 'VK';
			} elseif ( $social_site == 'wechat' ) {
				$label = 'WeChat';
			} elseif ( $social_site == 'tencent-weibo' ) {
				$label = 'Tencent Weibo';
			} elseif ( $social_site == 'paypal' ) {
				$label = 'PayPal';
			} elseif ( $social_site == 'email-form' ) {
				$label = 'Contact Form';
			}

			if ( $social_site == 'skype' ) {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'ct_author_sanitize_skype'
				) );
				// control
				$wp_customize->add_control( $social_site, array(
					'type'        => 'url',
					'label'       => $label,
					'description' => sprintf( __( 'Accepts Skype link protocol (<a href="%s" target="_blank">learn more</a>)', 'author' ), 'https://www.competethemes.com/blog/skype-links-wordpress/' ),
					'section'     => 'ct_author_social_media_icons',
					'priority'    => $priority
				) );
			} else {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'esc_url_raw'
				) );
				// control
				$wp_customize->add_control( $social_site, array(
					'type'     => 'url',
					'label'    => $label,
					'section'  => 'ct_author_social_media_icons',
					'priority' => $priority
				) );
			}
		}
		// increment the priority for next site
		$priority = $priority + 5;
	}

	/***** Blog *****/

	// section
	$wp_customize->add_section( 'author_blog', array(
		'title'    => _x( 'Blog', 'noun: the blog section',  'author' ),
		'priority' => 45
	) );
	// setting
	$wp_customize->add_setting( 'full_post', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_author_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'full_post', array(
		'label'    => __( 'Show full posts on blog?', 'author' ),
		'section'  => 'author_blog',
		'settings' => 'full_post',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'author' ),
			'no'  => __( 'No', 'author' )
		)
	) );
	// setting - comments link
	$wp_customize->add_setting( 'comments_link', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_author_sanitize_yes_no_settings'
	) );
	// control - comments link
	$wp_customize->add_control( 'comments_link', array(
		'label'    => __( 'Show link to comments after posts?', 'author' ),
		'section'  => 'author_blog',
		'settings' => 'comments_link',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'author' ),
			'no'  => __( 'No', 'author' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'excerpt_length', array(
		'default'           => '25',
		'sanitize_callback' => 'absint'
	) );
	// control
	$wp_customize->add_control( 'excerpt_length', array(
		'label'    => __( 'Excerpt word count', 'author' ),
		'section'  => 'author_blog',
		'settings' => 'excerpt_length',
		'type'     => 'number'
	) );
	// Read More text - setting
	$wp_customize->add_setting( 'read_more_text', array(
		'default'           => __( 'Continue reading', 'author' ),
		'sanitize_callback' => 'ct_author_sanitize_text'
	) );
	// Read More text - control
	$wp_customize->add_control( 'read_more_text', array(
		'label'    => __( 'Read More link text', 'author' ),
		'section'  => 'author_blog',
		'settings' => 'read_more_text',
		'type'     => 'text'
	) );

	/***** Comment Display *****/

	// section
	$wp_customize->add_section( 'ct_author_comments_display', array(
		'title'    => __( 'Comment Display', 'author' ),
		'priority' => 55
	) );
	// setting
	$wp_customize->add_setting( 'comments_display', array(
		'default'           => array( 'post', 'page', 'attachment', 'none' ),
		'sanitize_callback' => 'ct_author_sanitize_comments_setting'
	) );
	// control
	$wp_customize->add_control( new ct_author_multi_checkbox_control(
		$wp_customize, 'comments_display', array(
			'label'    => __( 'Show comments on:', 'author' ),
			'section'  => 'ct_author_comments_display',
			'settings' => 'comments_display',
			'type'     => 'multi-checkbox',
			'choices'  => array(
				'post'       => __( 'Posts', 'author' ),
				'page'       => __( 'Pages', 'author' ),
				'attachment' => __( 'Attachments', 'author' ),
				'none'       => __( 'Do not show', 'author' )
			)
		)
	) );

	/***** Custom CSS *****/

	if ( function_exists( 'wp_update_custom_css_post' ) ) {
		// Migrate any existing theme CSS to the core option added in WordPress 4.7.
		$css = get_theme_mod( 'custom_css' );
		if ( $css ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return = wp_update_custom_css_post( $core_css . $css );
			if ( ! is_wp_error( $return ) ) {
				// Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
				remove_theme_mod( 'custom_css' );
			}
		}
	} else {
		// section
		$wp_customize->add_section( 'author_custom_css', array(
			'title'    => __( 'Custom CSS', 'author' ),
			'priority' => 65
		) );
		// setting
		$wp_customize->add_setting( 'custom_css', array(
			'sanitize_callback' => 'ct_author_sanitize_css',
			'transport'         => 'postMessage'
		) );
		// control
		$wp_customize->add_control( 'custom_css', array(
			'type'     => 'textarea',
			'label'    => __( 'Add Custom CSS Here:', 'author' ),
			'section'  => 'author_custom_css',
			'settings' => 'custom_css'
		) );
	}
}

/***** Custom Sanitization Functions *****/

/*
 * Sanitize settings with show/hide as options
 * Used in: search bar
 */
function ct_author_sanitize_all_show_hide_settings( $input ) {

	$valid = array(
		'show' => __( 'Show', 'author' ),
		'hide' => __( 'Hide', 'author' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

/*
 * sanitize email address
 * Used in: Social Media Icons
 */
function ct_author_sanitize_email( $input ) {
	return sanitize_email( $input );
}

function ct_author_sanitize_comments_setting( $input ) {

	$valid = array(
		'post'       => __( 'Posts', 'author' ),
		'page'       => __( 'Pages', 'author' ),
		'attachment' => __( 'Attachments', 'author' ),
		'none'       => __( 'Do not show', 'author' )
	);

	foreach ( $input as $selection ) {
		return array_key_exists( $selection, $valid ) ? $input : '';
	}
}

function ct_author_sanitize_avatar_method( $input ) {

	$valid = array(
		'gravatar' => __( 'Gravatar', 'author' ),
		'upload'   => __( 'Upload an image', 'author' ),
		'none'     => __( 'Do not display avatar', 'author' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_author_sanitize_yes_no_settings( $input ) {

	$valid = array(
		'yes' => __( 'Yes', 'author' ),
		'no'  => __( 'No', 'author' ),
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_author_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

function ct_author_sanitize_skype( $input ) {
	return esc_url_raw( $input, array( 'http', 'https', 'skype' ) );
}

function ct_author_sanitize_css( $css ) {
	$css = wp_kses( $css, array( '\'', '\"' ) );
	$css = str_replace( '&gt;', '>', $css );

	return $css;
}