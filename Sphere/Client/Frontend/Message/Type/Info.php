<?php
namespace KREDA\Sphere\Client\Frontend\Message\Type;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Message\AbstractType;

/**
 * Class Info
 *
 * @package KREDA\Sphere\Client\Frontend\Message\Type
 */
class Info extends AbstractType
{

    /**
     * @param string $Content
     * @param AbstractIcon $Icon
     */
    function __construct( $Content, AbstractIcon $Icon = null )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Info.twig' );
        $this->Template->setVariable( 'ElementMessage', $Content );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
    }

}
