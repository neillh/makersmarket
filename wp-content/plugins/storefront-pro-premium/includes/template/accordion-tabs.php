<?php
/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$accordions = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $accordions ) ) : ?>

	<div class="woocommerce-accordions wc-accordions-wrapper">
		<ul class="accordions wc-accordions">
			<?php foreach ( $accordions as $key => $accordion ) : ?>
				<li class="wc-accordion <?php echo esc_attr( $key ); ?>_accordion">
					<a href="#<?php echo esc_attr( $key ); ?>"><span class="fas fa-angle-down"></span> <?php echo apply_filters( 'woocommerce_product_' . $key . '_accordion_title', esc_html( $accordion['title'] ), $key ); ?></a>
					<div class="wc-accordion-panel wc-accordion-panel-<?php echo esc_attr( $key ); ?> entry-content"
					     id="<?php echo esc_attr( $key ); ?>">
						<?php
						if ( isset( $accordion['callback'] ) && is_callable( $accordion['callback'] ) ) {
							call_user_func( $accordion['callback'], $key, $accordion );
						} ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<style>
			.wc-accordion,
			.wc-accordions {
				margin: 0;
				display: block;
				clear: both;
			}
			.wc-accordions {
				border-bottom: 1px solid rgba(0,0,0,0.25);
				margin-bottom: 70px;
			}
			.wc-accordion > a,
			.wc-accordion-panel {
				border-top: 1px solid rgba(0,0,0,0.25);
				padding: 0.7em 1em;
			}
			.wc-accordion > a,
			.wc-accordion > a:focus {
				display: block;
				line-height: 25px;
				color: rgba(0,0,0,0.34);
				outline: none;
				text-align: center;
			}
			.wc-accordion > a > .fa {
				font: 25px/25px fontAwesome;
				vertical-align: middle;
				opacity: 0.5;
				margin-right: 0.5em;
				float: left;
			}
			.wc-accordion-active .fa:before {
				content: "\f106";
			}
		</style>
	</div>
	<script>
		( function ( $ ) {
			var $lis = $( 'li.wc-accordion' );
			$lis.children( '.wc-accordion-panel' ).hide();
			$lis.children( 'a' ).click( function ( e ) {
				e.preventDefault();
				var $t = $( this );

				$lis.children( '.wc-accordion-panel' ).slideUp();

				if ( $t.hasClass( 'wc-accordion-active' ) ) {
					return $t.removeClass( 'wc-accordion-active' );
				}

				$lis.children( 'a.wc-accordion-active' ).removeClass( 'wc-accordion-active' );

				$t.addClass( 'wc-accordion-active' );
				$t.siblings( '.wc-accordion-panel' ).slideDown();
			} );

			// Reviews link in product info
			$( '.woocommerce-review-link' ).click( function ( e ) {
				e.preventDefault();
				var id = $( this ).attr( 'href' ),
					$a = $lis.children( 'a[href="' + id + '"]' );
				$a.click();
				$( 'html, body' ).animate( {
					scrollTop: $a.offset().top - 124
				}, 700 );

			} );

		} )( jQuery );
	</script>
<?php endif;
