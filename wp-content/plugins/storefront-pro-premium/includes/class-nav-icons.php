<?php
/**
 * Created by PhpStorm.
 * User: shramee
 * Date: 23/10/15
 * Time: 3:04 PM
 */
class SFP_Add_Nav_Icons {
	protected $ico;

	public function __construct() {
		add_filter( 'nav_menu_css_class', array( $this, 'icon_class' ), 10, 3 );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'add_icon' ), 10, 4 );

	}

	public function icon_class( $classes, $item, $args ) {
		$temp = array();
		$this->ico = [];

		foreach ( $classes as $class ) {
			if ( strpos( $class, 'fa-' ) === false && ! in_array( $class, [ 'fas', 'fab', 'fal', 'far' ] ) ) {
				$temp[] = $class;
			} else {
				$temp[] = 'menu-item-icon';
				$this->ico[] = $class;
			}
		}

		return $temp;
	}

	public function add_icon( $html, $i, $depth ) {
		if ( $this->ico ) {
			$icon = implode( ' ', $this->ico );
			$html = "<a href='{$i->url}'><i class='{$icon}'> </i><span>{$i->title}</span></a>";
		}
		return $html;
	}

}