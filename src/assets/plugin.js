/**
 * Created by User on 20.03.2018.
 */
CKEDITOR.config.protectedSource.push( /<video[\s|\S]+?<\/video>/g );
CKEDITOR.plugins.add( 'filemanager-jakhar', {
    init: function( editor ) {
            editor.addCommand( 'fildmanagerData', {
                exec: function( editor ) {
                    document.filemanagereditor.run(editor);
                }
            });
        editor.ui.addButton( 'Timestamp', {
            label: 'Insert Media',
            command: 'fildmanagerData',
            toolbar: 'insert',
            icon: 'https://image.flaticon.com/icons/png/128/148/148712.png'
        });
    }
});
