<?php
/**
 * Customizer setup.
 *
 * @package Skinny\Customizer
 */

namespace Skinny\Customizer;

use Skinny\Customizer\ControlTypes\Divider_Control;
use Skinny\Customizer\ControlTypes\Range_Slider_Control;
use Skinny\Customizer\ControlTypes\Switcher_Control;
use Skinny\Customizer\ControlTypes\Alpha_Color_Picker_Control;
use Skinny\Customizer\ControlTypes\Responsive_Control;
use Skinny\Customizer\ControlTypes\Multiple_Select_Control;
use Skinny\Customizer\ControlTypes\Toggle_Switch_Control;
use Skinny\Fonts\Fonts;
use function Skinny\Core\get_script_suffix;

/**
 * Set up Customizer hooks.
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'customize_register', $n( 'register_control_types' ) );
	add_action( 'customize_register', $n( 'default_controls' ) );
	add_action( 'customize_register', $n( 'register_panels_sections' ) );
	add_action( 'customize_register', $n( 'register_color_controls' ) );
	add_action( 'customize_register', $n( 'register_typo_controls' ) );
	add_action( 'customize_register', $n( 'register_button_controls' ) );
	add_action( 'customize_register', $n( 'register_layout_controls' ) );
	add_action( 'customize_register', $n( 'register_site_controls' ) );
	add_action( 'customize_register', $n( 'register_header_controls' ) );
	add_action( 'customize_register', $n( 'register_footer_controls' ) );
	add_action( 'customize_preview_init', $n( 'customize_preview_init' ) );
	add_action( 'customize_controls_enqueue_scripts', $n( 'customize_controls_assets' ) );

	require_once get_parent_theme_file_path( 'inc/customizer/helpers.php' ); // phpcs:ignore
	require_once get_parent_theme_file_path( 'inc/customizer/styles.php' ); // phpcs:ignore
}
/**
 * Register our custom control types.
 *
 * @param \WP_Customize_Manager $wp_customize The customizer object.
 *
 * @return void
 */
function register_control_types( \WP_Customize_Manager $wp_customize ) {
	// This file is a class for our Customizer switcher control, not template partials.
	// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	require_once get_parent_theme_file_path( 'inc/customizer/control-types/switcher-control/switcher-control.php' ); // phpcs:ignore
	// Divider control class.
	require_once get_parent_theme_file_path( 'inc/customizer/control-types/divider-control/divider-control.php' ); // phpcs:ignore
	// Alpha color picker control class.
	require_once get_parent_theme_file_path( 'inc/customizer/control-types/alpha-color-picker-control/alpha-color-picker-control.php' ); // phpcs:ignore
	// Multiple select2.
	require_once get_parent_theme_file_path( 'inc/customizer/control-types/multiple-select-control/multiple-select-control.php' ); // phpcs:ignore
	// Responsive.
	require_once get_parent_theme_file_path( 'inc/customizer/control-types/responsive-control/responsive-control.php' ); // phpcs:ignore
	// Range slider control.
	require_once get_parent_theme_file_path( 'inc/customizer/control-types/range-slider-control/range-slider-control.php' ); // phpcs:ignore
	// Toggle Switch control.
	require_once get_parent_theme_file_path( 'inc/customizer/control-types/toggle-switch-control/toggle-switch-control.php' ); // phpcs:ignore

	$wp_customize->register_control_type( Switcher_Control::class );
	// Register Responsive Custom Control.
	$wp_customize->register_control_type( Responsive_Control::class );
}

/**
 * Enqueues the preview js for the customizer.
 *
 * @return void
 */
function customize_preview_init() {

	$suffix = get_script_suffix();

	wp_enqueue_script(
		'skinny-customize-preview',
		get_theme_file_uri( "assets/js/customize-preview{$suffix}.js" ),
		array( 'jquery', 'customize-preview' ),
		filemtime( get_theme_file_path( "/assets/js/customize-preview{$suffix}.js" ) ),
		true
	);
}

/**
 * Enqueues the necessary controls assets
 *
 * @return void
 */
