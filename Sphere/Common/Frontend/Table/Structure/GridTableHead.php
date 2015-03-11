<?php
namespace KREDA\Sphere\Common\Frontend\Table\Structure;

use KREDA\Sphere\Common\Frontend\Table\AbstractTable;

/**
 * Class GridTableHead
 *
 * @package KREDA\Sphere\Common\Frontend\Table\Structure
 */
class GridTableHead extends AbstractTable
{

    /** @var GridTableRow[] $GridRowList */
    private $GridRowList = array();

    /**
     * @param null|GridTableRow|GridTableRow[] $GridRowList
     */
    function __construct( $GridRowList = null )
    {

        if (null !== $GridRowList && !is_array( $GridRowList )) {
            $GridRowList = array( $GridRowList );
        } elseif (null === $GridRowList) {
            $GridRowList = array();
        }
        $this->GridRowList = $GridRowList;
    }

    /**
     * @return GridTableRow[]
     */
    public function getRowList()
    {

        return $this->GridRowList;
    }
}
