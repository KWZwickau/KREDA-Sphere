<?php
namespace KREDA\Sphere\Client\Frontend\Text\Type;

use KREDA\Sphere\Client\Frontend\Text\AbstractType;

/**
 * Class Muted
 *
 * @package KREDA\Sphere\Client\Frontend\Text\Type
 */
class Muted extends AbstractType
{

    /**
     * @param string $Content
     */
    function __construct( $Content )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Muted.twig' );
        $this->Template->setVariable( 'ElementText', $Content );
    }

}
