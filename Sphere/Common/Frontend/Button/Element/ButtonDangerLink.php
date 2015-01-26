<?php
namespace KREDA\Sphere\Common\Frontend\Button\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\Frontend\Button\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class ButtonDangerLink
 *
 * @package KREDA\Sphere\Common\Frontend\Button\Element
 */
class ButtonDangerLink extends AbstractElement
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

        $this->Template = $this->extensionTemplate( __DIR__.'/ButtonDangerLink.twig' );

        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementPath', $Path );
        if (null != $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }

        $this->Template->setVariable( 'UrlBase', $this->extensionRequest()->getUrlBase() );
    }

}
