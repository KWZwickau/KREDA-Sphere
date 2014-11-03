<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Shell;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Screen
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Shell
 */
class Screen extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var Container[] $PositionNavigation */
    private $PositionNavigation = array();
    /** @var Container[] $PositionContent */
    private $PositionContent = array();

    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/Screen.twig' );
        $this->Template->setVariable( 'PathBase', HttpKernel::getRequest()->getPathBase() );
    }

    /**
     * @param Container $Container
     *
     * @return Screen
     */
    public function addToNavigation( Container $Container )
    {

        array_push( $this->PositionNavigation, $Container->getContent() );
        return $this;
    }

    /**
     * @param Container $Container
     *
     * @return Screen
     */
    public function addToContent( Container $Container )
    {

        array_push( $this->PositionContent, $Container->getContent() );
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $Request =
            '<div class="navbar-fixed-bottom container-fluid">'
            .'<div class="alert alert-info">'
            .'<strong>PathInfo</strong> '.HttpKernel::getRequest()->getPathInfo().'<br/>'
            .'<strong>PathBase</strong> '.HttpKernel::getRequest()->getPathBase().'<br/>'
            .'<strong>UrlBase</strong> '.HttpKernel::getRequest()->getUrlBase().'<br/>'
            .'<strong>UrlPort</strong> '.HttpKernel::getRequest()->getPort()
            .'</div>'
            .'</div>';

        $this->Template->setVariable( 'PositionNavigation', implode( '', $this->PositionNavigation ) );
        $this->Template->setVariable( 'PositionContent', implode( '', $this->PositionContent ).$Request );
        return $this->Template->getContent();
    }

}
