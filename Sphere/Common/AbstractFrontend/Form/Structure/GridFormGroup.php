<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractForm;

/**
 * Class GridFormGroup
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form\Structure
 */
class GridFormGroup extends AbstractForm
{

    /** @var GridFormRow[] $GridRowList */
    private $GridRowList = array();
    /** @var string $GridTitle */
    private $GridTitle = '';

    /**
     * @param GridFormRow|GridFormRow[] $GridRowList
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
     * @return GridFormRow[]
     */
    public function getRowList()
    {

        return $this->GridRowList;
    }
}
