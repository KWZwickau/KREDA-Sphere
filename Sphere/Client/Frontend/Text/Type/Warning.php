<?php
namespace KREDA\Sphere\Client\Frontend\Text\Type;

use KREDA\Sphere\Client\Frontend\Text\AbstractType;

/**
 * Class Warning
 *
 * @package KREDA\Sphere\Client\Frontend\Text\Type
 */
class Warning extends AbstractType
{

    /**
     * @param string $Content
     */
    public function __construct( $Content )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Warning.twig' );
        $this->Template->setVariable( 'ElementText', $Content );
    }

}
