<?php
namespace KREDA\Sphere\Client\Frontend\Message\Type;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Message\AbstractType;

/**
 * Class Warning
 *
 * @package KREDA\Sphere\Client\Frontend\Message\Type
 */
class Warning extends AbstractType
{

    /**
     * @param string $Content
     * @param AbstractIcon $Icon
     */
    public function __construct( $Content, AbstractIcon $Icon = null )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Warning.twig' );
        $this->Template->setVariable( 'ElementMessage', $Content );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
    }

}
