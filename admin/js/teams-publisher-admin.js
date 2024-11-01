(function( $ ) {
	'use strict';

	$(document).on('ready', () => {
		let id = parseInt( $('#add_teams_channel').data('id') );

		$('#add_teams_channel').on('click', (event) => {
			id++;
			let elm = ' <tr >\n' +
				'            <td class="col_id"><input pattern="[A-Za-z0-9_]+" required="true"  name="channels[__ID__][id]" placeholder="ID" type="text" id="channel_name___ID__" value="" class="widefat"></td>\n' +
				'            <td><input required="true" name="channels[__ID__][url]"  placeholder="URL" type="url" id="channel_url___ID__" value="" class="widefat"></td>\n' +
				'            <td><button class="button button-container remove_teams_channel" type="button">\n' +
	'                                <span class="dashicons dashicons-trash"></span>\n' +
	'                            </button></td>\n' +
				'        </tr>';
			$('#teams-publisher-channel-table tbody').append(elm.replaceAll('__ID__', id));
		});
		$(document).on('click', '.remove_teams_channel', (event) => {
			$(event.target).closest('tr').remove();
		});
		$(document).on('#teams-publisher-form', 'submit', (event) => {
			console.log(event);
		});
	} );
})( jQuery );
