<?php
/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */

namespace WPRuby\AustraliaPost\DVDoug\BoxPacker\Test;

use WPRuby\AustraliaPost\DVDoug\BoxPacker\Box;
use WPRuby\AustraliaPost\DVDoug\BoxPacker\ConstrainedItem;
use WPRuby\AustraliaPost\DVDoug\BoxPacker\ItemList;

class TestConstrainedTestItem extends TestItem implements ConstrainedItem
{
    /**
     * @var int
     */
    public static $limit = 3;

    /**
     * @param ItemList $alreadyPackedItems
     * @param TestBox  $box
     *
     * @return bool
     */
    public function canBePackedInBox(ItemList $alreadyPackedItems, Box $box)
    {
        $alreadyPackedType = array_filter(
            $alreadyPackedItems->asArray(),
            function (TestItem $item) {
                return $item->getDescription() === $this->getDescription();
            }
        );

        return count($alreadyPackedType) + 1 <= static::$limit;
    }
}
