/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * WordPress components that create the necessary UI elements for the block
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
import { Disabled, TextControl, CheckboxControl, SelectControl, RangeControl, Panel, PanelBody, PanelRow, ToggleControl, ColorPalette, ColorPicker, ColorIndicator } from '@wordpress/components';
import { more, code, brush } from '@wordpress/icons';

import { useSelect } from '@wordpress/data';
import { useState, useEffect } from 'react';

import apiFetch from '@wordpress/api-fetch';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps, InspectorControls, RichText, BlockControls, AlignmentToolbar, PanelColorSettings, ColorPaletteControl } from '@wordpress/block-editor';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit(props) {
	const { attributes, setAttributes, isSelected } = props;
	const [thePreview, setThePreview] = useState('');
	const [isChecked, setChecked] = useState(attributes.showBlog);
	const [isCheckedThumbnail, setCheckedThumbnail] = useState(attributes.displayThumbnails);
	useEffect(() => {
		async function go() {
			const response = await apiFetch({
				path: `/getPosts/v1/getHTML?showBlog=${attributes.showBlog}&displayThumbnails=${attributes.displayThumbnails}&noOfPost=${attributes.noOfPost}&blogColumns=${attributes.blogColumns}&titleColorList=${encodeURIComponent(attributes.titleColorList)}&descriptionColorList=${encodeURIComponent(attributes.descriptionColorList)}&readMoreText=${attributes.readMoreText}&readMoreColor=${encodeURIComponent(attributes.readMoreColor)}&paginationType=${attributes.paginationType}&paginationColor=${encodeURIComponent(attributes.paginationColor)}&paginationBackgroundColor=${encodeURIComponent(attributes.paginationBackgroundColor)}`,
				method: 'GET',
			});
			//console.log(`/getPosts/v1/getHTML?showBlog=${attributes.showBlog}&displayThumbnails=${attributes.displayThumbnails}&noOfPost=${attributes.noOfPost}&blogColumns=${attributes.blogColumns}&titleColorList=${encodeURIComponent(attributes.titleColorList)}&descriptionColorList=${encodeURIComponent(attributes.descriptionColorList)}&readMoreText=${attributes.readMoreText}&readMoreColor=${encodeURIComponent(attributes.readMoreColor)}&paginationType=${attributes.paginationType}&paginationColor=${encodeURIComponent(attributes.paginationColor)}&paginationBackgroundColor=${encodeURIComponent(attributes.paginationBackgroundColor)}`);
			setThePreview(response);
		}
		go();
	}, [attributes.showBlog, attributes.displayThumbnails, attributes.noOfPost, attributes.blogColumns, attributes.titleColorList, attributes.descriptionColorList, attributes.readMoreText, attributes.readMoreColor, attributes.paginationType, attributes.paginationColor, attributes.paginationBackgroundColor]);

	const blockProps = useBlockProps();
	const colors = [
		{ name: 'red', color: '#f00' },
		{ name: 'white', color: '#fff' },
		{ name: 'blue', color: '#00f' },
	];
	return (
		<div {...blockProps}>
			{isSelected && (
				<InspectorControls style={{ marginBottom: '40px' }}>
					<Panel header="Blog Styles">
						<PanelBody title="Blog Settings" initialOpen={true}>
							<PanelRow>
								<ToggleControl
									label={__('Show Blog On Page', 'entire-blocks')}
									help={isChecked ? __('Show Blog.', 'entire-blocks') : __('Hide Blog.', 'entire-blocks')}
									checked={isChecked}
									onChange={(val) => {
										setAttributes({
											showBlog: val === true,
										});
										setChecked(val);
									}}
								/>
							</PanelRow>
							<PanelRow>
								<ToggleControl
									label={__('Show Thumbnail', 'entire-blocks')}
									help={isCheckedThumbnail ? __('Show Thumbnail', 'entire-blocks') : __('Hide Thumbnail.', 'entire-blocks')}
									checked={isCheckedThumbnail}
									onChange={(val) => {
										console.log(val);
										setCheckedThumbnail(val);
										setAttributes({
											displayThumbnails:  val === true,
										});
									}}
								/>
							</PanelRow>

							<PanelRow>
								<RangeControl
									label={__('No. of Posts', 'entire-blocks')}
									value={attributes.noOfPost}
									onChange={(val) =>
										setAttributes({
											noOfPost: val,
										})
									}
									min={-1}
									max={20}
									step={1}
								/>
							</PanelRow>
							<PanelRow>
								<RangeControl
									label={__('No. of columns...', 'entire-blocks')}
									value={attributes.blogColumns}
									onChange={(val) => {
										setAttributes({
											blogColumns: val,
										});
										console.log(val, attributes.blogColumns);
									}}
									min={1}
									max={4}
									step={1}
								/>
							</PanelRow>
							<PanelRow>
								<TextControl label={__('Read More Text...', 'entire-blocks')} value={attributes.readMoreText} onChange={(val) => setAttributes({ readMoreText: val })} />
							</PanelRow>
						</PanelBody>
						<PanelBody title="Color" initialOpen={false}>
							<PanelRow>
								<p>
									<strong>{__('Title Color', 'entire-blocks')}</strong>
									<ColorPalette
										label={__('List Title Color', 'entire-blocks')}
										value={attributes.titleColorList}
										onChange={(val) => {
											setAttributes({
												titleColorList: val === undefined ? 'inherit' : val,
											});
										}}
									/>
								</p>
							</PanelRow>
							<PanelRow>
								<p>
									<strong>{__('Description Color', 'entire-blocks')}</strong>
									<ColorPalette
										value={attributes.descriptionColorList}
										onChange={(val) => {
											setAttributes({
												descriptionColorList: val === undefined ? 'inherit' : val,
											});
										}}
									/>
								</p>
							</PanelRow>
							<PanelRow>
								<p>
									<strong>{__('Read More Color', 'entire-blocks')}</strong>
									<ColorPalette
										label={__('List Title Color', 'entire-blocks')}
										value={attributes.readMoreColor}
										onChange={(val) => {
											setAttributes({
												readMoreColor: val === undefined ? 'inherit' : val,
											});
										}}
									/>
								</p>
							</PanelRow>
						</PanelBody>
						<PanelBody title="Pagination" initialOpen={false}>
							{/*<PanelRow>
								<SelectControl
									label="Pagination Type"
									value={attributes.paginationType}
									options={[
										{ label: __('Number', 'entire-blocks'), value: 'number' },
										{ label: __('Ajax', 'entire-blocks'), value: 'ajax' },
										{ label: __('Load More', 'entire-blocks'), value: 'load' },
									]}
									onChange={(val) => setAttributes({ paginationType: val })}
								/>
							</PanelRow>*/}
							<PanelRow>
								<p>
									<strong>{__('Text Color', 'entire-blocks')}</strong>
									<ColorPalette
										value={attributes.paginationColor}
										onChange={(val) => {
											setAttributes({
												paginationColor: val === undefined ? 'inherit' : val,
											});
										}}
									/>
								</p>
							</PanelRow>
							<PanelRow>
								<p>
									<strong>Background Color</strong>
									<ColorPalette
										label={__('Background Color', 'entire-blocks')}
										value={attributes.paginationBackgroundColor}
										onChange={(val) => {
											setAttributes({
												paginationBackgroundColor: val === undefined ? 'inherit' : val,
											});
										}}
									/>
								</p>
							</PanelRow>
						</PanelBody>
					</Panel>
				</InspectorControls>
			)}

			<BlockControls>
				<AlignmentToolbar value={attributes.alignment} onChange={(val) => setAttributes({ alignment: val })} />
			</BlockControls>
			<div style={{ textAlign: attributes.alignment }}>
				<RichText {...blockProps} tagName="h2" value={attributes.title} onChange={(val) => setAttributes({ title: val })} placeholder={__('Section Title...', 'entire-blocks')} />
				<RichText {...blockProps} tagName="p" value={attributes.description} onChange={(val) => setAttributes({ description: val })} placeholder={__('Section Description...', 'entire-blocks')} />
			</div>
			<Disabled>
			<div dangerouslySetInnerHTML={{ __html: thePreview }} />
			</Disabled>
		</div>
	);
}
