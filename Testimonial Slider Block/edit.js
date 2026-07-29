import { __} from '@wordpress/i18n';
import {
    BlockControls,
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';
import {
    ToolbarButton,
    ToolbarGroup,
    Button
} from '@wordpress/components';
import { createBlock} from '@wordpress/blocks';
import './editor.scss';
import personSvg from './publice/user.png'; 


export default function Edit({ }) {
    const groupOBJ ={
                className: 'testimonial-group',
                metadata: {
                    name: "testimonial"
                },
                templateLock: "insert",
                lock: {
                    move: false,
                    remove: false
                }
            };
	const columOBJ = {
				className: 'testimonial-column',
				layout: {
					type: "constrained",
					wideSize: "600px"
				},
				style: {
					spacing: {
						padding: {
							top: "30px",
							bottom: "30px",
							left: "30px",
							right: "30px"
						}
					}
					}
				};			
	const paragraphOBJ = {
				className: 'testimonial-paragraph',
				align: "center",
				placeholder: 'I have been looking for a product like this for years!'
			};
	const imageOBJ = {
				className: 'testimonial-image is-style-rounded',
				align: "center",
				scale: "cover",
				sizeSlug: "thumbnail",
				width: "55",
				height: "55",
				url: personSvg,
				caption: "Customer name"
			};
    const defaultBlocks = [
        ['core/group', groupOBJ,
            [
                ['core/column', columOBJ,
                    [
                        ['core/paragraph', paragraphOBJ ],
                        ['core/image', imageOBJ ]
                    ]
                ]
            ]
        ]
    ];
    const addGroupBlock = () => {
        const selectedBlockClientId = wp.data.select('core/block-editor').getSelectedBlockClientId();
        if (selectedBlockClientId) {
            const groupBlock = createBlock('core/group', groupOBJ, [
                createBlock('core/column', columOBJ, [
                    createBlock('core/paragraph', paragraphOBJ),
                    createBlock('core/image', imageOBJ)
                ])
            ]);
            wp.data.dispatch('core/block-editor').insertBlock(groupBlock, undefined, selectedBlockClientId);
        }
    };
    return ( 
    < > 	
			< div {...useBlockProps()} > 
				< div className = "wpcookie-testimonial-wrapper" > 
					< InnerBlocks 
						template = {defaultBlocks}
						renderAppender = {() => {return null}}
					/> 
				< /div>
			< /div> 
			< BlockControls > 
				< ToolbarGroup > 
					< Button icon = "plus-alt"
						className = "add-testimonial-btn"
						onClick = {addGroupBlock} >
						Add 
					< /Button> 
				< /ToolbarGroup> 
			< / BlockControls > 
        < />);
}

