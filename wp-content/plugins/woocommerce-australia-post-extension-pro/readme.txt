=== Australia Post WooCommerce Extension ===
Contributors: waseem_senjer
Donate link: https://wpruby.com
Tags: woocommerce,shipping, woocommerce extension, australia, australia post
Requires at least: 4.0
Requires PHP: 7.0
Tested up to: 6.1.0
Stable tag: 4.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Australia Post WooCommerce Extension is a Wordpress Plugin that integrate the Australia Post service, it will calculate the shipping cost and the delivery time for your customer.


== Description ==
Australia Post WooCommerce Extension is a Wordpress Plugin that integrate the Australia Post service, it will calculate the shipping cost and the delivery time for your customer.


== Installation ==


= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Australia Post WooCommerce Extension'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `woocommerce-australian-post.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `woocommerce-australian-post.zip`
2. Extract the `woocommerce-australian-post` directory to your computer
3. Upload the `woocommerce-australian-post` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Frequently Asked Questions ==

= What is API key? =
This is a key you must get it from Australia Post, so you can use their API, Get your key from https://developers.auspost.com.au/apis/pacpcs-registration

== Changelog ==
= 4.9.2 (11.11.2022) =
Fixed: limit packing volume to 0.25 m3

= 4.9.1 (10.11.2022) =
Fixed: improve the packing of the box iterations.

= 4.9.0 (09.11.2022) =
Improved: Boxpacker algorithm should be more accurate by adding box iterations.
Added: [Labels Pro] display tracked letters options when contract rates are enabled.
Added: [Labels Pro] compatibility with customer order numbers plugins.
UI: [Labels Pro] The shipments table in the Order Summary page was refactored.
Fixed: [Labels Pro] Some MyPost products were displaying the ID on the Cart page instead of the service label.
Fixed: [Labels Pro] Empty pending orders were created when trying to download lables.
Fixed: Grouping international express options instead of displaying different options for different packages.
Fixed: Taking into consideration cubic weight of the package.

= 4.8.0 (25.09.2022) =
Added: [Labels Pro] Adding a phone number field as it is required for international labels.
Added: [Labels Pro] Adding an option to calcaulte contract prices with surcharges.

= 4.7.1 (23.09.2022) =
Fixed: PHP warning when filtering tracked letters.

= 4.7.0 (21.09.2022) =
* Added: Domestic Tracked Letters.
* Added: Manual tracking numbers for lables created off the plugin.

= 4.6.1 (11.09.2022) =
* Added: MyPost Business offer.
* Fixed: [Labels Pro] Clearance items section was not displayed correctly.

= 4.6.0 (11.08.2022) =
* Added: [Labels Pro] Tracking numbers column in the Orders table.
* Fixed: Fatal error when checking variant product individual shipping flag

= 4.5.2 (14.07.2022) =
* Fixed: Shipping products individually for variant products were not working.
* Fixed: Increase max package weight to 22kg.
* Fixed: [Labels Pro] Performance issue in the admin dashboard.

= 4.5.1 (15.06.2022) =
* Fixed: non_delivery_option only for international packages.

= 4.5.0 (13.06.2022) =
* Added: Box names on the order page.
* Added: Auto-populate clearance information for international packages.
* Added: set a default value for non_delivery_option to enable the usage of MyPost UI.
* Fixed: add a return to meta box render to prevent content overflow.
* Fixed: Limiting address name to 40 characters.


= 4.4.3 (20.04.2022) =
* Added: WooCommerce 6.4 compatibility.
* Fixed: Labels requests were not deleted when the shipment is deleted.

= 4.4.2 (14.04.2022) =
* Fixed: Labels were not generated if company name was empty.

= 4.4.1 (13.04.2022) =
* Added: Company name to the shipping label.
* Added: Hook to change the label left offset `labels_pro_label_left_offset`
* Added: Hook to change the label top offset `labels_pro_label_top_offset`
* Fixed: SoD is required if the amount is more than $500.
* Fixed: Max weight for eParcel is 32kg.
* Fixed: Strip Tax option was not working for Labels Pro version.
* Removed: [Labels Pro] Manual Tracking Number box as it is not needed for the Labels Pro plugin.



