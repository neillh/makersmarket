jQuery( function () {
	var mapSelectToProps = function ( select ) {
		if ( !select( 'core/editor' ).getEditedPostAttribute( 'meta' ) ) {
			return {display: '', position: ''}
		}

		// Return nothing by default
		var getMeta = function( metaKey ) {
			return '';
		}

		// If meta values
		if ( select( 'core/editor' ).getEditedPostAttribute( 'meta' ) ) {
			getMeta = function( metaKey ) {
				// Return the meta value
				return select( 'core/editor' ).getEditedPostAttribute( 'meta' )[metaKey];
			}
		}
		var metaData = {};
		for ( let i = 0; i < woobuilderData.meta_keys.length; i ++ ) {
			const ki = woobuilderData.meta_keys[i];
			metaData[ki] = getMeta( ki );
		}
		return {metaData:metaData}
	}

	wp.plugins.registerPlugin( 'woobuilder-pro', {
		render: wp.data.withSelect( mapSelectToProps )( function ( props ) {
			var el = wp.element.createElement;
			return el(
				wp.editPost.PluginPostStatusInfo,
				{
					className: 'woobuilder-save-template'
				},
				el(
					'a',
					{
						id       : 'woobuilder-save-template-btn',
						className: 'is-secondary components-button editor-post-trash is-button is-default is-large',
						style    : {},
						onClick  : function () {
							var name = prompt( 'What would you like to call this template?' );
							if ( name ) {
								document.getElementById( 'woobuilder-save-template-btn' ).classList.add( 'is-busy' );
								jQuery.post(
									woobuilderBlocksTpl.saveTplEndpoint, {
										title   : name,
										tpl     : wp.data.select( "core/editor" ).getEditedPostContent(),
										meta: props.metaData,
									}, function ( resp ) {
										alert( resp );
										document.getElementById( 'woobuilder-save-template-btn' ).classList.remove( 'is-busy' );
									}
								);
							}
						},
					},
					'Save as template'
				),
			)
		} ),
	} );
} );