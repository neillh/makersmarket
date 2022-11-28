<?php
/**
 * Custom search form template.
 *
 * @package Skinny
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'skinny' ); ?></span>
		<input type="search" class="search-field"
				placeholder="<?php echo esc_attr_x( 'Type here to search&hellip;', 'placeholder', 'skinny' ); ?>"
				value="<?php echo get_search_query(); ?>" name="s"
				title="<?php echo esc_attr_x( 'Search for:', 'label', 'skinny' ); ?>"
				autocomplete="off"
		/>
	</label>
	<input type="submit" class="search-submit"
		   value="<?php echo esc_attr_x( 'Search', 'submit button', 'skinny' ); ?>"/>
</form>
