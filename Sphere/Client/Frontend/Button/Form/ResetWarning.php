<?php
namespace KREDA\Sphere\Client\Frontend\Button\Form;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Button\AbstractType;

/**
 * Class ResetWarning
 *
 * @package KREDA\Sphere\Client\Frontend\Button\Form
 */
class ResetWarning extends AbstractType
{

    /**
     * @param string       $Name
     * @param AbstractIcon $Icon
     */
    function __construct( $Name, AbstractIcon $Icon = null )
    {

        parent::__construct( $Name );
        $this->Template = $this->extensionTemplate( __DIR__.'/ResetWarning.twig' );
        $this->Template->setVariable( 'ElementName', $Name );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
    }
}
