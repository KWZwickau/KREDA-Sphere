<?php
namespace KREDA\Sphere\Client\Frontend\Text\Type;

use KREDA\Sphere\Client\Frontend\Text\AbstractType;

/**
 * Class Danger
 *
 * @package KREDA\Sphere\Client\Frontend\Text\Type
 */
class Danger extends AbstractType
{

    /**
     * @param string $Content
     */
    function __construct( $Content )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Danger.twig' );
        $this->Template->setVariable( 'ElementText', $Content );
    }
}
