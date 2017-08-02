<?php 
/**
* story-lines-admin.php
*
* Creates the custom fields for the post admin area
*
* The code for the repeatable fields comes from Helen Housandi and can be found here: https://gist.github.com/helenhousandi/1593065. Many thanks for this code.
*
* @author Jacob Martella
* @package Read More About
* @version 1.4
* @since 1.0
*/
/* Set up the float array */
$float_array = [];
$float_array[ 'left' ] = 'Left';
$float_array[ 'center' ] = 'Center';
$float_array[ 'right' ] = 'Right';

add_action( 'admin_init', 'story_lines_add_meta_boxes' );

//* Add the meta box
function story_lines_add_meta_boxes() {
	add_meta_box( 'story-lines-meta', __( 'Add Story Lines', 'story-lines' ) , 'story_lines_meta_box_display', 'post', 'normal', 'default' );
}
//* Create the meta box
function story_lines_meta_box_display() {
	global $post;
	global $float_array;
	if ( get_post_meta( $post->ID, 'story_lines_title', true ) ) { $title = get_post_meta( $post->ID, 'story_lines_title', true ); } else { $title = ''; }
	if ( get_post_meta( $post->ID, 'story_lines_size', true ) ) { $size = get_post_meta( $post->ID, 'story_lines_size', true ); } else { $size = ''; }
	if ( get_post_meta( $post->ID, 'story_lines_float', true ) ) { $float = get_post_meta( $post->ID, 'story_lines_float', true ); } else { $float = ''; }
	if ( get_post_meta( $post->ID, 'story_lines_title_background', true ) ) { $title_bg_color = get_post_meta( $post->ID, 'story_lines_title_background', true ); } else { $title_bg_color = ''; }
	if ( get_post_meta( $post->ID, 'story_lines_main_background', true ) ) { $main_bg_color = get_post_meta( $post->ID, 'story_lines_main_background', true ); } else { $main_bg_color = ''; }
	if ( get_post_meta( $post->ID, 'story_lines_title_color', true ) ) { $title_color = get_post_meta( $post->ID, 'story_lines_title_color', true ); } else { $title_color = ''; }
	if ( get_post_meta( $post->ID, 'story_lines_main_color', true ) ) { $main_color = get_post_meta( $post->ID, 'story_lines_main_color', true ); } else { $main_color = ''; }
	$highlights = get_post_meta( $post->ID, 'story_lines_highlights', true) ;
	wp_nonce_field( 'story_lines_meta_box_nonce', 'story_lines_meta_box_nonce' );
  
	echo '<div id="story-lines-repeatable-fieldset-one" width="100%">';

	echo '<table class="story-lines-options">';
	echo '<tr>';
	echo '<td><label for="story_lines_title">' . __( 'Title:', 'story-lines' ) . '</label></td>';
	echo '<td><input type="text" name="story_lines_title" id="story_lines_title" value="' . esc_attr( $title ) . '" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td><label for="story_lines_size">' . __( 'Size (as a percentage): ', 'story-lines' ) . '</label></td>';
	echo '<td><input type="number" name="story_lines_size" id="story_lines_size" value="' . esc_attr( $size ) . '" max="100" min="1" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td><label for="story_lines_float">' . __( 'Float:', 'story-lines' ) . '</label></td>';
	echo '<td><select name="story_lines_float" id="story_lines_float">';
	foreach ( $float_array as $key => $name ) {
		if ( $key == $float ) {
			$selected = 'selected="selected"';
		} else {
			$selected = '';
		}
		echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . $name . '</option>';
	}
	echo '</select></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td><label for="story_lines_title_background">' . __( 'Background Color for title section', 'story-lines' ) . '</label></td>';
	echo '<td><input type="text" name="story_lines_title_background" id="story_lines_title_background" value="' . $title_bg_color .'" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td><label for="story_lines_main_background">' . __( 'Background Color for main section', 'story-lines' ) . '</label></td>';
	echo '<td><input type="text" name="story_lines_main_background" id="story_lines_main_background" value="' . $main_bg_color .'" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td><label for="story_lines_title_color">' . __( 'Color for the title text', 'story-lines' ) . '</label></td>';
	echo '<td><input type="text" name="story_lines_title_color" id="story_lines_title_color" value="' . $title_color .'" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td><label for="story_lines_main_color">' . __( 'Color for text of the main section', 'story-lines' ) . '</label></td>';
	echo '<td><input type="text" name="story_lines_main_color" id="story_lines_main_color" value="' . $main_color .'" /></td>';
	echo '</tr>';
    echo '</table>';
	
	//* Check for fields already filled out
	if ( $highlights ) {
	
		//* Loop through each link the user has already entered
		foreach ( $highlights as $highlight ) {
		echo '<table class="story-lines-link-fields">';
			echo '<tr>';
				echo '<td><label for="story_lines_highlight">' . __( 'Story Line:', 'story-lines' ) . '</label></td>';
				echo '<td><input type="text" name="story_lines_highlight[]" id="story_lines_highlight" value="' . esc_attr( $highlight[ 'story_lines_highlight' ] ) . '" /></td>';
			echo '</tr>';
			echo '<tr>';
			if ( isset( $highlight[ 'story_lines_anchor_id' ] ) ) {
				$anchor = $highlight[ 'story_lines_anchor_id' ];
			} else {
				$anchor = '';
			}
			echo '<td><label for="story_lines_anchor_id">' . __( 'Anchor ID:', 'story-lines' ) . '</label></td>';
			echo '<td><input type="text" name="story_lines_anchor_id[]" id="story_lines_anchor_id" value="' . esc_attr( $anchor ) . '" /></td>';
			echo '</tr>';
			echo '<tr><td><a class="button story-lines-remove-row" href="#">' . __( 'Remove Line', 'story-lines' ) . '</a></td></tr>';
		echo '</table>';

		} //* End foreach

	} else {
	//* Show a blank set of fields if there are no fields filled in
		echo '<table class="story-lines-link-fields">';
		echo '<tr>';
		echo '<td><label for="story_lines_highlight">' . __( 'Story Line:', 'story-lines' ) . '</label></td>';
		echo '<td><input type="text" name="story_lines_highlight[]" id="story_lines_highlight" value="" /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '<td><label for="story_lines_anchor_id">' . __( 'Anchor ID:', 'story-lines' ) . '</label><br /></td>';
		echo '<td><input type="text" name="story_lines_anchor_id[]" id="story_lines_anchor_id" value="" /></td>';
		echo '</tr>';

		echo '<tr><td><a class="button story-lines-remove-row" href="#">' . __( 'Remove Line', 'story-lines' ) . '</a></td></tr>';
		
		echo '</table>';
	}
	
	//* Set up a hidden group of fields for the jQuery to grab
	echo '<table class="story-lines-empty-row screen-reader-text">';
	echo '<tr>';
	echo '<td><label for="story_lines_highlight">' . __( 'Story Line:', 'story-lines' ) . '</label></td>';
	echo '<td><input class="new-field" type="text" name="story_lines_highlight[]" id="story_lines_highlight" value="" disabled="disabled" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td><label for="story_lines_anchor_id">' . __( 'Anchor ID:', 'story-lines' ) . '</label></td>';
	echo '<td><input class="new-field" type="text" name="story_lines_anchor_id[]" id="story_lines_anchor_id" value="" disabled="disabled" /></td>';
	echo '</tr>';
		  
	echo '<tr><td><a class="button story-lines-remove-row" href="#">' . __( 'Remove Line', 'story-lines' ) . '</a></td></tr>';
	echo '</table>';
	
	echo '</div>';
	echo '<p><a id="story-lines-add-row" class="button" href="#">' . __( 'Add Story Line', 'story-lines' ) . '</a></p>';
	
}

