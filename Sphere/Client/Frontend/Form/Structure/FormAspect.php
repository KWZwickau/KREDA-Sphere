<?php
namespace KREDA\Sphere\Client\Frontend\Form\Structure;

/**
 * Class FormAspect
 *
 * @package KREDA\Sphere\Client\Frontend\Form\Structure
 */
class FormAspect extends FormTitle
{

    /** @var string $Title */
    private $Title = '';
    /** @var string $Description */
    private $Description = '';

    /**
     * @param string $Title
     * @param string $Description
     */
    public function __construct( $Title, $Description = '' )
    {

        $this->Title = $Title;
        $this->Description = $Description;
    }

    /**
     * @return string
     */
    function __toString()
    {

        if (empty( $this->Description )) {
            return '<h5>'.$this->Title.'</h5>';
        } else {
            return '<h5>'.$this->Title.' <small>'.$this->Description.'</small></h5>';
        }
    }
}
