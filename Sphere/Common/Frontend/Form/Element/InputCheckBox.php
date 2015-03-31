<?php
namespace KREDA\Sphere\Common\Frontend\Form\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\Frontend\Form\AbstractElement;

/**
 * Class InputCheckBox
 *
 * @package KREDA\Sphere\Common\Frontend\Form\Element
 */
class InputCheckBox extends AbstractElement
{

    /**
     * @param string       $Name
     * @param null|string  $Label
     * @param AbstractIcon $Icon
     */
    function __construct(
        $Name,
        $Label = '&nbsp',
        AbstractIcon $Icon = null
    ) {

        parent::__construct( $Name );

        $this->Template = $this->extensionTemplate( __DIR__.'/InputCheckBox.twig' );

        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        $this->Template->setVariable( 'ElementHash', sha1( $Name.$Label.( new \DateTime() )->getTimestamp() ) );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }

        $this->setPostValue( $this->Template, $Name, 'ElementValue' );
    }

}
