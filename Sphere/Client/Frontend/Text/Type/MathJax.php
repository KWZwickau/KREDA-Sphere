<?php
namespace KREDA\Sphere\Client\Frontend\Text\Type;

use KREDA\Sphere\Client\Frontend\Text\AbstractType;

/**
 * Class MathJax
 *
 * @package KREDA\Sphere\Client\Frontend\Text\Type
 */
class MathJax extends AbstractType
{

    /**
     * @param string $Formula
     */
    public function __construct( $Formula )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/MathJax.twig' );
        $this->Template->setVariable( 'MathJax', $Formula );
    }
}
