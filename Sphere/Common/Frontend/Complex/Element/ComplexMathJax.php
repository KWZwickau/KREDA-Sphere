<?php
namespace KREDA\Sphere\Common\Frontend\Complex\Element;

use KREDA\Sphere\Common\Frontend\Complex\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class ComplexMathJax
 *
 * @package KREDA\Sphere\Common\Frontend\Complex\Element
 */
class ComplexMathJax extends AbstractElement
{


    /**
     * @param string $Formula
     *
     * @throws TemplateTypeException
     */
    function __construct( $Formula )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/ComplexMathJax.twig' );
        $this->Template->setVariable( 'MathJax', $Formula );
    }
}
