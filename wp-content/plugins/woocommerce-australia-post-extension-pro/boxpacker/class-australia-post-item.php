<?php
namespace AustraliaPost\BoxPacker;

use WPRuby\AustraliaPost\DVDoug\BoxPacker\Box;
use WPRuby\AustraliaPost\DVDoug\BoxPacker\ConstrainedPlacementItem;
use WPRuby\AustraliaPost\DVDoug\BoxPacker\PackedItemList;

class Australia_Post_Item  implements ConstrainedPlacementItem
{
    public $description;
    public $width;
    public $length;
    public $depth;
    public $weight;
    public $volume;
    public $keepFlat;
    public $postcode;
    public $value;

    public function __construct($description, $width, $length, $depth, $weight, $keepFlat, $postcode, $value)
    {
        $this->description = $description;
        $this->width = $width;
        $this->length = $length;
        $this->depth = $depth;
        $this->weight = $weight;
        $this->keepFlat = $keepFlat;
        $this->postcode = $postcode;
        $this->value = $value;
        $this->volume = $this->length * $this->width * $this->depth;
    }

	/**
	 * @return mixed
	 */
	public function getPostcode()
    {
    	return $this->postcode;
    }

    /**
    * Item SKU etc
    * @return string
    */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Item width in mm
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Item length in mm
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Item depth in mm
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Item weight in g
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Item volume in mm^3
     * @return int
     */
    public function getVolume()
    {
        return $this->length * $this->width * $this->depth;
    }

    /**
     * Does this item need to be kept flat?
     * XXX not yet used, all items are kept flat
     * @return bool
     */
    public function getKeepFlat()
    {
        return $this->keepFlat;
    }

	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param Box $box
	 * @param PackedItemList $alreadyPackedItems
	 * @param int $proposedX
	 * @param int $proposedY
	 * @param int $proposedZ
	 * @param int $width
	 * @param int $length
	 * @param int $depth
	 *
	 * @return bool
	 */
	public function canBePacked(Box $box, PackedItemList $alreadyPackedItems, $proposedX, $proposedY, $proposedZ, $width, $length, $depth )
	{
		foreach ($alreadyPackedItems as $packedItem) {
			if ($packedItem->getItem()->getPostcode() !== $this->getPostcode()  ) {
				return false;
			}
		}

		return true;
	}
}
