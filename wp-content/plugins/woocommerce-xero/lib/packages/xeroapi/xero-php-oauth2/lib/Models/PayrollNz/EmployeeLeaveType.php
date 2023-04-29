<?php
/**
 * EmployeeLeaveType
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
 * EmployeeLeaveType Class Doc Comment
 *
 * @category Class
 * @package  XeroAPI\XeroPHP
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class EmployeeLeaveType implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'EmployeeLeaveType';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'leave_type_id' => 'string',
        'schedule_of_accrual' => 'string',
        'hours_accrued_annually' => 'double',
        'maximum_to_accrue' => 'double',
        'opening_balance' => 'double',
        'rate_accrued_hourly' => 'double',
        'percentage_of_gross_earnings' => 'double',
        'include_holiday_pay_every_pay' => 'bool',
        'show_annual_leave_in_advance' => 'bool',
        'annual_leave_total_amount_paid' => 'double'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'leave_type_id' => 'uuid',
        'schedule_of_accrual' => null,
        'hours_accrued_annually' => 'double',
        'maximum_to_accrue' => 'double',
        'opening_balance' => 'double',
        'rate_accrued_hourly' => 'double',
        'percentage_of_gross_earnings' => 'double',
        'include_holiday_pay_every_pay' => null,
        'show_annual_leave_in_advance' => null,
        'annual_leave_total_amount_paid' => 'double'
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
        'leave_type_id' => 'leaveTypeID',
        'schedule_of_accrual' => 'scheduleOfAccrual',
        'hours_accrued_annually' => 'hoursAccruedAnnually',
        'maximum_to_accrue' => 'maximumToAccrue',
        'opening_balance' => 'openingBalance',
        'rate_accrued_hourly' => 'rateAccruedHourly',
        'percentage_of_gross_earnings' => 'percentageOfGrossEarnings',
        'include_holiday_pay_every_pay' => 'includeHolidayPayEveryPay',
        'show_annual_leave_in_advance' => 'showAnnualLeaveInAdvance',
        'annual_leave_total_amount_paid' => 'annualLeaveTotalAmountPaid'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'leave_type_id' => 'setLeaveTypeId',
        'schedule_of_accrual' => 'setScheduleOfAccrual',
        'hours_accrued_annually' => 'setHoursAccruedAnnually',
        'maximum_to_accrue' => 'setMaximumToAccrue',
        'opening_balance' => 'setOpeningBalance',
        'rate_accrued_hourly' => 'setRateAccruedHourly',
        'percentage_of_gross_earnings' => 'setPercentageOfGrossEarnings',
        'include_holiday_pay_every_pay' => 'setIncludeHolidayPayEveryPay',
        'show_annual_leave_in_advance' => 'setShowAnnualLeaveInAdvance',
        'annual_leave_total_amount_paid' => 'setAnnualLeaveTotalAmountPaid'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'leave_type_id' => 'getLeaveTypeId',
        'schedule_of_accrual' => 'getScheduleOfAccrual',
        'hours_accrued_annually' => 'getHoursAccruedAnnually',
        'maximum_to_accrue' => 'getMaximumToAccrue',
        'opening_balance' => 'getOpeningBalance',
        'rate_accrued_hourly' => 'getRateAccruedHourly',
        'percentage_of_gross_earnings' => 'getPercentageOfGrossEarnings',
        'include_holiday_pay_every_pay' => 'getIncludeHolidayPayEveryPay',
        'show_annual_leave_in_advance' => 'getShowAnnualLeaveInAdvance',
        'annual_leave_total_amount_paid' => 'getAnnualLeaveTotalAmountPaid'
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

    const SCHEDULE_OF_ACCRUAL_ANNUALLY_AFTER6_MONTHS = 'AnnuallyAfter6Months';
    const SCHEDULE_OF_ACCRUAL_ON_ANNIVERSARY_DATE = 'OnAnniversaryDate';
    const SCHEDULE_OF_ACCRUAL_PERCENTAGE_OF_GROSS_EARNINGS = 'PercentageOfGrossEarnings';
    const SCHEDULE_OF_ACCRUAL_NO_ACCRUALS = 'NoAccruals';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getScheduleOfAccrualAllowableValues()
    {
        return [
            self::SCHEDULE_OF_ACCRUAL_ANNUALLY_AFTER6_MONTHS,
            self::SCHEDULE_OF_ACCRUAL_ON_ANNIVERSARY_DATE,
            self::SCHEDULE_OF_ACCRUAL_PERCENTAGE_OF_GROSS_EARNINGS,
            self::SCHEDULE_OF_ACCRUAL_NO_ACCRUALS,
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
        $this->container['leave_type_id'] = isset($data['leave_type_id']) ? $data['leave_type_id'] : null;
        $this->container['schedule_of_accrual'] = isset($data['schedule_of_accrual']) ? $data['schedule_of_accrual'] : null;
        $this->container['hours_accrued_annually'] = isset($data['hours_accrued_annually']) ? $data['hours_accrued_annually'] : null;
        $this->container['maximum_to_accrue'] = isset($data['maximum_to_accrue']) ? $data['maximum_to_accrue'] : null;
        $this->container['opening_balance'] = isset($data['opening_balance']) ? $data['opening_balance'] : null;
        $this->container['rate_accrued_hourly'] = isset($data['rate_accrued_hourly']) ? $data['rate_accrued_hourly'] : null;
        $this->container['percentage_of_gross_earnings'] = isset($data['percentage_of_gross_earnings']) ? $data['percentage_of_gross_earnings'] : null;
        $this->container['include_holiday_pay_every_pay'] = isset($data['include_holiday_pay_every_pay']) ? $data['include_holiday_pay_every_pay'] : null;
        $this->container['show_annual_leave_in_advance'] = isset($data['show_annual_leave_in_advance']) ? $data['show_annual_leave_in_advance'] : null;
        $this->container['annual_leave_total_amount_paid'] = isset($data['annual_leave_total_amount_paid']) ? $data['annual_leave_total_amount_paid'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        $allowedValues = $this->getScheduleOfAccrualAllowableValues();
        if (!is_null($this->container['schedule_of_accrual']) && !in_array($this->container['schedule_of_accrual'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'schedule_of_accrual', must be one of '%s'",
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
     * Gets leave_type_id
     *
     * @return string|null
     */
    public function getLeaveTypeId()
    {
        return $this->container['leave_type_id'];
    }

    /**
     * Sets leave_type_id
     *
     * @param string|null $leave_type_id The Xero identifier for leave type
     *
     * @return $this
     */
    public function setLeaveTypeId($leave_type_id)
    {

        $this->container['leave_type_id'] = $leave_type_id;

        return $this;
    }



    /**
     * Gets schedule_of_accrual
     *
     * @return string|null
     */
    public function getScheduleOfAccrual()
    {
        return $this->container['schedule_of_accrual'];
    }

    /**
     * Sets schedule_of_accrual
     *
     * @param string|null $schedule_of_accrual The schedule of accrual
     *
     * @return $this
     */
    public function setScheduleOfAccrual($schedule_of_accrual)
    {
        $allowedValues = $this->getScheduleOfAccrualAllowableValues();
        if (!is_null($schedule_of_accrual) && !in_array($schedule_of_accrual, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'schedule_of_accrual', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }

        $this->container['schedule_of_accrual'] = $schedule_of_accrual;

        return $this;
    }



    /**
     * Gets hours_accrued_annually
     *
     * @return double|null
     */
    public function getHoursAccruedAnnually()
    {
        return $this->container['hours_accrued_annually'];
    }

    /**
     * Sets hours_accrued_annually
     *
     * @param double|null $hours_accrued_annually The number of hours accrued for the leave annually. This is 0 when the scheduleOfAccrual chosen is \"OnHourWorked\"
     *
     * @return $this
     */
    public function setHoursAccruedAnnually($hours_accrued_annually)
    {

        $this->container['hours_accrued_annually'] = $hours_accrued_annually;

        return $this;
    }



    /**
     * Gets maximum_to_accrue
     *
     * @return double|null
     */
    public function getMaximumToAccrue()
    {
        return $this->container['maximum_to_accrue'];
    }

    /**
     * Sets maximum_to_accrue
     *
     * @param double|null $maximum_to_accrue The maximum number of hours that can be accrued for the leave
     *
     * @return $this
     */
    public function setMaximumToAccrue($maximum_to_accrue)
    {

        $this->container['maximum_to_accrue'] = $maximum_to_accrue;

        return $this;
    }



    /**
     * Gets opening_balance
     *
     * @return double|null
     */
    public function getOpeningBalance()
    {
        return $this->container['opening_balance'];
    }

    /**
     * Sets opening_balance
     *
     * @param double|null $opening_balance The initial number of hours assigned when the leave was added to the employee
     *
     * @return $this
     */
    public function setOpeningBalance($opening_balance)
    {

        $this->container['opening_balance'] = $opening_balance;

        return $this;
    }



    /**
     * Gets rate_accrued_hourly
     *
     * @return double|null
     */
    public function getRateAccruedHourly()
    {
        return $this->container['rate_accrued_hourly'];
    }

    /**
     * Sets rate_accrued_hourly
     *
     * @param double|null $rate_accrued_hourly The number of hours added to the leave balance for every hour worked by the employee. This is normally 0, unless the scheduleOfAccrual chosen is \"OnHourWorked\"
     *
     * @return $this
     */
    public function setRateAccruedHourly($rate_accrued_hourly)
    {

        $this->container['rate_accrued_hourly'] = $rate_accrued_hourly;

        return $this;
    }



    /**
     * Gets percentage_of_gross_earnings
     *
     * @return double|null
     */
    public function getPercentageOfGrossEarnings()
    {
        return $this->container['percentage_of_gross_earnings'];
    }

    /**
     * Sets percentage_of_gross_earnings
     *
     * @param double|null $percentage_of_gross_earnings Specific for scheduleOfAccrual having percentage of gross earnings. Identifies how much percentage of gross earnings is accrued per pay period.
     *
     * @return $this
     */
    public function setPercentageOfGrossEarnings($percentage_of_gross_earnings)
    {

        $this->container['percentage_of_gross_earnings'] = $percentage_of_gross_earnings;

        return $this;
    }



    /**
     * Gets include_holiday_pay_every_pay
     *
     * @return bool|null
     */
    public function getIncludeHolidayPayEveryPay()
    {
        return $this->container['include_holiday_pay_every_pay'];
    }

    /**
     * Sets include_holiday_pay_every_pay
     *
     * @param bool|null $include_holiday_pay_every_pay Specific to Holiday pay. Flag determining if pay for leave type is added on each pay run.
     *
     * @return $this
     */
    public function setIncludeHolidayPayEveryPay($include_holiday_pay_every_pay)
    {

        $this->container['include_holiday_pay_every_pay'] = $include_holiday_pay_every_pay;

        return $this;
    }



    /**
     * Gets show_annual_leave_in_advance
     *
     * @return bool|null
     */
    public function getShowAnnualLeaveInAdvance()
    {
        return $this->container['show_annual_leave_in_advance'];
    }

    /**
     * Sets show_annual_leave_in_advance
     *
     * @param bool|null $show_annual_leave_in_advance Specific to Annual Leave. Flag to include leave available to take in advance in the balance in the payslip
     *
     * @return $this
     */
    public function setShowAnnualLeaveInAdvance($show_annual_leave_in_advance)
    {

        $this->container['show_annual_leave_in_advance'] = $show_annual_leave_in_advance;

        return $this;
    }



    /**
     * Gets annual_leave_total_amount_paid
     *
     * @return double|null
     */
    public function getAnnualLeaveTotalAmountPaid()
    {
        return $this->container['annual_leave_total_amount_paid'];
    }

    /**
     * Sets annual_leave_total_amount_paid
     *
     * @param double|null $annual_leave_total_amount_paid Specific to Annual Leave. Annual leave balance in dollars
     *
     * @return $this
     */
    public function setAnnualLeaveTotalAmountPaid($annual_leave_total_amount_paid)
    {

        $this->container['annual_leave_total_amount_paid'] = $annual_leave_total_amount_paid;

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


