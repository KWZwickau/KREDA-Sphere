<?php
namespace KREDA\Sphere\Common\Frontend\Complex\Element;

use KREDA\Sphere\Common\Frontend\Complex\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class MathJax
 *
 * @package KREDA\Sphere\Common\Frontend\Complex\Element
 */
class MathJax extends AbstractElement
{


    /**
     * @param string $Formula
     *
     * @throws TemplateTypeException
     */
    function __construct( $Formula )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/MathJax.twig' );
        $this->Template->setVariable( 'MathJax', $Formula );
    }
}
