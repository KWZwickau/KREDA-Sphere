<?php
namespace KREDA\Sphere\Client\Frontend\Input\Type;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Input\AbstractType;

/**
 * Class CheckBox
 *
 * @package KREDA\Sphere\Client\Frontend\Input\Type
 */
class CheckBox extends AbstractType
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
        $this->Template = $this->extensionTemplate( __DIR__.'/CheckBox.twig' );
        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        $this->Template->setVariable( 'ElementHash', sha1( $Name.$Label.( new \DateTime() )->getTimestamp() ) );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
        $this->setPostValue( $this->Template, $Name, 'ElementValue' );
    }
}
