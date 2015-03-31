<?php
namespace KREDA\Sphere\Client\Frontend\Text\Type;

use KREDA\Sphere\Client\Frontend\Text\AbstractType;

/**
 * Class Success
 *
 * @package KREDA\Sphere\Client\Frontend\Text\Type
 */
class Success extends AbstractType
{

    /**
     * @param string $Content
     */
    function __construct( $Content )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Success.twig' );
        $this->Template->setVariable( 'ElementText', $Content );
    }

}
