<?php
namespace KREDA\Sphere\Common\Frontend\Text\Structure;

use KREDA\Sphere\Common\Frontend\Text\AbstractBackground;
use KREDA\Sphere\Common\Frontend\Text\AbstractText;
use MOC\V\Component\Template\Exception\TemplateTypeException;

class BackgroundSuccess extends AbstractBackground
{

    /**
     * @param string $Background
     *
     * @throws TemplateTypeException
     */
    function __construct( AbstractText $Background )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/BackgroundSuccess.twig' );
        $this->Template->setVariable( 'ElementBackground', $Background );
    }

}
