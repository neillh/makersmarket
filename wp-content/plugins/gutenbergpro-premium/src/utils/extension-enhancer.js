/**
 * Wordpress Dependencies
 */

import { withSelect, withDispatch } from "@wordpress/data";
import { isEqual, get, isEmpty, attempt, isError, filter } from "lodash";

/**
 *
 * Custom Dependencies
 *
 */

import classnames from "classnames";
import { removeCustomClass, hasCustomClass } from "../functions";

/**
 * Will compose some utilities props to make extension block enhancement easy
 * & more organized
 *
 * @param { string } targetBlockSlug
 *  ? ------ The slug of the block that is currently being extend
 *
 * @param { string } META_KEY
 *  ? ------ Where the css styling will be stored
 */

export function createCustomEnhancer(targetBlockSlug, META_KEY) {
	return [
		withSelect((select, { blockProps: { name, attributes, clientId } }) => {
			const { getEditedPostAttribute } = select("core/editor");
			const { getPreviousBlockClientId, getBlock } = select(
				"core/block-editor"
			);

			// current post meta value
			const currentPostMeta = getEditedPostAttribute("meta");

			/**
			 * Check if the current block is duplicated from the block above
			 */

			const isBlockDuplicated = () => {
				const aboveBlockClientId = getPreviousBlockClientId(clientId);
				const aboveBlock = getBlock(aboveBlockClientId);

				if (isEmpty(aboveBlock)) return false;

				// if both generated classNames match this means
				// the current block is duplicated from the above block
				// therefore we need to re-generate a new className for current block

				const aboveBlockGeneratedClassName = get(
					aboveBlock,
					"attributes.gutenbergProCustomClass"
				);

				const currentBlockGeneratedClassName = get(
					attributes,
					"gutenbergProCustomClass"
				);

				return isEqual(
					aboveBlockGeneratedClassName,
					currentBlockGeneratedClassName
				);
			};

			return {
				currentPostMeta,
				currentScopedMeta: get(currentPostMeta, META_KEY),
				isDuplicated: isBlockDuplicated(),

				/**
				 * Checking for the supported block i.e => core/spacer
				 * @return {boolean} block supported
				 */

				isBlockSupported() {
					return isEqual(targetBlockSlug, name);
				},
			};
		}),
		withDispatch(
			(
				dispatch,
				{
					blockProps: { clientId, setAttributes, attributes },
					currentScopedMeta,
					isDuplicated,
				}
			) => {
				const { editPost } = dispatch("core/editor");

				const { className = "", gutenbergProCustomClass = "" } = attributes;
				const uniqueId =
					typeof clientId === "string" ? clientId.substring(0, 5) : "";

				return {
					/**
					 * Will test and add custom class on spacer block
					 * @param {boolean} onLoad initial render
					 */

					addCustomClass(onLoad = false) {
						if (hasCustomClass(className, "gp-gutenbergpro-" + uniqueId))
							return;

						if (isEmpty(gutenbergProCustomClass) || isDuplicated || onLoad) {
							setAttributes({
								gutenbergProCustomClass: "gp-gutenbergpro-" + uniqueId,
							});
						}

						// updating the class
						setAttributes({
							className: classnames(
								removeCustomClass(className),
								gutenbergProCustomClass
							),
						});
					},

					/**
					 * Update the meta field to render styles on frontend
					 * @param {string} newGeneratedStyle updated style
					 */

					updateMeta(newGeneratedStyle) {
						// checking if the meta value can be parsed into json
						const canBeParsed = !isError(
							attempt(JSON.parse, currentScopedMeta)
						);

						// breaking if it's un-parsable
						if (!canBeParsed) return;

						const parsedStyles = JSON.parse(currentScopedMeta);

						parsedStyles[clientId] = newGeneratedStyle;

						editPost({ meta: { [META_KEY]: JSON.stringify(parsedStyles) } });
					},
				};
			}
		),
	];
}
