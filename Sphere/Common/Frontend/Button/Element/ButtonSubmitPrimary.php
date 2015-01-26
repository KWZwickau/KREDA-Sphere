<?php
namespace KREDA\Sphere\Common\Frontend\Button\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\Frontend\Button\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class ButtonSubmitPrimary
 *
 * @package KREDA\Sphere\Common\Frontend\Button\Element
 */
class ButtonSubmitPrimary extends AbstractElement
{

    /**
     * @param string       $Name
     * @param AbstractIcon $Icon
     *
     * @throws TemplateTypeException
     */
    function __construct( $Name, AbstractIcon $Icon = null )
    {

        parent::__construct( $Name );

        $this->Template = $this->extensionTemplate( __DIR__.'/ButtonSubmitPrimary.twig' );

        $this->Template->setVariable( 'ElementName', $Name );
        if (null != $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
    }

}
