jQuery(document).ready(function( $ ){
	$( '#story-lines-add-row' ).on( 'click', function() {
		var row = $( '.story-lines-empty-row.screen-reader-text' ).clone(true);
		row.addClass( 'new-row story-lines-link-fields' );
		row.removeClass( 'story-lines-empty-row screen-reader-text' );
		row.insertAfter( '.story-lines-link-fields:last' );
		$( '.new-row .new-field' ).attr( "disabled",false );
		return false;
	}) ;
  	
	$( '.story-lines-remove-row' ).on( 'click', function() {
		$( this ).parents( 'table' ).remove();
		return false;
	} );

	$( '#story-lines-repeatable-fieldset-one' ).sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		items: 'table'
	});

	jQuery( '#story_lines_title_background' ).wpColorPicker();
	jQuery( '#story_lines_main_background' ).wpColorPicker();
	jQuery( '#story_lines_title_color' ).wpColorPicker();
	jQuery( '#story_lines_main_color' ).wpColorPicker();

});