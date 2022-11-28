"use strict";

/**
 * File customizer-preview.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
(function ($) {
  /**
   * Layout.
   */
  var customize = wp.customize;
  /**
   * Toggle dom elements.
   *
   * @param display Boolean for conditional toggle.
   * @param elements Element css keys to target.
   */

  function toggle_elements(display, elements) {
    if (!display) {
      $(elements).hide();
    } else {
      $(elements).show();
    }
  } // Enable search button.


  customize('search_btn_toggle', function (value) {
    toggle_elements(value._value, '#header__search-toggle, #header__mobile-navigation .search-form');
    value.bind(function (to) {
      toggle_elements(to, '#header__search-toggle, #header__mobile-navigation .search-form');
    });
  }); // Enable cart toggle.

  customize('cart_widget_toggle', function (value) {
    toggle_elements(value._value, '#header__cart, .header__mobile-navigation .woo-mobile-cart');
    value.bind(function (to) {
      toggle_elements(to, '#header__cart, .header__mobile-navigation .woo-mobile-cart');
    });
  }); // Enable account button.

  customize('account_btn', function (value) {
    toggle_elements(value._value, '#header__account-link, .header__mobile-navigation .quick-links .account-link');
    value.bind(function (to) {
      toggle_elements(to, '#header__account-link, .header__mobile-navigation .quick-links .account-link');
    });
  }); // Enable light/dark mode toggle.

  customize('color_scheme_toggle', function (value) {
    toggle_elements(value._value, '.header__color-scheme-toggle');
    value.bind(function (to) {
      toggle_elements(to, '.header__color-scheme-toggle');
    });
  }); // Footer text.

  customize('footer_text', function (value) {
    value.bind(function (to) {
      if (to) {
        $('.footer-text').text(to);
      }
    });
  });
  /* Colors */
  // Site color scheme.

  customize('site_color_scheme', function (value) {
    value.bind(function (to) {
      if (to) {
        if ('dark' === to) {
          $('body').removeClass('light-color-scheme');
          $('body').removeClass('is-light-theme');
        } else {
          $('body').removeClass('dark-color-scheme');
          $('body').removeClass('is-dark-theme');
        }

        $('body').addClass("".concat(to, "-color-scheme"));
        $('body').addClass("is-".concat(to, "-theme"));
      }
    });
  });
  /* Buttons */
  // Text transform.

  customize('font_btn_text_transform', function (value) {
    value.bind(function (to) {
      if (to) {
        $('body').get(0).style.setProperty('--skinny--btn-text-transform', to);
      }
    });
  }); // Font weight.

  customize('font_btn_weight', function (value) {
    value.bind(function (to) {
      if (to) {
        $('body').get(0).style.setProperty('--skinny--btn-font-weight', to);
      }
    });
  }); // Border radius.

  customize('btn_border_radius', function (value) {
    value.bind(function (to) {
      if (to) {
        $('body').get(0).style.setProperty('--skinny--btn-border-radius', "".concat(to, "px"));
      }
    });
  });
  /* Layout */

  customize('pages_container_width', function (value) {
    value.bind(function (to) {
      if (to && $('body.page').length) {
        $('body.page').get(0).style.setProperty('--skinny--max-w-singular', "".concat(to, "px"));
        $('body.page').get(0).style.setProperty('--skinny--max-w-singular-content', "".concat(to, "px"));
      }
    });
  });
  customize('posts_container_width', function (value) {
    value.bind(function (to) {
      if (to && $('body.single-post').length) {
        $('body.single-post').get(0).style.setProperty('--skinny--max-w-singular', "".concat(to, "px"));
        $('body.single-post').get(0).style.setProperty('--skinny--max-w-singular-content', "".concat(to, "px"));
      }
    });
  });
})(jQuery);