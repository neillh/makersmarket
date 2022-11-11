/**
 * Wordpress external dependencies
 */

import {Fragment, useEffect} from "@wordpress/element";
import {compose} from "@wordpress/compose";
import {InspectorControls} from "@wordpress/block-editor";
import {PanelBody, RangeControl, FormToggle, PanelRow,} from "@wordpress/components";
import {__} from "@wordpress/i18n";

/**
 * Lodash ( pre-installed with wordpress )
 */

import {get, toString, isEmpty} from "lodash";

/**
 * Custom imports and dependencies
 */

import BackgroundPicker, {
	convertBackground,
} from "../../components/background";
import Dimensions, {
	convertDimensions,
} from "../../components/dimension-picker/dimensions";
import styleToCss from "style-object-to-css-string";
import classnames from "classnames";
import {
	excludeEmpties,
	removeBlockMeta,
	testAndRemoveUnusedMeta,
} from "../../functions";

import FontFamilyPicker from "../../components/font-picker";
import {TEXT_DOMAIN} from "../../globals";
import {useRef} from "@wordpress/element";

// enhancer

import {createCustomEnhancer} from "../../utils/extension-enhancer";

const META_KEY = "gtp_paragraph_styling";
const $ = jQuery;

/**
 * Main Extended Block Component
 */

function ExtendedParagraph( {
	BlockEdit,
	blockProps,
	isBlockSupported,
	addCustomClass,
	updateMeta,
} ) {
	if ( !isBlockSupported() ) {
		return BlockEdit;
	}

	const {clientId, attributes, setAttributes, name} = blockProps;
	const handle = useRef();

	// custom attributes
	const background    = get( attributes, "gutenbergProBackground" ),
				innerPadding  = get( attributes, "gutenbergProInnerPadding" ),
				paragraphFont = get( attributes, "gutenbergProFontFamily" ),
				isFullWidth   = get( attributes, "gutenbergProParagraphFullWidth" );

	const customClass = get( attributes, "gutenbergProCustomClass" );

	useEffect( () => {
		const newStyles = getStyledCSS( false );
		updateMeta( newStyles ); // updating meta value
		addCustomClass();
		testAndRemoveUnusedMeta( META_KEY );
	}, [attributes] );

	useEffect( () => {
		addCustomClass( true );

		return () => {
			testAndRemoveUnusedMeta( META_KEY );
			removeBlockMeta( clientId, META_KEY );
		};
	}, [] );

	useEffect( () => {
		if ( isFullWidth ) {
			$( handle.current )
				.parents( ".coverpro-block-admin" )
				.addClass( "coverpro-image_full_screen" );
		} else {
			$( handle.current )
				.parents( ".coverpro-block-admin" )
				.removeClass( "coverpro-image_full_screen" );
		}
	}, [isFullWidth] );

	const getStyledCSS = ( isEditor = true ) => {
		const convertedBackground = convertBackground( background );

		let generatedStyles = excludeEmpties( {
			...convertedBackground,
			height    : toString( attributes.gutenbergProParagraphHeight ) + "px",
			padding   : convertDimensions( innerPadding ),
			fontFamily: !isEmpty( paragraphFont ) ? paragraphFont + " !important" : "",
		} );

		if ( isFullWidth ) {
			generatedStyles = Object.assign(
				generatedStyles,
				!isEditor
					? {
						marginLeft : "calc(-50vw + 50%)",
						marginRight: "calc(-50vw + 50%)",
						maxWidth   : "100vw",
						width      : "100vw",
					}
					: {}
			);
		}

		let familyImport = "";

		if ( !isEmpty( paragraphFont ) && paragraphFont !== "Default" ) {
			familyImport = `@import url('https://fonts.googleapis.com/css2?family=${paragraphFont}');`;
		}

		const paragraphsStyling = `${familyImport} .${customClass} { ${styleToCss(
			generatedStyles
		)} }`;

		return paragraphsStyling;
	};

	return (
		<Fragment>
			{BlockEdit}
			<style
				dangerouslySetInnerHTML={{
					__html: getStyledCSS(),
				}}
			></style>
			<div ref={handle}></div>
			<InspectorControls>
				<PanelBody
					title={__( "Paragraph Block Pro Settings", TEXT_DOMAIN )}
					initialOpen={false}
				>
					<FontFamilyPicker
						value={paragraphFont}
						onChange={( newPgFont ) =>
							setAttributes( {gutenbergProFontFamily: newPgFont} )
						}
					/>
					<PanelRow className="paragraphpro-option">
						<span>{__( "Full Width", TEXT_DOMAIN )}</span>
						<FormToggle
							checked={attributes.gutenbergProParagraphFullWidth}
							onChange={() =>
								setAttributes( {
									gutenbergProParagraphFullWidth: !attributes.gutenbergProParagraphFullWidth,
								} )
							}
						/>
					</PanelRow>

					<RangeControl
						className="paragraphpro-option"
						label={__( "Height", TEXT_DOMAIN )}
						value={attributes.gutenbergProParagraphHeight}
						min={0}
						max={1000}
						allowReset
						resetFallbackValue={0}
						onChange={( newHeight ) =>
							setAttributes( {gutenbergProParagraphHeight: newHeight} )
						}
					/>

					<div className="paragraphpro-option">
						<BackgroundPicker
							value={background}
							onChange={( gutenbergProBackground ) => {
								setAttributes( {gutenbergProBackground} );
							}}
						/>
					</div>

					<div className="paragraphpro-option">
						<span>{__( "Inner Padding", TEXT_DOMAIN )}</span>
						<Dimensions
							viewPort={"Desktop"}
							value={innerPadding}
							onChange={( dim, unit ) => {
								setAttributes( {
									gutenbergProInnerPadding: {
										...innerPadding,
										value: dim,
										unit : unit,
									},
								} );
							}}
							onImportant={( imp ) => {
								setAttributes( {
									gutenbergProInnerPadding: {
										...innerPadding,
										important: imp,
									},
								} );
							}}
						/>
					</div>
				</PanelBody>
			</InspectorControls>
		</Fragment>
	);
}

const paragraphBlockEnhancer = createCustomEnhancer( "core/paragraph", META_KEY );

export default compose( paragraphBlockEnhancer )( ExtendedParagraph );
