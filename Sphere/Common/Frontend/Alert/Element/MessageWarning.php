<?php
namespace KREDA\Sphere\Common\Frontend\Alert\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\Frontend\Alert\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class MessageWarning
 *
 * @package KREDA\Sphere\Common\Frontend\Button\Element
 */
class MessageWarning extends AbstractElement
{

    /**
     * @param string       $Message
     * @param AbstractIcon $Icon
     *
     * @throws TemplateTypeException
     */
    function __construct( $Message, AbstractIcon $Icon = null )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/MessageWarning.twig' );
        $this->Template->setVariable( 'ElementMessage', $Message );
        if (null != $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
    }

}
