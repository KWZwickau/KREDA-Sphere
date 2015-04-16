<?php
namespace KREDA\Sphere\Client\Frontend\Background\Type;

use KREDA\Sphere\Client\Frontend\Background\AbstractType;

/**
 * Class Success
 *
 * @package KREDA\Sphere\Client\Frontend\Background\Type
 */
class Success extends AbstractType
{

    /**
     * @param string $Content
     */
    public function __construct( $Content )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Success.twig' );
        $this->Template->setVariable( 'ElementBackground', $Content );
    }

}
