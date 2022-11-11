import domReady from '@wordpress/dom-ready';

domReady(() => {
    wp.blocks.registerBlockStyle('core/media-text', {
        name: 'gtp-overlap',
        label: 'Overlap'
    });
});