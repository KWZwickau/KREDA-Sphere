<?php
namespace KREDA\Sphere\Client\Frontend\Input\Type;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Input\AbstractType;

/**
 * Class HiddenField
 *
 * @package KREDA\Sphere\Client\Frontend\Input\Type
 */
class HiddenField extends AbstractType
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
        $this->Template = $this->extensionTemplate( __DIR__.'/HiddenField.twig' );
        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        $this->Template->setVariable( 'ElementPlaceholder', $Placeholder );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->setPostValue( $this->Template, $this->getName(), 'ElementValue' );
        return parent::getContent();
    }
}
