<?php
namespace KREDA\Sphere\Client\Frontend\Text\Type;

use KREDA\Sphere\Client\Frontend\Text\AbstractType;

/**
 * Class Primary
 *
 * @package KREDA\Sphere\Client\Frontend\Text\Type
 */
class Primary extends AbstractType
{

    /**
     * @param string $Content
     */
    public function __construct( $Content )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Primary.twig' );
        $this->Template->setVariable( 'ElementText', $Content );
    }

}
