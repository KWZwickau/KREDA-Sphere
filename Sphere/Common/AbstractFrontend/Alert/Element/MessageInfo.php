<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Alert\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\AbstractFrontend\Alert\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class MessageInfo
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Button\Element
 */
class MessageInfo extends AbstractElement
{

    /**
     * @param string       $Message
     * @param AbstractIcon $Icon
     *
     * @throws TemplateTypeException
     */
    function __construct( $Message, AbstractIcon $Icon = null )
    {

        $this->Template = Template::getTemplate( __DIR__.'/MessageInfo.twig' );
        $this->Template->setVariable( 'ElementMessage', $Message );
        if (null != $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
    }

}
