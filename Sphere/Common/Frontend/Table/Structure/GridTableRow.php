<?php
namespace KREDA\Sphere\Common\Frontend\Table\Structure;

use KREDA\Sphere\Common\Frontend\Form\AbstractForm;

/**
 * Class GridTableRow
 *
 * @package KREDA\Sphere\Common\Frontend\Form\Structure
 */
class GridTableRow extends AbstractForm
{

    /** @var GridTableCol[] $GridColList */
    private $GridColList = array();

    /**
     * @param GridTableCol|GridTableCol[] $GridColList
     */
    function __construct( $GridColList )
    {

        if (!is_array( $GridColList )) {
            $GridColList = array( $GridColList );
        }
        $this->GridColList = $GridColList;
    }

    /**
     * @return GridTableCol[]
     */
    public function getColList()
    {

        return $this->GridColList;
    }
}