= 4.4.0 (16.03.2022) =
* Added: The ability to ship certain products individually.
* Added: An API logger for the Debug Mode.
* Added: Split packages by postcode in case of dropshipping.
* Added: [Labels Pro] Adding the weight and dimensions of the order's items as defaults in the Labels Printer.
* Added: [Labels Pro] Adding the total value of the order in the Label Printer.
* Added: [Labels Pro] A WordPress hook when the shipment is created `labels_pro_shipment_for_order_created`.
* Added: [Labels Pro] A WordPress hook to change customer reference for the label `labels_pro_shipment_customer_reference`.
* Added: [Labels Pro] Compatibility with sequential order numbers plugins.
* Added: [Labels Pro] Printing labels as 4 labels per page by default.
* Improved: Prepaid satchels calculations.
* Improved: [Labels Pro] Better wording for actions buttons in the Label and Orders printer.
* Fixed: [Labels Pro] Adding Signature on Delivery to shipping cost was missing.
* Fixed: [Labels Pro] Taking into consideration the cubic weight of the shipment.
* Fixed: [Labels Pro] A fatal error when deleting a shipment.
* Fixed: [Labels Pro] A fatal error in the get_shipments endpoint.
* Fixed: Deprecated cart fee method was causing a fatal error.



= 4.3.0 (16.01.2022) =
* Added: [Labels Pro] Support for MyPost Business charge accounts as a payment method.


= 4.2.0 (07.01.2022) =
* Fixed: [Labels Pro] Plugin updater was not working as expected.

= 4.1.2 (05.01.2022) =
* Fixed: [Labels Pro] MyPost Business hide the Express Doc removed.

= 4.1.1 (02.01.2022) =
* Fixed: [Labels Pro] MyPost Business international services were not filtered according to the enabled options.

= 4.1.0 (29.12.2021) =
* Added: [Labels Pro] Added the ability to request Pickup Order for MyPost accounts.
* Added: [Labels Pro] Added the option to mark products as containing hazardous goods to be declared to Australia Post.
* Added: [Labels Pro] MyPost prepaid boxes.
* Added: [Labels Pro] WooCommerce Order Id column to the Past Orders table.
* Fixed: [Labels Pro] Calculating prices at the Label Printer was not working if the Extra Cover value is more than $500.
* Fixed: [Labels Pro] The editing From address in the label printer was not working correctly when WordPress is installed in a subdirectory.

= 4.0.2 (03.12.2021) =
* Fixed: Adding the Extra Cover amount when creating the shipments.

= 4.0.1 (30.11.2021) =
* Fixed: setting the default account type to eParcel.

= 4.0.0 (29.11.2021) =
* Added: [Labels Pro] MyPost Business support.
* Added: [Labels Pro] Scheduled Australia Post outage notification.
* Added: [Labels Pro] The ability to add Signature on Delivery and Extra Cover when creating labels.
* Added: [Labels Pro] You have now the ability to delete created shipments.
* Improved: [Labels Pro] The creating labels UI enhanced.
* Improved: [Labels Pro] Error messages.
* Fixed: disable prepaid satchels calculations if the weight of the package is more than 5kg.
* Fixed: Letters and envelopes calculations.
* Fixed: Weight only shipping not returning letters prices.

= 3.3.0 (24.08.2021) =
* Added: [Labels Pro] Adding delivery instructions to the generated label.
* Added: [Labels Pro] Adding phone number to the generated label.
* Fixed: [Labels Pro] Extra Cover seperation for Contract Rates.
* Fixed: sort dimensions before boxpacker calculations.
* Fixed: Fatal error when seperate fees option is enabled.

= 3.2.1 (24.04.2021) =
Fixed: When only two pre-paid satchel enabled, the packing calculations were not accurate.

