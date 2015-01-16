<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Table\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Table\AbstractTable;

/**
 * Class GridTableHead
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Table\Structure
 */
class GridTableHead extends AbstractTable
{

    /** @var GridTableRow[] $GridRowList */
    private $GridRowList = array();

    /**
     * @param GridTableRow|GridTableRow[] $GridRowList
     */
    function __construct( $GridRowList )
    {

        if (!is_array( $GridRowList )) {
            $GridRowList = array( $GridRowList );
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
