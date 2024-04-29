<?php
/**
 * This file adds functions to the Robby WordPress theme.
 * Based on Frost WordPress theme by WP Engine
 * https://github.com/wpengine/frost
 * 
 * @package Robby
 * @author  bm3n
 * @license GNU General Public License v3
 * @link    https://github.com/cmuetc/robby
 */

if ( ! function_exists( 'robby_setup' ) ) {

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since 0.8.0
	 *
	 * @return void
	 */
	function robby_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'robby', get_template_directory() . '/languages' );

		// Enqueue editor stylesheet.
		add_editor_style( get_template_directory_uri() . '/style.css' );

		// Remove core block patterns.
		remove_theme_support( 'core-block-patterns' );

	}
}
add_action( 'after_setup_theme', 'robby_setup' );

// Enqueue stylesheet.
add_action( 'wp_enqueue_scripts', 'robby_enqueue_stylesheet' );
function robby_enqueue_stylesheet() {

	wp_enqueue_style( 'robby', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );

}

/**
 * Register block styles.
 *
 * @since 0.9.2
 */
function robby_register_block_styles() {

	$block_styles = array(
		'core/columns' => array(
			'columns-reverse' => __( 'Reverse', 'robby' ),
		),
		'core/group' => array(
			'shadow-light' => __( 'Shadow', 'robby' ),
			'shadow-solid' => __( 'Solid', 'robby' ),
		),
		'core/image' => array(
			'shadow-light' => __( 'Shadow', 'robby' ),
			'shadow-solid' => __( 'Solid', 'robby' ),
		),
		'core/list' => array(
			'no-disc' => __( 'No Disc', 'robby' ),
		),
		'core/quote' => array(
			'shadow-light' => __( 'Shadow', 'robby' ),
			'shadow-solid' => __( 'Solid', 'robby' ),
		),
		'core/social-links' => array(
			'outline' => __( 'Outline', 'robby' ),
		),
	);

	foreach ( $block_styles as $block => $styles ) {
		foreach ( $styles as $style_name => $style_label ) {
			register_block_style(
				$block,
				array(
					'name'  => $style_name,
					'label' => $style_label,
				)
			);
		}
	}
}
add_action( 'init', 'robby_register_block_styles' );

/**
 * Register block pattern categories.
 *
 * @since 1.0.4
 */
function robby_register_block_pattern_categories() {

	register_block_pattern_category(
		'robby-page',
		array(
			'label'       => __( 'Page', 'robby' ),
			'description' => __( 'Create a full page with multiple patterns that are grouped together.', 'robby' ),
		)
	);
	register_block_pattern_category(
		'robby-pricing',
		array(
			'label'       => __( 'Pricing', 'robby' ),
			'description' => __( 'Compare features for your digital products or service plans.', 'robby' ),
		)
	);

}

add_action( 'init', 'robby_register_block_pattern_categories' );

// Add custom logo support
// TODO: Review if this is actually needed to support header logos
add_theme_support( 'custom-logo' );

/**
 * Disable emoji's
 * Ref: https://www.wpbeginner.com/plugins/how-to-disable-emojis-in-wordpress-4-2/
 * 
 * This reduces the number of JavaScript dependencies in the exported site.
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );
	
/**
* Filter function used to remove the tinymce emoji plugin.
* 
* @param array $plugins 
* @return array Difference between the two arrays
*/
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}
	
/**
* Remove emoji CDN hostname from DNS prefetching hints.
*
* @param array $urls URLs to print for resource hints.
* @param string $relation_type The relation type the URLs are printed for.
* @return array Difference between the two arrays.
*/
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
		/** This filter is documented in wp-includes/formatting.php */
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}

	return $urls;
}
