<?php
require_once( 'login.php' );

//Buy now button
function add_content_after_addtocart() {
	$current_product_id = get_the_ID();
	$product = wc_get_product( $current_product_id );
	$checkout_url = wc_get_checkout_url();

	if ( $product->is_type( 'simple' ) ) {
		echo '<button class="buy-now button"><a href="'.$checkout_url.'?add-to-cart='.$current_product_id.'" class="">Buy Now</a></button>';
	}
}
add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart' );

//Move email field to top of checkout
function bbloomer_move_checkout_email_field( $address_fields ) {
	$address_fields['billing_email']['priority'] = 1;
	return $address_fields;
}
add_filter( 'woocommerce_billing_fields', 'bbloomer_move_checkout_email_field' );


// //user state filter
// add_filter( 'wp_nav_menu_item_custom_fields', 'my_nav_menu_item_custom_fields', 10, 4 );
// function my_nav_menu_item_custom_fields( $fields, $item, $depth, $args ) {
//     $fields .= '<p class="field-visibility description description-thin">
//         <label for="edit-menu-item-visibility-'. $item->ID .'">Visibility:
//             <select id="edit-menu-item-visibility-'. $item->ID .'" name="menu-item-visibility['. $item->ID .']">
//                 <option value="everyone" '. selected( 'everyone', get_post_meta( $item->ID, 'visibility', true ), false ) .'>Everyone</option>
//                 <option value="logged_in" '. selected( 'logged_in', get_post_meta( $item->ID, 'visibility', true ), false ) .'>Logged In Users</option>
//                 <option value="logged_out" '. selected( 'logged_out', get_post_meta( $item->ID, 'visibility', true ), false ) .'>Logged Out Users</option>
//             </select>
//         </label>
//     </p>';
//     return $fields;
// }


// add_action( 'wp_update_nav_menu_item', 'my_update_nav_menu_item', 10, 3 );
// function my_update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {
//     if ( isset( $_REQUEST['menu-item-visibility'][$menu_item_db_id] ) ) {
//         update_post_meta( $menu_item_db_id, 'visibility', $_REQUEST['menu-item-visibility'][$menu_item_db_id] );
//     }
// }


// function my_nav_menu_items_visibility( $items, $menu, $args ) {
//     // Iterate through each menu item
//     foreach ( $items as $key => $item ) {
//         // Get the selected visibility option for the menu item
//         $visibility = get_post_meta( $item->ID, 'visibility', true );

//         // Check if the current user's state matches the selected option
//         if ( $visibility == 'logged_in' && ! is_user_logged_in() ) {
//             unset( $items[$key] );
//         } elseif ( $visibility == 'logged_out' && is_user_logged_in() ) {
//             unset( $items[$key] );
//         }
//     }

//     return $items;
// }
// add_filter( 'wp_get_nav_menu_items', 'my_nav_menu_items_visibility', 10, 3 );
