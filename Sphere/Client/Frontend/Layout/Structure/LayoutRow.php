<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Structure;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutRow
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Structure
 */
class LayoutRow extends AbstractType
{

    /** @var LayoutColumn[] $LayoutColumn */
    private $LayoutColumn = array();

    /**
     * @param LayoutColumn|LayoutColumn[] $LayoutColumn
     */
    public function __construct( $LayoutColumn )
    {

        if (!is_array( $LayoutColumn )) {
            $LayoutColumn = array( $LayoutColumn );
        }
        $this->LayoutColumn = $LayoutColumn;
    }

    /**
     * @param LayoutColumn $layoutColumn
     *
     * @return LayoutRow
     */
    public function addColumn( LayoutColumn $layoutColumn )
    {

        array_push( $this->LayoutColumn, $layoutColumn );
        return $this;
    }

    /**
     * @return LayoutColumn[]
     */
    public function getLayoutColumn()
    {

        return $this->LayoutColumn;
    }
}
