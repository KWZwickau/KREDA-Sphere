<?php
namespace KREDA\Sphere\Common\Frontend\Form\Structure;

use KREDA\Sphere\Common\Frontend\Form\AbstractForm;

/**
 * Class GridFormGroup
 *
 * @package KREDA\Sphere\Common\Frontend\Form\Structure
 */
class GridFormGroup extends AbstractForm
{

    /** @var GridFormRow[] $GridRowList */
    private $GridRowList = array();
    /** @var string $GridTitle */
    private $GridTitle = '';

    /**
     * @param GridFormRow|GridFormRow[] $GridFormRowList
     * @param GridFormTitle             $GridFormTitle
     */
    function __construct( $GridFormRowList, $GridFormTitle = null )
    {

        if (!is_array( $GridFormRowList )) {
            $GridFormRowList = array( $GridFormRowList );
        }
        $this->GridRowList = $GridFormRowList;
        $this->GridTitle = $GridFormTitle;
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
