<?php
namespace KREDA\Sphere\Common\Frontend\Form\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\Frontend\Form\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class InputCompleter
 *
 * @package KREDA\Sphere\Common\Frontend\Form\Element
 */
class InputCompleter extends AbstractElement
{

    /**
     * @param string       $Name
     * @param string       $Label
     * @param string       $Placeholder
     * @param array        $Data array( value, value, .. )
     * @param AbstractIcon $Icon
     *
     *
     *
     * @throws TemplateTypeException
     */
    function __construct(
        $Name,
        $Label = '',
        $Placeholder = '',
        $Data = array(),
        AbstractIcon $Icon = null
    ) {

        parent::__construct( $Name );

        $this->Template = $this->extensionTemplate( __DIR__.'/InputCompleter.twig' );

        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        $this->Template->setVariable( 'ElementPlaceholder', $Placeholder );
        $this->Template->setVariable( 'ElementData', $Data );
        if (null != $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }

        if (isset( $_REQUEST[$Name] ) && !empty( $_REQUEST[$Name] ) && is_string( $_REQUEST[$Name] )) {
            $this->Template->setVariable( 'ElementValue', $_REQUEST[$Name] );
        }

    }

}
