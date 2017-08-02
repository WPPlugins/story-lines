<?php 
/*
* Plugin Name: Story Lines
* Plugin URI: http://www.jacobmartella.com/wordpress/wordpress-plugins/story-lines
* Description: Add a list of story highlights at the top of your posts to let your readers really know what your story is all about.
* Version: 1.4
* Author: Jacob Martella
* Author URI: http://www.jacobmartella.com
* License: GPLv3
*/
/**
* Set up the plugin when the user activates the plugin. Adds the breaking news custom post type the text domain for translations.
*/
$story_lines_plugin_path = plugin_dir_path( __FILE__ );
define( 'STORY_LINE_PATH', $story_lines_plugin_path );

//* Load the custom fields
include_once( STORY_LINE_PATH . 'admin/story-lines-admin.php' );

//* Load the text domain
function story_lines_load_plugin_textdomain() {
	load_plugin_textdomain( 'story-lines', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'story_lines_load_plugin_textdomain' );

/**
* Loads the styles for the read more about section on the front end
*/
function story_lines_styles() {
	wp_enqueue_style( 'story-lines-style', plugin_dir_url(__FILE__) . 'css/story-lines.css' );
	wp_enqueue_style( 'roboto', '//fonts.googleapis.com/css?family=Roboto:400,300,100,700', array(), '', 'all' );
}
add_action( 'wp_enqueue_scripts', 'story_lines_styles' );

/**
* Loads and prints the styles for the breaking news custom post type
*/
function story_lines_admin_style() {
	global $typenow;
	if ( $typenow == 'post' ) {
		wp_enqueue_style( 'story_lines_admin_styles', plugin_dir_url(__FILE__) . 'css/story-lines-admin.css' );
	}
}
add_action( 'admin_print_styles', 'story_lines_admin_style' );

/**
* Loads the script for the breaking news custom post type
*/
function story_lines_admin_scripts() {
	global $typenow;
	if ( $typenow == 'post' ) {
		wp_enqueue_script( 'story_lines_admin_script', plugin_dir_url(__FILE__) . 'js/story-lines-admin.js' );
	}
}
add_action( 'admin_enqueue_scripts', 'story_lines_admin_scripts' );

//* Register and create the shortcode to display the section
function story_lines_register_shortcode() {
	add_shortcode( 'story-lines', 'story_lines_shortcode' );
}
add_action( 'init', 'story_lines_register_shortcode' );
function story_lines_shortcode( $atts ) {
	extract( shortcode_atts( array(
	), $atts ) );
	$the_post_id = get_the_ID();

	if ( get_post_meta( $the_post_id, 'story_lines_title', true ) ) { $title = get_post_meta( $the_post_id, 'story_lines_title', true ); } else { $title = __( 'Story Lines', 'story-lines' ); }
	if ( get_post_meta( $the_post_id, 'story_lines_size', true ) ) { $size = 'width: ' . esc_attr( get_post_meta( $the_post_id, 'story_lines_size', true ) ) . '%;'; } else { $size = 'width: 25%;'; }
	if ( get_post_meta( $the_post_id, 'story_lines_float', true ) ) { $float =  esc_attr( get_post_meta( $the_post_id, 'story_lines_float', true ) ); } else { $float = 'left'; }
	if ( get_post_meta( $the_post_id, 'story_lines_highlights', true ) ) { $highlights = get_post_meta( $the_post_id, 'story_lines_highlights', true ); } else { $highlights = ''; }
	if ( get_post_meta( $the_post_id, 'story_lines_title_background', true ) ) { $title_bg_color = 'background-color: ' . esc_attr( get_post_meta( $the_post_id, 'story_lines_title_background', true ) ) . ';'; } else { $title_bg_color = ''; }
	if ( get_post_meta( $the_post_id, 'story_lines_main_background', true ) ) { $main_bg_color = 'background-color: ' . esc_attr( get_post_meta( $the_post_id, 'story_lines_main_background', true ) ) . ';'; } else { $main_bg_color = ''; }
	if ( get_post_meta( $the_post_id, 'story_lines_title_color', true ) ) { $title_color = 'color: ' . esc_attr( get_post_meta( $the_post_id, 'story_lines_title_color', true ) ) . ';'; } else { $title_color = ''; }
	if ( get_post_meta( $the_post_id, 'story_lines_main_color', true ) ) { $main_color = 'color: ' . esc_attr( get_post_meta( $the_post_id, 'story_lines_main_color', true ) ) . ';'; } else { $main_color = ''; }

	$html = '';

	if ($highlights) {
		$html .= '<aside class="story-lines ' . $float . '" style="' . $size . $main_bg_color . '">';
		$html .= '<h2 class="title" style="' . $title_bg_color . $title_color . '">' . $title . '</h2>';
		$html .= '<ul>';
		foreach ( $highlights as $highlight ) {
			if ( isset( $highlight[ 'story_lines_anchor_id' ] ) ) {
				$html .= '<li><a href="#' . $highlight[ 'story_lines_anchor_id' ] . '" style="' . $main_color . '">' . $highlight[ 'story_lines_highlight' ] . '</a></li>';
			} else {
				$html .= '<li style="' . $main_color . '">' . $highlight[ 'story_lines_highlight' ] . '</li>';
			}
		}
		$html .= '</ul>';
		$html .= '</aside>';
	}

	return $html;
}

//* Add a button to the TinyMCE Editor to make it easier to add the shortcode
add_action( 'init', 'story_lines_buttons' );
function story_lines_buttons() {
    add_filter( 'mce_external_plugins', 'story_lines_add_buttons' );
    add_filter( 'mce_buttons', 'story_lines_register_buttons' );
}
function story_lines_add_buttons( $plugin_array ) {
    $plugin_array[ 'story_lines' ] = plugin_dir_url(__FILE__) . 'js/story-lines-admin-button.js';
    return $plugin_array;
}
function story_lines_register_buttons( $buttons ) {
    array_push( $buttons, 'story_lines' );
    return $buttons;
}

//* Load the Widget
include_once( STORY_LINE_PATH . 'story-lines-widget.php' );

//* Load the Contextual Help
include_once( STORY_LINE_PATH . 'admin/story-lines-help.php' );
?>