= 3.2.0 (24.04.2021) =
* Fixed: When only two pre-paid satchel enabled, the packing calculations were not accurate.

= 3.2.0 (12.04.2021) =
* Added: [Labels Pro] Adding tracking numbers to the order emails.
* Fixed: BoxPacker update to fix calcaulations with zero height.
* Fixed: Tracking Button not appearing in the list of orders.

= 3.1.4 (12.02.2021) =
* Fixed: PHP warnings at checkout.
* Fixed: The plugin updater was not working.

= 3.1.3 (16.01.2021) =
* Fixed: Seperate Fees were not calculated based on session.

= 3.1.2 (08.01.2021) =
* Fixed: Update boxpacker to take into account weight_only_shipping
* Fixed: Regular satchel double price.
* Fixed: Consider letters only when there are options enabled.
* Fixed: Some domestic services are not included.
* Fixed: Separate fees depending on the session.
* Fixed: Change envelops size as it was not calculating express.
* Fixed: Default dimensions to 1cm in case of letters shipping.
* Fixed: [Labels Pro] set the first postage product as the default one.

= 3.1.1 (28.11.2020) =
 * Fixed: WC_Order in separated fees were not imported correctly.
 * Fixed: re-enable add parcel if only regular large satchel enabled.

= 3.1.0 (22.11.2020) =
 * Added: International Express supports Signature on Delivery now.
 * Added: [Labels Pro] The ability to create Australia Post shipping orders.
 * Added: [Labels Pro] The ability to download the order summary document.
 * Added: [Labels Pro] The ability to choose the Label layout.
 * Added: [Labels Pro] The ability to enable/disable certain contract postage product.
 * Added: [Labels Pro] The ability to change the name of the contract postage product.
 * Added: [Labels Pro] The ability to verify your Australia Post credentials.
 * Added: Knowledgebase links for documentation articles.
 * Improved: Letters are using the BoxPacker algorithm now.
 * Fixed: [Labels Pro] Support of international shipping labels with clearance information.
 * Fixed: fix separated fees were working even after disabling the option.
 * Fixed: Consider satchels only if all items in cart fit satchels.
 * Fixed: Domestic satchels were included in the international calculations.
 * Fixed: Regular option was not showing at all if only a large satchel is enabled and the item in cart fit small satchel.
 * Fixed: Side widgets layout changed after WordPress 5.5
 * Removed: Changelog alerts on activation.

= 2.6.2 (28.04.2020) =
 * FIXED: Exclude satchels calculations in international shipping.
 * FIXED: Consider satchels only if all items in cart fit satchels.
 * FIXED: Add international express to Signature on Delivery options.

 = 2.6.1 (18.03.2020) =
 * FIXED: The boxpacker should not be used when the cart has only letters.

 = 2.6.0 (14.03.2020) =
 * ADDED: Now, you can customize the text of the extra services (Extra Cover & Signature on Delivery) at the Checkout page.
 * ADDED: International (Economy Air Medium Light Letter) was added.
 * ADDED: `australia_post_extra_cover_label` WordPress filter to adjust the Extra Cover Label option.
 * ADDED: `australia_post_signature_on_delivery_label` WordPress filter to adjust the Signature on Delivery Label option.
 * ADDED: Knowledge Base links directly in the plugin settings page for better support.
 * ADDED: PHP 7.4 compatibility.
 * ADDED: WooCommerce 4.0 compatibility.
 * FIXED: No options available if the small satchel is not enabled.
 * FIXED: Convert item dimensions to float foe better accuracy.
 * FIXED: Extra fees were not reset correctly.
 * FIXED: Round shipping costs to 2 decimal digits.
 * FIXED: Strip taxes were not applied to the extra services fees.
 * FIXED: At the Cart page, do not display Large Letter if the items fit in a small letter.
 * REMOVED: What's new? admin notices.

