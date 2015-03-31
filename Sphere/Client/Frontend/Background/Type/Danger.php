<?php
namespace KREDA\Sphere\Client\Frontend\Background\Type;

use KREDA\Sphere\Client\Frontend\Background\AbstractType;

/**
 * Class Danger
 *
 * @package KREDA\Sphere\Client\Frontend\Background\Type
 */
class Danger extends AbstractType
{

    /**
     * @param string $Content
     */
    function __construct( $Content )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Danger.twig' );
        $this->Template->setVariable( 'ElementBackground', $Content );
    }

}
