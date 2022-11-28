<?php
/**
 * The template for displaying pagination for any query.
 *
 * @package Skinny
 */

global $wp_query;

\Skinny\get_pagination( $wp_query );
