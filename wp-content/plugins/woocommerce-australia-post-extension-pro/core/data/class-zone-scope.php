<?php
namespace AustraliaPost\Core\Data;

class Zone_Scope {

	private $is_only_local = false;
	private $is_only_international = false;
	private $is_worldwide = false;

	/**
	 * ZoneScope constructor.
	 *
	 * @param array $countries
	 */
	public function __construct($countries) {

		$this->evaluate($countries);
	}

	/**
	 * @param array $countries
	 */
	private function evaluate( array $countries ) {
		if (count($countries) === 0 ){
			$this->set_worldwide(true);
			return;
		}

		if (count($countries) === 1 && in_array('AU', $countries)){
			$this->set_only_local(true);
			return;
		}

		if (! in_array('AU', $countries)) {
			$this->set_only_international(true);
			return;
		}

		$this->set_worldwide(true);
	}

	/**
	 * @return bool
	 */
	public function is_only_local() {
		return $this->is_only_local;
	}

	/**
	 * @param bool $is_only_local
	 */
	public function set_only_local( $is_only_local ) {
		$this->is_only_local = $is_only_local;
	}

	/**
	 * @return bool
	 */
	public function is_only_international() {
		return $this->is_only_international;
	}

	/**
	 * @param bool $is_only_international
	 */
	public function set_only_international( $is_only_international ) {
		$this->is_only_international = $is_only_international;
	}

	/**
	 * @return bool
	 */
	public function is_worldwide() {
		return $this->is_worldwide;
	}

	/**
	 * @param bool $is_worldwide
	 */
	public function set_worldwide( $is_worldwide ) {
		$this->is_worldwide = $is_worldwide;
	}
}
