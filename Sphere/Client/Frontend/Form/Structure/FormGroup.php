<?php
namespace KREDA\Sphere\Client\Frontend\Form\Structure;

use KREDA\Sphere\Client\Frontend\Form\AbstractType;

/**
 * Class FormGroup
 *
 * @package KREDA\Sphere\Client\Frontend\Form\Structure
 */
class FormGroup extends AbstractType
{

    /** @var FormRow[] $FormRow */
    private $FormRow = array();
    /** @var FormTitle $FormTitle */
    private $FormTitle = null;

    /**
     * @param FormRow|FormRow[] $FormRow
     * @param FormTitle         $FormTitle
     */
    function __construct( $FormRow, FormTitle $FormTitle = null )
    {

        if (!is_array( $FormRow )) {
            $FormRow = array( $FormRow );
        }
        $this->FormRow = $FormRow;
        $this->FormTitle = $FormTitle;
    }

    /**
     * @return FormTitle
     */
    public function getFormTitle()
    {

        return $this->FormTitle;
    }

    /**
     * @return FormRow[]
     */
    public function getFormRow()
    {

        return $this->FormRow;
    }
}
