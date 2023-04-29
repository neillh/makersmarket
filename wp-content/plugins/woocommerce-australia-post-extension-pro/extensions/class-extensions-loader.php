<?php
namespace AustraliaPost\Extensions;

use AustraliaPost\Core\Api\General;
use AustraliaPost\Core\Interface_Extender;

class Extensions_Loader {

	private $extensions;

	/**
	 * The single instance of the class.
	 *
	 * @var Extensions_Loader
	 */
	protected static $_instance = null;

	/**
	 * @return Extensions_Loader
	 */
	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{
		$this->extensions = $this->extensions();
	}

	public function extensions()
	{
		$extensions_directory = dirname(__FILE__);
		$extensions_directory_items = array_diff(scandir($extensions_directory), array('..', '.'));

		$extensions = [];

		foreach ($extensions_directory_items as $item){
			if(! is_dir($extensions_directory . '/' . $item)) {
				continue;
			}

			$adapter_file = $extensions_directory . '/' . $item. '/class-' . $item . '-adapter.php';
			if(!file_exists($adapter_file)){
				continue;
			}

			/** @var Interface_Extender $adapter_class */
			$adapter_class = "AustraliaPost\Extensions\\".ucfirst($item) . "\\" . ucfirst($item) . '_Adapter';

			if (class_exists($adapter_class)) {
				/** @var Interface_Extender $extension */
				$extension = new $adapter_class();

				if ($extension->is_enabled()) {
					$extension->initiate();
					$extensions[] = $extension;
				}

			}
		}
		return $extensions;
	}

	public function extra_settings()
	{
		$extra_settings = array();
		/** @var Interface_Extender $extension */
		foreach ($this->extensions as $extension){
			foreach ( $extension->settings() as $key => $extension_setting){
				$extra_settings[$key] = $extension_setting;
			}
		}
		return $extra_settings;
	}

	public function extra_settings_keys()
	{
		$settings_keys = array();
		/** @var Interface_Extender $extension */
		foreach ($this->extensions as $extension){
			foreach ( $extension->settings_keys() as $extension_key){
				$settings_keys[] = $extension_key;
			}
		}

		return $settings_keys;
	}

	public function calculator($settings)
	{
		/** @var Interface_Extender $extension */
		foreach ($this->extensions as $extension){
			if($extension->should_use_calculator($settings)){
				return $extension->calculator($settings);
			}
		}

		return new General($settings);
	}

	public function load_metaboxes()
	{
		/** @var Interface_Extender $extension */
		foreach ($this->extensions as $extension){
			$extension->load_metaboxes();
		}
	}

}