<?php
class Storefront_Blocks_Grid {

	/** @var self Instance */
	private static $_instance;

	/**
	 * Returns instance of current calss
	 * @return self Instance
	 */
	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected $item_classes_lookup;

	protected function prepare_item_classes( $total ) {
		$item_classes = [];
		$item_classes[1] = [ 'ca' ];
		$item_classes[2] = [ 'c2', 'c2' ];
		$item_classes[3] = [ 'c2 sfbk-grid-r2', 'c2', 'c2' ];
		$item_classes[4] = [ 'r2', 'c2', 'r2', 'c2' ];
		$item_classes[5] = [ 'c2', '', 'r2', '', 'c2', '', ];
		$item_classes[6] = [ 'c2', 'r2', '', '', '', '', ];
		$item_classes[7] = [ 'c2', '', 'r2', 'r2', 'c2', '', 'c2' ];
		$item_classes[8] = [ 'c2', '', 'r2', 'r2', '', '', 'c2', '' ];
		$item_classes[9] = [ 'c2', '', 'r2', '', '', 'r2', '', '', '' ];
		$item_classes[10] = array_merge( $item_classes[6], $item_classes[4] );
		$item_classes[11] = array_merge( $item_classes[6], $item_classes[5] );
		$item_classes[12] = array_merge( $item_classes[6], [ '', 'c2', 'r2', '', '', '', ] );

		$classes = [];
//		$debug = '';
		if ( ! empty( $item_classes[ $total ] ) ) {
			$classes = $item_classes[ $total ];
		} else if ( $item_classes > 12 ) {
			// Keep filling with 12 col cofig until upto last 12 - 16
			for ( $left = $total; $left > 16; $left -= 12 ) {
//				$debug .= '<span class="adddd">' . 12 . '</span>';
				$classes = array_merge( $classes, $item_classes[12] );
			}
			if ( $left % 12 > 4 ) { // 17 - 23 items left
//				$debug .= '<span class="adddd">' . $left % 12 . '</span>';
				$classes = array_merge( $classes, $item_classes[$left % 12] );
			} else if ( $left == 12 ) { // 12 items left
//				$debug .= '<span class="adddd">' . $left . '</span>';
				$classes = array_merge( $classes, $item_classes[ $left ] );
			} else { // 13 - 16 items left
//				$debug .= '<span class="adddd">' . 6 . '</span>';
				$classes = array_merge( $classes, $item_classes[6] );
				$left -= 6;
//				$debug .= '<span class="adddd">' . $left . '</span>';
				$classes = array_merge( $classes, $item_classes[ $left ] );
			}
//			echo "<pre class='fl ma2'>Total \t $total\n$debug</pre>";
		}
		return $this->item_classes_lookup = $classes;
	}

	protected function item_class( $i, $total ) {

		$lookup = $this->item_classes_lookup;

		if ( ! empty( $lookup[ $i ] ) ) {
			return " sfbk-grid-{$lookup[ $i ]}";
		}
	}

	public static function fancy( $items, $data = false ) {
		return self::instance()->_fancy( $items, $data );
	}

	public function _fancy( $items, $data = false ) {
		$i = 0;
		$total = count( $items );
		$grid_items = [];

		if( $total > 0 ) {
			$this->prepare_item_classes( $total );

			foreach ( $items as $item ) {
				$style = 'background-image: url("' . $item['image'] . '")';
				$classes = "sfbk-grid-i " . $this->item_class( $i, $total );
				$grid_items[] = "<a class='$classes' style='$style' href='$item[link]'>" .
												"<span class='sfbk-grid-content'>$item[label]</span></a>";

				$i++;
			}
		}

		if ( $data ) {
			return [
				'class' => 'sfbk-grid sfbk-grid-' . $total,
				'items' => $grid_items,
			];
		}

		return '<div class="sfbk-grid sfbk-grid-' . $total . '">' . implode( '', $grid_items ) . '</div>';

	}
}