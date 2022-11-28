"use strict";

/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function ($) {
  $(document).ready(function () {
    var mobileMenuBtn, mobileNavigation;
    mobileMenuBtn = document.querySelector('#mobile__menu-toggle');
    mobileNavigation = $('#mobile-menu'); // Open mobile menu.

    mobileMenuBtn.onclick = function (e) {
      e.preventDefault();
      $(this).toggleClass('open');
      $('.header__mobile-navigation').slideToggle();
    }; // Toggle class for sub-menu lists.


    mobileNavigation.on('click', 'li', function () {
      $(this).toggleClass('toggled');
    });
  });
})(jQuery);
"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/**
 * File scheme-switcher.js.
 *
 * Handles toggling the color scheme & saving them as cookies.
 */
(function ($, SC) {
  /**
   * Set color scheme.
   *
   * @param sname
   * @param svalue
   * @param expiryDays
   */
  function setScheme(sname, svalue, expiryDays) {
    var d = new Date();
    d.setTime(d.getTime() + expiryDays * 24 * 60 * 60 * 1000);
    var object = {
      value: svalue,
      timestamp: d.toUTCString()
    };
    sessionStorage.setItem(sname, JSON.stringify(object));
  }
  /**
   * Get color scheme.
   *
   * @param sname
   * @returns {string}
   */


  function getScheme(sname) {
    if ("object" === _typeof(sessionStorage.getItem(sname)) || null === sessionStorage.getItem(sname)) {
      return '';
    }

    var object = JSON.parse(sessionStorage.getItem(sname));
    var expiry = new Date(object.timestamp).getTime().toString();
    var now = new Date().getTime().toString();

    if (now >= expiry) {
      sessionStorage.removeItem(sname);
      return '';
    }

    return object.value;
  }

  function switchColorScheme(toScheme, el) {
    if ('' === toScheme || 'undefined' === typeof toScheme) {
      return;
    }

    if ('dark' === toScheme) {
      $('body').removeClass('light-color-scheme');
      $('body').removeClass('is-light-theme');
    } else {
      $('body').removeClass('dark-color-scheme');
      $('body').removeClass('is-dark-theme');
    }

    $('body').addClass("".concat(toScheme, "-color-scheme"));
    $('body').addClass("is-".concat(toScheme, "-theme"));
    el.attr('data-color-scheme', toScheme);
    setScheme('skinnyColorScheme', toScheme, 7);
  }

  $(document).ready(function () {
    var colorSchemeButton, currentColorScheme;
    /* Color Scheme Toggle */

    colorSchemeButton = $('.header__color-scheme-toggle');

    if (SC.isCustomizePreview) {
      return;
    }

    switchColorScheme(getScheme('skinnyColorScheme'), colorSchemeButton);
    colorSchemeButton.click(function () {
      currentColorScheme = this.getAttribute('data-color-scheme');

      if ('dark' === currentColorScheme) {
        switchColorScheme('light', colorSchemeButton);
      } else {
        switchColorScheme('dark', colorSchemeButton);
      }
    });
  });
})(jQuery, skinnyCustom);
"use strict";

/**
 * File search-toggle.js.
 *
 * Handles toggling of the Search Popover.
 */
(function ($) {
  $(document).ready(function () {
    var searchButton, searchForm, searchAreaClose, searchField;
    /* Search toggle */

    searchButton = document.querySelector('#header__search-toggle');
    searchForm = document.querySelector('.search__modal');
    searchAreaClose = document.querySelector('#search__close-btn'); // Return early if no search.

    if ('undefined' === typeof searchForm || null === searchForm) {
      return;
    }

    searchField = searchForm.getElementsByClassName('search-field')[0];

    searchButton.onclick = function () {
      if (-1 !== searchForm.className.indexOf('toggled')) {
        searchForm.className = searchForm.className.replace(' toggled', '');
        searchForm.setAttribute('aria-expanded', 'false');
      } else {
        searchForm.className += ' toggled';
        searchForm.setAttribute('aria-expanded', 'true');
        searchField.focus();
      }
    };

    searchAreaClose.onclick = function () {
      if (-1 !== searchForm.className.indexOf('toggled')) {
        searchForm.className = searchForm.className.replace(' toggled', '');
        searchForm.setAttribute('aria-expanded', 'false');
      }
    };
  });
})(jQuery);