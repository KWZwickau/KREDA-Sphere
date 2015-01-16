<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Table\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Table\AbstractTable;

/**
 * Class GridTableBody
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Table\Structure
 */
class GridTableBody extends AbstractTable
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
