<?php
namespace KREDA\Sphere\Common\Frontend\Layout\Structure;

use KREDA\Sphere\Common\Frontend\Layout\AbstractLayout;

/**
 * Class GridLayoutRow
 *
 * @package KREDA\Sphere\Common\Frontend\Layout
 */
class GridLayoutRow extends AbstractLayout
{

    /** @var GridLayoutCol[] $GridLayoutCol */
    private $GridLayoutCol = array();

    /**
     * @param GridLayoutCol|GridLayoutCol[] $GridLayoutCol
     */
    function __construct( $GridLayoutCol )
    {

        if (!is_array( $GridLayoutCol )) {
            $GridLayoutCol = array( $GridLayoutCol );
        }
        $this->GridLayoutCol = $GridLayoutCol;
    }

    /**
     * @return GridLayoutCol[]
     */
    public function getLayoutCol()
    {

        return $this->GridLayoutCol;
    }
}
