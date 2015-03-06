<?php
namespace KREDA\Sphere\Common\Frontend\Button\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\Frontend\Button\AbstractElement;
use KREDA\Sphere\Common\Signature\Type\GetSignature;

/**
 * Class ButtonLinkPrimary
 *
 * @package KREDA\Sphere\Common\Frontend\Button\Element
 */
class ButtonLinkPrimary extends AbstractElement
{

    /**
     * @param string       $Name
     * @param string       $Path
     * @param AbstractIcon $Icon
     * @param array        $Data
     */
    function __construct( $Name, $Path, AbstractIcon $Icon = null, $Data = array() )
    {

        parent::__construct( $Name );

        $this->Template = $this->extensionTemplate( __DIR__.'/ButtonLinkPrimary.twig' );

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
