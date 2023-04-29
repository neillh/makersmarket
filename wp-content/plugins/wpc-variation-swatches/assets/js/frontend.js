'use strict';

window.wpcvs = {};

(function(wpcvs, $) {
  wpcvs = wpcvs || {};

  $.extend(wpcvs, {
    Swatches: {
      init: function() {
        var $term = $('.wpcvs-term'),
            $active_term = $('.wpcvs-term:not(.wpcvs-disabled)');

        // load default value
        $term.each(function() {
          var $this = $(this),
              term = $this.attr('data-term'),
              attr = $this.closest('.wpcvs-terms').attr('data-attribute'),
              $select = $this.closest('.wpcvs-terms').
                  parent().find('select#' + attr);

          if (!$select.length) {
            $select = $this.closest('.wpcvs-terms').parent().
                find('select[data-attribute_name="attribute_' + attr + '"]');
          }

          if (!$select.length) {
            $select = $this.closest('.wpcvs-terms').parent().
                find('select[name="attribute_' + attr + '"]');
          }

          if ($select.length) {
            if ($select.val() !== '' && term === $select.val()) {
              $(this).addClass('wpcvs-selected').find('input[type="radio"]').
                  prop('checked', true);
            }
          }
        });

        $active_term.unbind('click touch').on('click touch', function(e) {
          if ($(this).hasClass('wpcvs-disabled')) {
            return false;
          }

          var $this = $(this),
              term = $this.attr('data-term'),
              title = $this.attr('title'),
              attr = $this.closest('.wpcvs-terms').attr('data-attribute'),
              $select = $this.closest('.wpcvs-terms').parent().
                  find('select#' + attr);

          if (!$select.length) {
            $select = $this.closest('.wpcvs-terms').parent().
                find('select[data-attribute_name="attribute_' + attr + '"]');
          }

          if (!$select.length) {
            $select = $this.closest('.wpcvs-terms').parent().
                find('select[name="attribute_' + attr + '"]');
          }

          if (!$select.length) {
            return false;
          }

          if (!$this.hasClass('wpcvs-selected')) {
            $select.val(term).trigger('change');

            $this.closest('.wpcvs-terms').
                find('.wpcvs-selected').
                removeClass('wpcvs-selected').
                find('input[type="radio"]').
                prop('checked', false);

            $this.addClass('wpcvs-selected').
                find('input[type="radio"]').
                prop('checked', true);

            $(document).trigger('wpcvs_selected', [attr, term, title]);
          } else {
            if (wpcvs_vars.second_click === 'yes') {
              // second click
              $select.val('').trigger('change');

              $this.closest('.wpcvs-terms').
                  find('.wpcvs-selected').
                  removeClass('wpcvs-selected').
                  find('input[type="radio"]').
                  prop('checked', false);

              $(document).trigger('wpcvs_reset', [attr, term, title]);
            }
          }

          e.preventDefault();
        });

        $(document).trigger('wpcvs_init');
      },
    },
  });
}).apply(this, [window.wpcvs, jQuery]);