= 2.5.1 (20.10.2019) =
* ADDED: adding the new prepaid satchels service codes.
* FIXED: Signature on Delivery was not showing in the cart.
* FIXED: sorting dimensions for satchel calculations.
* ENHANCED: Satchels calculations using non-flat option.


= 2.5.0 (30.09.2019) =
* ADDED: Australia Post 30.09 changes on maximum weight of the satchels. Now all satchels have a maximum weight of 5kg.
* FIXED: The box packer was splitting items if they have a different name.
* FIXED: PHP warning for math operation on strings.
* FIXED: if two Australia Post methods were added to the same zone, all rates from the two of them should be displayed.
* FIXED: multiple bugs fix.

= 2.4.4 (04.05.2019) =
* FIXED: deciding the Boxpacker usage based on letters shipping.

= 2.4.3 (23.04.2019) =
* FIXED: Using letters regardless of the prepaid satchels options.

= 2.4.2 (19.04.2019) =

* ADDED: Splitting the own packaged international shipping.
* ADDED: Translations support.
* ADDED: WooCommerce 3.6 support.
* FIXED: Ignoring the prepaid satchels for international shipments.
* FIXED: Reduce the plugin updater timout to 5 seconds.

= 2.4.1 (24.03.2019) =
 * FIXED: Error message appears because of a method type-hinting

= 2.4.0 (24.03.2019) =
 * ADDED: support for WordPress 5.1
 * FIXED: Remove the support of 1kg from the code, as it became supported from Australia Post API directly.
 * FIXED: Wrapping wp_widget with try catch to prevent exceptions
 * FIXED: Using boxpacker if satchels are enabled
 * FIXED: The international extra cover and sod fees calculations
 * FIXED: The obligatory Signature on Delivery cost value
 * FIXED: The girth calculations
 * FIXED: Letters calculations - reversing width and length for better packing

= 2.3.0 (03.12.2018) =
 * ADDED: an option to de-emphasize satchels dimensions.
 * UPDATED: Extra cover cost.
 * FIXED: improve splitting packages for stachels.
 * FIXED: The 1kg prepaid satchel calculations.
 * FIXED: Prepaid satchels calculations.


= 2.2.5 (01.11.2018) =
* ADDED: WooCommerce 3.5 support.
* FIXED: The 1kg prepaid satchel calculations.
* FIXED: Extra Cover for international shipping.
* FIXED: illegal offset when sorting shipping prices.
* FIXED: PHP warnings in the packaging guide table.

=  2.2.4 (18.07.2018) =
* FIXED: Fixing the 1Kg satchel calculations

= 2.2.3 (29.05.2018) =
* FIXED: PHP warning if the letters options array is empty.

= 2.2.2 (26.05.2018) =
* FIXED: WooCommerce 3.4 support tag

= 2.2.1 (21.05.2018) =
* FIXED: Fatal error after updating the plugin

= 2.2.0 (20.05.2018) =
* ADDED: The support of the 1kg prepaid satchel.
* ADDED: The ability to customize the domestic and international letters options.

= 2.1.2 (20.03.2018) =
* ADDED: A link to the license page to add missing license key for smoother updates.
* FIXED: check on if session object exists to avoid fatal errors.
* FIXED: separated fees session check
* FIXED: letters dimensions override
* FIXED: tracking URL generation

= 2.1.1 (11.02.2018) =
* ADDED: WC support tag, piece of mind for the customers.

= 2.1.0 (5.11.2017) =
* ADDED: Add shpping class (aupost_not_letter) to products to exclude them from letters calculations.
* UPDATED: Adapt new Signature on Delivery international service fee.
* FIXED: Compatibility with WooCommerce Cart Fees introduced in v3.2.0
* FIXED: Compatibility with WooCommerce 2.6
* FIXED: Pre-paid satchels calculations

= 2.0.2 (30.06.2017) =
* FIXED: Saving custom boxes data.

= 2.0.1 (26.06.2017) =
* ADDED: Tracking information in the completed order email.

