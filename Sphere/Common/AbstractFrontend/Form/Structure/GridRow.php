<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractForm;

/**
 * Class GridRow
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form\Structure
 */
class GridRow extends AbstractForm
{

    /** @var GridCol[] $GridColList */
    private $GridColList = array();

    /**
     * @param GridCol|GridCol[] $GridColList
     */
    function __construct( $GridColList )
    {

        if (!is_array( $GridColList )) {
            $GridColList = array( $GridColList );
        }
        $this->GridColList = $GridColList;
    }

    /**
     * @return GridCol[]
     */
    public function getColList()
    {

        return $this->GridColList;
    }
}
