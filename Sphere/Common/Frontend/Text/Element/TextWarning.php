<?php
namespace KREDA\Sphere\Common\Frontend\Text\Element;

use KREDA\Sphere\Common\Frontend\Text\AbstractText;
use MOC\V\Component\Template\Exception\TemplateTypeException;

class TextWarning extends AbstractText
{

    /**
     * @param string $Text
     *
     * @throws TemplateTypeException
     */
    function __construct( $Text )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/TextWarning.twig' );
        $this->Template->setVariable( 'ElementText', $Text );
    }

}
