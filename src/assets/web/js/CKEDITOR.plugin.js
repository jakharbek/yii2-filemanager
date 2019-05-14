CKEDITOR.config.protectedSource.push( /<video[\s|\S]+?<\/video>/g );
CKEDITOR.plugins.add( 'filemanager-jakhar', {
    init: function( editor ) {
        editor.addCommand( 'fildmanagerData', {
            exec: function( editor ) {
                document.fileManagerEditor.initEditor(editor);
            }
        });
        editor.ui.addButton( 'FileManager', {
            label: 'Insert Media',
            command: 'fildmanagerData',
            toolbar: 'insert',
            icon: 'https://image.flaticon.com/icons/png/128/148/148712.png'
        });
    }
});