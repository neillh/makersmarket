<?php

namespace AustraliaPost\BoxPacker;

use WPRuby\AustraliaPost\DVDoug\BoxPacker\Box;

class Australia_Post_Box implements Box
{
    private $description;
    private $OuterWidth;
    private $OuterLength;
    private $OuterDepth;
    private $EmptyWeight;
    private $InnerWidth;
    private $InnerLength;
    private $InnerDepth;
    private $MaxWeight;
    private $isSatchel = false;
    private $isFake = false;

    public function __construct(
    	$description,
	    $OuterLength,
	    $OuterWidth,
	    $OuterDepth,
	    $EmptyWeight,
	    $InnerLength,
	    $InnerWidth,
	    $InnerDepth,
	    $MaxWeight
    )
    {
        $this->description = $description;
        $this->OuterWidth = intval($OuterWidth);
        $this->OuterLength = intval($OuterLength);
        $this->OuterDepth = intval($OuterDepth);
        $this->EmptyWeight = intval($EmptyWeight);
        $this->InnerWidth = intval($InnerWidth);
        $this->InnerLength = intval($InnerLength);
        $this->InnerDepth = intval($InnerDepth);
        $this->MaxWeight = intval($MaxWeight);
    }

    /**
     * Reference for box type (e.g. SKU or description)
     * @return string
     */
    public function getReference()
    {
        return $this->description;
    }

    /**
     * Outer width in mm
     * @return int
     */
    public function getOuterWidth()
    {
        return $this->OuterWidth;
    }

    /**
     * Outer length in mm
     * @return int
     */
    public function getOuterLength()
    {
        return $this->OuterLength;
    }

    /**
     * Outer depth in mm
     * @return int
     */
    public function getOuterDepth()
    {
        return $this->OuterDepth;
    }

    /**
     * Empty weight in g
     * @return int
     */
    public function getEmptyWeight()
    {
        return $this->EmptyWeight;
    }

    /**
     * Inner width in mm
     * @return int
     */
    public function getInnerWidth()
    {
        return $this->InnerWidth;
    }

    /**
     * Inner length in mm
     * @return int
     */
    public function getInnerLength()
    {
        return $this->InnerLength;
    }

    /**
     * Inner depth in mm
     * @return int
     */
    public function getInnerDepth()
    {
        return $this->InnerDepth;
    }

    /**
     * Total inner volume of packing in mm^3
     * @return int
     */
    public function getInnerVolume()
    {
        return $this->InnerWidth * $this->InnerLength * $this->InnerDepth;
    }

    /**
     * Max weight the packaging can hold in g
     * @return int
     */
    public function getMaxWeight()
    {
        return $this->MaxWeight;
    }

	/**
	 * @return boolean
	 */
	public function isSatchel()
	{
		return $this->isSatchel;
	}

	/**
	 * @param boolean $isSatchel
	 *
	 * @return Australia_Post_Box
	 */
	public function setIsSatchel( $isSatchel ) {
		$this->isSatchel = $isSatchel;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isFake(): bool
	{
		return $this->isFake;
	}

	/**
	 * @param boolean $isFake
	 *
	 * @return Australia_Post_Box
	 */
	public function setIsFake( bool $isFake ): self {
		$this->isFake = $isFake;

		return $this;
	}

	public function toArray(): array
	{
		return [
			'length' => $this->getOuterLength(),
			'width' => $this->getOuterWidth(),
			'depth' => $this->getOuterDepth(),
			'max_weight' => $this->getMaxWeight(),
		];
	}


}
