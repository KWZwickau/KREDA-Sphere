<?php
namespace KREDA\Sphere\Client\Frontend\Form\Structure;

use KREDA\Sphere\Client\Frontend\Form\AbstractType;

/**
 * Class FormRow
 *
 * @package KREDA\Sphere\Client\Frontend\Form\Structure
 */
class FormRow extends AbstractType
{

    /** @var FormColumn[] $FormColumn */
    private $FormColumn = array();

    /**
     * @param FormColumn|FormColumn[] $FormColumn
     */
    function __construct( $FormColumn )
    {

        if (!is_array( $FormColumn )) {
            $FormColumn = array( $FormColumn );
        }
        $this->FormColumn = $FormColumn;
    }

    /**
     * @return FormColumn[]
     */
    public function getFormColumn()
    {

        return $this->FormColumn;
    }
}
