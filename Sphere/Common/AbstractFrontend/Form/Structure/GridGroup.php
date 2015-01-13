<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractForm;

/**
 * Class GridGroup
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form\Structure
 */
class GridGroup extends AbstractForm
{

    /** @var GridRow[] $GridRowList */
    private $GridRowList = array();
    /** @var string $GridTitle */
    private $GridTitle = '';

    /**
     * @param GridRow|GridRow[] $GridRowList
     * @param string            $GridTitle
     */
    function __construct( $GridRowList, $GridTitle = '' )
    {

        if (!is_array( $GridRowList )) {
            $GridRowList = array( $GridRowList );
        }
        $this->GridRowList = $GridRowList;
        $this->GridTitle = $GridTitle;
    }

    /**
     * @return string
     */
    public function getTitle()
    {

        return $this->GridTitle;
    }

    /**
     * @return GridRow[]
     */
    public function getRowList()
    {

        return $this->GridRowList;
    }
}
