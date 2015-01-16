<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractForm;

/**
 * Class GridFormRow
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form\Structure
 */
class GridFormRow extends AbstractForm
{

    /** @var GridFormCol[] $GridColList */
    private $GridColList = array();

    /**
     * @param GridFormCol|GridFormCol[] $GridColList
     */
    function __construct( $GridColList )
    {

        if (!is_array( $GridColList )) {
            $GridColList = array( $GridColList );
        }
        $this->GridColList = $GridColList;
    }

    /**
     * @return GridFormCol[]
     */
    public function getColList()
    {

        return $this->GridColList;
    }
}
