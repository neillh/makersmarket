<?php
namespace AustraliaPost\Metaboxes;

abstract class Metabox {
    /**
     * Class constructor.
     * @since 2.0.0
     *
     * @uses hook()
     *
     */
    public function __construct() {
	    add_action( 'add_meta_boxes', array( $this, 'adding_metabox' ), 10, 2 );
	    add_action( 'save_post', array( $this, 'process_metabox' ) );
    }


    /**
     * To add the metabox in the order's page.
     *
     * @uses add_meta_box()
     * @since 2.0.0
     *
     * @param object $post The object of the post.
     */
    abstract public function adding_metabox( $post_type, $post  );

    /**
     * Display the HTML of the metabox
     *
     * @uses get_post_meta()
     * @since 2.0.0
     *
     * @param object $order The object of the order.
     */
    abstract public function render_metabox( $order );

    /**
     * Save the metabox form.
     *
     * @uses update_post_meta()
     * @since 2.0.0
     *
     * @param integer $order_id The ID number of the order.
     */
    abstract public function process_metabox( $order_id );

	protected function load_view( $path, $variables = array() ) {
		extract($variables);
		include $path;
	}

}
