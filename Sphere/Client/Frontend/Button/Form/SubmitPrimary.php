<?php
namespace KREDA\Sphere\Client\Frontend\Button\Form;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Button\AbstractType;

/**
 * Class SubmitPrimary
 *
 * @package KREDA\Sphere\Client\Frontend\Button\Form
 */
class SubmitPrimary extends AbstractType
{

    /**
     * @param string       $Name
     * @param AbstractIcon $Icon
     */
    public function __construct( $Name, AbstractIcon $Icon = null )
    {

        parent::__construct( $Name );
        $this->Template = $this->extensionTemplate( __DIR__.'/SubmitPrimary.twig' );
        $this->Template->setVariable( 'ElementName', $Name );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
    }
}
