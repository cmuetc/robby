<?php
/**
 * This file adds functions to the Robby WordPress theme.
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
