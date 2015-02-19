<?php
namespace KREDA\Sphere\Common\Frontend\Form\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\Frontend\Form\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class InputTextArea
 *
 * @package KREDA\Sphere\Common\Frontend\Form\Element
 */
class InputTextArea extends AbstractElement
{

    /**
     * @param string       $Name
     * @param null|string  $Placeholder
     * @param null|string  $Label
     * @param AbstractIcon $Icon
     *
     * @throws TemplateTypeException
     */
    function __construct(
        $Name,
        $Placeholder = '',
        $Label = '',
        AbstractIcon $Icon = null
    ) {

        parent::__construct( $Name );

        $this->Template = $this->extensionTemplate( __DIR__.'/InputTextArea.twig' );

        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        $this->Template->setVariable( 'ElementPlaceholder', $Placeholder );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }

        $this->setPostValue( $this->Template, $Name, 'ElementValue' );
    }

}
