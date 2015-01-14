<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Button\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\AbstractFrontend\Button\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class ButtonPrimaryLink
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Button\Element
 */
class ButtonPrimaryLink extends AbstractElement
{

    /**
     * @param string       $Name
     * @param string       $Path
     * @param AbstractIcon $Icon
     *
     * @throws TemplateTypeException
     */
    function __construct( $Name, $Path, AbstractIcon $Icon = null )
    {

        parent::__construct( $Name );

        $this->Template = Template::getTemplate( __DIR__.'/ButtonPrimaryLink.twig' );

        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementPath', $Path );
        if (null != $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }

        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );
    }

}
