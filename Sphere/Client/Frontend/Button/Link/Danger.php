<?php
namespace KREDA\Sphere\Client\Frontend\Button\Link;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Button\AbstractType;
use KREDA\Sphere\Common\Signature\Type\GetSignature;

/**
 * Class Danger
 *
 * @package KREDA\Sphere\Client\Frontend\Button\Link
 */
class Danger extends AbstractType
{

    /**
     * @param string       $Name
     * @param              $Path
     * @param AbstractIcon $Icon
     * @param array        $Data
     */
    function __construct( $Name, $Path, AbstractIcon $Icon = null, $Data = array() )
    {

        parent::__construct( $Name );
        $this->Template = $this->extensionTemplate( __DIR__.'/Danger.twig' );
        $this->Template->setVariable( 'ElementName', $Name );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
        if (!empty( $Data )) {
            $Signature = new GetSignature();
            $Data = '?'.http_build_query( $Signature->createSignature( $Data, $Path ) );
        } else {
            $Data = '';
        }
        $this->Template->setVariable( 'ElementPath', $Path.$Data );
        $this->Template->setVariable( 'UrlBase', $this->extensionRequest()->getUrlBase() );
    }
}
