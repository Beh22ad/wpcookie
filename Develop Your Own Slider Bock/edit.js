/**
 * edit.js complete code by WPCookie
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import {
  useBlockProps,
  MediaPlaceholder,
  MediaUpload,
  MediaUploadCheck,
  BlockIcon,
  BlockControls,
  InspectorControls,
  AlignmentToolbar,
} from "@wordpress/block-editor";

import {
  ToolbarButton,
  ToolbarGroup,
  ToggleControl,
  PanelBody,
  SelectControl,
  __experimentalUnitControl as UnitControl,
} from "@wordpress/components";



/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit(props) {
  const hasImages = props.attributes.images.length > 0;

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("General", "wpcookie-image-slider")} initialOpen>
          <SelectControl
            value={props.attributes.imageSize}
            options={[
              { value: "thumbnail", label: "Thumbnail" },
              { value: "medium", label: "Medium" },
              { value: "full", label: "Full" },
            ]}
            label={__("Image Size", "wpcookie-image-slider")}
            onChange={(newimageSize) =>
              props.setAttributes({ imageSize: newimageSize })
            }
          />

          <SelectControl
            value={props.attributes.layout}
            options={[
              { value: "slider-item-nogap", label: "1 slide per page" },
              { value: "slider-item-show2", label: "2 slide per page" },
              { value: "slider-item-show3", label: "3 slide per page" },
              { value: "slider-item-show4", label: "4 slide per page" },
              { value: "slider-item-show5", label: "5 slide per page" },
              { value: "slider-item-show6", label: "6 slide per page" },
            ]}
            label={__("Layout", "wpcookie-image-slider")}
            onChange={(newlayout) => props.setAttributes({ layout: newlayout })}
          />

          <ToggleControl
            checked={props.attributes.showCaption}
            label={__("Show Caption", "wpcookie-image-slider")}
            onChange={() =>
              props.setAttributes({
                showCaption: !props.attributes.showCaption,
              })
            }
          />
        </PanelBody>

        <PanelBody
          title={__("Theme settings", "wpcookie-image-slider")}
          initialOpen={false}
        >
          <SelectControl
            value={props.attributes.theme}
            options={[
              { value: "", label: "Light" },
              {
                value: "slider-indicators-dark slider-nav-dark",
                label: "Dark",
              },
            ]}
            label={__("Theme", "wpcookie-image-slider")}
            onChange={(newtheme) => props.setAttributes({ theme: newtheme })}
          />
          
          <UnitControl
          label="Height"
          value={ props.attributes.height || "" }
          onChange={ ( newHeight ) => props.setAttributes({ height: newHeight }) }
          units={ [
            { value: 'px', label: 'px' },
            { value: '%', label: '%' },
            { value: 'em', label: 'em' },
            { value: 'vh', label: 'vh', default: true },
          ] }
        />        
          <ToggleControl
            checked={props.attributes.autoPlay}
            label={__("Auto Play", "wpcookie-image-slider")}
            onChange={() =>
              props.setAttributes({
                autoPlay: !props.attributes.autoPlay,
              })
            }
          />

          {props.attributes.autoPlay && (
            <ToggleControl
              checked={props.attributes.pauseOnHover}
              label={__("Pause on hover", "wpcookie-image-slider")}
              onChange={() =>
                props.setAttributes({
                  pauseOnHover: !props.attributes.pauseOnHover,
                })
              }
            />
          )}

          <SelectControl
            value={props.attributes.navigation}
            options={[
              { value: "slider-nav-visible", label: "Always visible" },
              { value: "", label: "Auto hide" },
            ]}
            label={__("Navigation", "wpcookie-image-slider")}
            onChange={(newnavigation) =>
              props.setAttributes({ navigation: newnavigation })
            }
          />

          <SelectControl
            value={props.attributes.indicators}
            options={[
              { value: "", label: "Inside" },
              { value: "slider-indicators-outside", label: "Outside" },
            ]}
            label={__("Indicators", "wpcookie-image-slider")}
            onChange={(newindicators) =>
              props.setAttributes({ indicators: newindicators })
            }
          />
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps()}>
        <BlockControls>
          <ToolbarGroup>
            <MediaUploadCheck>
              <MediaUpload
                multiple
                gallery
                addToGallery={true}
                onSelect={(newImages) =>
                  props.setAttributes({ images: newImages })
                }
                allowedTypes={["image"]}
                value={props.attributes.images.map((image) => image.id)}
                render={({ open }) => (
                  <ToolbarButton onClick={open}>
                    {__("Edit Gallery", "wpcookie-image-slider")}
                  </ToolbarButton>
                )}
              />
            </MediaUploadCheck>
            
			<AlignmentToolbar
				value={ props.attributes.align }
				onChange={ ( newAlign ) => 
				 props.setAttributes({ align: newAlign }) 
				 }				
				alignmentControls={ [
				  {
					icon: 'align-none',
					title: 'None',
					align: '',
				  },
				  {
					icon: 'align-wide',
					title: 'Wide Width',
					align: 'alignwide',
				  },
				  {
					icon: 'align-full-width',
					title: 'Full Width',
					align: 'alignfull',
				  },
				] }
			/>
           
          </ToolbarGroup>
        </BlockControls>

        {hasImages && (
          <figure className="wpcookie-image-slider-inner-container">
            {props.attributes.images.map((image, index) => (
              <img key={index} src={image.url} />
            ))}
          </figure>
        )}

        {!hasImages && (
          <MediaPlaceholder
            multiple
            gallery
            icon={<BlockIcon icon="format-gallery" />}
            labels={{
              title: "WPCookie Image Sider",
              instructions: "Create an awesome image slider.",
            }}
            onSelect={(newImages) => props.setAttributes({ images: newImages })}
          />
        )}
      </div>
    </>
  );
}
