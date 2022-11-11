/**
 * jscroll-init.js
 *
 * Initialize the jscroll script
 */
( function() {
	$( document ).ready( function() {
		$( 'div[class^="columns"] + .storefront-sorting' ).hide();

		$( '.site-main' ).jscroll({
		    loadingHtml: '<div class="sfp-loading"></div><h4 class="sfp-loading-text">' + sfpPublicL10n.loading + '...</h4>',
		    nextSelector: 'a.next',
		    contentSelector: '.scroll-wrap',
		});
	});
} )();
