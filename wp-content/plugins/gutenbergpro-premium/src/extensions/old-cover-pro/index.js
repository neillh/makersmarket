import { parseBool } from "../../functions";

!(function () {
	var getAttr,
		renderKenBurns,
		_shapeEl,
		renderShape,
		fieldClassesInProps,
		pluginRegisterBlockType,
		pluginSaveElement,
		el = wp.element.createElement,
		SETUP_ATTR = "", // Placeholder for semantics
		components = wp.components,
		editor = wp.editor,
		shapes = {
			"": { label: "None" },
			waves: {
				label: "Waves",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path></svg>',
			},
			wavesInv: {
				label: "Waves Inverted",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg)" d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"></path></svg>',
			},
			wavesTr: {
				label: "Waves transparent",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path></svg>',
			},
			curve: {
				label: "Curve",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z"></path></svg>',
			},
			curveInv: {
				label: "Curve Inverted",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg)" d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z"></path></svg>',
			},
			curve2: {
				label: "Curve 2",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V6c0,21.6,291,111.46,741,110.26,445.39,3.6,459-88.3,459-110.26V0Z"></path></svg>',
			},
			curve2Inv: {
				label: "Curve 2 Inverted",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg)" d="M741,116.23C291,117.43,0,27.57,0,6V120H1200V6C1200,27.93,1186.4,119.83,741,116.23Z"></path></svg>',
			},
			tringl: {
				label: "Triangle",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 0L0 0 598.97 114.72 1200 0z"></path></svg>',
			},
			tringlInv: {
				label: "Triangle Inverted",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg)" d="M598.97 114.72L0 0 0 120 1200 120 1200 0 598.97 114.72z"></path></svg>',
			},
			tringl2: {
				label: "Triangle 2",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 0L0 0 892.25 114.72 1200 0z"></path></svg>',
			},
			tringl2Inv: {
				label: "Triangle 2 Inverted",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg)" d="M892.25 114.72L0 0 0 120 1200 120 1200 0 892.25 114.72z"></path></svg>',
			},
			tilt: {
				label: "Tilt",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 120L0 16.48 0 0 1200 0 1200 120z"></path></svg>',
			},
			arrow: {
				label: "Arrow",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M649.97 0L550.03 0 599.91 54.12 649.97 0z"></path></svg>',
			},
			arrowInv: {
				label: "Arrow Inverted",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg)" d="M649.97 0L599.91 54.12 550.03 0 0 0 0 120 1200 120 1200 0 649.97 0z"></path></svg>',
			},
			book: {
				label: "Book",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200,0H0V120H281.94C572.9,116.24,602.45,3.86,602.45,3.86h0S632,116.24,923,120h277Z"></path></svg>',
			},
			bookInv: {
				label: "Book Inverted",
				svg:
					'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg)" d="M602.45,3.86h0S572.9,116.24,281.94,120H923C632,116.24,602.45,3.86,602.45,3.86Z"></path></svg>',
			},
		},
		controls = [
			{
				id: "bg_effect",
				label: "Background effect",
				addClass: true,
				help: "Animations don't show a preview but work on site.",
				type: "radio",
				options: [
					{ value: "", label: "None" },
					{ value: "ken_burns", label: "Zoom and Pan" },
				],
			},
			{
				id: "image_full_screen",
				label: "Stretch full screen",
				help: "Makes the background stretch full width and height.",
				addClass: true,
				type: "toggle",
			},
			{
				id: "shape",
				label: "Shape style",
				type: "select",
				options: lodash.map(lodash.keys(shapes), function (s) {
					return { value: s, label: shapes[s].label };
				}),
			},
			{
				id: "shape_position",
				label: "Shape position",
				type: "select",
				options: [
					{ value: "", label: "Top" },
					{ value: "bottom", label: "Bottom" },
					{ value: "topbottom", label: "Top and bottom" },
				],
			},
			{
				id: "shape_flip",
				addClass: true,
				label: "Shape flip",
				type: "toggle",
			},
			{
				id: "shape_height",
				label: "Shape height",
				type: "number",
				default: "100",
				min: 20,
				max: 250,
			},
			{
				id: "shape_color",
				label: "Shape color",
				type: "color",
				default: "#fff",
			},
			{
				id: "hide_desktop",
				label: "Hide on Desktop",
				addClass: true,
				type: "toggle",
			},
			{
				id: "hide_tablet",
				label: "Hide on Tablets",
				addClass: true,
				type: "toggle",
			},
			{
				id: "hide_mobile",
				label: "Hide on Mobiles",
				addClass: true,
				type: "toggle",
			},
			{
				id: "mobile_height",
				label: "Mobile Height",
				addClass: true,
				type: "select",
				options: [
					{ value: "", label: "Same as desktop" },
					{ value: "hd", label: "HD - Good for 16:9 images" },
					{ value: "sd", label: "SD - Good for 4:3 images" },
					{ value: "full", label: "Stretch full height" },
				],
			},
		],
		supportedBlocks = function (name) {
			const blocks = {
				"core/spacer": {
					extension: "gtp_spacer_styling",
				},
				"core/cover": {
					extension: "gtp_cover_styling",
				},
				"core/paragraph": {
					extension: "gtp_paragraph_styling",
				},
			};

			const currentExtensionName = lodash.get(blocks, `${name}.extension`);
			const status = lodash.get(
				window,
				`gtpGlobals.extensions.${currentExtensionName}.status`
			);

			const isExtensionEnabled = parseBool(status);

			if (isExtensionEnabled) return [name];

			return [];
		};

	for (var i = 0; i < controls.length; i++) {
		var f = controls[i];
		f.id = f.id.indexOf("coverPro_") > -1 ? f.id : "coverPro_" + f.id;
		controls[f.id] = f;
	}

	// region Utilities
	getAttr = function (id, attr) {
		if (attr) {
			getAttr.attr = attr;
		} else {
			attr = getAttr.attr;
		}

		if (!id) {
			return "";
		}

		id = id.indexOf("coverPro_") > -1 ? id : "coverPro_" + id;
		return attr[id] || controls[id].default || "";
	};

	renderKenBurns = function (attributes) {
		getAttr(SETUP_ATTR, attributes);

		if (!getAttr("bg_effect")) {
			return null;
		}
		var className = "coverpro-bg_effect";
		if ("ken_burns" === getAttr("bg_effect")) {
			className += " coverpro-ken-burns";
		} else {
			className += " coverpro-parallax";
		}

		let focalPoint = lodash.get(attributes, "focalPoint") ?? {
			x: ".5",
			y: ".5",
		};

		return el(
			"div",
			{
				className: className,
				ariaHidden: true,
				style: {
					backgroundImage: "url(" + attributes.url + ")",
					transformOrigin:
						focalPoint.x * 100 + "%" + " " + focalPoint.y * 100 + "%",
				},
			},
			" "
		);
	};

	_shapeEl = function (svg, classname) {
		return el("div", {
			className: "coverpro-shape-wrap " + classname,
			style: {
				color: getAttr("shape_color"),
				height: getAttr("shape_height"),
			},
			dangerouslySetInnerHTML: { __html: svg },
		});
	};
	renderShape = function (attributes) {
		getAttr(SETUP_ATTR, attributes);

		if (!shapes[getAttr("shape")] || !shapes[getAttr("shape")].svg) {
			return null;
		}
		var els = [];

		if (getAttr("shape_position").indexOf("bottom") > -1) {
			els.push(
				_shapeEl(shapes[getAttr("shape")].svg, "coverpro-shape-wrap-bottom")
			);
		}

		if (getAttr("shape_position") !== "bottom") {
			els.push(
				_shapeEl(shapes[getAttr("shape")].svg, "coverpro-shape-wrap-top")
			);
		}

		return els;
	};
	fieldClassesInProps = function (attributes, props) {
		props = props || {};

		getAttr(SETUP_ATTR, attributes);

		var className = props.className || "";

		className += getAttr("shape") ? " coverpro-has-shape" : "";

		for (var i = 0; i < controls.length; i++) {
			var f = controls[i];
			if (f.addClass && attributes[f.id]) {
				className += " " + f.id.toLowerCase().replace("_", "-");
				className += f.type === "toggle" ? "" : "-" + attributes[f.id];
			}
		}
		if (className) {
			props.className = className;
		}

		return props;
	};
	// endregion Utilities

	// region STEP 1: Inspector controls
	var pluginBlockEdit = wp.compose.createHigherOrderComponent(function (
		BlockEdit
	) {
		function _setAttrCallback(props, attrName) {
			return function (value) {
				var newProps = {};
				newProps[attrName] = value;
				props.setAttributes(newProps);
			};
		}

		return function (props) {
			if (!supportedBlocks(props.name).includes(props.name)) {
				return el(BlockEdit, props);
			}

			var fieldRenderer = {
				color: function (f) {
					f.title = f.label;

					if (f.initialOpen === undefined) {
						f.initialOpen = f.value ? false : true;
					}

					f.colorSettings = [
						{
							label: f.label,
							value: f.value,
							onChange: f.onChange,
						},
					];

					return el(editor.PanelColorSettings, f);
				},
				toggle: function (f) {
					f.checked = !!f.value;
					return el(components.ToggleControl, f);
				},
				number: function (f) {
					return el(components.RangeControl, f);
				},
				radio: function (f) {
					f.selected = f.value;
					return el(components.RadioControl, f);
				},
				select: function (f) {
					return el(components.SelectControl, f);
				},
			};

			var _selectEls = [];

			getAttr(SETUP_ATTR, props.attributes);

			for (var i = 0; i < controls.length; i++) {
				var f = controls[i];
				var id = f.id.indexOf("coverPro_") > -1 ? f.id : "coverPro_" + f.id;

				f.default = f.default || "";

				const isCoverBlock = lodash.isEqual("core/cover", props.name);

				if ((!isCoverBlock && i === 0) || (!isCoverBlock && i === 1)) {
					continue;
				}

				_selectEls.push(
					fieldRenderer[f.type](
						lodash.assign(
							{
								value: getAttr(id),
								onChange: _setAttrCallback(props, id),
							},
							f
						)
					)
				);
			}

			return el(
				"div",
				fieldClassesInProps(props.attributes, {
					className: "wp-block coverpro-block-admin",
				}),
				el(BlockEdit, props),
				renderShape(props.attributes),
				// Not rendering Ken burns in admin.
				// renderKenBurns( props.attributes ),
				el(
					wp.blockEditor.InspectorControls,
					{},
					el(
						wp.components.PanelBody,
						{
							title: props.name === "core/cover" ? "Cover Block Pro" : "Shapes",
							initialOpen: false,
						},
						_selectEls
					)
				)
			);
		};
	},
	"withInspectorControls");

	wp.hooks.addFilter(
		"editor.BlockEdit",
		"cover-pro/with-inspector-controls",
		pluginBlockEdit
	);
	// endregion STEP 1: Inspector controls

	// region STEP 3: Block settings attributes
	pluginRegisterBlockType = function (settings, name) {
		if (!supportedBlocks(name).includes(name)) {
			return settings;
		}

		for (var i = 0; i < controls.length; i++) {
			var id =
				controls[i].id.indexOf("coverPro_") > -1
					? controls[i].id
					: "coverPro_" + controls[i].id;
			settings.attributes[id] = { type: "text" };
		}

		return settings;
	};

	wp.hooks.addFilter(
		"blocks.registerBlockType",
		"cover-pro/cover-settings",
		pluginRegisterBlockType
	);
	// endregion STEP 3: Block settings attributes

	// region STEP 4: Block save props
	pluginSaveElement = function (element, bkProps, attributes) {
		if (!supportedBlocks(bkProps.name).includes(bkProps.name)) {
			return element;
		}

		getAttr(SETUP_ATTR, attributes);

		var props = element.props;

		props.style = props.style || {};

		if (getAttr("shape_position").indexOf("bottom") > -1) {
			props.style.paddingBottom = getAttr("shape_height");
		}

		if (getAttr("shape_position") !== "bottom") {
			props.style.paddingTop = getAttr("shape_height");
		}

		props.children = props.children || [];

		if (!props.children.push) {
			props.children = [props.children];
		}

		props.children.push(renderShape(attributes));
		props.children.push(renderKenBurns(attributes));

		return wp.element.cloneElement(
			element,
			fieldClassesInProps(attributes, element.props),
			props.children
		);
	};

	wp.hooks.addFilter(
		"blocks.getSaveElement",
		"cover-pro/cover-props",
		pluginSaveElement
	);
	// endregion STEP 4: Block save props
})();
