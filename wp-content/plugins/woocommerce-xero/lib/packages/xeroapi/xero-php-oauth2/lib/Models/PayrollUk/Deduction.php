<?php
/**
 * Deduction
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
 * Xero Payroll UK
 *
 * This is the Xero Payroll API for orgs in the UK region.
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

namespace Automattic\WooCommerce\Xero\Vendor\XeroAPI\XeroPHP\Models\PayrollUk;

use \ArrayAccess;
use \Automattic\WooCommerce\Xero\Vendor\XeroAPI\XeroPHP\PayrollUkObjectSerializer;
use \Automattic\WooCommerce\Xero\Vendor\XeroAPI\XeroPHP\StringUtil;
use ReturnTypeWillChange;

/**
 * Deduction Class Doc Comment
 *
 * @category Class
 * @package  XeroAPI\XeroPHP
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class Deduction implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'Deduction';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'deduction_id' => 'string',
        'deduction_name' => 'string',
        'deduction_category' => 'string',
        'liability_account_id' => 'string',
        'current_record' => 'bool',
        'standard_amount' => 'double',
        'reduces_super_liability' => 'bool',
        'reduces_tax_liability' => 'bool',
        'calculation_type' => 'string',
        'percentage' => 'double',
        'subject_to_nic' => 'bool',
        'subject_to_tax' => 'bool',
        'is_reduced_by_basic_rate' => 'bool',
        'apply_to_pension_calculations' => 'bool',
        'is_calculating_on_qualifying_earnings' => 'bool',
        'is_pension' => 'bool'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'deduction_id' => 'uuid',
        'deduction_name' => null,
        'deduction_category' => null,
        'liability_account_id' => 'uuid',
        'current_record' => null,
        'standard_amount' => 'double',
        'reduces_super_liability' => null,
        'reduces_tax_liability' => null,
        'calculation_type' => null,
        'percentage' => 'double',
        'subject_to_nic' => null,
        'subject_to_tax' => null,
        'is_reduced_by_basic_rate' => null,
        'apply_to_pension_calculations' => null,
        'is_calculating_on_qualifying_earnings' => null,
        'is_pension' => null
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
        'deduction_id' => 'deductionId',
        'deduction_name' => 'deductionName',
        'deduction_category' => 'deductionCategory',
        'liability_account_id' => 'liabilityAccountId',
        'current_record' => 'currentRecord',
        'standard_amount' => 'standardAmount',
        'reduces_super_liability' => 'reducesSuperLiability',
        'reduces_tax_liability' => 'reducesTaxLiability',
        'calculation_type' => 'calculationType',
        'percentage' => 'percentage',
        'subject_to_nic' => 'subjectToNIC',
        'subject_to_tax' => 'subjectToTax',
        'is_reduced_by_basic_rate' => 'isReducedByBasicRate',
        'apply_to_pension_calculations' => 'applyToPensionCalculations',
        'is_calculating_on_qualifying_earnings' => 'isCalculatingOnQualifyingEarnings',
        'is_pension' => 'isPension'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'deduction_id' => 'setDeductionId',
        'deduction_name' => 'setDeductionName',
        'deduction_category' => 'setDeductionCategory',
        'liability_account_id' => 'setLiabilityAccountId',
        'current_record' => 'setCurrentRecord',
        'standard_amount' => 'setStandardAmount',
        'reduces_super_liability' => 'setReducesSuperLiability',
        'reduces_tax_liability' => 'setReducesTaxLiability',
        'calculation_type' => 'setCalculationType',
        'percentage' => 'setPercentage',
        'subject_to_nic' => 'setSubjectToNic',
        'subject_to_tax' => 'setSubjectToTax',
        'is_reduced_by_basic_rate' => 'setIsReducedByBasicRate',
        'apply_to_pension_calculations' => 'setApplyToPensionCalculations',
        'is_calculating_on_qualifying_earnings' => 'setIsCalculatingOnQualifyingEarnings',
        'is_pension' => 'setIsPension'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'deduction_id' => 'getDeductionId',
        'deduction_name' => 'getDeductionName',
        'deduction_category' => 'getDeductionCategory',
        'liability_account_id' => 'getLiabilityAccountId',
        'current_record' => 'getCurrentRecord',
        'standard_amount' => 'getStandardAmount',
        'reduces_super_liability' => 'getReducesSuperLiability',
        'reduces_tax_liability' => 'getReducesTaxLiability',
        'calculation_type' => 'getCalculationType',
        'percentage' => 'getPercentage',
        'subject_to_nic' => 'getSubjectToNic',
        'subject_to_tax' => 'getSubjectToTax',
        'is_reduced_by_basic_rate' => 'getIsReducedByBasicRate',
        'apply_to_pension_calculations' => 'getApplyToPensionCalculations',
        'is_calculating_on_qualifying_earnings' => 'getIsCalculatingOnQualifyingEarnings',
        'is_pension' => 'getIsPension'
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

    const DEDUCTION_CATEGORY_CAPITAL_CONTRIBUTIONS = 'CapitalContributions';
    const DEDUCTION_CATEGORY_CHILD_CARE_VOUCHER = 'ChildCareVoucher';
    const DEDUCTION_CATEGORY_MAKING_GOOD = 'MakingGood';
    const DEDUCTION_CATEGORY_POSTGRADUATE_LOAN_DEDUCTIONS = 'PostgraduateLoanDeductions';
    const DEDUCTION_CATEGORY_PRIVATE_USE_PAYMENTS = 'PrivateUsePayments';
    const DEDUCTION_CATEGORY_SALARY_SACRIFICE = 'SalarySacrifice';
    const DEDUCTION_CATEGORY_STAKEHOLDER_PENSION = 'StakeholderPension';
    const DEDUCTION_CATEGORY_STAKEHOLDER_PENSION_POST_TAX = 'StakeholderPensionPostTax';
    const DEDUCTION_CATEGORY_STUDENT_LOAN_DEDUCTIONS = 'StudentLoanDeductions';
    const DEDUCTION_CATEGORY_UK_OTHER = 'UkOther';
    const CALCULATION_TYPE_FIXED_AMOUNT = 'FixedAmount';
    const CALCULATION_TYPE_PERCENTAGE_OF_GROSS = 'PercentageOfGross';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getDeductionCategoryAllowableValues()
    {
        return [
            self::DEDUCTION_CATEGORY_CAPITAL_CONTRIBUTIONS,
            self::DEDUCTION_CATEGORY_CHILD_CARE_VOUCHER,
            self::DEDUCTION_CATEGORY_MAKING_GOOD,
            self::DEDUCTION_CATEGORY_POSTGRADUATE_LOAN_DEDUCTIONS,
            self::DEDUCTION_CATEGORY_PRIVATE_USE_PAYMENTS,
            self::DEDUCTION_CATEGORY_SALARY_SACRIFICE,
            self::DEDUCTION_CATEGORY_STAKEHOLDER_PENSION,
            self::DEDUCTION_CATEGORY_STAKEHOLDER_PENSION_POST_TAX,
            self::DEDUCTION_CATEGORY_STUDENT_LOAN_DEDUCTIONS,
            self::DEDUCTION_CATEGORY_UK_OTHER,
        ];
    }
    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getCalculationTypeAllowableValues()
    {
        return [
            self::CALCULATION_TYPE_FIXED_AMOUNT,
            self::CALCULATION_TYPE_PERCENTAGE_OF_GROSS,
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
        $this->container['deduction_id'] = isset($data['deduction_id']) ? $data['deduction_id'] : null;
        $this->container['deduction_name'] = isset($data['deduction_name']) ? $data['deduction_name'] : null;
        $this->container['deduction_category'] = isset($data['deduction_category']) ? $data['deduction_category'] : null;
        $this->container['liability_account_id'] = isset($data['liability_account_id']) ? $data['liability_account_id'] : null;
        $this->container['current_record'] = isset($data['current_record']) ? $data['current_record'] : null;
        $this->container['standard_amount'] = isset($data['standard_amount']) ? $data['standard_amount'] : null;
        $this->container['reduces_super_liability'] = isset($data['reduces_super_liability']) ? $data['reduces_super_liability'] : null;
        $this->container['reduces_tax_liability'] = isset($data['reduces_tax_liability']) ? $data['reduces_tax_liability'] : null;
        $this->container['calculation_type'] = isset($data['calculation_type']) ? $data['calculation_type'] : null;
        $this->container['percentage'] = isset($data['percentage']) ? $data['percentage'] : null;
        $this->container['subject_to_nic'] = isset($data['subject_to_nic']) ? $data['subject_to_nic'] : null;
        $this->container['subject_to_tax'] = isset($data['subject_to_tax']) ? $data['subject_to_tax'] : null;
        $this->container['is_reduced_by_basic_rate'] = isset($data['is_reduced_by_basic_rate']) ? $data['is_reduced_by_basic_rate'] : null;
        $this->container['apply_to_pension_calculations'] = isset($data['apply_to_pension_calculations']) ? $data['apply_to_pension_calculations'] : null;
        $this->container['is_calculating_on_qualifying_earnings'] = isset($data['is_calculating_on_qualifying_earnings']) ? $data['is_calculating_on_qualifying_earnings'] : null;
        $this->container['is_pension'] = isset($data['is_pension']) ? $data['is_pension'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['deduction_name'] === null) {
            $invalidProperties[] = "'deduction_name' can't be null";
        }
        $allowedValues = $this->getDeductionCategoryAllowableValues();
        if (!is_null($this->container['deduction_category']) && !in_array($this->container['deduction_category'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'deduction_category', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['liability_account_id'] === null) {
            $invalidProperties[] = "'liability_account_id' can't be null";
        }
        $allowedValues = $this->getCalculationTypeAllowableValues();
        if (!is_null($this->container['calculation_type']) && !in_array($this->container['calculation_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'calculation_type', must be one of '%s'",
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
     * Gets deduction_id
     *
     * @return string|null
     */
    public function getDeductionId()
    {
        return $this->container['deduction_id'];
    }

    /**
     * Sets deduction_id
     *
     * @param string|null $deduction_id The Xero identifier for Deduction
     *
     * @return $this
     */
    public function setDeductionId($deduction_id)
    {

        $this->container['deduction_id'] = $deduction_id;

        return $this;
    }



    /**
     * Gets deduction_name
     *
     * @return string
     */
    public function getDeductionName()
    {
        return $this->container['deduction_name'];
    }

    /**
     * Sets deduction_name
     *
     * @param string $deduction_name Name of the deduction
     *
     * @return $this
     */
    public function setDeductionName($deduction_name)
    {

        $this->container['deduction_name'] = $deduction_name;

        return $this;
    }



    /**
     * Gets deduction_category
     *
     * @return string|null
     */
    public function getDeductionCategory()
    {
        return $this->container['deduction_category'];
    }

    /**
     * Sets deduction_category
     *
     * @param string|null $deduction_category Deduction Category type
     *
     * @return $this
     */
    public function setDeductionCategory($deduction_category)
    {
        $allowedValues = $this->getDeductionCategoryAllowableValues();
        if (!is_null($deduction_category) && !in_array($deduction_category, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'deduction_category', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }

        $this->container['deduction_category'] = $deduction_category;

        return $this;
    }



    /**
     * Gets liability_account_id
     *
     * @return string
     */
    public function getLiabilityAccountId()
    {
        return $this->container['liability_account_id'];
    }

    /**
     * Sets liability_account_id
     *
     * @param string $liability_account_id Xero identifier for Liability Account
     *
     * @return $this
     */
    public function setLiabilityAccountId($liability_account_id)
    {

        $this->container['liability_account_id'] = $liability_account_id;

        return $this;
    }



    /**
     * Gets current_record
     *
     * @return bool|null
     */
    public function getCurrentRecord()
    {
        return $this->container['current_record'];
    }

    /**
     * Sets current_record
     *
     * @param bool|null $current_record Identifier of a record is active or not.
     *
     * @return $this
     */
    public function setCurrentRecord($current_record)
    {

        $this->container['current_record'] = $current_record;

        return $this;
    }



    /**
     * Gets standard_amount
     *
     * @return double|null
     */
    public function getStandardAmount()
    {
        return $this->container['standard_amount'];
    }

    /**
     * Sets standard_amount
     *
     * @param double|null $standard_amount Standard amount of the deduction
     *
     * @return $this
     */
    public function setStandardAmount($standard_amount)
    {

        $this->container['standard_amount'] = $standard_amount;

        return $this;
    }



    /**
     * Gets reduces_super_liability
     *
     * @return bool|null
     */
    public function getReducesSuperLiability()
    {
        return $this->container['reduces_super_liability'];
    }

    /**
     * Sets reduces_super_liability
     *
     * @param bool|null $reduces_super_liability Identifier of reduces super liability
     *
     * @return $this
     */
    public function setReducesSuperLiability($reduces_super_liability)
    {

        $this->container['reduces_super_liability'] = $reduces_super_liability;

        return $this;
    }



    /**
     * Gets reduces_tax_liability
     *
     * @return bool|null
     */
    public function getReducesTaxLiability()
    {
        return $this->container['reduces_tax_liability'];
    }

    /**
     * Sets reduces_tax_liability
     *
     * @param bool|null $reduces_tax_liability Identifier of reduces tax liability
     *
     * @return $this
     */
    public function setReducesTaxLiability($reduces_tax_liability)
    {

        $this->container['reduces_tax_liability'] = $reduces_tax_liability;

        return $this;
    }



    /**
     * Gets calculation_type
     *
     * @return string|null
     */
    public function getCalculationType()
    {
        return $this->container['calculation_type'];
    }

    /**
     * Sets calculation_type
     *
     * @param string|null $calculation_type determine the calculation type whether fixed amount or percentage of gross
     *
     * @return $this
     */
    public function setCalculationType($calculation_type)
    {
        $allowedValues = $this->getCalculationTypeAllowableValues();
        if (!is_null($calculation_type) && !in_array($calculation_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'calculation_type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }

        $this->container['calculation_type'] = $calculation_type;

        return $this;
    }



    /**
     * Gets percentage
     *
     * @return double|null
     */
    public function getPercentage()
    {
        return $this->container['percentage'];
    }

    /**
     * Sets percentage
     *
     * @param double|null $percentage Percentage of gross
     *
     * @return $this
     */
    public function setPercentage($percentage)
    {

        $this->container['percentage'] = $percentage;

        return $this;
    }



    /**
     * Gets subject_to_nic
     *
     * @return bool|null
     */
    public function getSubjectToNic()
    {
        return $this->container['subject_to_nic'];
    }

    /**
     * Sets subject_to_nic
     *
     * @param bool|null $subject_to_nic Identifier of subject To NIC
     *
     * @return $this
     */
    public function setSubjectToNic($subject_to_nic)
    {

        $this->container['subject_to_nic'] = $subject_to_nic;

        return $this;
    }



    /**
     * Gets subject_to_tax
     *
     * @return bool|null
     */
    public function getSubjectToTax()
    {
        return $this->container['subject_to_tax'];
    }

    /**
     * Sets subject_to_tax
     *
     * @param bool|null $subject_to_tax Identifier of subject To Tax
     *
     * @return $this
     */
    public function setSubjectToTax($subject_to_tax)
    {

        $this->container['subject_to_tax'] = $subject_to_tax;

        return $this;
    }



    /**
     * Gets is_reduced_by_basic_rate
     *
     * @return bool|null
     */
    public function getIsReducedByBasicRate()
    {
        return $this->container['is_reduced_by_basic_rate'];
    }

    /**
     * Sets is_reduced_by_basic_rate
     *
     * @param bool|null $is_reduced_by_basic_rate Identifier of reduced by basic rate applicable or not
     *
     * @return $this
     */
    public function setIsReducedByBasicRate($is_reduced_by_basic_rate)
    {

        $this->container['is_reduced_by_basic_rate'] = $is_reduced_by_basic_rate;

        return $this;
    }



    /**
     * Gets apply_to_pension_calculations
     *
     * @return bool|null
     */
    public function getApplyToPensionCalculations()
    {
        return $this->container['apply_to_pension_calculations'];
    }

    /**
     * Sets apply_to_pension_calculations
     *
     * @param bool|null $apply_to_pension_calculations Identifier for apply to pension calculations
     *
     * @return $this
     */
    public function setApplyToPensionCalculations($apply_to_pension_calculations)
    {

        $this->container['apply_to_pension_calculations'] = $apply_to_pension_calculations;

        return $this;
    }



    /**
     * Gets is_calculating_on_qualifying_earnings
     *
     * @return bool|null
     */
    public function getIsCalculatingOnQualifyingEarnings()
    {
        return $this->container['is_calculating_on_qualifying_earnings'];
    }

    /**
     * Sets is_calculating_on_qualifying_earnings
     *
     * @param bool|null $is_calculating_on_qualifying_earnings Identifier of calculating on qualifying earnings
     *
     * @return $this
     */
    public function setIsCalculatingOnQualifyingEarnings($is_calculating_on_qualifying_earnings)
    {

        $this->container['is_calculating_on_qualifying_earnings'] = $is_calculating_on_qualifying_earnings;

        return $this;
    }



    /**
     * Gets is_pension
     *
     * @return bool|null
     */
    public function getIsPension()
    {
        return $this->container['is_pension'];
    }

    /**
     * Sets is_pension
     *
     * @param bool|null $is_pension Identifier of applicable for pension or not
     *
     * @return $this
     */
    public function setIsPension($is_pension)
    {

        $this->container['is_pension'] = $is_pension;

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
            PayrollUkObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }
}