if ( ! function_exists( 'check_color' ) ) {
	function check_color( $value ) {
		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #
			return true;
		}

		return false;
	}
}

add_action( 'save_post', 'story_lines_meta_box_save' );
function story_lines_meta_box_save( $post_id ) {
	global $float_array;
	if ( ! isset( $_POST[ 'story_lines_meta_box_nonce' ] ) ||
	! wp_verify_nonce( $_POST[ 'story_lines_meta_box_nonce' ], 'story_lines_meta_box_nonce' ) )
		return;
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	
	$old = get_post_meta( $post_id, 'story_lines_highlights', true );
	$new = array();

	$lines = $_POST[ 'story_lines_highlight' ];
	$anchor_ids = $_POST[ 'story_lines_anchor_id'] ;
	
	$num = count( $lines );

	if( isset( $_POST[ 'story_lines_title' ] ) ) {
		update_post_meta( $post_id, 'story_lines_title', sanitize_text_field( wp_filter_nohtml_kses( $_POST[ 'story_lines_title' ] ) ) );
	}

	if( isset( $_POST[ 'story_lines_size' ] ) ) {
		update_post_meta( $post_id, 'story_lines_size', wp_filter_nohtml_kses( intval( $_POST[ 'story_lines_size' ] ) ) );
	}

	if ( $_POST[ 'story_lines_float' ] && array_key_exists( $_POST[ 'story_lines_float' ], $float_array ) ) {
		update_post_meta( $post_id, 'story_lines_float', wp_filter_nohtml_kses( $_POST[ 'story_lines_float' ] ) );
	}

	$title_bg_color = trim( $_POST[ 'story_lines_title_background' ] );
	$title_bg_color = strip_tags( stripslashes( $title_bg_color ) );

	if( TRUE === check_color( $title_bg_color ) ) {
		update_post_meta( $post_id, 'story_lines_title_background', $title_bg_color );
	}

	$main_bg_color = trim( $_POST[ 'story_lines_main_background' ] );
	$main_bg_color = strip_tags( stripslashes( $main_bg_color ) );

	if( TRUE === check_color( $main_bg_color ) ) {
		update_post_meta( $post_id, 'story_lines_main_background', $main_bg_color );
	}

	$title_color = trim( $_POST[ 'story_lines_title_color' ] );
	$title_color = strip_tags( stripslashes( $title_color ) );

	if( TRUE === check_color( $title_color ) ) {
		update_post_meta( $post_id, 'story_lines_title_color', $title_color );
	}

	$main_color = trim( $_POST[ 'story_lines_main_color' ] );
	$main_color = strip_tags( stripslashes( $main_color ) );

	if( TRUE === check_color( $main_color ) ) {
		update_post_meta( $post_id, 'story_lines_main_color', $main_color );
	}
	
	for ( $i = 0; $i < $num; $i++ ) {

		if( isset( $lines [ $i ] ) ) {
        	$new[ $i ][ 'story_lines_highlight' ] = sanitize_text_field( wp_filter_nohtml_kses( $lines[ $i ] ) );
    	}
		if( isset( $anchor_ids[ $i ] ) ) {
			$new[ $i ][ 'story_lines_anchor_id' ] = sanitize_text_field( wp_filter_nohtml_kses( $anchor_ids[ $i ] ) );
		}

		}

	if ( ! empty( $new ) && $new != $old ) {
		update_post_meta( $post_id, 'story_lines_highlights', $new );
	} elseif ( empty( $new ) && $old ) {
		delete_post_meta( $post_id, 'story_lines_highlights', $old );
	}
}
?>