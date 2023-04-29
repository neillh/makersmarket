<?php
/**
 * TaxSettings
 *
 * PHP version 5
 *
 * @category Class
 * @package  XeroAPI\XeroPHP
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 *
 * @license MIT
 * Modified by woocommerce on 13-March-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

/**
 * Xero Payroll NZ
 *
 * This is the Xero Payroll API for orgs in the NZ region.
 *
 * Contact: api@xero.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.4.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Automattic\WooCommerce\Xero\Vendor\XeroAPI\XeroPHP\Models\PayrollNz;

use \ArrayAccess;
use \Automattic\WooCommerce\Xero\Vendor\XeroAPI\XeroPHP\PayrollNzObjectSerializer;
use \Automattic\WooCommerce\Xero\Vendor\XeroAPI\XeroPHP\StringUtil;
use ReturnTypeWillChange;

/**
 * TaxSettings Class Doc Comment
 *
 * @category Class
 * @package  XeroAPI\XeroPHP
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class TaxSettings implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'TaxSettings';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'period_units' => 'double',
        'period_type' => 'string',
        'tax_code' => '\Automattic\WooCommerce\Xero\Vendor\XeroAPI\XeroPHP\Models\PayrollNz\TaxCode',
        'special_tax_rate' => 'string',
        'lump_sum_tax_code' => 'string',
        'lump_sum_amount' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'period_units' => 'double',
        'period_type' => null,
        'tax_code' => null,
        'special_tax_rate' => null,
        'lump_sum_tax_code' => null,
        'lump_sum_amount' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'period_units' => 'periodUnits',
        'period_type' => 'periodType',
        'tax_code' => 'taxCode',
        'special_tax_rate' => 'specialTaxRate',
        'lump_sum_tax_code' => 'lumpSumTaxCode',
        'lump_sum_amount' => 'lumpSumAmount'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'period_units' => 'setPeriodUnits',
        'period_type' => 'setPeriodType',
        'tax_code' => 'setTaxCode',
        'special_tax_rate' => 'setSpecialTaxRate',
        'lump_sum_tax_code' => 'setLumpSumTaxCode',
        'lump_sum_amount' => 'setLumpSumAmount'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'period_units' => 'getPeriodUnits',
        'period_type' => 'getPeriodType',
        'tax_code' => 'getTaxCode',
        'special_tax_rate' => 'getSpecialTaxRate',
        'lump_sum_tax_code' => 'getLumpSumTaxCode',
        'lump_sum_amount' => 'getLumpSumAmount'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }

    const PERIOD_TYPE_WEEKS = 'weeks';
    const PERIOD_TYPE_MONTHS = 'months';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getPeriodTypeAllowableValues()
    {
        return [
            self::PERIOD_TYPE_WEEKS,
            self::PERIOD_TYPE_MONTHS,
        ];
    }
    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['period_units'] = isset($data['period_units']) ? $data['period_units'] : null;
        $this->container['period_type'] = isset($data['period_type']) ? $data['period_type'] : null;
        $this->container['tax_code'] = isset($data['tax_code']) ? $data['tax_code'] : null;
        $this->container['special_tax_rate'] = isset($data['special_tax_rate']) ? $data['special_tax_rate'] : null;
        $this->container['lump_sum_tax_code'] = isset($data['lump_sum_tax_code']) ? $data['lump_sum_tax_code'] : null;
        $this->container['lump_sum_amount'] = isset($data['lump_sum_amount']) ? $data['lump_sum_amount'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        $allowedValues = $this->getPeriodTypeAllowableValues();
        if (!is_null($this->container['period_type']) && !in_array($this->container['period_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'period_type', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets period_units
     *
     * @return double|null
     */
    public function getPeriodUnits()
    {
        return $this->container['period_units'];
    }

    /**
     * Sets period_units
     *
     * @param double|null $period_units The number of units for the period type
     *
     * @return $this
     */
    public function setPeriodUnits($period_units)
    {

        $this->container['period_units'] = $period_units;

        return $this;
    }



    /**
     * Gets period_type
     *
     * @return string|null
     */
    public function getPeriodType()
    {
        return $this->container['period_type'];
    }

    /**
     * Sets period_type
     *
     * @param string|null $period_type The type of period (\"weeks\" or \"months\")
     *
     * @return $this
     */
    public function setPeriodType($period_type)
    {
        $allowedValues = $this->getPeriodTypeAllowableValues();
        if (!is_null($period_type) && !in_array($period_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'period_type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }

        $this->container['period_type'] = $period_type;

        return $this;
    }



    /**
     * Gets tax_code
     *
     * @return \Automattic\WooCommerce\Xero\Vendor\XeroAPI\XeroPHP\Models\PayrollNz\TaxCode|null
     */
    public function getTaxCode()
    {
        return $this->container['tax_code'];
    }

    /**
     * Sets tax_code
     *
     * @param \Automattic\WooCommerce\Xero\Vendor\XeroAPI\XeroPHP\Models\PayrollNz\TaxCode|null $tax_code tax_code
     *
     * @return $this
     */
    public function setTaxCode($tax_code)
    {

        $this->container['tax_code'] = $tax_code;

        return $this;
    }



    /**
     * Gets special_tax_rate
     *
     * @return string|null
     */
    public function getSpecialTaxRate()
    {
        return $this->container['special_tax_rate'];
    }

    /**
     * Sets special_tax_rate
     *
     * @param string|null $special_tax_rate Tax rate for STC and WT
     *
     * @return $this
     */
    public function setSpecialTaxRate($special_tax_rate)
    {

        $this->container['special_tax_rate'] = $special_tax_rate;

        return $this;
    }



    /**
     * Gets lump_sum_tax_code
     *
     * @return string|null
     */
    public function getLumpSumTaxCode()
    {
        return $this->container['lump_sum_tax_code'];
    }

    /**
     * Sets lump_sum_tax_code
     *
     * @param string|null $lump_sum_tax_code Tax code for a lump sum amount
     *
     * @return $this
     */
    public function setLumpSumTaxCode($lump_sum_tax_code)
    {

        $this->container['lump_sum_tax_code'] = $lump_sum_tax_code;

        return $this;
    }



    /**
     * Gets lump_sum_amount
     *
     * @return string|null
     */
    public function getLumpSumAmount()
    {
        return $this->container['lump_sum_amount'];
    }

    /**
     * Sets lump_sum_amount
     *
     * @param string|null $lump_sum_amount The total of the lump sum amount
     *
     * @return $this
     */
    public function setLumpSumAmount($lump_sum_amount)
    {

        $this->container['lump_sum_amount'] = $lump_sum_amount;

        return $this;
    }


    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            PayrollNzObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }
}


