<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Button\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\AbstractFrontend\Button\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class ButtonResetWarning
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Button\Element
 */
class ButtonResetWarning extends AbstractElement
{

    /**
     * @param string       $Name
     * @param string       $Path
     * @param AbstractIcon $Icon
     *
     * @throws TemplateTypeException
     */
    function __construct( $Name, AbstractIcon $Icon = null )
    {

        parent::__construct( $Name );

        $this->Template = Template::getTemplate( __DIR__.'/ButtonResetWarning.twig' );

        $this->Template->setVariable( 'ElementName', $Name );
        if (null != $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
    }

}
