import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function save( ) {

    return (
        <div { ...useBlockProps.save() }>			
				<InnerBlocks.Content />
        </div>
    );
}

export default function save( ) {

    return (
        <div { ...useBlockProps.save() }>			
				<InnerBlocks.Content />
        </div>
    );
}
