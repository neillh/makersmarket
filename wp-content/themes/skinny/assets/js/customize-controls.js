"use strict";

/**
 * Scripts within the customizer controls window.
 *
 * Contextually shows the color hue control and informs the preview
 * when users open or close the front page sections section.
 */
(function ($, fontsData) {
  var __ = wp.i18n.__;
  /**
   * Get Body Font Variants to load.
   * @param {string} key.
   */

  function fontBodyVariantsHandler(param) {
    if (param in fontsData.fonts) {
      var fontBodyVars = fontsData.fonts[param];
      return fontVariantsHandler(fontBodyVars);
    }

    return false;
  }
  /**
   * Get Head Font Variants to load.
   * @param {string} key.
   */


  function fontHeadVariantsHandler(param) {
    if (param in fontsData.fonts) {
      var fontHeadVars = fontsData.fonts[param];
      return fontVariantsHandler(fontHeadVars);
    }

    return false;
  }

  function fontVariantsHandler(param) {
    var fontVars = JSON.parse(JSON.stringify(param));
    $.each(fontVars, function (index, value) {
      switch (value) {
        case '100':
          fontVars[index] = [value, __('Thin 100', 'skinny')];
          break;

        case '100italic':
          fontVars[index] = [value, __('100 Italic', 'skinny')];
          break;

        case '200':
          fontVars[index] = [value, __('Extra-Light 200', 'skinny')];
          break;

        case '200italic':
          fontVars[index] = [value, __('200 Italic', 'skinny')];
          break;

        case '300':
          fontVars[index] = [value, __('Light 300', 'skinny')];
          break;

        case '300italic':
          fontVars[index] = [value, __('300 Italic', 'skinny')];
          break;

        case '400':
          fontVars[index] = [value, __('Regular 400', 'skinny')];
          break;

        case '400italic':
          fontVars[index] = [value, __('400 Italic', 'skinny')];
          break;

        case '500':
          fontVars[index] = [value, __('Medium 500', 'skinny')];
          break;

        case '500italic':
          fontVars[index] = [value, __('500 Italic', 'skinny')];
          break;

        case '600':
          fontVars[index] = [value, __('Semi-Bold 600', 'skinny')];
          break;

        case '600italic':
          fontVars[index] = [value, __('600 Italic', 'skinny')];
          break;

        case '700':
          fontVars[index] = [value, __('Bold 700', 'skinny')];
          break;

        case '700italic':
          fontVars[index] = [value, __('700 Italic', 'skinny')];
          break;

        case '800':
          fontVars[index] = [value, __('Extra-Bold 800', 'skinny')];
          break;

        case '800italic':
          fontVars[index] = [value, __('800 Italic', 'skinny')];
          break;

        case '900':
          fontVars[index] = [value, __('Ultra-Bold 900', 'skinny')];
          break;

        case '900italic':
          fontVars[index] = [value, __('900 Italic', 'skinny')];
          break;

        case 'italic':
          fontVars[index] = [value, __('400 Italic', 'skinny')];
          break;

        case 'regular':
          fontVars[index] = [value, __('Regular 400', 'skinny')];
          break;

        default:
          fontVars[index] = [value, __(value, 'skinny')];
          break;
      }
    });
    return fontVars;
  }
  /**
   * Switch color schemes and controls.
   *
   * @param mode Selected color scheme.
   */


  function switch_color_scheme_controls(mode) {
    var schemeControls = ['body_bg_color', 'text_color', 'accent_color', 'custom_header_bg_color', 'wrap_btn_colors', 'wrap_btn_hover_colors'];

    if ('dark' === mode) {
      schemeControls.forEach(function (item) {
        $("#customize-control-light_scheme_".concat(item)).hide();
        $("#customize-control-dark_scheme_".concat(item)).show();
      });
    } else {
      schemeControls.forEach(function (item) {
        $("#customize-control-light_scheme_".concat(item)).show();
        $("#customize-control-dark_scheme_".concat(item)).hide();
      });
    }

    $('[id*="customize-control-light_scheme_btn_"], [id*="customize-control-dark_scheme_btn_"]').hide();
  }
  /**
   * Display CTA block toggles conditionally.
   *
   * @param key Selected block id.
   */


  function display_cta_toggles(key) {
    var ctaToggles = ['block_toggles_title', 'homepage_toggle', 'single_posts_toggle', 'blog_archives_toggle', 'single_products_toggle', 'shop_archives_toggle'];

    if ('select' !== key) {
      ctaToggles.forEach(function (item) {
        $("#customize-control-footer_cta_".concat(item)).show();
      });
    } else {
      ctaToggles.forEach(function (item) {
        $("#customize-control-footer_cta_".concat(item)).hide();
      });
    }
  }

  wp.customize.bind('ready', function () {
    var customize = this; // Color Scheme.

    customize('site_color_scheme', function (value) {
      switch_color_scheme_controls(value._value);
    });
    $('#site_color_scheme-dark, #site_color_scheme-light').on('change', function () {
      customize('site_color_scheme', function (value) {
        switch_color_scheme_controls(value._value);
      });
    });
    $('#_customize-input-font_body').on('change', function () {
      customize('font_body', function (value) {
        if (false !== fontBodyVariantsHandler) {
          window.skinnyCurrentBodyFontVars = fontBodyVariantsHandler(value._value);
        } else {
          window.skinnyCurrentBodyFontVars = false;
        }
      });
      customize('font_body_load_variant', function () {
        var variants = window.skinnyCurrentBodyFontVars;
        var $ctrlEl = $('#customize-control-font_body_load_variant');

        if (false !== variants) {
          $ctrlEl.show();
          var $el = $('#customize-control-font_body_load_variant .customize-control-select2');
          $el.empty(); // remove old options

          $.each(variants, function (key, value) {
            $el.append($('<option></option>').attr({
              value: value[0]
            }).text(value[1]));
          });
        } else {
          $ctrlEl.hide();
        }
      });
    });
    $('#_customize-input-font_headings').on('change', function () {
      customize('font_headings', function (value) {
        if (false !== fontHeadVariantsHandler) {
          window.skinnyCurrentHeadingFontVars = fontHeadVariantsHandler(value._value);
        } else {
          window.skinnyCurrentHeadingFontVars = false;
        }
      });
      customize('font_headings_load_variant', function () {
        var variants = window.skinnyCurrentHeadingFontVars;
        var $ctrlEl = $('#customize-control-font_headings_load_variant');

        if (false !== variants) {
          $ctrlEl.show();
          var $el = $('#customize-control-font_headings_load_variant .customize-control-select2');
          $el.empty(); // remove old options

          $.each(variants, function (key, value) {
            $el.append($('<option></option>').attr({
              value: value[0]
            }).text(value[1]));
          });
        } else {
          $ctrlEl.hide();
        }
      });
    });
    /**
     * Font Variant Control onLoad Visibility.
     */

    customize('font_headers', function (value) {
      var $ctrlEl = $('#customize-control-font_headings_load_variant');

      if ('inherit' === value._value) {
        $ctrlEl.hide();
      } else {
        $ctrlEl.show();
      }
    });
    customize('font_body', function (value) {
      var $ctrlEl = $('#customize-control-font_body_load_variant');

      if ('inherit' === value._value) {
        $ctrlEl.hide();
      } else {
        $ctrlEl.show();
      }
    }); // Font Headers Hook.

    $('#_customize-input-font_headings').select2({
      width: '100%'
    }); // Font Body Hook.

    $('#_customize-input-font_body').select2({
      width: '100%'
    }); // Font Subset Hook.

    $('#_customize-input-font_subset').select2({
      width: '100%'
    }); // Conditionally display CTA Block toggles.

    customize('footer_cta_block', function (value) {
      display_cta_toggles(value._value);
    });
    $('#_customize-input-footer_cta_block').on('change', function () {
      customize('footer_cta_block', function (value) {
        display_cta_toggles(value._value);
      });
    });
  });
})(jQuery, fontsData);