function customize_controls_assets() {

	$suffix = get_script_suffix();

	// Conditionally register Select2 script & styles.
	if ( ! wp_script_is( 'select2', 'registered' ) && ! wp_style_is( 'select2', 'registered' ) ) {
		wp_register_style(
			'select2',
			get_parent_theme_file_uri( "assets/css/select2{$suffix}.css" ),
			array(),
			'4.0.6-rc.1'
		);
		wp_register_script(
			'select2',
			get_parent_theme_file_uri( "assets/js/select2{$suffix}.js" ),
			array( 'jquery' ),
			'4.0.6-rc.1',
			true
		);
	}

	// Load Select2 script & styles.
	wp_enqueue_style( 'select2' );
	wp_enqueue_script( 'select2' );

	// Attach Select2 Customizer Z-index patch.
	wp_add_inline_style(
		'select2',
		'.select2-container {
					z-index: 600000;
				}
			'
	);

	wp_enqueue_style(
		'skinny-customize-style',
		get_theme_file_uri( "assets/css/customizer{$suffix}.css" ),
		array(),
		filemtime( get_theme_file_path( "/assets/css/customizer{$suffix}.css" ) )
	);

	wp_enqueue_script(
		'skinny-customize-controls',
		get_theme_file_uri( "assets/js/customize-controls{$suffix}.js" ),
		array( 'jquery', 'customize-controls', 'select2' ),
		filemtime( get_theme_file_path( "/assets/js/customize-controls{$suffix}.js" ) ),
		true
	);

	wp_set_script_translations( 'skinny-customize-controls', 'skinny' );

	$fonts = Fonts::customizer_fonts_data_filtered();
	wp_localize_script(
		'skinny-customize-controls',
		'fontsData',
		array(
			'fonts' => $fonts,
		)
	);
}

/**
 * Tweaks the default customizer controls.
 *
 * @param \WP_Customize_Manager $wp_customize The customize manager object.
 *
 * @return void
 */
function default_controls( \WP_Customize_Manager $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'custom_logo' )->transport     = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.header__title-inner',
				'render_callback' => '\\Skinny\\site_branding',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.header__title-inner',
				'render_callback' => '\\Skinny\\site_branding',
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'custom_logo',
			array(
				'selector'        => '.header__title-inner',
				'render_callback' => '\\Skinny\\site_branding',
			)
		);
	}

	$wp_customize->remove_section( 'background_image' );
}

/**
 * Register Panels & Sections
 *
 * @param \WP_Customize_Manager $wp_customize The customize manager object
 */
function register_panels_sections( \WP_Customize_Manager $wp_customize ) {

	// Panel: Theme Options.
	$wp_customize->add_panel(
		'skinny_theme_options',
		array(
			'title'    => esc_html__( 'Theme Options', 'skinny' ),
			'priority' => 999,
		)
	);

	// Section: Typography.
	$wp_customize->add_section(
		'skinny_typo_settings',
		array(
			'title'    => esc_html__( 'Typography', 'skinny' ),
			'panel'    => 'skinny_theme_options',
			'priority' => 0,
		)
	);

	// Section: Button.
	$wp_customize->add_section(
		'skinny_button_settings',
		array(
			'title'    => esc_html__( 'Theme Button', 'skinny' ),
			'panel'    => 'skinny_theme_options',
			'priority' => 5,
		)
	);

	// Section: Layout.
	$wp_customize->add_section(
		'skinny_layout_settings',
		array(
			'title'    => esc_html__( 'Layout', 'skinny' ),
			'panel'    => 'skinny_theme_options',
			'priority' => 10,
		)
	);

	// Section: Header.
	$wp_customize->add_section(
		'skinny_header_settings',
		array(
			'title'    => esc_html__( 'Header', 'skinny' ),
			'panel'    => 'skinny_theme_options',
			'priority' => 20,
		)
	);

	// Section: Footer.
	$wp_customize->add_section(
		'skinny_footer_settings',
		array(
			'title'    => esc_html__( 'Footer', 'skinny' ),
			'panel'    => 'skinny_theme_options',
			'priority' => 30,
		)
	);

	// Section: Site Settings.
	$wp_customize->add_section(
		'skinny_site_settings',
		array(
			'title'    => esc_html__( 'Site Settings', 'skinny' ),
			'panel'    => 'skinny_theme_options',
			'priority' => 40,
		)
	);
}

/**
 * Register Site Color Controls within Customize.
 *
 * @param \WP_Customize_Manager $wp_customize The customize manager object.
 *
 * @return void
 */
