<?php

namespace AustraliaPost\Core;

use AustraliaPost\Core\Api\Abstract_Calculator;

interface Interface_Extender {

	/**
	 * @return void
	 */
	public function initiate();

	/**
	 * @return array
	 */
	public function settings();

	/**
	 * @return array
	 */
	public function settings_keys();

	/**
	 * @param array $settings
	 *
	 * @return Abstract_Calculator
	 */
	public function calculator($settings);

	/**
	 * @param array $settings
	 *
	 * @return boolean
	 */
	public function should_use_calculator($settings);
	
	public function load_metaboxes();

	/**
	 * @return bool
	 */
	public function is_enabled();
}