= 2.0.0 (22.06.2017) =
* ADDED: Custom Boxes support.
* ADDED: Australia Post Tracking support.
* ADDED: Compatibility with WC 3.0.
* FIXED: Satchels box packing.
* FIXED: Show express services letters only when express is selected.
* FIXED: Custom titles when satchels are enabled.
* REMOVED: Legacy settings page, Please use shipping zones with WC 2.6+


= 1.9.1 =
* FIXED: Signature on Deliver calculation bug.
* FIXED: Not allowing items with length more than 105cm for international shipping.

= 1.9.0 =
* ADDED: The fallback price functionality
* ADDED: The ability to rename shipping services
* FIXED: Calculting handling fees
* FIXED: Letters calculations
* FIXED: WC() fatal error
* FIXED: Max weight for satchels
* FIXED: Signature on Delivery and Extra Cover fees showing when free shipping selected




= 1.8.5 =
* FIXED: satchels girth validation
* FIXED: letters dimensions calculations
* FIXED: max weight conversion

= 1.8.4 =
* FIXED: settings debugging mode for WC 2.6+

= 1.8.3 =
* FIXED: Shipping methods not added to zones.
= 1.8.2 =
* ADDED: The "Enable" option for the legacy shipping method.
= 1.8.1 =
* ADDED: Enabled old shipping methods way for WooCommerce 2.6 to make store owners able to migrate to shipping zones
* REMOVED: availability and countries options since they are no longer relevant for WooCommerce shipping zones

= 1.8.0 =
* ADDED: Compatibility with Shipping Zones which introduced in WooCommerce 2.6.0
* ADDED: A new setting to make Extra Cover and Signature on Delivery optional for the customers.
* ADDED: A new setting to remove the GST(tax) value from the returned shipping prices from Australia Post.
* IMPROVED: Showing the cheapest shipping rate calculations.
* IMPROVED: International Letters calculations.
* IMPROVED: User Interface improved by combining the default product size in one field.
* FIXED: Signature on Delivery caused free shipping prices in some cases.
* FIXED: Courier option calculations bug.


= 1.7.3 =
* CHANGED: API URL.
* FIXED: Satchels calculating issue.
* FIXED: Redundant satchels and own packing issue.
* ADDED: New Letters Services codes.

= 1.7.2 =
* ADDED: NEW International Australia Post Services.
* FIXED: Letters shipping bug.
* FIXED: International Shipping bug.

= 1.7.1 =
* ADDED: International Express option.
* ADDED: Debuging mode major enhancements.
* ADDED: Re-Branded the plugin.
* ADDED: Updated the auto-updated class.
* FIXED: Satchels calculation bug.
* FIXED: Letters packaging bug.



= 1.7.0 =
ADDED: Extra Cover feature for domestical and international shipping.
FIXED: Letters support bugs.
FIXED: Prepaid satchels bugs.
ADDED: UI enhancements.
REMOVED: Deprecated WooCommerce functions.

= 1.6.4 =
* Fixed a dimensions bug.

= 1.6.3 =
* Fixed a packaging bug.

= 1.6.2 =
* Show errors only in debug mode.
* Enhance the settings page.


= 1.6.1 =
* letters support
* fixing the duplication of the satchels

= 1.5.1 =
* Fixing tax issues

= 1.5.0 =
* Adding dropshipping support
* Add beta feature of packing table guide
* Tax issue fix
* Dimensions bug fix

= 1.4.2 =
* Delivery Time Estimation has been added.


= 1.4.1 =
* Fix a bug in the dimensions other than 'cm'

= 1.4.0 =
* Fixing major bugs
* Adding the Debug mode
* Adding the Satchels Feature.

= 1.3.2 =
* Fixing the length bug

= 1.3.1 =
* Fixing a minor bug


= 1.3.0 =
* Fixing the quantity bug
* Fixing other minor bugs
= 1.0 =
* Initial release.

== Upgrade Notice ==
* Important to upgrade, International shipping might not work if not upgraded. Australia Post changed their API.
