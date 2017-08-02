(function() {
    tinymce.create( 'tinymce.plugins.story_lines', {
 		init : function( ed, url ) {
 			url = url.slice(0, -3);
           	ed.addButton( 'story_lines', {
            	title : 'Story Lines',
            	cmd : 'story_lines',
             	image : url + '/images/story-lines.png'
          	} );
            ed.addCommand( 'story_lines', function() {
            	var returnText = '[story-lines]';
            	ed.execCommand( 'mceInsertContent', 0, returnText );
            } );
        },
        createControl : function( n, cm ) {
            return null;
        },
        getInfo : function() {
            return {
                longname : 'Story Lines Plugin',
                author : 'Jacob Martella',
                authorurl : 'http://jacobmartella.com',
                infourl : 'http://www.jacobmartella.com/story-lines/',
                version : "1.2"
            };
        }
    } );
    tinymce.PluginManager.add( 'story_lines', tinymce.plugins.story_lines );
})();