(function(wpcvs, $) {
  $(document).on('wc_variation_form', function() {
    if (typeof wpcvs.Swatches !== 'undefined') {
      wpcvs.Swatches.init();
    }
  });

  $(document).on('woocommerce_update_variation_values',
      function(e) {
        $(e['target']).find('select').each(function() {
          var $this = $(this);
          var $terms = $this.parent().parent().find('.wpcvs-terms');

          $terms.find('.wpcvs-term').
              removeClass('wpcvs-enabled').
              addClass('wpcvs-disabled');

          $this.find('option.enabled').each(function() {
            var val = $(this).val();

            $terms.find(
                '.wpcvs-term[data-term="' + val + '"]').
                removeClass('wpcvs-disabled').
                addClass('wpcvs-enabled');
          });
        });
      });

  $(document).on('found_variation', function(e, t) {
    if ($(e['target']).closest('.wpcvs_archive').length &&
        $(e['target']).closest(wpcvs_vars.archive_product).length) {
      var $product = $(e['target']).closest(wpcvs_vars.archive_product);
      var $atc = $product.find(wpcvs_vars.archive_atc);
      var $atc_text = $product.find(wpcvs_vars.archive_atc_text);
      var $image = $product.find(wpcvs_vars.archive_image);
      var $price = $product.find('.price');

      if ($atc.length) {
        $atc.addClass('wpcvs_add_to_cart').
            attr('data-variation_id', t['variation_id']).
            attr('data-product_sku', t['sku']);

        if (!t['is_purchasable'] || !t['is_in_stock']) {
          $atc.addClass('disabled wc-variation-is-unavailable');
        } else {
          $atc.removeClass('disabled wc-variation-is-unavailable');
        }

        $atc.removeClass('added error loading');
      }

      $product.find('a.added_to_cart').remove();

      if ($atc_text.length) {
        // add to cart button text
        $atc_text.html(wpcvs_vars.add_to_cart);
      }

      if ($image.length) {
        // product image
        if ($image.attr('data-src') == undefined) {
          $image.attr('data-src', $image.attr('src'));
        }

        if ($image.attr('data-srcset') == undefined) {
          $image.attr('data-srcset', $image.attr('srcset'));
        }

        if ($image.attr('data-sizes') == undefined) {
          $image.attr('data-sizes', $image.attr('sizes'));
        }

        if (t['image']['wpcvs_src'] != undefined &&
            t['image']['wpcvs_src'] != '') {
          $image.attr('src', t['image']['wpcvs_src']);
        }

        if (t['image']['wpcvs_srcset'] != undefined &&
            t['image']['wpcvs_srcset'] != '') {
          $image.attr('srcset', t['image']['wpcvs_srcset']);
        } else {
          $image.attr('srcset', '');
        }

        if (t['image']['wpcvs_sizes'] != undefined &&
            t['image']['wpcvs_sizes'] != '') {
          $image.attr('sizes', t['image']['wpcvs_sizes']);
        } else {
          $image.attr('sizes', '');
        }
      }

      if ($price.length) {
        // product price
        if ($price.attr('data-price') == undefined) {
          $price.attr('data-price', $price.html());
        }

        if (t['price_html']) {
          $price.html(t['price_html']);
        }
      }

      $(document).trigger('wpcvs_archive_found_variation', [t]);
    }
  });

  $(document).on('reset_data', function(e) {
    var $this = $(e['target']);

    $this.find('.wpcvs-selected').
        removeClass('wpcvs-selected').
        find('input[type="radio"]').
        prop('checked', false);

    $this.find('select').each(function() {
      var attr = $(this).attr('id');
      var title = $(this).find('option:selected').text();
      var term = $(this).val();

      if (term != '') {
        $(this).parent().parent().
            find('.wpcvs-term[data-term="' + term + '"]').
            addClass('wpcvs-selected').find('input[type="radio"]').
            prop('checked', true);

        $(document).trigger('wpcvs_reset', [attr, term, title]);
      }
    });

    $(document).trigger('wpcvs_reset_data', [$this]);

    // archive
    if ($this.closest('.wpcvs_archive').length &&
        $this.closest(wpcvs_vars.archive_product).length) {
      var $product = $this.closest(wpcvs_vars.archive_product);
      var $atc = $product.find(wpcvs_vars.archive_atc);
      var $atc_text = $product.find(wpcvs_vars.archive_atc_text);
      var $image = $product.find(wpcvs_vars.archive_image);
      var $price = $product.find('.price');

      if ($atc.length) {
        $atc.removeClass(
            'wpcvs_add_to_cart disabled wc-variation-is-unavailable').
            attr('data-variation_id', '0').
            attr('data-product_sku', '');

        $atc.removeClass('added error loading');
      }

      $product.find('a.added_to_cart').remove();

      if ($atc_text.length) {
        // add to cart button text
        $atc_text.html(wpcvs_vars.select_options);
      }

      if ($image.length) {
        // product image
        $image.attr('src', $image.attr('data-src'));
        $image.attr('srcset', $image.attr('data-srcset'));
        $image.attr('sizes', $image.attr('data-sizes'));
      }

      if ($price.length) {
        // product price
        $price.html($price.attr('data-price'));
      }

      $(document).trigger('wpcvs_archive_reset_data');
    }
  });

  $(document).on('click touch', '.wpcvs_add_to_cart', function(e) {
    e.preventDefault();
    var $btn = $(this);
    var $product = $btn.closest(wpcvs_vars.archive_product);
    var attributes = {};

    $btn.removeClass('added error').addClass('loading');

    if ($product.length) {
      $product.find('a.added_to_cart').remove();

      $product.find('[name^="attribute"]').each(function() {
        attributes[$(this).attr('data-attribute_name')] = $(this).val();
      });

      var data = {
        action: 'wpcvs_add_to_cart',
        nonce: wpcvs_vars.nonce,
        product_id: $btn.attr('data-product_id'),
        variation_id: $btn.attr('data-variation_id'),
        quantity: $btn.attr('data-quantity'),
        attributes: JSON.stringify(attributes),
      };

      $.post(wpcvs_vars.ajax_url, data, function(response) {
        if (response) {
          if (response.error && response.product_url) {
            window.location = response.product_url;
            return;
          }

          if ((typeof wc_add_to_cart_params !== 'undefined') &&
              (wc_add_to_cart_params.cart_redirect_after_add === 'yes')) {
            window.location = wc_add_to_cart_params.cart_url;
            return;
          }

          $btn.removeClass('loading').
              addClass('added').
              after(wpcvs_vars.view_cart);
          $(document.body).
              trigger('added_to_cart',
                  [response.fragments, response.cart_hash, $btn]).
              trigger('wc_fragment_refresh');
        } else {
          $btn.removeClass('loading').addClass('error');
        }
      });

      $(document).trigger('wpcvs_add_to_cart', [$btn, $product, attributes]);
    }
  });
}).apply(this, [window.wpcvs, jQuery]);