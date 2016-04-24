/**
 * @license MIT 
 *
 * all right I have no idea about MIT license, but put it on seems cool. :P  Enjoy!
 */
CKEDITOR.plugins.add( 'wenzgmap', {
    icons: 'wenzgmap',
    init: function( editor ) {
        editor.addCommand('wenzgmapDialog', new CKEDITOR.dialogCommand('wenzgmapDialog'));
        editor.ui.addButton('wenzgmap', {
            label: 'Inserir Mapa do Google Maps',
            command: 'wenzgmapDialog',
            toolbar: 'paragraph'
        });

        CKEDITOR.dialog.add('wenzgmapDialog', this.path + 'dialogs/wenzgmap.js');
    }
});