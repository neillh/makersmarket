import { select, dispatch } from '@wordpress/data';
import { clone, isEmpty, isEqual } from 'lodash';

function removeUnnecessaryCss(css) {

    const newCSS = clone(css);

    for (const [clientId, blockCSS] of Object.entries(css)) {
        const foundBlock = select('core/block-editor').getBlocks(clientId);

        if ( isEmpty(foundBlock) ) {
            delete newCSS[clientId];
        }

    }

    return newCSS;
}


let initialCSS = wp.hooks.applyFilters('gutenbergPro.columns.css', {});

const unsubscribe = wp.data.subscribe(() => {
    
    const currentCSS = wp.hooks.applyFilters('gutenbergPro.columns.css', {});
        
    if (!isEqual(currentCSS, initialCSS)) {

        initialCSS = currentCSS;

        const finalMetadata = removeUnnecessaryCss(currentCSS);

        dispatch('core/editor').editPost({
            meta: {
                "gtp_columnspro_styling": JSON.stringify(finalMetadata)
            }
        })

        
    }

});

