<?php
namespace KREDA\Sphere\Client\Frontend\Input\Type;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Input\AbstractType;

/**
 * Class TextArea
 *
 * @package KREDA\Sphere\Client\Frontend\Input\Type
 */
class TextArea extends AbstractType
{

    /**
     * @param string       $Name
     * @param null|string  $Placeholder
     * @param null|string  $Label
     * @param AbstractIcon $Icon
     */
    public function __construct(
        $Name,
        $Placeholder = '',
        $Label = '',
        AbstractIcon $Icon = null
    ) {

        parent::__construct( $Name );
        $this->Template = $this->extensionTemplate( __DIR__.'/TextArea.twig' );
        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        $this->Template->setVariable( 'ElementPlaceholder', $Placeholder );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
        $this->setPostValue( $this->Template, $Name, 'ElementValue' );
    }
}
