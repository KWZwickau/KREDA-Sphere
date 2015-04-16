<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Structure;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutGroup
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Structure
 */
class LayoutGroup extends AbstractType
{

    /** @var LayoutRow[] $LayoutRow */
    private $LayoutRow = array();
    /** @var string $LayoutTitle */
    private $LayoutTitle = '';

    /**
     * @param LayoutRow|LayoutRow[] $LayoutRow
     * @param LayoutTitle           $LayoutTitle
     */
    public function __construct( $LayoutRow, LayoutTitle $LayoutTitle = null )
    {

        if (!is_array( $LayoutRow )) {
            $LayoutRow = array( $LayoutRow );
        }
        $this->LayoutRow = $LayoutRow;
        $this->LayoutTitle = $LayoutTitle;
    }

    /**
     * @return string
     */
    public function getLayoutTitle()
    {

        return $this->LayoutTitle;
    }

    /**
     * @return LayoutRow[]
     */
    public function getLayoutRow()
    {

        return $this->LayoutRow;
    }
}