function register_color_controls( \WP_Customize_Manager $wp_customize ) {
	$settings = skinny_get_setting();

	// Site Color Scheme.
	$wp_customize->add_setting(
		'site_color_scheme',
		array(
			'default'           => $settings['site_color_scheme'],
			'transport'         => 'postMessage',
			'sanitize_callback' => __NAMESPACE__ . '\\sanitize_radio',
		)
	);

	$wp_customize->add_control(
		new Switcher_Control(
			$wp_customize,
			'site_color_scheme',
			array(
				'label'         => esc_html__( 'Color Scheme', 'skinny' ),
				'description'   => esc_html__( 'Select which of the color modes will be active by default.', 'skinny' ),
				'section'       => 'colors',
				'choices'       => array(
					'light' => array(
						'label'         => esc_html_x( 'Light', 'name of the second color scheme option', 'skinny' ),
						'preview_image' => get_theme_file_uri( 'assets/images/svg/light-scheme.svg' ),
					),
					'dark'  => array(
						'label'         => esc_html_x( 'Dark', 'name of the third color scheme option', 'skinny' ),
						'preview_image' => get_theme_file_uri( 'assets/images/svg/dark-scheme.svg' ),
					),
				),
				'switcher_type' => 'color-scheme',
				'priority'      => 1,
			)
		)
	);

	// Dark scheme body bg color.
	$wp_customize->add_setting(
		'dark_scheme_body_bg_color',
		array(
			'default'           => $settings['dark_scheme_body_bg_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'dark_scheme_body_bg_color',
			array(
				'label'   => esc_html__( 'Body Background', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Dark scheme text color.
	$wp_customize->add_setting(
		'dark_scheme_text_color',
		array(
			'default'           => $settings['dark_scheme_text_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'dark_scheme_text_color',
			array(
				'label'   => esc_html__( 'Text', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Dark scheme accent color.
	$wp_customize->add_setting(
		'dark_scheme_accent_color',
		array(
			'default'           => $settings['dark_scheme_accent_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'dark_scheme_accent_color',
			array(
				'label'   => esc_html__( 'Accent', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Light scheme body bg color.
	$wp_customize->add_setting(
		'light_scheme_body_bg_color',
		array(
			'default'           => $settings['light_scheme_body_bg_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'light_scheme_body_bg_color',
			array(
				'label'   => esc_html__( 'Body Background', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Light scheme text color.
	$wp_customize->add_setting(
		'light_scheme_text_color',
		array(
			'default'           => $settings['light_scheme_text_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'light_scheme_text_color',
			array(
				'label'   => esc_html__( 'Text', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Light scheme accent color.
	$wp_customize->add_setting(
		'light_scheme_accent_color',
		array(
			'default'           => $settings['light_scheme_accent_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'light_scheme_accent_color',
			array(
				'label'   => esc_html__( 'Accent', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Dark Scheme: Custom Header Background Color.
	$wp_customize->add_setting(
		'dark_scheme_custom_header_bg_color',
		array(
			'default'           => $settings['dark_scheme_custom_header_bg_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);

	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'dark_scheme_custom_header_bg_color',
			array(
				'label'   => esc_html__( 'Custom Header BG Overlay', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Light Scheme: Custom Header Background Color.
	$wp_customize->add_setting(
		'light_scheme_custom_header_bg_color',
		array(
			'default'           => $settings['light_scheme_custom_header_bg_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);

	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'light_scheme_custom_header_bg_color',
			array(
				'label'   => esc_html__( 'Custom Header BG Overlay', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	/**
	 * Dark Scheme: Expand toggle for Buttons Normal.
	 */
	$wp_customize->add_setting(
		'dark_scheme_wrap_btn_colors',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'dark_scheme_wrap_btn_colors',
			array(
				'label'      => __( 'Buttons Normal', 'skinny' ),
				'toggle_ids' => '#customize-control-dark_scheme_btn_bg_color, #customize-control-dark_scheme_btn_text_color',
				'type'       => 'expand-header',
				'section'    => 'colors',
			)
		)
	);

	// Dark Scheme: Button bg color.
	$wp_customize->add_setting(
		'dark_scheme_btn_bg_color',
		array(
			'default'           => $settings['dark_scheme_btn_bg_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'dark_scheme_btn_bg_color',
			array(
				'label'   => esc_html__( 'Button Background', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Dark Scheme: Button text color.
	$wp_customize->add_setting(
		'dark_scheme_btn_text_color',
		array(
			'default'           => $settings['dark_scheme_btn_text_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'dark_scheme_btn_text_color',
			array(
				'label'   => esc_html__( 'Button Text', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	/**
	 * Light Scheme: Expand toggle for Normal Buttons.
	 */
	$wp_customize->add_setting(
		'light_scheme_wrap_btn_colors',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'light_scheme_wrap_btn_colors',
			array(
				'label'      => __( 'Buttons Normal', 'skinny' ),
				'toggle_ids' => '#customize-control-light_scheme_btn_bg_color, #customize-control-light_scheme_btn_text_color',
				'type'       => 'expand-header',
				'section'    => 'colors',
			)
		)
	);

	// Light Scheme: Button bg color.
	$wp_customize->add_setting(
		'light_scheme_btn_bg_color',
		array(
			'default'           => $settings['light_scheme_btn_bg_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'light_scheme_btn_bg_color',
			array(
				'label'   => esc_html__( 'Button Background', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Light Scheme: Button text color.
	$wp_customize->add_setting(
		'light_scheme_btn_text_color',
		array(
			'default'           => $settings['light_scheme_btn_text_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'light_scheme_btn_text_color',
			array(
				'label'   => esc_html__( 'Button Text', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	/**
	 * Dark Scheme: Expand toggle for Buttons Hover.
	 */
	$wp_customize->add_setting(
		'dark_scheme_wrap_btn_hover_colors',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'dark_scheme_wrap_btn_hover_colors',
			array(
				'label'      => __( 'Buttons Hover', 'skinny' ),
				'toggle_ids' => '#customize-control-dark_scheme_btn_hover_bg_color, #customize-control-dark_scheme_btn_hover_text_color',
				'type'       => 'expand-header',
				'section'    => 'colors',
			)
		)
	);

	// Dark Scheme: Button hover bg color.
	$wp_customize->add_setting(
		'dark_scheme_btn_hover_bg_color',
		array(
			'default'           => $settings['dark_scheme_btn_hover_bg_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'dark_scheme_btn_hover_bg_color',
			array(
				'label'   => esc_html__( 'Button Background', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Dark Scheme: Button hover text color.
	$wp_customize->add_setting(
		'dark_scheme_btn_hover_text_color',
		array(
			'default'           => $settings['dark_scheme_btn_hover_text_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'dark_scheme_btn_hover_text_color',
			array(
				'label'   => esc_html__( 'Button Text', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	/**
	 * Light Scheme: Expand toggle for Buttons Hover.
	 */
	$wp_customize->add_setting(
		'light_scheme_wrap_btn_hover_colors',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'light_scheme_wrap_btn_hover_colors',
			array(
				'label'      => __( 'Buttons Hover', 'skinny' ),
				'toggle_ids' => '#customize-control-light_scheme_btn_hover_bg_color, #customize-control-light_scheme_btn_hover_text_color',
				'type'       => 'expand-header',
				'section'    => 'colors',
			)
		)
	);

	// Light Scheme: Button hover bg color.
	$wp_customize->add_setting(
		'light_scheme_btn_hover_bg_color',
		array(
			'default'           => $settings['light_scheme_btn_hover_bg_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'light_scheme_btn_hover_bg_color',
			array(
				'label'   => esc_html__( 'Button Background', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Light Scheme: Button hover text color.
	$wp_customize->add_setting(
		'light_scheme_btn_hover_text_color',
		array(
			'default'           => $settings['light_scheme_btn_hover_text_color'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_hex_rgba',
		)
	);
	$wp_customize->add_control(
		new Alpha_Color_Picker_Control(
			$wp_customize,
			'light_scheme_btn_hover_text_color',
			array(
				'label'   => esc_html__( 'Button Text', 'skinny' ),
				'section' => 'colors',
			)
		)
	);

	// Reset color controls button.
	$wp_customize->add_setting(
		'reset_scheme_colors_btn',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'reset_scheme_colors_btn',
			array(
				'label'   => __( 'Reset color scheme', 'skinny' ),
				'type'    => 'reset-btn',
				'section' => 'colors',
			)
		)
	);

}

/**
 * Register Typography Controls within Customize.
 *
 * @param \WP_Customize_Manager $wp_customize The customize manager object.
 *
 * @return void
 */
function register_typo_controls( \WP_Customize_Manager $wp_customize ) {

	// Get Fonts List.
	$google_fonts = Fonts::all_font_choices();

	$settings = skinny_get_setting();

	/**
	 * BODY & CONTENT.
	 */

	// Body font settings.
	$wp_customize->add_setting(
		'font_body',
		array(
			'default'           => $settings['font_body'],
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'font_body',
		array(
			'label'   => __( 'Body Font', 'skinny' ),
			'section' => 'skinny_typo_settings',
			'type'    => 'select',
			'choices' => $google_fonts,
		)
	);

	$font_body_variants_list = Fonts::load_google_font_variants( 'font_body' );
	// Load body font variant settings.
	$wp_customize->add_setting(
		'font_body_load_variant',
		array(
			'default'           => $settings['font_body_load_variant'],
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_text',
		)
	);
	$wp_customize->add_control(
		new Multiple_Select_Control(
			$wp_customize,
			'font_body_load_variant',
			array(
				'label'       => __( 'Font Variant', 'skinny' ),
				'section'     => 'skinny_typo_settings',
				'input_attrs' => array(
					'multiselect' => true,
				),
				'choices'     => $font_body_variants_list,
			)
		)
	);

	// Base font size settings.
	$wp_customize->add_setting(
		'font_base_size',
		array(
			'default'           => $settings['font_base_size'],
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_responsive_typo',
		)
	);
	$wp_customize->add_control(
		new Responsive_Control(
			$wp_customize,
			'font_base_size',
			array(
				'label'       => __( 'Base Font Size', 'skinny' ),
				'section'     => 'skinny_typo_settings',
				'type'        => 'skinny-responsive',
				'input_attrs' => array(
					'min'  => 6,
					'max'  => 200,
					'step' => 1,
				),
				'units'       => array(
					'px' => 'px',
				),
			)
		)
	);

	// Body font line height settings.
	$wp_customize->add_setting(
		'font_base_line_height',
		array(
			'default'           => $settings['font_base_line_height'],
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_responsive_typo',
		)
	);
	$wp_customize->add_control(
		new Responsive_Control(
			$wp_customize,
			'font_base_line_height',
			array(
				'label'       => __( 'Base Line Height', 'skinny' ),
				'section'     => 'skinny_typo_settings',
				'type'        => 'skinny-responsive',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 2000,
					'step' => 1,
				),
				'units'       => array(
					'%' => '%',
				),
			)
		)
	);

	/**
	 * HEADINGS.
	 */
	// Headings Divider horizontal line.
	$wp_customize->add_setting(
		'font_headings_divider',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'font_headings_divider',
			array(
				'label'   => 'Type Divider',
				'type'    => 'line',
				'section' => 'skinny_typo_settings',
			)
		)
	);
	// Headings font settings.
	$wp_customize->add_setting(
		'font_headings',
		array(
			'default'           => $settings['font_headings'],
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'font_headings',
		array(
			'label'   => __( 'Heading Font', 'skinny' ),
			'section' => 'skinny_typo_settings',
			'type'    => 'select',
			'choices' => $google_fonts,
		)
	);

	$font_heading_variants_list = Fonts::load_google_font_variants( 'font_headings' );

	// Load heading font variant settings.
	$wp_customize->add_setting(
		'font_headings_load_variant',
		array(
			'default'           => $settings['font_headings_load_variant'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_text',
		)
	);
	$wp_customize->add_control(
		new Multiple_Select_Control(
			$wp_customize,
			'font_headings_load_variant',
			array(
				'label'       => __( 'Font Variant', 'skinny' ),
				'section'     => 'skinny_typo_settings',
				'input_attrs' => array(
					'multiselect' => true,
				),
				'choices'     => $font_heading_variants_list,
			)
		)
	);

	/**
	 * Font Subset.
	 */
	// Font subset divider line.
	$wp_customize->add_setting(
		'font_subset_divider',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'font_subset_divider',
			array(
				'description' => 'Type Divider',
				'type'        => 'line',
				'section'     => 'skinny_typo_settings',
			)
		)
	);
	// Font character subset settings.
	$wp_customize->add_setting(
		'font_subset',
		array(
			'default'           => $settings['font_subset'],
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'font_subset',
		array(
			'label'       => __( 'Google Font Subset', 'skinny' ),
			'description' => sprintf(
			/* translators: Link to Google fonts website */
				esc_html__( 'Not all fonts provide each of these subsets. Please visit the %s to see which subsets are available for each font.', 'skinny' ),
				sprintf(
					'<a href="%1$s" target="_blank">%2$s</a>',
					esc_url( 'https://fonts.google.com' ),
					esc_html__( 'Google Fonts website', 'skinny' )
				)
			),
			'section'     => 'skinny_typo_settings',
			'type'        => 'select',
			'choices'     => Fonts::get_google_font_subsets(),
		)
	);
}

/**
 * Register theme button controls within customize.
 *
 * @param \WP_Customize_Manager $wp_customize The customize manager object.
 *
 * @return void
 */
function register_button_controls( \WP_Customize_Manager $wp_customize ) {
	$settings = skinny_get_setting();

	// Get Font weights.
	$font_weights = Fonts::get_google_font_weights();

	// Get Font Text Transform array.
	$font_tt = Fonts::get_font_text_transform();

	// Button text transform.
	$wp_customize->add_setting(
		'font_btn_text_transform',
		array(
			'default'           => $settings['font_btn_text_transform'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'font_btn_text_transform',
		array(
			'label'   => __( 'Text Transform', 'skinny' ),
			'section' => 'skinny_button_settings',
			'type'    => 'select',
			'choices' => $font_tt,
		)
	);

	// Button font weight.
	$wp_customize->add_setting(
		'font_btn_weight',
		array(
			'default'           => $settings['font_btn_weight'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'font_btn_weight',
		array(
			'label'   => __( 'Font Weight', 'skinny' ),
			'section' => 'skinny_button_settings',
			'type'    => 'select',
			'choices' => $font_weights,
		)
	);

	// Button border radius.
	$wp_customize->add_setting(
		'btn_border_radius',
		array(
			'default'           => $settings['btn_border_radius'],
			'transport'         => 'postMessage',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_range',
		)
	);

	$wp_customize->add_control(
		new Range_Slider_Control(
			$wp_customize,
			'btn_border_radius',
			array(
				'label'       => __( 'Border Radius (px)', 'skinny' ),
				'section'     => 'skinny_button_settings',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			)
		)
	);

}

/**
 * Register layout controls within customize.
 *
 * @param \WP_Customize_Manager $wp_customize The customize manager object.
 *
 * @return void
 */
function register_layout_controls( \WP_Customize_Manager $wp_customize ) {

	$settings = skinny_get_setting();

	// Content width controls title.
	$wp_customize->add_setting(
		'content_container_width_title',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'content_container_width_title',
			array(
				'description' => __( 'Content Width', 'skinny' ),
				'type'        => 'heading',
				'section'     => 'skinny_layout_settings',
			)
		)
	);

	// Pages container width.
	$wp_customize->add_setting(
		'pages_container_width',
		array(
			'default'           => $settings['pages_container_width'],
			'transport'         => 'postMessage',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_range',
		)
	);

	$wp_customize->add_control(
		new Range_Slider_Control(
			$wp_customize,
			'pages_container_width',
			array(
				'label'       => __( 'Pages (px)', 'skinny' ),
				'section'     => 'skinny_layout_settings',
				'input_attrs' => array(
					'min'  => 200,
					'max'  => 4000,
					'step' => 1,
				),
			)
		)
	);

	// Posts container width.
	$wp_customize->add_setting(
		'posts_container_width',
		array(
			'default'           => $settings['posts_container_width'],
			'transport'         => 'postMessage',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_range',
		)
	);

	$wp_customize->add_control(
		new Range_Slider_Control(
			$wp_customize,
			'posts_container_width',
			array(
				'label'       => __( 'Posts (px)', 'skinny' ),
				'section'     => 'skinny_layout_settings',
				'input_attrs' => array(
					'min'  => 200,
					'max'  => 1800,
					'step' => 1,
				),
			)
		)
	);

}

/**
 * Register Site Controls within Customize.
 *
 * @param \WP_Customize_Manager $wp_customize The customize manager object.
 *
 * @return void
 */
function register_site_controls( \WP_Customize_Manager $wp_customize ) {

	$settings = skinny_get_setting();

	// Blog Header Background.
	$wp_customize->add_setting(
		'blog_header_background',
		array(
			'default'           => $settings['blog_header_background'],
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new \WP_Customize_Image_Control(
			$wp_customize,
			'blog_header_background',
			array(
				'label'       => esc_html__( 'Blog Header Background', 'skinny' ),
				'description' => esc_html__( ' a background image for blog header.', 'skinny' ),
				'section'     => 'skinny_site_settings',
			)
		)
	);

	if ( skinny_is_woocommerce_activated() ) {
		// Shop Header Background.
		$wp_customize->add_setting(
			'shop_header_background',
			array(
				'default'           => $settings['shop_header_background'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$wp_customize,
				'shop_header_background',
				array(
					'label'       => esc_html__( 'Shop Header Background', 'skinny' ),
					'description' => esc_html__( ' a background image for shop header.', 'skinny' ),
					'section'     => 'skinny_site_settings',
				)
			)
		);
	}

}

/**
 * Register Header Controls within Customize.
 *
 * @param \WP_Customize_Manager $wp_customize The customize manager object.
 *
 * @return void
 */
function register_header_controls( \WP_Customize_Manager $wp_customize ) {

	$settings = skinny_get_setting();

	// Retinafy site logo.
	$wp_customize->add_setting(
		'site_logo_retina',
		array(
			'default'           => $settings['site_logo_retina'],
			'transport'         => 'refresh',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Toggle_Switch_Control(
			$wp_customize,
			'site_logo_retina',
			array(
				'label'    => esc_html__( 'Retinafy Site Logo', 'skinny' ),
				'section'  => 'title_tagline',
				'type'     => 'checkbox',
				'priority' => 8,
			)
		)
	);

	// Header layout variations.
	$wp_customize->add_setting(
		'header_layout_variations',
		array(
			'default'           => $settings['header_layout_variations'],
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'header_layout_variations',
		array(
			'label'    => esc_html__( 'Header Layout', 'skinny' ),
			'section'  => 'skinny_header_settings',
			'type'     => 'radio',
			'choices'  => array(
				'default'  => esc_html_x( 'Default', 'name of the first layout option', 'skinny' ),
				'centered' => esc_html_x( 'Centered', 'name of the second layout option', 'skinny' ),
			),
			'priority' => 1,
		)
	);

	// Enable header icons title.
	$wp_customize->add_setting(
		'enable_header_toggles_title',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'enable_header_toggles_title',
			array(
				'description' => __( 'Header Toggles', 'skinny' ),
				'type'        => 'heading',
				'section'     => 'skinny_header_settings',
			)
		)
	);

	// Search toggle.
	$wp_customize->add_setting(
		'search_btn_toggle',
		array(
			'default'           => $settings['search_btn_toggle'],
			'transport'         => 'postMessage',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Toggle_Switch_Control(
			$wp_customize,
			'search_btn_toggle',
			array(
				'label'   => esc_html__( 'Search', 'skinny' ),
				'section' => 'skinny_header_settings',
				'type'    => 'checkbox',
			)
		)
	);

	if ( skinny_is_woocommerce_activated() ) {
		// Cart widget toggle.
		$wp_customize->add_setting(
			'cart_widget_toggle',
			array(
				'default'           => $settings['cart_widget_toggle'],
				'transport'         => 'postMessage',
				'sanitize_callback' => __NAMESPACE__ . '\sanitize_switch',
			)
		);
		$wp_customize->add_control(
			new Toggle_Switch_Control(
				$wp_customize,
				'cart_widget_toggle',
				array(
					'label'   => esc_html__( 'Cart', 'skinny' ),
					'section' => 'skinny_header_settings',
					'type'    => 'checkbox',
				)
			)
		);

		// Account button.
		$wp_customize->add_setting(
			'account_btn',
			array(
				'default'           => $settings['account_btn'],
				'transport'         => 'postMessage',
				'sanitize_callback' => __NAMESPACE__ . '\sanitize_switch',
			)
		);
		$wp_customize->add_control(
			new Toggle_Switch_Control(
				$wp_customize,
				'account_btn',
				array(
					'label'   => esc_html__( 'Account', 'skinny' ),
					'section' => 'skinny_header_settings',
					'type'    => 'checkbox',
				)
			)
		);
	}

	// Light/Dark mode toggle.
	$wp_customize->add_setting(
		'color_scheme_toggle',
		array(
			'default'           => $settings['color_scheme_toggle'],
			'transport'         => 'postMessage',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Toggle_Switch_Control(
			$wp_customize,
			'color_scheme_toggle',
			array(
				'label'   => esc_html__( 'Light / Dark mode', 'skinny' ),
				'section' => 'skinny_header_settings',
				'type'    => 'checkbox',
			)
		)
	);
}

/**
 * Register Footer Controls within Customize.
 *
 * @param \WP_Customize_Manager $wp_customize The customize manager object.
 *
 * @return void
 */
function register_footer_controls( \WP_Customize_Manager $wp_customize ) {

	$settings = skinny_get_setting();

	// Footer CTA Block.
	$wp_customize->add_setting(
		'footer_cta_block',
		array(
			'default'           => $settings['footer_cta_block'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'footer_cta_block',
		array(
			'label'       => esc_html__( 'CTA Block', 'skinny' ),
			'description' => esc_html__( 'To add a CTA block, save any editor block as "Reusable Block".', 'skinny' ),
			'section'     => 'skinny_footer_settings',
			'type'        => 'select',
			'choices'     => get_reusable_blocks(),
		)
	);

	// CTA Block helper description.
	$wp_customize->add_setting(
		'footer_cta_block_help_description',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'footer_cta_block_help_description',
			array(
				'description' => sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( get_admin_url() . 'edit.php?post_type=wp_block' ),
					__( 'Manage re-usable blocks here', 'skinny' )
				),
				'type'        => 'text',
				'section'     => 'skinny_footer_settings',
			)
		)
	);

	// CTA Block toggles title.
	$wp_customize->add_setting(
		'footer_cta_block_toggles_title',
		array(
			'default'           => false,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Divider_Control(
			$wp_customize,
			'footer_cta_block_toggles_title',
			array(
				'description' => __( 'Display on', 'skinny' ),
				'type'        => 'heading',
				'section'     => 'skinny_footer_settings',
			)
		)
	);

	// CTA Block: Homepage toggle.
	$wp_customize->add_setting(
		'footer_cta_homepage_toggle',
		array(
			'default'           => $settings['footer_cta_homepage_toggle'],
			'transport'         => 'postMessage',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Toggle_Switch_Control(
			$wp_customize,
			'footer_cta_homepage_toggle',
			array(
				'label'   => __( 'Homepage', 'skinny' ),
				'section' => 'skinny_footer_settings',
				'type'    => 'checkbox',
			)
		)
	);

	// CTA Block: Single Posts.
	$wp_customize->add_setting(
		'footer_cta_single_posts_toggle',
		array(
			'default'           => $settings['footer_cta_single_posts_toggle'],
			'transport'         => 'postMessage',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Toggle_Switch_Control(
			$wp_customize,
			'footer_cta_single_posts_toggle',
			array(
				'label'   => __( 'Single Posts', 'skinny' ),
				'section' => 'skinny_footer_settings',
				'type'    => 'checkbox',
			)
		)
	);

	// CTA Block: Blog Archives.
	$wp_customize->add_setting(
		'footer_cta_blog_archives_toggle',
		array(
			'default'           => $settings['footer_cta_blog_archives_toggle'],
			'transport'         => 'postMessage',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Toggle_Switch_Control(
			$wp_customize,
			'footer_cta_blog_archives_toggle',
			array(
				'label'   => __( 'Blog Archives', 'skinny' ),
				'section' => 'skinny_footer_settings',
				'type'    => 'checkbox',
			)
		)
	);


	if ( skinny_is_woocommerce_activated() ) {
		// CTA Block: Single Products.
		$wp_customize->add_setting(
			'footer_cta_single_products_toggle',
			array(
				'default'           => $settings['footer_cta_single_products_toggle'],
				'transport'         => 'postMessage',
				'sanitize_callback' => __NAMESPACE__ . '\sanitize_switch',
			)
		);
		$wp_customize->add_control(
			new Toggle_Switch_Control(
				$wp_customize,
				'footer_cta_single_products_toggle',
				array(
					'label'   => __( 'Single Products', 'skinny' ),
					'section' => 'skinny_footer_settings',
					'type'    => 'checkbox',
				)
			)
		);

		// CTA Block: Shop Archives.
		$wp_customize->add_setting(
			'footer_cta_shop_archives_toggle',
			array(
				'default'           => $settings['footer_cta_shop_archives_toggle'],
				'transport'         => 'postMessage',
				'sanitize_callback' => __NAMESPACE__ . '\sanitize_switch',
			)
		);
		$wp_customize->add_control(
			new Toggle_Switch_Control(
				$wp_customize,
				'footer_cta_shop_archives_toggle',
				array(
					'label'   => __( 'Shop Archives', 'skinny' ),
					'section' => 'skinny_footer_settings',
					'type'    => 'checkbox',
				)
			)
		);
	}

	// Widget columns.
	$wp_customize->add_setting(
		'footer_widget_cols',
		array(
			'default'           => $settings['footer_widget_cols'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'footer_widget_cols',
		array(
			'label'       => esc_html__( 'Widget Columns', 'skinny' ),
			'description' => esc_html__( 'Choose a footer column layout for your site.', 'skinny' ),
			'type'        => 'select',
			'section'     => 'skinny_footer_settings',
			'choices'     => array(
				0 => esc_html_x( 'Hidden', 'name of the first layout option', 'skinny' ),
				1 => esc_html_x( '1 Column', 'name of the second layout option', 'skinny' ),
				2 => esc_html_x( '2 Column', 'name of the third layout option', 'skinny' ),
				3 => esc_html_x( '3 Column', 'name of the fourth layout option', 'skinny' ),
				4 => esc_html_x( '4 Column', 'name of the fifth layout option', 'skinny' ),
			),
		)
	);

	// Footer text.
	$wp_customize->add_setting(
		'footer_text',
		array(
			'default'           => $settings['footer_text'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'footer_text',
		array(
			'label'   => esc_html__( 'Footer text', 'skinny' ),
			'section' => 'skinny_footer_settings',
			'type'    => 'text',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'footer_cta_block',
			array(
				'selector'        => '.site-footer__cta',
				'render_callback' => '\\Skinny\\display_footer_cta',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'footer_cta_homepage_toggle',
			array(
				'selector'        => '.site-footer__cta',
				'render_callback' => '\\Skinny\\display_footer_cta',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'footer_cta_single_posts_toggle',
			array(
				'selector'        => '.site-footer__cta',
				'render_callback' => '\\Skinny\\display_footer_cta',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'footer_cta_blog_archives_toggle',
			array(
				'selector'        => '.site-footer__cta',
				'render_callback' => '\\Skinny\\display_footer_cta',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'footer_cta_single_products_toggle',
			array(
				'selector'        => '.site-footer__cta',
				'render_callback' => '\\Skinny\\display_footer_cta',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'footer_cta_shop_archives_toggle',
			array(
				'selector'        => '.site-footer__cta',
				'render_callback' => '\\Skinny\\display_footer_cta',
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'footer_widget_cols',
			array(
				'selector'        => '.footer-widget-area',
				'render_callback' => '\\Skinny\\footer_widgets',
			)
		);
	}
}
