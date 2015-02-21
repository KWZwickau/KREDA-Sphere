<?php
namespace KREDA\Sphere\Common\Frontend\Layout\Structure;

use KREDA\Sphere\Common\Frontend\Layout\AbstractLayout;

/**
 * Class GridLayoutGroup
 *
 * @package KREDA\Sphere\Common\Frontend\Layout
 */
class GridLayoutGroup extends AbstractLayout
{

    /** @var GridLayoutRow[] $GridLayoutRow */
    private $GridLayoutRow = array();
    /** @var string $GridLayoutTitle */
    private $GridLayoutTitle = '';

    /**
     * @param GridLayoutRow|GridLayoutRow[] $GridLayoutRow
     * @param GridLayoutTitle               $GridLayoutTitle
     */
    function __construct( $GridLayoutRow, GridLayoutTitle $GridLayoutTitle = null )
    {

        if (!is_array( $GridLayoutRow )) {
            $GridLayoutRow = array( $GridLayoutRow );
        }
        $this->GridLayoutRow = $GridLayoutRow;
        $this->GridLayoutTitle = $GridLayoutTitle;
    }

    /**
     * @return string
     */
    public function getTitle()
    {

        return $this->GridLayoutTitle;
    }

    /**
     * @return GridLayoutRow[]
     */
    public function getLayoutRow()
    {

        return $this->GridLayoutRow;
    }
}
