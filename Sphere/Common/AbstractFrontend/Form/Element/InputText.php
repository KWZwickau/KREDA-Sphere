<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class InputText
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form\Element
 */
class InputText extends AbstractElement
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

        $this->Template = Template::getTemplate( __DIR__.'/InputText.twig' );
        $this->Name = $Name;

        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        $this->Template->setVariable( 'ElementPlaceholder', $Placeholder );
        if (null != $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }

        if (isset( $_REQUEST[$Name] ) && !empty( $_REQUEST[$Name] ) && is_string( $_REQUEST[$Name] )) {
            $this->Template->setVariable( 'ElementValue', $_REQUEST[$Name] );
        }

    }

}
